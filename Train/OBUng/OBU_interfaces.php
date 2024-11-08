<?php
// WinterTrain, OBUng
// Interfaces

// This module provides interfaces to: IP network and Train HW

$DMIbuffer = "";
$motorControl = 0;
$directionForward = FORWARD;
$lightOrderA = array("cabin" => OFF, "cab" => OFF, "front" => OFF, "rear" => OFF);
$lightOrderB = array("cabin" => OFF, "cab" => OFF, "front" => OFF, "rear" => OFF);

function interfaceServer() {
  global $listenerDMI, $listenerMMI, $clients, $clientsData, $inChargeDMI, $inChargeMMI, $DMIbuffer, $dmiState, $triggerMMIupdate,
    $modeSel, $driSel, $driveSel, $batteryCgarge, $chargeStatus, $rssi;
  $read = $clients;
  $read[] = $listenerDMI;
  $read[] = $listenerMMI;
  $except = NULL;
  $write = NULL; // FIXME to be used for writing to DMI etc. ???
  if (stream_select($read, $write, $except, 0, SERVER_WAIT )) {
    foreach ($read as $r) {
      if ($r == $listenerDMI) { // new DMI client
        if ($newClient = stream_socket_accept($listenerDMI,0,$clientName)) {
          msgLog("DMI Client $clientName signed in");
          stream_set_blocking($newClient,false);
          if (!$inChargeDMI) { // Only one DMI client allowed at a time ----- FIXME allow for read-only DMI
            $inChargeDMI = $newClient;
            $clients[] = $newClient;
            $clientsData[(int)$newClient] = [
              "addr" => $clientName,
              "signIn" => date("Ymd H:i:s"),
              "inChargeSince" => "",
              "activeAt" => "",
              "type" => "DMI",
              "userName" => ""];
          } else {
            fclose($newClient);
            msgLog("Another DMI client already signed in - rejecting this client >$clientName<.");        
          }
        } else {
          fatalError("DMI: accept failed");
        }
      } elseif ($r == $listenerMMI) { // new MMI Client
        if ($newClient = stream_socket_accept($listenerMMI,0,$clientName)) {
          msgLog("MMI Client $clientName signed in");
          stream_set_blocking($newClient,false);
          $clients[] = $newClient;
          $clientsData[(int)$newClient] = [
            "addr" => $clientName,
            "signIn" => date("Ymd H:i:s"),
            "inChargeSince" => "",
            "activeAt" => "",
            "type" => "MMI",
            "userName" => ""];
          MMIstartup($newClient);
        } else {
          fatalError("MMI: accept failed");
        }
      } else { // Existing client
        if ($data = fgets($r)) {
//        print "data >$data<\n";
          switch ($clientsData[(int)$r]["type"]) {
            case "DMI":
              $DMIbuffer .= $data;
              if ($DMIbuffer[-1] == "\n") {
                processCommandDMI(trim($DMIbuffer),$r);
                $DMIbuffer = "";
              }
            break;
            case "MMI":
              processCommandMMI(trim($data),$r); // FIXME compile via buffer as for DMI?
            break;
          }
        } else { // Connection closed by client
          msgLog("Client ".stream_socket_get_name($r,true)." signed out");
          fclose($r);
          unset($clientsData[(int)$r]);
          unset($clients[array_search($r, $clients, TRUE)]);
          if ($r == $inChargeDMI) { // DMI disconnected - FIXME move to separate funciton?
            $inChargeDMI = false;
            $DMIbuffer = "";
            $dmiState = DMI_DISCONNECTED; $modeSel = $dirSel = $driveSel = $batteryCgarge = $chargeStatus = $rssi = 0;
            $triggerMMIupdate = true;
          } elseif ($r == $inChargeMMI) {
            $inChargeMMI = false;
          } 
        }
      }
    }
  }
}

function pollHWbackend() { // Dynamic control -- in case of DOUBLE BACKEND polling both backends
// API
//	$motorControl		0..255
//	$directionForward	bool
//	$lightOrderA and B

  global $I2CFh, $noHWbackend, $activeOBUprofile, $activeLocoProfile,
  $motorControl, $directionForward, $lightOrderA, $triggerHWbackendPoll, $emergencyStop;
  if (!$noHWbackend and $I2CFh) {  // FIXME here?
    switch($activeOBUprofile["HWconfig"]) {
      case SINGLE_BACKEND:
      break;
      case DOUBLE_BACKEND:
        $order = 0;
        foreach ($lightOrderA as $value) $order |= $value;
        writeCmd("l", $order);
        writeCmd("M", $emergencyStop ? 0 :
          ($motorControl <= $activeLocoProfile["maxMotorControl"] ? $motorControl : $activeLocoProfile["maxMotorControl"]));
        writeCmd("r", $directionForward ? FORWARD : REVERSE);
        
      break;
      default:
        errLog("Unknown type of HWbackend: {$activeOBUprofile["HWconfig"]}");
    }
  }
  $triggerHWbackendPoll = false;
}

function configureHWbackend() {
  global $I2CFh, $ttyFh, $noHWbackend, $activeOBUprofile, $activeLocoProfile;
  if (!$noHWbackend and $I2CFh) {  // FIXME here?
    switch($activeOBUprofile["HWconfig"]) {
      case SINGLE_BACKEND:
      break;
      case DOUBLE_BACKEND:
// --------------------- motor car / serial IF
        writeCmd("w", $activeLocoProfile["frontLightNormal"]);        
        writeCmd("W", $activeLocoProfile["frontLightBright"]);
        writeCmd("R", $activeLocoProfile["rearLightNormal"]);
        writeCmd("x", $activeLocoProfile["cabinLightR"]);
        writeCmd("y", $activeLocoProfile["cabinLightG"]);
        writeCmd("z", $activeLocoProfile["cabinLightB"]);
        
// --------------------- Trailer / I2C IF

      break;
      default:
        errLog("Unknown type of HWbackend: {$activeOBUprofile["HWconfig"]}");
    }
  }  
}

function writeCmd($c, $v) { // for serial IF
  global $ttyFh;
  fwrite($ttyFh, sprintf("%1s%02x\n", $c, $v));
}

function initInterfaces() {
  global $DMIport, $MMIport, $DMIaddress, $MMIaddress, $listenerDMI, $listenerMMI, $I2C_FILE, $I2CFh, $TTY_FILE, $ttyFh, $clients,
    $clientsData, $activeOBUprofile, $inChargeDMI, $inChargeMMI, $connectedHWbackend, $OBU_HOSTNAME;
// ---------------------------------------------------------------------------------------------------- Stream interface for DMI and MMI
  $clients = array();
  $clientsData = array();
  $inChargeDMI = false;
  $inChargeMMI = false;

  $listenerDMI = @stream_socket_server("tcp://$DMIaddress:".$DMIport, $errno, $errstr);
  if (!$listenerDMI) {
    fatalError("Cannot create server socket (port: $DMIport) for DMI connection: $errstr ($errno)");
  }
  stream_set_blocking($listenerDMI,false);
  
  $listenerMMI = @stream_socket_server("tcp://$MMIaddress:".$MMIport, $errno, $errstr);
  if (!$listenerMMI) {
    fatalError("Cannot create server socket (port: $MMIport) for MMI connection: $errstr ($errno)");
  }
  stream_set_blocking($listenerMMI,false);
// ---------------------------------------------------------------------------------------------------- I2C interface to HW backend
  $connectedHWbackend = false;
  if (php_uname("n") == $OBU_HOSTNAME) { // Only configure I2C for OBU HW
    $I2CFh = i2c_open($I2C_FILE);
    if (!$I2CFh) fatalError("Cannot open I2C interface:: $I2C_FILE"); 
  } else {
    $I2CFh = false;
    errLog("Warning: No HW backend");
  }
// ---------------------------------------------------------------------------------------------------- TTY interface to HW backend
  if ($activeOBUprofile["HWconfig"] == DOUBLE_BACKEND) {
    if (!$ttyFh = fopen($TTY_FILE, "a+")) {
      msgLog("Warning: cannot open tty for HWbackend");
    }
  }
}


?>
