#!/usr/bin/php
<?php
// WinterTrain, Track Panle Master

//--------------------------------------- Default Configuration
$VERSION = "01P01";  // What does this mean with git?? FIXME

$RBC_SERVER_ADDR = "0.0.0.0";
$RBC_SERVER_PORT = 9900;

// ----------------------------------------- File names
$DIRECTORY = ".";
$MSGLOG = "Log/TPM.log";
$ERRLOG = "Log/TPM_ErrLog.txt";

// ---------------------------------------- Timing
define("RECONNECT_TIMEOUT",3);

// ----------------------------------------Enummerations  FIXME move to common Panel Master and RBC enummeration??

//--------------------------------------- System variable
$debug = 0x00; $background = FALSE; $run = true;
$startTime = time();

//----------------------------------------- Panel Master variable


//---------------------------------------------------------------------------------------------------------- System 
cmdLineParam();
prepareMainProgram();
forkToBackground();
initMainProgram();

do {
  if (initServer()) {
    initTPM();
    do {
      $now = time();
    } while (server() and $run);
  } else {
    sleep(RECONNECT_TIMEOUT);
  }
} while ($run);
msgLog("Exitting...");

// ---------------------------------------------------------------------------------------------------------- TPM
function initTPM() {

}

function processNotificationRBC($data) {
  $tokens = explode(" ", $data);
  switch ($tokens[0]) {
    case "pointState":
      print "pt\n";
    break;
    default:
      //print "$data\n";
  }
}

function sendCommandRBC($command) {
global $RBCfh;
  if ($RBCfh) fwrite($RBCfh,"$command\n");
}

//----------------------------------------------------------------------------------------- (server)

function initServer() {
global $RBCfh, $RBC_SERVER_ADDR, $RBC_SERVER_PORT, $version;
  $RBCfh = @stream_socket_client("tcp://$RBC_SERVER_ADDR:$RBC_SERVER_PORT", $errno,$errstr);
  if ($RBCfh) {
    stream_set_blocking($RBCfh,false);
    msgLog("Connected to RBC");
    sendCommandRBC("Hello this is Panel Master $version");
    return true;
  } else {
    fwrite(STDERR,"Cannot create client socket for RBC: $errstr ($errno)\n");
    return false;
  }
}

function server() {
global $RBCfh;
  $except = NULL;
  $write = NULL;
  $read[] = $RBCfh;
  if (stream_select($read, $write, $except, 0, 1000000 )) {
    foreach ($read as $r) {
      if ($r == $RBCfh) {
        if ($data = fgets($r)) {
          processNotificationRBC(trim($data));
        } else { //RBC gone
          msgLog("RBC gone");
          return false;
        }
      }
    }
  }
  return true;
}

//----------------------------------------------------------------------------------------- Utility

function toSigned($b1, $b2) {
  $dec = $b2 * 256 + $b1;
  $_dec = 65536 - $dec;
  return $dec > $_dec ? -$_dec : $dec;
}

function CmdLineParam() {
global $debug, $background, $TMS_CONFIG, $VERSION, $argv, $RBC_SERVER_ADDR, $TT_FILE, $PT2_FILE, $DIRECTORY, $TRAIN_DATA_FILE;
  if (in_array("-h",$argv)) {
    fwrite(STDERR,"Track Panel Master, version $VERSION
Usage:
-b, --background      Start as daemon
-IP <IP-address>      IP-address of RBC to contact
-d                    Enable debug info, level all
");
    exit();
  }
  next($argv);
  while (list(,$opt) = each($argv)) {
    switch ($opt) {
    case "-IP":
      list(,$p) = each($argv);
      if ($p) {
        $RBC_SERVER_ADDR = $p;
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
global $logFh, $errFh, $debug, $TMS_CONFIG, $MSGLOG, $ERRLOG, $DIRECTORY;
  if ($debug) {
    error_reporting(E_ALL);
  } else {
    error_reporting(0);
  }
  if (!($errFh = fopen("$DIRECTORY/$ERRLOG","a"))) {
    fwrite(STDERR,"Warning: Cannot open Error log file: $DIRECTORY/$ERRLOG\n");
    $errFh = fopen("/dev/null","w");
  }
  if (!($logFh = fopen("$DIRECTORY/$MSGLOG","a"))) {
    fwrite(STDERR,"Warning: Cannot open Log file: $DIRECTORY/$MSGLOG\n");
    $logFh = fopen("/dev/null","w");
  }
}

function initMainProgram() {
global $logFh, $errFh, $debug, $background, $ERRLOG, $MSGLOG, $PT2_FILE, $PT2, $trainData, $DIRECTORY, $TRAIN_DATA_FILE;

  if (!($errFh = fopen("$DIRECTORY/$ERRLOG","a"))) {
    fwrite(STDERR,"Warning: Cannot open Error log file: $DIRECTORY/$ERRLOG\n");
    $errFh = fopen("/dev/null","w");
  }
  if (!($logFh = fopen("$DIRECTORY/$MSGLOG","a"))) {
    fwrite(STDERR,"Warning: Cannot open Log file: $DIRECTORY/$MSGLOG\n");
    $logFh = fopen("/dev/null","w");
  }
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
