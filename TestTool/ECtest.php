#!/usr/bin/php
<?php
// WinterTrain, EC Test Tool

//--------------------------------------- Abus configuration
$arduino = 0x33; // CBU Master, I2C addresse
$ABUS = "cbu";
$DATA_FILE = "";
$addr = 0;

// --------------------------------------- Text
$CONF_TXT = array(0 => "Configuration accepted", 1 => "Invalid major device number", 2 => "Invalid minor device number", 10 => "Element capacity exceeded", 11 => "Unknown configuration command", 12 => "Unknown element type"); 

//--------------------------------------- System variable
$debug = FALSE;
print "WinterTrain, EC Test Tool\n";
cmdLineParam();
  if ($debug) {
    error_reporting(E_ALL);
  } else {
    error_reporting(0);
  }
include '/home/jabe/scripts/AbusMasterLib.php'; // must be included at global level

switch ($command) {
  case "c":
    print "Clear EC ($addr)\n";
    resetEC($addr);
    break;
  case "s":
    print "Request element status from EC ($addr)\n";
    requestElementStatus($addr);
    break;
  case "e":
    print "Request EC status from EC ($addr)\n";
    requestECstatus($addr);
    break;
  case "a":
    print "Add configuration to EC ($addr): $elementType, $majorDevice, $minorDevice\n";
    configureEC($addr, $elementType, $majorDevice, $minorDevice);
    break;
  case "o":
    print "Send element order to EC ($addr): $elementIndex $elementOrder\n";
    orderEC($addr, $elementIndex, $elementOrder);
    break;
}


function receivedFromEC($addr, $data) {
global $CONF_TXT;
  if ($addr) {
    print "Received from EC ($addr):\n";
    print "Data: ";
    foreach ($data as $value) {
      print dechex($value)." ";
    }
    print "\n";
    switch ($data[2]) { // packet type
      case 01: // status
      case 10: // status
        print "Element status\n";
        print "Number of configured elements: {$data[3]}\n";
        for ($index = 0; $index < $data[3]; $index++) {
          $status = $index % 2 ?  (int)$data[$index/2 +4] & 0x0F : ((int)$data[$index/2 +4] & 0xF0) >> 4 ;
          print "Index $index: $status\n";
        }
      break;
      case 02: // EC status
        print "EC status\n";
        $uptime = 0;
        for ($i = 3; $i >= 0; $i--) {
          $uptime = 256 * $uptime + (int)$data[$i + 3];
        }
        print "Uptime: ".round($uptime / 1000)."\n";
        print "ElementConf {$data[7]}\n";
        print "N_ELEMENT {$data[8]}\n";
        print "N_UDEVICE {$data[9]}\n";
        print "N_LDEVICE {$data[10]}\n";
        print "N_PDEVICE {$data[11]}\n";
      break;
      case 20: // configuration
        print "EC ($addr), Configuration: ".$CONF_TXT[$data[3]];
        break;
      default:
        print "EC ($addr) Unknown Abus packet: ".$data[2];
    }
  } else {
      print "EC ($addr) Timeout\n";
  }
}

function resetEC($addr) {
  $packet[2] = 20;
  $packet[3] = 00;
  AbusSendPacket($addr, $packet, 4);
}

function configureEC($addr, $elementType, $majorDevice, $minorDevice = 0) {
  $packet[2] = 20;
  $packet[3] = 01;
  $packet[4] = $elementType;
  $packet[5] = $majorDevice;
  $packet[6] = $minorDevice;
  AbusSendPacket($addr, $packet, 7);
 }

function requestECstatus($addr) {
  $packet[2] = 02;
  AbusSendPacket($addr, $packet, 3);
}

function orderEC($addr, $index, $order) {
  $packet[2] = 10;
  $packet[3] = $index;
  $packet[4] = $order;
  AbusSendPacket($addr, $packet, 5);
}

function requestElementStatus($addr) {
  $packet[2] = 01;
  AbusSendPacket($addr, $packet, 3);
}


//----------------------------------------------------------------------------------------- Utility
//-------------------------------------------- Abus interface
function AbusInit() {
global $ABUS;
  switch ($ABUS) {
    case "genie":
    global $toGenie, $fromGenie, $ABUS_GATEWAYaddress, $ABUS_GATEWAYport;
      $toGenie = stream_socket_client("udp://$ABUS_GATEWAYaddress:$ABUS_GATEWAYport", $errno,$errstr);
      $fromGenie = stream_socket_server("udp://0.0.0.0:9202", $errno,$errstr, STREAM_SERVER_BIND);
      stream_set_blocking($toGenie,false);
      stream_set_blocking($fromGenie,false);
    break;
    case "cbu":
//      global $grInd;
//      $grInd = fopen("/sys/class/gpio/gpio17/value","w");
    break;
  }
}

function AbusSendPacket($addr, $packet, $length) { // $packet is indexed as Abus packets, that is: packet type at index 2
global $ABUS;
  switch ($ABUS) {
    case "genie":
    global $toGenie;
      $TXbuf = sprintf("A%02X",$addr);
      for ($b = 2; $b <$length; $b++) {
        $TXbuf .= sprintf("%02X",$packet[$b]);
      }
    //print ">$TXbuf< \n";
      fwrite($toGenie,$TXbuf);
    break;
    case "cbu":
      $packet[0] = $addr;
      $packet[1] = 0; // dummy
      $data = AbusGateway($packet,20);
      for ($x = 0; $x < count($data); $x++) {
        $data[$x] = hexdec($data[$x]);
      }
//print_r($data);
//sleep(1);
      if ($data[0] != 0) { // timeout
        $addr = false;
        $data = array();
      }
      receivedFromEC($addr, $data);
    break;
  }
}

function CmdLineParam() {
global $debug, $DATA_FILE, $argv, $ABUS, $index, $order, $elementType, $majorDevice, $minorDevice, $elementIndex, $elementOrder, $command, $addr;
  if (in_array("-h",$argv) or count($argv) == 1) {
    print "Usage: [option] COMMAND [PARAM]
Send COMMAND to Element Controller (EC) with Abus address EC_ADDRESS. PARAM depends on COMMAND
COMMAND can be:
c, clear          Delete any existing configuration data in EC
s, status         Request element status from EC
e, ecstatus       Request system status from EC
a, add            Add configuration data to EC. PARAM is ELEMENT_TYPE MAJOR_DEVICE MINOR_DEVICE
o, order          Send element order to EC: PARAM is ELEMENT_INDEX ELEMENT_ORDER

-a                EC address (dec) 
-c                select CBUMaster as Abus gateway (default)
-g                select GenieMaster as Abus gateway (not implemented)
-D <Data_file>    Read EC configurtion data from PT1 and Train data file
-d                enable debug info
";
    exit();
  }
  next($argv);
  while (list(,$opt) = each($argv)) {
    switch ($opt) {
      case "c";
      case "clear";
        $command = "c";
        break;
      case "s";
      case "status";
        $command = "s";
        break;      
      case "e";
      case "ecstatus";
        $command = "e";
        break;
      case "o":
      case "order":
        $command = "o";
        list(,$p) = each($argv);
        if (is_numeric($p)) {
          $elementIndex = $p;
        } else {
          print "Error: command ORDER requires two parameters: elementIndex elementOrder \n";
          exit(1);
        }
        list(,$p) = each($argv);
        if (is_numeric($p)) {
          $elementOrder = $p;
        } else {
          print "Error: command ORDER requires two parameters: elementIndex elementOrder \n";
          exit(1);
        }
        break;
      case "a":
      case "add":
        $command = "a";
        list(,$p) = each($argv);
        if (is_numeric($p)) {
          $elementType = $p;
        } else {
          print "Error: command ADD requires three parameters: elementType majorDevice minorDevice \n";
          exit(1);
        }
        list(,$p) = each($argv);
        if (is_numeric($p)) {
          $majorDevice = $p;
        } else {
          print "Error: command ADD requires three parameters: elementType majorDevice minorDevice \n";
          exit(1);
        }
        list(,$p) = each($argv);
        if (is_numeric($p)) {
          $minorDevice = $p;
        } else {
          print "Error: command ADD requires three parameters: elementType majorDevice minorDevice \n";
          exit(1);
        }
      break;
      case "-a":
        list(,$p) = each($argv);
        if ($p and is_numeric($p)) {
          $addr = $p;
        } else {
          print "Error: option -a requires EC address \n";
          exit(1);
        }
        break;
      case "-c":
        $ABUS = "cbu";
        print "Abus gateway: CBUMaster\n";
        break;
      case "-g":
        $ABUS = "genie";
        print "Abus gateway: GenieMaster\n";
        break;
      case "-n":
        $ABUS = "none";
        print "No Abus gateway selected\n";
        break;
      case "-D":
        list(,$p) = each($argv);
        if ($p) {
          $DATA_FILE = $p;
          if (!is_readable($DATA_FILE)) {
            print "Error: option -D: Cannot read $DATA_FILE \n";
            exit(1); // If a data file is specified at the cmd line, it has to exist
          }
        } else {
          print "Error: option -D: File name is missing \n";
          exit(1);
        }
        break;
      case "-d":
      case "--debug";
        $debug = TRUE;
        print "Debugging mode\n";
        break;
      default :
        print "Unknown option: $opt\n";
      exit(1);
    }
  }
}


?>

