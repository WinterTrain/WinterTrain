<?php
// WinterTrain, RBC2
// Utility functions

function CmdLineParam() {
global $argv, $debug, $background, $PT2_FILE, $TRAIN_DATA_FILE, $DIRECTORY, $ERRLOG_FILE, $MSGLOG_FILE, $BL_FILE, $radioInterface, $AbusInterface,
      $allowSR, $allowSH, $allowFS, $allowATO, $HMIport, $MCePort;
  if (in_array("-h",$argv)) {
    print "RBC2 - WinterTrain
    
Usage:

-sr               Allow mode SR
-sh               Allow mode SH
-fs               Allow mode FS
-ato              Allow mode ATO

-nr               No Radio, disable radio interface
-ai2c             Abus gateway via I2C
-ai2ct            Abus gateway via I2C (using I2C tool)
-aip              Abus gateway via ethernet
-na               do not connect to Abus gateway

-b, --background  start as daemon

-d                enable debug info, level all
-dg               enable debug info, general
-dr               enable debug info, RBC
-dt               enable debug info, TMS

-D <directory>    use <directory> as working directory for all files. Must be given before option -td, -pt2, -l, -e and -bl in order to take effect
-td <file>        read Train Data from <file>
-pt2 <file>       read PT2 data from <file>
-l <file>         use <file> as Message Log File instead of default
-e <file>         use <file> as Error Log File instead of default
-bl <file>        use <file> as Balise Dump File instead of default

-HMIport <port>   listen to port <port> for HMI interface
-MCeport <port>   listen to port <port> for MCe interface

";
    exit();
  }
  next($argv);
  while (list(,$opt) = each($argv)) {
    switch ($opt) {
    case "-sr":
      $allowSR = true;
      break;
    case "-sh":
      $allowSH = true;
      break;
    case "-fs":
      $allowFS = true;
      break;
    case "-ato":
      $allowATO = true;
      break;
    case "-nr":
      $radioInterface = "NONE";
      print "Warning: Radio interface disabled\n";
      break;
    case "-ai2c":
      $AbusInterface = "I2C";
      print "Abus gateway via I2C\n";
      break;
    case "-ai2ct":
      $AbusInterface = "I2C_T";
      print "Abus gateway via I2C (using I2C tool)\n";
      break;
    case "-aip":
      $AbusInterface = "IP";
      print "Abus gateway via ethernet\n";
      break;
    case "-na":
      $AbusInterface = "none";
      print "Warning: No Abus gateway selected\n";
      break;
    case "-pt2":
      list(,$p) = each($argv);
      if ($p) {
        $PT2_FILE = $p;
        if (!is_readable("$DIRECTORY/$PT2_FILE")) {
          print "Error: option -pt2: Cannot read PT2 file: $DIRECTORY/$PT2_FILE \n";
          exit(1);
        }
      } else {
        print "Error: option -pt2: File name is missing \n";
        exit(1);
      }
      break;
    case "-td":
      list(,$p) = each($argv);
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
    case "-D":
      list(,$p) = each($argv);
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
      list(,$p) = each($argv);
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
      list(,$p) = each($argv);
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
    case "-bl":
      list(,$p) = each($argv);
      if ($p) {
        $BL_FILE = $p;
        if (!is_writeable("$DIRECTORY/$BL_FILE")) {
          print "Error: option -bl: Cannot write to Balise Dump File: $DIRECTORY/$BL_FILE\n";
          exit(1);
        }
      } else {
        print "Error: option -bl: File name is missing \n";
        exit(1);
      }
      break;   
    case "-HMIport":
      list(,$p) = each($argv);
      if ($p) {
        $HMIport = $p;
        if (!is_numeric($HMIport)) {
          print "Error: option -HMIport: <port> must be numeric\n";
          exit(1);
        }
      } else {
        print "Error: option -HMIport: <port> is missing \n";
        exit(1);
      }
    break; 
    case "-MCeport":
      list(,$p) = each($argv);
      if ($p) {
        $MCePort = $p;
        if (!is_numeric($MCePort)) {
          print "Error: option -MCeport: <port> must be numeric\n";
          exit(1);
        }
      } else {
        print "Error: option -MCeport: <port> is missing \n";
        exit(1);
      }
    break; 
    case "-b":
    case "--background" :
      $background = TRUE;
      break;
    case "-d":
      $debug = 0x07;
      print "Debug, all\n";
      break;
    case "-dg":
    case "--debug";
      $debug = $debug | 0x01;
      print "Debug general mode\n";
      break;
    case "-dr":
    case "--debugRBC";
      $debug = $debug | 0x02;
      print "Debug RBC mode\n";
      break;
    case "-dt":
    case "--debugTMS";
      $debug = $debug | 0x04;
      "Debug TMS mode\n";
      break;
    default :
      print "Unknown option: $opt\n";
      exit(1);
    }
  }
}

function prepareMainProgram() {
global $debug, $logFh, $errFh, $blFh, $DIRECTORY, $ERRLOG_FILE, $MSGLOG_FILE, $PT2_FILE, $TRAIN_DATA_FILE, $BL_FILE;
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
  if (!is_readable("$DIRECTORY/$PT2_FILE")) {
    print "Error: Cannot read PT2 data file: $DIRECTORY/$PT2_FILE\n";
    exit(1);
  }
  if (!is_readable("$DIRECTORY/$TRAIN_DATA_FILE")) {
    print "Error: Cannot read Train Data file: $DIRECTORY/$TRAIN_DATA_FILE\n";
    exit(1);
  }
  if (!($blFh = @fopen("$DIRECTORY/$BL_FILE","w"))) {
    print "Warning: Cannot write Balise List file: $DIRECTORY/$BL_FILE\n";
    $blFh = fopen("/dev/null","w");
  }
}

function initMainProgram() {
global $logFh, $errFh, $debug, $DIRECTORY, $ERRLOG_FILE, $MSGLOG_FILE, $background;
// logFh and errFh are closed before fork to background, so need to be reopened here:
  if (!($errFh = @fopen("$DIRECTORY/$ERRLOG_FILE","a"))) {
    print "Warning: Cannot open Error log file: $DIRECTORY/$ERRLOG_FILE\n";
    $errFh = fopen("/dev/null","w");
  }
  if (!($logFh = @fopen("$DIRECTORY/$MSGLOG_FILE","a"))) {
    print "Warning: Cannot open Log file: $DIRECTORY/$MSGLOG_FILE\n";
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
      print "Starting as daemon...\n";
      exit();
    }
  } else {
    print "Starting in forground\n";
  }
}

function debugPrint($txt) {
global $debug, $background;
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
