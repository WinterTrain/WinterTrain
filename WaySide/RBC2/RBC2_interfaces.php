<?php
// WinterTrain, RBC2
// Interfaces

// This module provides interfaces to: IP network, Abus network, radio network


function interfaceServer() {
global $AbusInterface, $listenerRBC, $listenerMCe, $clients, $clientsData, $inChargeHMI, $inChargeMCe, $inChargeTMS, $listenerTMS, $radioInterface, $tmsStatus, $toAnusGw, $fromAbusGw, $radioLinkFh, $radioBuf;

  $read = $clients;
  $read[] = $listenerRBC;
  $read[] = $listenerMCe;
  $read[] = $listenerTMS;
  if ($radioInterface == "USB") {
    $read[] = $radioLinkFh; 
  }
  if ($AbusInterface == "IP") {
    $read[] = $fromAbusGw;
  }
  $except = NULL;
  $write = NULL;
  if (stream_select($read, $write, $except, 0, 100000 )) {
    foreach ($read as $r) {
      if ($r == $listenerRBC) { // new HMI client
        if ($newClient = stream_socket_accept($listenerRBC,0,$clientName)) {
          msgLog("HMI Client $clientName signed in");
          $clients[] = $newClient;
          $clientsData[(int)$newClient] = [
            "addr" => $clientName,
            "signIn" => date("Ymd H:i:s"),
            "inChargeSince" => "",
            "type" => "HMI"];
          HMIstartup($newClient);
        } else {
          fatalError("HMI: accept failed");
        }
      } elseif ($r == $listenerMCe) { // new MCe Client
        if ($newClient = stream_socket_accept($listenerMCe,0,$clientName)) {
          msgLog("MCe Client $clientName signed in");
          $clients[] = $newClient;
          $clientsData[(int)$newClient] = [
            "addr" => $clientName,
            "signIn" => date("Ymd H:i:s"),
            "inChargeSince" => "",
            "type" => "MCe"];
          MCeStartup($newClient);
        } else {
          fatalError("MCe: accept failed");
        }
      } elseif ($r == $listenerTMS) { // new TMS Client
          if ($newClient = stream_socket_accept($listenerTMS,0,$clientName)) {
            if (!$inChargeTMS) { // Only one TMC client allowed
              $inChargeTMS = $newClient;
              $clients[] = $newClient;
              $clientsData[(int)$newClient] = [
                "addr" => $clientName,
                "signIn" => date("Ymd H:i:s"),
                "inChargeSince" => "",
                "type" => "TMS"];        
              msgLog("TMS Client $clientName signed in");
            TMSStartup();
          } else {
            fclose($newClient);
            errLog("TMS already signed in");
          }
        } else {
          fatalError("TMS: accept failed");
        }
      } elseif ($r == $fromAbusGw) { // ----------------------------- Abus Gateway
        if ($line = fgets($r)) {
          debugPrint ("Abus: >$line<");
          if (substr($line, 0,9) != "<TimeOut>") {
            $data = array();
            for ($i = 0; $i < strlen($line); $i += 2) {
              $data[] = hexdec(substr($line,$i,2));
            }
            $addr = $data[1];
          } else {
            $addr = false; $data = array();
          }
          receivedFromEC($addr, $data); 
        }
      } elseif ($r == $radioLinkFh) { // ---------------------------- Radio Link
        while ($res = fgets($r)) { // Get all available data
          $radioBuf = $radioBuf.$res;
          if (false !== strpos($res,"\n")) {
            receivedFromRadioLink(trim($radioBuf)); // Process data from radio link
            $radioBuf = "";
          }
        }
      } else { // Existing client
        if ($data = fgets($r)) {
          switch ($clientsData[(int)$r]["type"]) {
            case "HMI":
              processCommandRBC(trim($data),$r);
            break;
            case "MCe":
              processCommandMCe(trim($data),$r);
            break;
            case "TMS":
              processCommandTMS(trim($data));
            break;
          }
        } else { // Connection closed by client
          msgLog("Client ".stream_socket_get_name($r,true)." signed out");
          fclose($r);
          unset($clientsData[(int)$r]);
          unset($clients[array_search($r, $clients, TRUE)]);
          if ($r == $inChargeHMI) {
            $inChargeHMI = false;
          } elseif ($r == $inChargeMCe) {
            $inChargeMCe = false;
          } elseif ($r == $inChargeTMS) {
            $inChargeTMS = false;
            $tmsStatus = TMS_NO_TMS;
          }
        }
      }
    }
  }
}

function AbusSendPacket($addr, $packet) { // $packet is indexed as Abus packets, that is: packet type at index 2
// Data from the slave is returned via call back function  receivedFromEC($addr, $data)
global $AbusInterface, $toAbusGw, $AbusI2CFh;

  if (count($packet) > MAX_ABUS_BUF) {
    fatalLog("AbusSendPacket: Packet too long: ".count($packet));
  }
  switch ($AbusInterface) {
    case "IP": // -------------------------------------------------------- AbusMasterGateway connected via Ethernet
      $TXbuf = sprintf("G2%02X",$addr); // reply port 2 as constant FIXME
      for ($b = 2; $b < count($packet) + 2; $b++) {
        $TXbuf .= sprintf("%02X",$packet[$b]);
      }
      fwrite($toAbusGw,$TXbuf); //      usleep(1000000); // Buffer problems FIXME
    break;
    case "I2C":
    case "I2C_T":
      $packet[0] = $addr;
      $packet[1] = 0; // Master address
      if ($AbusInterface == "I2C_T") { // ------------------------------- Use command line tool for I2C communication
        $cmd = "/usr/sbin/i2cset -y 1 ".ABUS_MASTER_I2C_ADDR." 101";
        for ($x = 0; $x < count($packet); $x++) {
          $cmd .= " ".$packet[$x];
        }
        $cmd .= " i";
        $n = 0;
        do {
          if ($n > 0) {
            errLog("AbusGateway retry $n");
          }
          exec($cmd,$output,$wStat);
          usleep(ABUS_WAIT); //  Wait for potentiel Abus timeout. Might be optimized using pending status from AbusMasterGateway FIXME
          $data = array();
          for ($t = 0; $t < MAX_ABUS_BUF + 1; $t++) { // + 1 as the gateway adds one status byte
            exec("/usr/sbin/i2cget -y 1 ".ABUS_MASTER_I2C_ADDR, $data, $rStat);
            if ($rStat) errLog("AbusGateway: Error reading AbusMaster. Status: $rStat");
          }
          $n += 1;
        } while ($wStat > 0 and $n < N_I2CSET);
        if ($wStat) {
          errLog("AbusGateway: Error writing AbusMaster. Status: $wStat after $n retry");
        }
        for ($x = 0; $x < count($data); $x++) { // i2c tool returns data as hex
          $data[$x] = hexdec($data[$x]);
        }
      } else { // ------------------------------------------------------- Use PHP extention for I2C communication
        $n = 0;
        while (!@i2c_write($AbusI2CFh, 101, $packet) and $n < N_I2C_WRITE) {
          debugPrint("I2C write retry");
          $n +=1;
        }
        usleep(ABUS_WAIT); //  Wait for potentiel Abus timeout. Might be optimized using pending status from AbusMasterGateway FIXME
        $data = array();
        for ($b = 0; $b < MAX_ABUS_BUF + 1; $b++) { // + 1 as the gateway adds one status byte
          $data[] = i2c_read($AbusI2CFh, 1)[0];
        }
      }
      if ($data[0] != 0) { // Check communication status
        debugPrint("AbusGateway: Time out: {$data[0]} Address: $addr Packet type: {$packet[2]}");
        $addr = false;
        $data = array();
        return;
      }
      array_shift($data); // Remove the gateway status at index 0 leaving the Abus packet
      receivedFromEC($addr, $data);
    break;
  }
}

function receivedFromRadioLink($data) {  // Distribute radio packet received via USB radio
  $res = explode(" ",$data);
  if ($res[0] == "OK") {
    switch ($res[2]) {
    case 10: // Packet type Position report
      // Unpack Abus packet
      // processPositionReport($trainID, $requestedMode, $MAreceived, $monDir, $pwr, $balise, $distance,  $speed, $rtoMode);
      processPositionReport($res[1] & $RF12_ID_MASK, $res[11] & 0x07, ($res[11] & 0x80) >> 7, ($res[11] & 0x18) >> 3,
        ($res[11] & 0x60) >> 5, sprintf("%02X:%02X:%02X:%02X:%02X",$res[3],$res[4],$res[5],$res[6],$res[7]), 
        toSigned($res[8], $res[9]), $res[10], $res[12]);
    break;
    case 50: // Packet type HHT request
      processHhtRequest($res);
    break;
    }
  }
}

function initInterfaces() {
global $HMIport, $MCePort, $TMSport, $HMIaddress, $MCeAddress, $TMSaddress, $listenerRBC, $listenerMCe, $listenerTMS, $RADIO_DEVICE_FILE, $ABUS_I2C_FILE, $radioLinkFh, $radioBuf, $AbusI2CFh, $radioInterface, $AbusInterface, $toAbusGw, $fromAbusGw, $clients, $clientsData, $inChargeHMI, $inChargeMCe, $inChargeTMS;

// --------------------------------------------------------------------------------------------------- Abus Gateway interface
  $AbusI2CFh = $toAbusGw = $fromAbusGw = null; // Default setup
  switch($AbusInterface) {
    case "I2C":
      $AbusI2CFh = i2c_open($ABUS_I2C_FILE);
      if (!$AbusI2CFh) {
        fatalError("Cannot open I2C interface:: $ABUS_I2C_FILE");      
      }
      i2c_select($AbusI2CFh, ABUS_MASTER_I2C_ADDR);
    break;
    case "I2C_T":
    break;
    case "IP":
      $toAbusGw = stream_socket_client("udp://".ABUS_GATEWAYaddress.":".ABUS_GATEWAYport, $errno,$errstr);
      $fromAbusGw = stream_socket_server("udp://".LOCAL_GATEWAYaddress.":".LOCAL_GATEWAYport, $errno,$errstr, STREAM_SERVER_BIND);
      stream_set_blocking($toAbusGw,false);
      stream_set_blocking($fromAbusGw,false);
    break;
    default:
  }
// ---------------------------------------------------------------------------------------------------- Radio Interface  
  $radioBuf = "";
  switch($radioInterface) {// init radioLink (JeeLink)
  case  "USB":
    $radioLinkFh = fopen($RADIO_DEVICE_FILE,"r+");
    if (!$radioLinkFh) {
      fatalError("Cannot create server socket for radioLink: $errstr ($errno)");
    }
    stream_set_blocking($radioLinkFh,false);
    fwrite($radioLinkFh,RF12GROUP."g\n");       // Set radio group
    fwrite($radioLinkFh,"1q\n");                // Don't report bad packets
    fwrite($radioLinkFh,RBC_RADIO_ID."i\n");    // Set radio address
    break;
  case "ABUS":
    fatalError("Radio interface via ABUS not implemented");
  default:
    $radioLinkFh = null;
  }
// ---------------------------------------------------------------------------------------------------- Stream interface for HMI, MCe and TMS
  $clients = array();
  $clientsData = array();
  $inChargeHMI = false;
  $inChargeMCe = false;
  $inChargeTMS = false;

  $listenerRBC = @stream_socket_server("tcp://$HMIaddress:".$HMIport, $errno, $errstr);
  if (!$listenerRBC) {
    fatalError("Cannot create server socket for HMI connection: $errstr ($errno)");
  }
  stream_set_blocking($listenerRBC,false);
  
  $listenerMCe = @stream_socket_server("tcp://$MCeAddress:".$MCePort, $errno, $errstr);
  if (!$listenerMCe) {
    fatalError("Cannot create server socket for MCe connection: $errstr ($errno)");
  }
  stream_set_blocking($listenerMCe,false);
  
  $listenerTMS = @stream_socket_server("tcp://$TMSaddress:".$TMSport, $errno, $errstr);
  if (!$listenerTMS) {
    fatalError("Cannot create server socket for TMS connection: $errstr ($errno)");
  }
  stream_set_blocking($listenerTMS,false);
}

?>
