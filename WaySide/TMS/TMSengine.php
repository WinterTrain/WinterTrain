#!/usr/bin/php
<?php
// WinterTrain, TMS engine

//--------------------------------------- Default Configuration
$VERSION = "01P01";  // What does this mean with git?? FIXME
$TMSport = 9903;
$HMIaddress = "0.0.0.0";

// File names
$TMS_CONFIG = "TMSconf.php";
$DATA_FILE = "../SiteData/CBU/CBU.php";
$ERRLOG = "Log/TMS_ErrLog.txt";
$MSGLOG = "Log/TMS.log";

// ---------------------------------------- Timing

// ----------------------------------------Enummerations

//--------------------------------------- System variable
$debug = 0x00; $background = FALSE; $run = true;
$startTime = time();

//---------------------------------------------------------------------------------------------------------- System 
cmdLineParam();
prepareMainProgram();
versionInfo();
//processPT1();
initTMS();
forkToBackground();
initMainProgram();
do {
  $now = time();
//  if ($now != $pollTimeout) { // every 1 second
//  }
} while ($run);
msgLog("Exitting...");

// ---------------------------------------------------------------------------------------------------------- TMS


function initTMS() {

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
global $debug, $background, $TMS_CONFIG, $VERSION, $argv;
  if (in_array("-h",$argv)) {
    fwrite(STDERR,"TMS Engine, version $VERSION
Usage:
-b, --background  start as daemon
-f <conf_file>    configuration of TMS engine
-D <Data_file>    PT1 and Train data
-d                enable debug info, level all
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
          fwrite(STDERR,"Error: option -f: Cannot read $DATA_FILE \n");
          exit(1); // If a data file is specified at the cmd line, it has to exist
        }
      } else {
        fwrite(STDERR,"Error: option -D: File name is missing \n");
        exit(1);
      }
      break;
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
