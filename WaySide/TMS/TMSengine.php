#!/usr/bin/php
<?php
// WinterTrain, TMS engine

//--------------------------------------- Default Configuration
$VERSION = "01P01";  // What does this mean with git?? FIXME

$RBCIL_SERVER_ADDR = "0.0.0.0";
$RBCIL_SERVER_PORT = 9903;

// File names
$TMS_CONFIG = "TMSconf.php";
$PT2_FILE = "PT2.php";    // Site specific data
$TRAIN_DATA_FILE = "TrainData.php";
$TT_FILE = "TimeTables.php";    // Time Tables
$DIRECTORY = ".";
$ERRLOG = "Log/TMS_ErrLog.txt";
$MSGLOG = "Log/TMS.log";

// ---------------------------------------- Timing
define("HEARTBEAT_TIMEOUT",5);
define("RECONNECT_TIMEOUT",3);

// ----------------------------------------Enummerations  FIXME move to common TMS and RBC enummeration??

define("TRN_UDEF",0);
define("TRN_NORMAL",1);
define("TRN_COMPLETED",2);
define("TRN_FAILED",3);
define("TRN_ARS_DISABLED",4);
define("TRN_BLOCKED",5);
define("TRN_WAITING",6);
define("TRN_CONFIRM",7);
define("TRN_UNKNOWN",8);

// Route setting state
define("RS_UDEF",0);                // state undefined
define("RS_ROUTE_SET",1);           // route set
define("RS_REJECTED",2);            // impossible route
define("RS_BLOCKED",3);             // route temporary blocked by other route
define("RS_INHIBITED",4);           // route cannot be set due to inhibitions
define("RS_ARS_DISABLED",5);        // ARS disabled for route
define("RS_PENDING",6);             // route is being set
define("RS_NO_ROUTE_SPECIFIED",7);  // time table has no route
define("RS_COMPLETED",8);           // route setting completed for time table
define("RS_NO_ROUTE",9);            // route not set (yet)
define("RS_WAIT",10);               // await departure delay
define("RS_CONFIRM", 11);           // Signaller to set next route by hand
define("RS_WAIT_DEPARTURE",12);     // await departure time
define("RS_WAIT_TRAIN",13);         // await meeting train

// Response code (from RBC) for route setting
// udef
// set
// rejected as impossible
//             blocked by other route
//             inhibited by command
//             ARS disabled

define("TMS_UDEF",0);
define("TMS_NO_TT",1);
define("TMS_OK",2);
define("TMS_NO_TMS",3);

//--------------------------------------- System variable
$debug = 0x00; $background = FALSE; $run = true;
$startTime = time();
$heartBeatTimer = 0;
$pollTimer = 0;
$tmsStatus = TMS_NO_TT;

//----------------------------------------- TMS variable
$tts = array(); // time tables


//---------------------------------------------------------------------------------------------------------- System 
cmdLineParam();
prepareMainProgram();
versionInfo();
forkToBackground();
initMainProgram();
readTimeTables();
do {
  if (initServer()) {
    initTMS();
    do {
      $now = time();
      if ($heartBeatTimer < $now) {
        $heartBeatTimer = $now + HEARTBEAT_TIMEOUT;
        sendCommandRBCIL("TMS_HB $tmsStatus $now");
      }
      if ($pollTimer < $now) { // every 1 second
        $pollTimer = $now + 1;
        processTimeout();
      }
    } while (server() and $run);
  } else {
    sleep(RECONNECT_TIMEOUT);
  }
} while ($run);
msgLog("Exitting...");

// --------------------------------------------------------------------------------------------------------- Time Tables

function readTimeTables() {
global $tts, $PT1, $trainData, $TT_FILE, $SITE_DATA_FILE, $tmsStatus, $now, $DIRECTORY;
  msgLog("Loading time table \"$DIRECTORY/$TT_FILE\"");
  require("$DIRECTORY/$TT_FILE"); // FIXME handle syntax errors
  $tts = $timeTables;
  if (checkTimeTable($tts)) {
    $tts = array();
    $tmsStatus = TMS_NO_TT;
    sendCommandRBCIL("TMS_HB $tmsStatus $now");
  } else {
    $tmsStatus = TMS_OK;
    sendCommandRBCIL("TMS_HB $tmsStatus $now");
  }
}

function signalExists($signal) {
global $PT1;
  return array_key_exists($signal, $PT1) and 
    ($PT1[$signal]["element"] == "SU" or
     $PT1[$signal]["element"] == "SD" or
     $PT1[$signal]["element"] == "BSB" or
     $PT1[$signal]["element"] == "BSE");
}
  
function ttError($txt) {
global $ttError;
  msgLog("Error: $txt");
  $ttError = true;
}

function ttWarning($txt) {
  msgLog("Warning: $txt");
}
 
function checkTimeTable($tts) {
global $PT1, $ttError;
  $ttError = false;
  foreach ($tts as $trn => $tt) {
    if ($trn != "") {
      foreach ($tt["routeTable"] as $routeIndex => $route) {
        if (!signalExists($route["start"])) {
          ttError("TRN: $trn Route $routeIndex Unknown route start: {$route["start"]}");
        }
        if ((!isset($route["condition"]) or $route["condition"] != "E" and $route["condition"] != "N") and !signalExists($route["dest"])) {
          ttError("TRN: $trn Route $routeIndex Unknown route destination: {$route["dest"]}");
        }
        if (isset($route["condition"]) and $route["condition"] == "N" and (!isset($route["nextTrn"]) or !isset($tts[$route["nextTrn"]]))) { // 
          ttError("TRN: $trn Route $routeIndex \"nextTrn\" missing or unknown nextTrn for condition \"N\"");
        }
        if (isset($route["condition"]) and $route["condition"] == "M" and !isset($route["mTrain"])) {
          ttError("TRN: $trn Route $routeIndex \"mTrain\" missing for condition \"M\"");
        }
        if (isset($route["condition"]) and $route["condition"] == "M" and (!isset($route["mSignal"]) or !signalExists($route["mSignal"]) )) { // ??
          ttError("TRN: $trn Route $routeIndex \"mSignal\" missing or signal unknown for condition \"M\"");
        }
        if (isset($route["condition"]) and $route["condition"] == "M" and !isset($route["mTrain"])) {
          ttError("TRN: $trn Route $routeIndex \"mTrain\" missing for condition \"M\"");
        }
        if (isset($route["condition"]) and $route["condition"] == "M" and (!isset($route["mSignal"]) or !signalExists($route["mSignal"]) )) { // ??
          ttError("TRN: $trn Route $routeIndex \"mSignal\" missing or signal unknown for condition \"M\"");
        }
        if (isset($route["time"]) and $route["time"] != "" and isset($rouyte["delay"])) {
          ttWarning("TRN: $trn Route $routeIndex: both \"time\" and \"delay\" are specified - \"delay\" will be ignored");
        }
        // check valid time format FIXME
        // check route existance and signal orientation FIXME
      }
    }
  }
  return $ttError;
}


// ---------------------------------------------------------------------------------------------------------- TMS
function initTMS() {
global $SITE_DATA_FILE, $trainData, $PT1;
  foreach ($trainData as $index => &$train) { // train data
    resetTrainState($train);
  }
}

function resetTrainState(&$train) {
    $train["trn"] = "";
    $train["location"]["U"] = ""; // start signal of trains current location for direction up
    $train["location"]["D"] = "";
    $train["locationState"]["U"] = ""; // driving state of train at current location for direction up
    $train["locationState"]["D"] = "";
    $train["routeState"]["U"] = RS_UDEF;
    $train["routeState"]["D"] = RS_UDEF;
    $train["locationTS"] = false;
    $train["routeIndex"] = false; // set to false in order to start search from beginning of route list
    $train["trnState"] = TRN_UNKNOWN; 
    $train["ETD"] = 0;
}

function processTrainLocaiton($trainIndex, $nextElementName, $state, $dir) {
// $state is "A" if the train is at standstill occupying the element just before $nextElementName - that is has arrived
// otherwise "F"
global $tts, $trainData, $now;
  $train = &$trainData[$trainIndex];
  $trn = $train["trn"];
  $revDir = ($dir == "U" ? "D" : "U");
  if (array_key_exists($trn, $tts)) { // trn is known in time table
    $tt = $tts[$trn];
    if ($nextElementName != $train["location"][$dir] or $state != $train["locationState"][$dir]) { // new situation for this direction
      debugPrint("Train: {$train["ID"]} New location ($dir): $nextElementName / $state Prev. location {$train["location"][$dir]} / {$train["locationState"][$dir]}");
      $train["location"][$dir] = $nextElementName;
      $train["locationState"][$dir] = $state;
      if ($state == "A") { // Train is at stopping location
        $train["locationTS"] = $now; 
      }
      $train["routeState"][$dir] = RS_NO_ROUTE;
    } else {
print "situation unchanged $dir\n";
    }
    switch ($train["routeState"][$dir]) {
      case  RS_NO_ROUTE:
        $routeIndex = findRoute($tt, $nextElementName, false);  // prevRouteIndex set to false in order to search from beginning of route list
        if ($routeIndex !== false) {
          $train["routeIndex"] = $routeIndex;
          $route = $tt["routeTable"][$routeIndex];
          switch ($route["condition"]) { 
            case "E": // location is destination
              $train["routeState"][$dir] = RS_COMPLETED;
              $train["trnState"] = TRN_COMPLETED;
            break;
            case "N": // location is destination, assign new TRN
              $train["routeState"][$dir] = RS_COMPLETED;
              $train["trnState"] = TRN_COMPLETED;
              if (isset($route["delay"]) and is_numeric($route["delay"])) {
                $train["ETD"] = $now + $route["delay"];       
                sendETD($trainIndex,$train["ETD"]);
              } else { // set next Trn
                resetTrainState($train);
                $train["trn"] = $route["nextTrn"];
                $train["trnState"] = TRN_NORMAL;
                setTRN($trainIndex, $train["trn"]);
              }
            break;
            case "": // set route
            case "R":
              if (isset($route["time"]) and $route["time"] != "") { //                              await departure time
                $train["routeState"][$dir] = RS_WAIT_DEPARTURE;
                $train["trnState"] = TRN_WAITING;
                $train["ETD"] = departureTime($route["time"]);
                sendETD($trainIndex,$train["ETD"]);
                print "Train: {$train["ID"]} RS_WAIT_DEPARTURE $dir ".date("H:i:s")." ETD/time: ".date("H:i:s",$train["ETD"])."\n";
              } elseif (isset($route["delay"]) and is_numeric($route["delay"])) { //                await delay
                if ($state == "A") { // if at stopping location => start delay timer
                  $train["routeState"][$dir] = RS_WAIT;
                  $train["trnState"] = TRN_WAITING;   
                  $train["ETD"] = $now + $route["delay"];       
                  sendETD($trainIndex,$train["ETD"]);
                  print "Train: {$train["ID"]} RS_WAIT $dir".date("H:i:s")." ETD/delay: ".date("H:i:s",$train["ETD"])."\n";
                } // if not at stopping location, keep routeState RS_NO_ROUTE until delaytimer to be started
              } else { //                                                                           set route unconditional
                $train["routeState"][$dir] = RS_PENDING;
                $train["trnState"] = TRN_NORMAL;
                setRoute($trainIndex, $dir, $route["start"], $route["dest"]);
              }
            break;
            case "W": // wait for another train
              $train["awaiteTrn"] = $route["mTrn"];    // which trn to wait for
              $train["awaiteSignal"] = $route["mSignal"]; // at which signal 
              $train["routeState"][$dir] = RS_WAIT_TRAIN;
              $train["trnState"] = TRN_WAITING;
              $arrived = false; // check if meeting trail has arrived already
              foreach ($trainData as $waitingTrain) {
                if ($waitingTrain["trn"] != $trn) { // don't check this train
                  if ($waitingTrain["routeState"] == RS_WAIT_TRAIN and 
                    $train["awaitTrn"] == $waitingTrain["trn"] and
                    ($train["awaitSignal"] == $waitingTrain["location"]["U"] or $train["awaitSignal"] == $waitingTrain["location"]["D"]) and
                    $waitingTrain["awaitTrn"] == $trn and
                    $waitingTrain["awaitSignal"] == $nextElementName) {
                    $arrived = true;
                    break; // only one train can wait for this train
                  }
                }
              }
              if ($arrived) { // Train to meet has arrived already
print "Set route for trn {$train["mTrn"]} waiting at {$train["mSignal"]}\n";

print "Set route for trn $trn arrived at $nextElementName\n";
              
              } else { // Train to meet has not yet arrived
print "Trn: $trn waiting at $nextElementName for trn {$route["mTrn"]} to arrive at signal {$route["mSignal"]}\n";
              }
            break;
            case "M": // Manual - operator to set next route
              $train["routeState"][$dir] = RS_CONFIRM;
              $train["trnState"] = TRN_CONFIRM;
            break;
          }
        } else { // no route specified in tt for this location and direction
          $train["routeState"][$dir] = RS_NO_ROUTE_SPECIFIED; 
          if ($train["routeState"][$revDir] == RS_NO_ROUTE_SPECIFIED) { // no routes specified at all
            $train["trnState"] = TRN_FAILED;
          }
        }
      break;
      
// PENDING, ARS and BLOCKED to be moved to timeout function FIXME
      case RS_PENDING: // optional alarm if route setting takes too long
      break;
      case  RS_ARS_DISABLED:
      case RS_BLOCKED: // --------------------------------------------------- FIXME check for alternative routes
print "RS_BLOCKED at location\n";
        $route = $tts[$train["trn"]]["routeTable"][$train["routeIndex"]];
        $train["routeState"][$dir] = RS_PENDING;
        setRoute($trainIndex, $dir, $route["start"], $route["dest"]);
        if (!isset($route["dest"])) {
          debugPrint("Case RS_BLOCKED, Destination not defined for trn: {$train["trn"]} RouteIndex: {$train["routeIndex"]}");
        }
      break;
    }
    sendTRNstate($trainIndex, $train["trnState"]);
  } // else unknown trn, ignore location report
}

function departureTime($depTime) {
global $now;
  $depH = substr($depTime,0,2);
  $depM = substr($depTime,3,2);
  $depS = substr($depTime,6,2);
  $wallH = date("H",$now);
  $wallM = date("i",$now);
  if (substr($depTime,0,5) == "**:**") {
    $depH = $wallH;
    $depM = $wallM;
    $dep = mktime($depH, $depM, $depS);
    if ($dep < $now) {
      $dep = $dep + 60;
    } 
  } elseif (substr($depTime,0,4) == "**:*") {
    $depH = $wallH;
    $depM = substr($wallM,0,1).substr($depM,1,1) ;
    $dep = mktime($depH, $depM, $depS);
    if ($dep < $now) {
      $dep = $dep + 600;
    } 
  } elseif (substr($depTime,0,2) == "**") {
    $depH = $wallH;
    $dep = mktime($depH, $depM, $depS);
    if ($dep < $now) {
      $dep = $dep + 3600;
    } 
  } else { // pattern "*_:__:__" not implemented
    $dep = mktime($depH, $depM, $depS);
    if ($dep < $now) {
      $dep = $dep + 86400;
    } 
  }
  return $dep;
}

function processTimeout() {
global $trainData, $tts, $now;
  foreach ($trainData as $trainIndex => &$train) {
    $dir = ($train["routeState"]["U"] == RS_NO_ROUTE_SPECIFIED ? "D" : "U"); 
    switch ($train["routeState"][$dir]) {
      case RS_NO_ROUTE_SPECIFIED:
      break;
      case RS_WAIT: // if time elapsed set route
      case RS_WAIT_DEPARTURE: // if departure time, set route
        if ($now > $train["ETD"]) { // waiting time elapsed
          $route = $tts[$train["trn"]]["routeTable"][$train["routeIndex"]];
          $train["routeState"][$dir] = RS_PENDING;
          $train["trnState"] = TRN_NORMAL;
          setRoute($trainIndex, $dir, $route["start"], $route["dest"]);
          sendETD($trainIndex,0); // clear indication at HMI
        }
      break;
      case RS_WAIT_TRAIN:     // FIXME   
      break;
      case RS_COMPLETED:
        $route = $tts[$train["trn"]]["routeTable"][$train["routeIndex"]];
        if ($route["condition"] == "N" and $now > $train["ETD"]) {
          resetTrainState($train);
          $train["trn"] = $route["nextTrn"];
          $train["trnState"] = TRN_NORMAL;
          setTRN($trainIndex, $train["trn"]);
        }
      break;
// FIXME:
      case RS_PENDING: // optional alarm if route setting takes too long
      break;
      case  RS_ARS_DISABLED:
      break;
      case RS_BLOCKED:
      break;
    }
  }
}

function processRouteStatus($trainIndex, $dir, $routeSettingStatus) {
global $trainData, $tts;
  $train = &$trainData[$trainIndex];
  switch($routeSettingStatus) {
    case RS_ROUTE_SET:
      $train["routeState"][$dir] = RS_ROUTE_SET;
      $train["trnState"] = TRN_NORMAL;
    break;
    case RS_ARS_DISABLED:
      $train["routeState"][$dir] = RS_ARS_DISABLED;
      $train["trnState"] = TRN_ARS_DISABLED;
    break;
    case RS_BLOCKED:
    case RS_REJECTED:
    // FIXME destinguish between impossible routes (= time table failure), routes temporary blocked by other routes and routes blocked by inhibitions
    // FIXME If route temporary blocked, whait a while before trying again
    // FIXME check for alternative routes in route table. Set the route, set status to pending and set routeIndex
    // FIXME retry setting temporary blocked route by time out
      $tt = $tts[$train["trn"]];
      $routeIndex = findRoute($tt, $train["location"][$dir], $train["routeIndex"]);
      if ($routeIndex !== false) {
        $train["routeState"][$dir] = RS_PENDING;
        $train["trnState"] = TRN_NORMAL;
        $route = $tt["routeTable"][$routeIndex];
        setRoute($trainIndex, $dir, $route["start"], $route["dest"]);
      } else {
        $train["routeState"][$dir] = RS_BLOCKED;
        $train["trnState"] = TRN_BLOCKED;
      }
    break;
  }
  sendTRNstate($trainIndex, $train["trnState"]);
}


function findRoute($tt, $nextSignal, $prevRouteIndex) {
  foreach ($tt["routeTable"] as $routeIndex => $route) {
    if ($route["start"] == $nextSignal and (!$prevRouteIndex or $routeIndex > $prevRouteIndex)) {
      return $routeIndex;
    }
  }
  return false;
}

function processNotificationRBCIL($data) {
global $tts, $trainData, $run;
  $param = explode(" ",$data);
  switch ($param[0]) {
    case "setTRN": // Param: trainIndex (trn)
      if (isset($param[1])) {
        if (isset($param[2])) {
          if (array_key_exists($param[2],$tts)) { // trn known in time table
            resetTrainState($trainData[$param[1]]);
            $trainData[$param[1]]["trn"] = $param[2];
            $trainData[$param[1]]["trnState"] = TRN_NORMAL; 
            sendETD($param[1],0); // clear indication at HMI
          } else {
            resetTrainState($trainData[$param[1]]);
            $trainData[$param[1]]["trn"] = $param[2];
            $trainData[$param[1]]["trnState"] = TRN_FAILED; 
            sendETD($param[1],0); // clear indication at HMI
          }
        } else { // clear TRN for train
          resetTrainState($trainData[$param[1]]);
          $trainData[$param[1]]["trnState"] = TRN_UNKNOWN; 
          sendETD($param[1],0); // clear indication at HMI
        }
        sendTRNstate($param[1], $trainData[$param[1]]["trnState"]);
      } else {
        errLog("Warning: RBCIL command setTRN without valid train index");
      }
    break;
    case "Hello":
    break;
    case "trainLoc":
      processTrainLocaiton($param[1], $param[2], $param[3], $param[4]);
    break;
    case "routeStatus":
      processRouteStatus($param[1], $param[2], $param[3]);
    break;
    case "loadTT":
      readTimeTables();
    break;
    case "exitTMS":
      $run = false;
    break;
    default:
      errLog("Ups, unimplemented notification frim RBCIL {$param[0]}");
  }
}

function setRoute($trainIndex, $dir, $start, $destination) {
  sendCommandRBCIL("setRoute $trainIndex $dir $start $destination");
}

function sendTRNstate($trainIndex, $status) {
  sendCommandRBCIL("trnStatus $trainIndex $status");
}

function setTRN($trainIndex, $trn) {
  sendCommandRBCIL("setTRN $trainIndex $trn");
}

function sendETD($trainIndex, $etd) {
  sendCommandRBCIL("etd $trainIndex $etd");
}

function sendCommandRBCIL($command) {
global $RBCILfh;
  if ($RBCILfh) fwrite($RBCILfh,"$command\n");
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
  fwrite(STDERR,"TMS Engine, version: $VERSION\n");
//  fwrite(STDERR,"PT1 data, version: $PT1_VERSION\n");
}

function CmdLineParam() {
global $debug, $background, $TMS_CONFIG, $VERSION, $argv, $RBCIL_SERVER_ADDR, $TT_FILE, $PT2_FILE, $DIRECTORY, $TRAIN_DATA_FILE;
  if (in_array("-h",$argv)) {
    fwrite(STDERR,"TMS Engine, version $VERSION
Usage:
-b, --background      Start as daemon
-f <file name>        Configuration file for TMS engine
-pt2 <file name>      PT2 data file
-tt <file name>       Time tables file
-D <directory>        use <directory> as working directory for all files. Must be given before -f -tt and -pt2 in order to take effect

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
        if (!is_readable("$DIRECTORY/$TMS_CONFIG")) {
          fwrite(STDERR,"Error: option -f: Cannot read $DIRECTORY/$TMS_CONFIG \n");
          exit(1); // If a config file is specified at the cmd line, it has to exist
        }
      } else {
        fwrite(STDERR,"Error: option -f: File name is missing \n");
        exit(1);
      }
      break;
    case "-pt2":
      list(,$p) = each($argv);
      if ($p) {
        $PT2_FILE = $p;
        if (!is_readable("$DIRECTORY/$PT2_FILE")) {
          fwrite(STDERR,"Error: option -pt2: Cannot read $DIRECTORY/$PT2_FILE \n");
          exit(1); // If a data file is specified at the cmd line, it has to exist
        }
      } else {
        fwrite(STDERR,"Error: option -pt2: File name is missing \n");
        exit(1);
      }
    break;
    case "-td":
      list(,$p) = each($argv);
      if ($p) {
        $TRAIN_DATA_FILE = $p;
        if (!is_readable("$DIRECTORY/$TRAIN_DATA_FILE")) {
          fwrite(STDERR,"Error: option -td: Cannot read Train Data file: $DIRECTORY/$TRAIN_DATA_FILE\n");
          exit(1);
        }
      } else {
        fwrite(STDERR,"Error: option -td: File name is missing \n");
        exit(1);
      }
    break;
    case "-tt":
      list(,$p) = each($argv);
      if ($p) {
        $TT_FILE = $p;
        if (!is_readable("$DIRECTORY/$TT_FILE")) {
          fwrite(STDERR,"Error: option -tt: Cannot read $DIRECTORY/$TT_FILE \n");
          exit(1); // If a time table file is specified at the cmd line, it has to exist
        }
      } else {
        fwrite(STDERR,"Error: option -tt: File name is missing \n");
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
  if (is_readable("$DIRECTORY/$TMS_CONFIG")) {
    require("$DIRECTORY/$TMS_CONFIG");
  } else {
    fwrite(STDERR,"Warning: Cannot read TMS config file: $DIRECTORY/$TMS_CONFIG\n");
    Fwrite(STDERR,"Using default parameters...\n");
  } // config file is optional
}

function initMainProgram() {
global $logFh, $errFh, $debug, $background, $ERRLOG, $MSGLOG, $PT2_FILE, $PT1, $trainData, $DIRECTORY, $TRAIN_DATA_FILE;

  if (!($errFh = fopen("$DIRECTORY/$ERRLOG","a"))) {
    fwrite(STDERR,"Warning: Cannot open Error log file: $DIRECTORY/$ERRLOG\n");
    $errFh = fopen("/dev/null","w");
  }
  if (!($logFh = fopen("$DIRECTORY/$MSGLOG","a"))) {
    fwrite(STDERR,"Warning: Cannot open Log file: $DIRECTORY/$MSGLOG\n");
    $logFh = fopen("/dev/null","w");
  }
  if (!is_readable("$DIRECTORY/$PT2_FILE")) {
    fwrite(STDERR,"Error: Cannot open PT2 data file: $DIRECTORY/$PT2_FILE\n");
    exit(1); // PT1 data is mandatory
  }
  if (!is_readable("$DIRECTORY/$TRAIN_DATA_FILE")) {
    fwrite(STDERR,"Error: Cannot open Train data file: $DIRECTORY/$TRAIN_DATA_FILE\n");
    exit(1); // PT1 data is mandatory
  }
  include("$DIRECTORY/$PT2_FILE");
  include("$DIRECTORY/$TRAIN_DATA_FILE");
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
