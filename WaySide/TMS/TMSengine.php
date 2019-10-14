#!/usr/bin/php
<?php
// WinterTrain, TMS engine

//--------------------------------------- Default Configuration
$VERSION = "01P01";  // What does this mean with git?? FIXME

$RBCIL_SERVER_ADDR = "0.0.0.0";
$RBCIL_SERVER_PORT = 9903;

// File names
$TMS_CONFIG = "TMSconf.php";
$DATA_FILE = "../SiteData/CBU/CBU.php";
$TT_FILE = "../SiteData/CBU/TimeTables.php";
$ERRLOG = "Log/TMS_ErrLog.txt";
$MSGLOG = "Log/TMS.log";

// ---------------------------------------- Timing
define("HEARTBEAT_TIMEOUT",5);
define("RECONNECT_TIMEOUT",3);

// ----------------------------------------Enummerations

define("TRN_UDEF",0);
define("TRN_NORMAL",1);
define("TRN_COMPLETED",2);
define("TRN_FAILED",3);
define("TRN_DISABLED",4);
define("TRN_BLOCKED",5);
define("TRN_WAITING",6);
define("TRN_CONFIRM",7);
define("TRN_UNKNOWN",8);

//--------------------------------------- System variable
$debug = 0x00; $background = FALSE; $run = true;
$startTime = time();
$heartBeatTimer = 0;

//----------------------------------------- TMS variable
$tts = array(); // time tables


//---------------------------------------------------------------------------------------------------------- System 
cmdLineParam();
prepareMainProgram();
versionInfo();
//processPT1();
initTMS();
forkToBackground();
initMainProgram();
do {
  if (initServer()) {
    do {
//      print ".";
      $now = time();
      if ($heartBeatTimer < $now) {
        $heartBeatTimer = $now + HEARTBEAT_TIMEOUT;
        if ($RBCILfh) {
          sendCommandRBCIL("TMS_HB $now");
        }
      }
//    if ($now != $pollTimeout) { // every 1 second
//    }
    } while (server() and $run);
  } else {
    sleep(RECONNECT_TIMEOUT);
  }
} while ($run);
msgLog("Exitting...");

// ---------------------------------------------------------------------------------------------------------- TMS


function initTMS() {
global $DATA_FILE, $trainData, $tt; 
  require($DATA_FILE);
  foreach ($trainData as $index => &$train) { // train data
    $train["trn"] = "";
  }

  importTimetable();
}

function importTimetable() {
global $tts, $TT_FILE;
  require($TT_FILE);
  $tts = $timeTables; // merge FIXME
}

function processTrainLocaiton($trainIndex, $nextElementName, $progress) {
global $tts, $trainData;
  $trn = $trainData[$trainIndex]["trn"];
  if (array_key_exists($trn, $tts)) { // trn known in time table
    $tt = &$tts[$trn];
    $routeIndex = findRoute($tt, $nextElementName);
    if ($routeIndex !== false) {
      $action = ttAction($trainIndex, $routeIndex, $tt);
      print "action $action\n";
      switch ($action) {
        case TRN_NORMAL:
          setRoute($tt["routeTable"][$routeIndex]["start"], $tt["routeTable"][$routeIndex]["dest"]);
        break;
        case TRN_COMPLETED:
          print "train $trainIndex at destination\n";
        break;
        case TRN_FAILED:
        case TRN_DISABLED:
        case TRN_BLOCKED:
        case TRN:WAITING:
        break;
        case TRN_CONFIRM:
        break;
      }
      sendTRNstatus($trainIndex, $action);
    } else { // no route for this location, but maybe for locaiton 2...
      print "no route for $trainIndex at $nextElementName\n";
    }
  } else { // unknown trn, ignore location report
  print "unknown trn\n";
  }
}

function ttAction($trainIndex, $routeIndex, $tt) {
global $trainData;
print "$trainIndex $routeIndex\n";
  if ($tt["routeTable"][$routeIndex]["cond"] == "E") return TRN_COMPLETED;
  return TRN_NORMAL;
}

function findRoute($tt, $nextSignal) {

  foreach ($tt["routeTable"] as $routeIndex => $route) {
    if ($route["start"] == $nextSignal) {
    print "found route $routeIndex\n";
      return $routeIndex;
    }
  }
  return false;
}

function processNotificationRBCIL($data) {
global $tts, $trainData;
  $param = explode(" ",$data);
  switch ($param[0]) {
    case "setTRN":
      if (isset($param[2])) {
        if (array_key_exists($param[2],$tts)) { // trn known in time table
          $trainData[$param[1]]["trn"] = $param[2];
          sendTRNstatus($param[1], TRN_NORMAL);
        } else {
          sendTRNstatus($param[1], TRN_UNKNOWN);
        }
      } else { // clear TRN for train
        $trainData[$param[1]]["trn"] = "";
      }
    break;
    case "Hello":
    break;
    case "trainLoc":
      processTrainLocaiton($param[1], $param[2], $param[3]);
    break;
    default:
      print "Ups unimplemented notification\n";
  }
}

function setRoute($start, $destination) {
  sendCommandRBCIL("setRoute $start $destination");
}

function sendTRNstatus($trainIndex, $status) {
  sendCommandRBCIL("trnStatus $trainIndex $status");
}

function sendCommandRBCIL($command) {
global $RBCILfh;
  fwrite($RBCILfh,"$command\n");
}

//----------------------------------------------------------------------------------------- (server)

function initServer() {
global $RBCILfh, $RBCIL_SERVER_ADDR, $RBCIL_SERVER_PORT, $version;
  $RBCILfh = @stream_socket_client("tcp://$RBCIL_SERVER_ADDR:$RBCIL_SERVER_PORT", $errno,$errstr);
  if ($RBCILfh) {
    stream_set_blocking($RBCILfh,false);
    msgLog("Connected to RBCIL");
    sendCommandRBCIL("Hello this is TMS version $version");
    return true;
  } else {
    fwrite(STDERR,"Cannot create client socket for RBCIL: $errstr ($errno)\n");
    return false;
  }
}


function server() {
global $RBCILfh;
  $except = NULL;
  $write = NULL;
  $read[] = $RBCILfh;
  if (stream_select($read, $write, $except, 0, 1000000 )) {
    foreach ($read as $r) {
      if ($r == $RBCILfh) {
        if ($data = fgets($r)) {
          print "Data from RBCIL: >$data<\n";
          processNotificationRBCIL(trim($data));
        } else { //RBCIL gone
        msgLog("RBCIL gone");
        return false;
        }
      }
    }
  }
  return true;
}

//----------------------------------------------------------------------------------------- Utility
//----------------------------------------------------------------

function toSigned($b1, $b2) {
  $dec = $b2 * 256 + $b1;
  $_dec = 65536 - $dec;
  return $dec > $_dec ? -$_dec : $dec;
}

function versionInfo() {
global $VERSION, $PT1_VERSION;
  fwrite(STDERR,"RBCIL, version: $VERSION\n");
  fwrite(STDERR,"PT1 data, version: $PT1_VERSION\n");
}

function CmdLineParam() {
global $debug, $background, $TMS_CONFIG, $VERSION, $argv, $RBCIL_SERVER_ADDR, $TT_FILE, $DATA_FILE;
  if (in_array("-h",$argv)) {
    fwrite(STDERR,"TMS Engine, version $VERSION
Usage:
-b, --background      Start as daemon
-f <file name>        Configuration file for TMS engine
-D <file name>        PT1 and Train data file
-T <file name>        Time tables file
-IP <IP-address>      IP-address of RBCIL to contact
-d                    Enable debug info, level all
");
    exit();
  }
  next($argv);
  while (list(,$opt) = each($argv)) {
    switch ($opt) {
    case "-f":
      list(,$p) = each($argv);
      if ($p) {
        $TMS_CONFIG = $p;
        if (!is_readable($TMS_CONFIG)) {
          fwrite(STDERR,"Error: option -f: Cannot read $TMS_CONFIG \n");
          exit(1); // If a config file is specified at the cmd line, it has to exist
        }
      } else {
        fwrite(STDERR,"Error: option -f: File name is missing \n");
        exit(1);
      }
      break;
    case "-D":
      list(,$p) = each($argv);
      if ($p) {
        $DATA_FILE = $p;
        if (!is_readable($DATA_FILE)) {
          fwrite(STDERR,"Error: option -D: Cannot read $DATA_FILE \n");
          exit(1); // If a data file is specified at the cmd line, it has to exist
        }
      } else {
        fwrite(STDERR,"Error: option -D: File name is missing \n");
        exit(1);
      }
    break;
    case "-T":
      list(,$p) = each($argv);
      if ($p) {
        $TT_FILE = $p;
        if (!is_readable($TT_FILE)) {
          fwrite(STDERR,"Error: option -T: Cannot read $TT_FILE \n");
          exit(1); // If a time table file is specified at the cmd line, it has to exist
        }
      } else {
        fwrite(STDERR,"Error: option -T: File name is missing \n");
        exit(1);
      }
    break;
    case "-IP":
      list(,$p) = each($argv);
      if ($p) {
        $RBCIL_SERVER_ADDR = $p;
      } else {
        fwrite(STDERR,"Error: option -IP: IP-address is missing \n");
        exit(1);
      }
    break;
    case "-b":
    case "--background" :
      $background = TRUE;
    break;
    case "-d":
      $debug = 0x07;
      fwrite(STDERR,"Debug, all\n");
    break;
    default :
      fwrite(STDERR,"Unknown option: $opt\n");
      exit(1);
    }
  }
}


function prepareMainProgram() {
global $logFh, $errFh, $debug, $TMS_CONFIG, $MSGLOG, $ERRLOG;
  if ($debug) {
    error_reporting(E_ALL);
  } else {
    error_reporting(0);
  }
  if (!($errFh = fopen($ERRLOG,"a"))) {
    fwrite(STDERR,"Warning: Cannot open Error log file: $ERRLOG\n");
    $errFh = fopen("/dev/null","w");
  }
  if (!($logFh = fopen($MSGLOG,"a"))) {
    fwrite(STDERR,"Warning: Cannot open Log file: $MSGLOG\n");
    $logFh = fopen("/dev/null","w");
  }
/*  if (!is_readable($DATA_FILE)) {
    fwrite(STDERR,"Error: Cannot read data file: $DATA_FILE\n");
    exit(1); // PT1 and Train data is mandatory
  }
*/
  if (is_readable($TMS_CONFIG)) {
    require($TMS_CONFIG);
  } else {
    fwrite(STDERR,"Warning: Cannot read TMS config file: $TMS_CONFIG\n");
    Fwrite(STDERR,"Using default parameters...\n");
  } // config file is optional
}

function initMainProgram() {
global $logFh, $errFh, $debug, $background, $ERRLOG, $MSGLOG;

  if (!($errFh = fopen($ERRLOG,"a"))) {
    fwrite(STDERR,"Warning: Cannot open Error log file: $ERRLOG\n");
    $errFh = fopen("/dev/null","w");
  }
  if (!($logFh = fopen($MSGLOG,"a"))) {
    fwrite(STDERR,"Warning: Cannot open Log file: $MSGLOG\n");
    $logFh = fopen("/dev/null","w");
  }
/*  if (!(@$DATAFh = fopen($DATA_FILE,"r"))) { // file exsist FIXME
    fwrite(STDERR,"Error: Cannot open PT1 and Train data file: $DATA_FILE\n");
    exit(1); // PT1 data is mandatory
  }
  */
  if ($background) {
    msgLog("Starting as daemon");
  } else {
    msgLog("Starting in forground");
  }
}

function forkToBackground() {
global $background, $errFh, $logFh;

  fclose($errFh);
  fclose($logFh);
  if ($background) {
  	$pid = pcntl_fork();
    if ($pid == -1) {
      die("Could not fork");
    } else if ($pid) {
      fwrite(STDERR,"Starting as daemon...\n");
      exit();
    }
  } else {
    fwrite(STDERR,"Starting in forground\n");
  }
}

function debugPrint($txt) {
global $debug;
  if ($debug & 0x01) {
    print "$txt\n";
  }
}

function msgLog($txt) {
global $logFh;
  debugPrint (date("Ymd H:i:s")." $txt");
  fwrite($logFh,date("Ymd H:i:s")." $txt\n");
}

function errLog($txt) {
global $errFh;
  debugPrint (date("Ymd H:i:s")." $txt");
  fwrite($errFh,date("Ymd H:i:s")." $txt\n");
}

function fatalError($txt) {
global $logFh, $errFh;
  debugPrint (date("Ymd H:i:s")." Fatal Error: $txt");
  fwrite($logFh,date("Ymd H:i:s")." Fatal Error: $txt - Exiting...\n");
  fwrite($errFh,date("Ymd H:i:s")." Fatal Error: $txt - Exiting...\n");
  exit(1);
}

?>
