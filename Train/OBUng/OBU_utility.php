<?php
// WinterTrain, OBUng
// Utility functions

function CmdLineParam() {
  global $argv, $debug, $background, $noHWbackend, $activeOBUprofileID;
  
  if (in_array("-h",$argv)) {
    print "OBUng - WinterTrain
    
Usage:

-b, --background  start as daemon
-nhwb             No HW backend, disable backend interface

-d                enable debug info, level all

-D <directory>    use <directory> as working directory for all files. Must be given before option -td, -pt2, -l, -e and -bl in order to take effect
-td <file>        read Train Data from <file>
-p <ID>		  use OBU profile <profile>

-l <file>         use <file> as Message Log File instead of default
-e <file>         use <file> as Error Log File instead of default

-DMIport <port>   listen to port <port> for DMI interface
-MMIport <port>   listen to port <port> for MMI interface

";
    exit();
  }
  while ($opt = next($argv)) {
    switch ($opt) {
    case "-td":
      $p = next($argv);
      if ($p) {
        $TRAIN_DATA_FILE = $p;
        if (!is_readable("$DIRECTORY/$TRAIN_DATA_FILE")) {
          print "Error: option -td: Cannot read Train Data file: $DIRECTORY/$TRAIN_DATA_FILE\n";
          exit(1);
        }
      } else {
        print "Error: option -td: File name is missing \n";
        exit(1);
      }
      break;
    case "-p":
      $p = next($argv);
      if ($p) {
	$activeOBUprofileID = $p;
      } else {
        print "Error: option -p: OBU ID is missing \n";
        exit(1);
      }
    break;
    case "-D":
      $p = next($argv);
      if ($p) {
        $DIRECTORY = $p;
        if (!is_dir($DIRECTORY)) {
          print "Error: option -D: Cannot access directory: $DIRECTORY\n";
          exit(1);
        }
      } else {
        print "Error: option -D: Directory name is missing\n";
        exit(1);
      }
      break;
    case "-l":
      $p = next($argv);
      if ($p) {
        $MSGLOG_FILE_FILE = $p;
        if (!is_writeable("$DIRECTORY/$MSGLOG_FILE")) {
          print "Error: option -l: Cannot write to message log file: $DIRECTORY/$MSGLOG_FILE\n";
          exit(1);
        }
      } else {
        print "Error: option -l: File name is missing \n";
        exit(1);
      }
      break; 
    case "-e":
      $p = next($argv);
      if ($p) {
        $ERRLOG_FILE = $p;
        if (!is_writeable("$DIRECTORY/$ERRLOG_FILE")) {
          print "Error: option -e: Cannot write to Error log file: $DIRECTORY/$ERRLOG_FILE\n";
          exit(1);
        }
      } else {
        print "Error: option -e: File name is missing \n";
        exit(1);
      }
      break;
    case "-DMIport":
      $p = next($argv);
      if ($p) {
        $HMIport = $p;
        if (!is_numeric($HMIport)) {
          print "Error: option -DMIport: <port> must be numeric\n";
          exit(1);
        }
      } else {
        print "Error: option -DMIport: <port> is missing \n";
        exit(1);
      }
    break; 
    case "-MMIport":
      $p = next($argv);
      if ($p) {
        $MCePort = $p;
        if (!is_numeric($MCePort)) {
          print "Error: option -MMIport: <port> must be numeric\n";
          exit(1);
        }
      } else {
        print "Error: option -MMIport: <port> is missing \n";
        exit(1);
      }
    break; 
    case "-nhwb":
      $noHWbackend = TRUE;
      break;
    case "-b":
    case "--background" :
      $background = TRUE;
      break;
    case "-d":
      $debug = 0x07;
      print "Debug, all\n";
      break;
    default :
      print "Unknown option: $opt\n";
      exit(1);
    }
  }
}

function prepareMainProgram() {
  global $debug, $logFh, $errFh, $DIRECTORY, $ERRLOG_FILE, $MSGLOG_FILE, $TRAIN_DATA_FILE, $DUMP_FILE, $dumpFh ;
  if ($debug) {
    error_reporting(E_ALL);
  } else {
    error_reporting(0);
  }
  if (!($errFh = @fopen("$DIRECTORY/$ERRLOG_FILE","a"))) {
    print "Warning: Cannot open Error log file: $DIRECTORY/$ERRLOG_FILE\n";
    $errFh = fopen("/dev/null","w");
  }
  if (!($logFh = @fopen("$DIRECTORY/$MSGLOG_FILE","a"))) {
    print "Warning: Cannot open Log file: $DIRECTORY/$MSGLOG_FILE\n";
    $logFh = fopen("/dev/null","w");
  }
  if (!($dumpFh = @fopen("$DIRECTORY/$DUMP_FILE","a"))) {
    print "Warning: Cannot open Dump file: $DIRECTORY/$DUMP_FILE\n";
    $dumpFh = fopen("/dev/null","w");
  }
  if (!is_readable("$DIRECTORY/$TRAIN_DATA_FILE")) {
    print "Error: Cannot read Train Data file: $DIRECTORY/$TRAIN_DATA_FILE\n";
    exit(1);
  }
}

function initMainProgram() {
  global $logFh, $errFh, $debug, $DIRECTORY, $ERRLOG_FILE, $MSGLOG_FILE, $background, $DUMP_FILE, $dumpFh, $OBUstart;
  // logFh and errFh are closed before fork to background, so need to be reopened here:
  if (!($errFh = @fopen("$DIRECTORY/$ERRLOG_FILE","a"))) {
    print "Warning: Cannot open Error log file: $DIRECTORY/$ERRLOG_FILE\n";
    $errFh = fopen("/dev/null","w");
  }
  if (!($logFh = @fopen("$DIRECTORY/$MSGLOG_FILE","a"))) {
    print "Warning: Cannot open Log file: $DIRECTORY/$MSGLOG_FILE\n";
    $logFh = fopen("/dev/null","w");
  }
  if (!($dumpFh = @fopen("$DIRECTORY/$DUMP_FILE","a"))) {
    print "Warning: Cannot open dump file: $DIRECTORY/$DUMP_FILE\n";
    $dumpFh = fopen("/dev/null","w");
  }
  if ($background) {
    msgLog("Starting as daemon");
  } else {
    msgLog("Starting in forground");
  }
  $OBUstart = time();
}

function prettyPrintTime($sec) {
  $s = $sec % 60;
  $min = ($sec - $s) / 60;
  $m = $min % 60;
  $hour = ($min - $m) / 60;
  $h = $hour % 24;
  $d = ($hour - $h) / 24;
  return "$d:$h:$m:$s";
}

function forkToBackground() {
  global $background, $errFh, $logFh, $dumpFh;
  fclose($errFh);
  fclose($logFh);
  fclose($dumpFh);
  if ($background) {
  	$pid = pcntl_fork();
    if ($pid == -1) {
      die("Could not fork");
    } else if ($pid) {
      print "Starting as daemon...\n";
      exit();
    }
  } else {
    print "Starting in forground\n";
  }
}

function debugPrint($txt) {
  global $debug, $background, $dumpActive, $dumpFh;
  if ($dumpActive) fwrite($dumpFh, "$txt\n");
  if ($debug & 0x01 and !$background) {
    print "$txt\n";
  }
}

function msgLog($txt) {
  global $logFh;
  debugPrint(date("Ymd H:i:s")." $txt");
  fwrite($logFh,date("Ymd H:i:s")." $txt\n");
}

function errLog($txt) {
  global $errFh;
  debugPrint (date("Ymd H:i:s")." $txt");
  fwrite($errFh,date("Ymd H:i:s")." $txt\n");
}

function fatalError($txt) {
  global $logFh, $errFh;
  debugPrint (date("Ymd H:i:s")." Fatal Error: $txt\n");
  fwrite($logFh,date("Ymd H:i:s")." Fatal Error: $txt - Exiting...\n");
  fwrite($errFh,date("Ymd H:i:s")." Fatal Error: $txt - Exiting...\n");
  exit(1);
}

function shutDown() {
  msgLog("Exitting...");
}

?>
