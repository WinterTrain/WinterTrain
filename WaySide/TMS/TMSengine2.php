#!/usr/bin/php
<?php
// WinterTrain, TMS engine

//--------------------------------------- Default Configuration
$VERSION = "01P01";  // What does this mean with git?? FIXME

$RBC_SERVER_ADDR = "0.0.0.0";
$RBC_SERVER_PORT = 9903;

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
define("RETRY_ROUTE_TIMEOUT",1); // Retry setting blocked route

// ----------------------------------------Enummerations  FIXME move to common TMS and RBC enummeration??

// Train number state machine
define("TRN_UDEF",0);
define("TRN_NORMAL",1);
define("TRN_COMPLETED",2);
define("TRN_FAILED",3);
define("TRN_ARS_DISABLED",4);
define("TRN_BLOCKED",5);
define("TRN_WAITING",6);
define("TRN_CONFIRM",7);
define("TRN_UNASSIGNED",8);

// Route setting state machins
define("RS_UDEF",0);                // state undefined
define("RS_ROUTE_SET",1);           // route set
define("RS_REJECTED",2);            // impossible route
define("RS_BLOCKED",3);             // route (and alternatives) temporary blocked by other route, try again later
define("RS_INHIBITED",4);           // route cannot be set due to inhibitions
define("RS_ARS_DISABLED",5);        // ARS disabled for route - RS_BLOCKED used instead
define("RS_PENDING",6);             // route is being set
define("RS_NO_ROUTE_SPECIFIED",7);  // time table has no route
define("RS_COMPLETED",8);           // route setting completed for time table
define("RS_NO_ROUTE",9);            // route not set (yet)
define("RS_WAIT",10);               // await departure delay
define("RS_CONFIRM", 11);           // Signaller to set next route by hand
define("RS_WAIT_DEPARTURE",12);     // await departure time
define("RS_WAIT_TRAIN",13);         // await meeting train

// Direction
define("D_UDEF",0);
define("D_DOWN",1);
define("D_UP",2);
define("D_STOP",3);


// Response code (from RBC) for route setting. Must be aligned with RBC
define("RSR_UDEF", 0);
define("RSR_ROUTE_SET", 1);
define("RSR_SET_EXTENDED", 2);    // Route set, but extended by existing route - Used?? currently blocked by RBC FIXME
define("RSR_REJECTED", 3);        // rejected as impossible
define("RSR_BLOCKED", 4);         // blocked by other route
define("RSR_INHIBITED", 5);       // inhibited by command
define("RSR_ARS_DISABLED", 6);    // ARS disabled
define("RSR_ROUTE_OCCUPIED", 7);  // occupied by train

// TMS engine status
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
        sendCommandRBC("TMS_HB|$tmsStatus|$now");
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
global $tts, $PT2, $trainData, $TT_FILE, $SITE_DATA_FILE, $tmsStatus, $now, $DIRECTORY;
  msgLog("Loading time table \"$DIRECTORY/$TT_FILE\"");
  @include("$DIRECTORY/$TT_FILE");
  // PT1 => PT2 FIXME
  if (!isset($timeTables) or checkTimeTable($timeTables)) {
    $tts = array();
    $tmsStatus = TMS_NO_TT;
    sendCommandRBC("TMS_HB|$tmsStatus|$now");
  } else {
    $tts = $timeTables;
    $tmsStatus = TMS_OK;
    sendCommandRBC("TMS_HB|$tmsStatus|$now");
  }
}

function signalExists($signal) {
global $PT2;
  return array_key_exists($signal, $PT2) and 
    ($PT2[$signal]["element"] == "SU" or
     $PT2[$signal]["element"] == "SD" or
     $PT2[$signal]["element"] == "BSB" or
     $PT2[$signal]["element"] == "BSE");
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
global $PT2, $ttError;
  $ttError = false;
  foreach ($tts as $trn => $tt) {
    if ($trn != "") {
      foreach ($tt["locationTable"] as $locationIndex => $location) {
        if (!signalExists($location["location"])) {
          ttError("TRN: $trn LocIndex $locationIndex Unknown location: {$location["location"]}");
        }
        // check valid time format FIXME
        foreach ($location["actionTable"] as $actionIndex => $action) {
          if (isset($location["time"]) and $location["time"] != "" and isset($action["delay"]) and $action["delay"] != "") {
            ttWarning(
              "TRN: $trn LocIndex: $locationIndex, Action: $actionIndex both \"time\" and \"delay\" are specified - \"delay\" will be ignored");
          }
          if (isset($action["action"])) {
            switch ($action["action"]) {
              case "E": // End
              case "D": // Change Dir
              break;
              case "N": // End, New trn
                if (!isset($action["xTrn"]) or !isset($tts[$action["xTrn"]])) {
                  ttError("TRN: $trn LocIndex: $locationIndex Action: $actionIndex \"xTrn\" missing or unknown xTrn for action \"N\"");
                }
              break;
              case "W": // Wait for meeting train
                if (!signalExists($action["dest"])) {
                  ttError("TRN: $trn action $actionIndex Unknown route destination: {$action["dest"]}");
                }
                if (!isset($action["xTrn"]) or ($action["xTrn"] != "*" and !isset($tts[$action["xTrn"]]))) {
                  ttError("TRN: $trn LocIndex: $locationIndex Action: $actionIndex \"xTrn\" missing or unknown xTrn for action \"W\"");
                }
                if (!isset($action["xSig"]) or !signalExists($action["xSig"]) ) {
                  ttError("TRN: $trn LocIndex: $locationIndex Action: $actionIndex \"xSig\" missing or unknown xSig for action \"W\"");
                }        
              break;
              case "R": // set Route
              case " ":
                if (!signalExists($action["dest"])) {
                  ttError("TRN: $trn action $actionIndex Unknown route destination: {$action["dest"]}");
                }
              break;
              default:
                ttError("TRN: $trn  LocIndex: $locationIndex Action: $actionIndex, Unknown \"action\" >{$action["action"]}<");
            }
          } else {
            ttError("TRN: $trn  LocIndex: $locationIndex Action: $actionIndex, \"action\" missing");        
          }
        }
        // check route existance and signal orientation - partly handled by time table web-editor FIXME
      }
    }
  }
  return $ttError;
}

// ---------------------------------------------------------------------------------------------------------- TMS
function initTMS() {
  global $SITE_DATA_FILE, $trainData, $PT2;
  foreach ($trainData as $index => &$train) { // train data
    resetTrainState($train);
  }
}

function resetTrainState(&$train) {
  $train["trn"] = "";
  $train["prevLocation"] = "";            // start signal of trains previous location
  $train["prevLocationState"] = "";       // driving state of train at previous location
  $train["location"] = "";                // Current location
  $train["routeState"] = RS_NO_ROUTE;     // State of the route currently being set
  $train["locationIndex"] = 0;            // First applicable location table entry
  $train["ttDir"] = D_UDEF;               // Applicable driving direction for time table
  $train["trnState"] = TRN_UNASSIGNED;    // TrainNumber state
  $train["ETD"] = 0;
  $train["blockedTimer"] = 0;
}

function processTrainLocaiton($trainIndex, $nextElementName, $state) {
// $nextElementName is the next signal or bufferstop seen from the train
// $state is "S" if the train is at standstill occupying the element in rear of $nextElementName - i.e. at stopping location
// otherwise "A" (approaching)
  global $tts, $trainData, $now, $PT2;
  $train = &$trainData[$trainIndex];
  $trn = $train["trn"];
//print "TrainLoc {$train["ID"]}, $nextElementName, $state\n";
  if ($train["trnState"] != TRN_UNASSIGNED and $train["trnState"] != TRN_UDEF) { // valid trn is assigned
    $tt = $tts[$trn];
    if ($train["ttDir"] == D_UP and ($PT2[$nextElementName]["element"] == "SU" or $PT2[$nextElementName]["element"] == "BSE") 
      or $train["ttDir"] == D_DOWN and ($PT2[$nextElementName]["element"] == "SD" or $PT2[$nextElementName]["element"] == "BSB")) {
      // direction of next signal is matching applicable time table direction
      if ($nextElementName != $train["prevLocation"] or $state != $train["prevLocationState"]) {
// FIXME check also if train mode has changed e.g. from SR
        // and location or approach state has changed -> check for new action
//print "loc/app changed\n";
//print_r($train);
        $train["prevLocation"] = $nextElementName;
        $train["prevLocationState"] = $state;
        $locationIndex = -1;
        for ($i = $train["locationIndex"]; $i < count($tt["locationTable"]); $i++) {
          if ($nextElementName == $tt["locationTable"][$i]["location"]
            and ($state == "S" or isset($tt["locationTable"][$i]["approach"]))) {
            $locationIndex = $i;
            break;
          }
        }
        if ($locationIndex >= 0) { // ---------------------------------- Time table has valid entry for this location and progress state
          $train["location"] = $nextElementName;
          $train["routeState"] = RS_NO_ROUTE;
          $train["locationIndex"] = $locationIndex;
          $train["actionIndex"] = 0;
          $location = $tt["locationTable"][$locationIndex];
          $action = $location["actionTable"][$train["actionIndex"]];
print "New situation: Train: {$train["ID"]} trn: $trn locationIndex: $locationIndex, action: {$action["action"]}, nextElementName: $nextElementName, state: $state\n";
          switch ($action["action"]) {
            case "E": // location is destination
              if ($state == "S") {
                $train["routeState"] = RS_COMPLETED;
                $train["trnState"] = TRN_COMPLETED;
              }
            break;
            case "N": // location is destination, assign new TRN
              if ($state == "S") {
                $train["routeState"] = RS_COMPLETED;
                $train["trnState"] = TRN_COMPLETED;
                if (isset($action["delay"]) and is_numeric($action["delay"])) {
                  $train["ETD"] = $now + $action["delay"];       
                  sendETD($trainIndex,$train["ETD"]);
                } else { // set next Trn
                  resetTrainState($train);
                  $train["trn"] = $action["xTrn"];
                  $train["trnState"] = TRN_NORMAL;
                  $train["ttDir"] = $PT2[$tts[$train["trn"]]["locationTable"][0]["location"]]["element"] == "SU" ? D_UP : D_DOWN;
                  setTRN($trainIndex, $train["trn"]);
                }
              }
            break;
            case "D": // Change applicable driving direction
              if ($state == "S" or isset($location["approach"])) {
                if (false and isset($action["delay"]) and is_numeric($action["delay"])) { // Needed?? FIXME
                  $train["routeState"] = RS_WAIT;
                  $train["trnState"] = TRN_WAITING;   
                  $train["ETD"] = $now + $action["delay"];       
                  sendETD($trainIndex,$train["ETD"]);
                } else {
                print "change dir\n";
                  $train["ttDir"] = $train["ttDir"] == D_UP ? D_DOWN : D_UP;
                  $train["prevLocation"] = "";
                  $train["prevLocationState"] = ""; 
                }
              }
            break;
            case " ": // set route
            case "R":
              if ($state == "S" or isset($location["approach"])) {
                if (isset($location["time"]) and $location["time"] != "") { //                await departure time
                  $train["routeState"] = RS_WAIT_DEPARTURE;
                  $train["trnState"] = TRN_WAITING;
                  $train["ETD"] = departureTime($location["time"]);
                  sendETD($trainIndex,$train["ETD"]);
                } elseif (isset($action["delay"]) and is_numeric($action["delay"])) { //      await delay
                  $train["routeState"] = RS_WAIT;
                  $train["trnState"] = TRN_WAITING;   
                  $train["ETD"] = $now + $action["delay"];       
                  sendETD($trainIndex,$train["ETD"]);
                } else { //                                                                   set route unconditional
                  $train["routeState"] = RS_PENDING;
                  $train["trnState"] = TRN_NORMAL;
print "A ";
                  setRoute($trainIndex, $location["location"], $action["dest"]);
                }
              }
            break;
            case "W": // wait for another trai
              if ($state == "S"  or isset($location["approach"])) { // At stopping location, await meeting train
                $train["awaitTrn"] = $action["xTrn"];    // which trn to wait for
                $train["awaitSignal"] = $action["xSig"]; // at which signal 
                $train["routeState"] = RS_WAIT_TRAIN;
                $train["trnState"] = TRN_WAITING;
                $arrived = false; // check if meeting trail has arrived already
                foreach ($trainData as $wIndex => $waitingTrain) {
                  if ($waitingTrain["trn"] != $trn) {
                    if ($train["awaitTrn"]== "*") { // wait for any train
                      if (false and $train["awaitSignal"] == $waitingTrain["location"]
                      // FIXME either non-trn-train or trn-train waiting for any train
                      // FIXME what if that train is waiting for a specific train?? Ignore
                      ) {
                        $arrived = true;
                        $waitingTrainIndex = $wIndex;
                        break; // only one train can wait for this train
                      }                    
                    } else { // wait for specific train
                      if ($train["awaitTrn"] == $waitingTrain["trn"] and // this train is waiting for that train at that location
                        $train["awaitSignal"] == $waitingTrain["location"] and
                        $waitingTrain["awaitTrn"] == $trn and // that train is waiting for this train at this location
                        $waitingTrain["awaitSignal"] == $nextElementName) { // FIXME check route/trn-state of that train??
                        $arrived = true;
                        $waitingTrainIndex = $wIndex;
                        break; // only one train can wait for this train
                      }
                    }
                  }  // don't check this train
                }
                if ($arrived) { // Train to meet has already arrived 
                  $waitingTrain = &$trainData[$waitingTrainIndex];
                  $waitingTrainLocation = $tts[$waitingTrain["trn"]]["locationTable"][$waitingTrain["locationIndex"]];
                  $waitingTrainAction =
                    $tts[$waitingTrain["trn"]]["locationTable"][$waitingTrain["locationIndex"]]["actionTable"][$waitingTrain["actionIndex"]];
                  // Check departure time in tt for this FIXME
                  if (isset($action["delay"]) and is_numeric($action["delay"])) { // await delay, this train
                    $train["routeState"] = RS_WAIT;
                    $train["trnState"] = TRN_WAITING;   
                    $train["ETD"] = $now + $action["delay"];       
                    sendETD($trainIndex,$train["ETD"]);
                  } else {
                    $train["routeState"] = RS_PENDING;
                    $train["trnState"] = TRN_NORMAL;
                    print "B ";
                    setRoute($trainIndex, $location["location"], $action["dest"]);
                    sendETD($trainIndex);
                  }
                  // Check departure time in tt for that FIXME
                  if (isset($waitingTrainAction["delay"]) and is_numeric($waitingTrainAction["delay"])) { // await delay, that train
                    $waitingTrain["routeState"] = RS_WAIT;
                    $waitingTrain["trnState"] = TRN_WAITING;   
                    $waitingTrain["ETD"] = $now + $waitingTrainAction["delay"];
                    sendETD($waitingTrainIndex,$waitingTrain["ETD"]);
                  } else {
                    $waitingTrain["routeState"] = RS_PENDING;
                    $waitingTrain["trnState"] = TRN_NORMAL;
                    print "C ";
                    setRoute($waitingTrainIndex, $waitingTrainLocation["location"], $waitingTrainAction["dest"]);
                    sendETD($waitingTrainIndex);
                  }
                } else { // Train to meet has not yet arrived
                  sendDepTxt($trainIndex, "Waiting for TRN {$train["awaitTrn"]} at {$train["awaitSignal"]}");
                }
              }
            break;
            case "M": // Manual - operator to set next route  -------------------- how does operator confirm this?? By setting the route??  FIXME
              $train["routeState"] = RS_CONFIRM;
              $train["trnState"] = TRN_CONFIRM;
            break;
            default:
              errLog("Error: Unknown action >{$action["action"]}< for trn $trn");
          }
        } else { // no action specified in tt for this location and direction -> ignore
print "Warning: No action for this location: >$nextElementName< >$state<\n";
        }
      }
    } // else ignore location for opposite direction
    sendTRNstate($trainIndex, $train["trnState"]);
  } else { // trn not assigned, only register location if at stand still - for which direction?
  // check if any trn-train is waiting for any train at this location
  // FIXME what if that train is waiting for a specific train?? Do nothing
  // FIXME how to detect when a non-trn-train has departured? Necessary?
  }
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
global $trainData, $tts, $now, $PT2;
  foreach ($trainData as $trainIndex => &$train) {
    if ($train["trnState"] != TRN_UNASSIGNED and $train["trnState"] != TRN_UDEF ) { // FIXME change to positive test
      $tt = $tts[$train["trn"]];
      $location = $tt["locationTable"][$train["locationIndex"]];
      switch ($train["routeState"]) {
        case RS_WAIT: // if time elapsed set route
        case RS_WAIT_DEPARTURE: // if departure time, set route
          if ($now > $train["ETD"]) { // waiting time elapsed
            $train["routeState"] = RS_PENDING;
            $train["trnState"] = TRN_NORMAL;
            $action = $location["actionTable"][$train["actionIndex"]];
            print "D ";
            setRoute($trainIndex, $location["location"], $action["dest"]);
            sendETD($trainIndex); // clear indication at HMI
          }
        break;
        case RS_COMPLETED:
          $action = $location["actionTable"][$train["actionIndex"]];
          if ($action["action"] == "N" and $now > $train["ETD"]) {
            resetTrainState($train);
            $action = $location["actionTable"][$train["actionIndex"]];
            $train["trn"] = $action["xTrn"];
            $train["trnState"] = TRN_NORMAL;
            $train["ttDir"] = $PT2[$tts[$train["trn"]]["locationTable"][0]["location"]]["element"] == "SU" ? D_UP : D_DOWN;
            setTRN($trainIndex, $train["trn"]);
            sendETD($trainIndex); // clear indication at HMI
          }
        break;
        case RS_PENDING: // optional alarm if route setting takes too long FIXME
        break;
        case RS_BLOCKED:
          if ($now > $train["blockedTimer"] + RETRY_ROUTE_TIMEOUT) { // Reset action alternatives
            $train["actionIndex"] = 0;
            $action = $location["actionTable"][$train["actionIndex"]];
            $train["routeState"] = RS_PENDING;
            $train["trnState"] = TRN_NORMAL;
            print "E ";
            setRoute($trainIndex, $location["location"], $action["dest"]);
          }
        break;
        case RS_ARS_DISABLED:
          if ($now > $train["blockedTimer"] + RETRY_ROUTE_TIMEOUT) { // set same route again
            $train["routeState"] = RS_PENDING;
            $train["trnState"] = TRN_NORMAL;
            $action = $location["actionTable"][$train["actionIndex"]];
            print "F ";
            setRoute($trainIndex, $location["location"], $action["dest"]);
          }
        break;
        default: // Do nothing
      }
    }
  }
}

function processRouteStatus($trainIndex, $routeSettingStatus) {
global $trainData, $tts, $now;
  $train = &$trainData[$trainIndex];
  if ($train["routeState"] == RS_PENDING) { // Only evaluate route status if route setting is pending
    switch($routeSettingStatus) { // FIXME check if route setting status is outdated or applies to current situation
      case RSR_ROUTE_SET:
        if ($train["routeState"] != RS_PENDING) print "Warning: Route set, but route state not RS_PENDING\n";
        $train["routeState"] = RS_ROUTE_SET;
        $train["trnState"] = TRN_NORMAL;
      break;
      case RSR_REJECTED: // Route is impossible, time table failure
        $train["routeState"] = RS_NO_ROUTE;
        $train["trnState"] = TRN_FAILED;
        errLog("TRN {$train["trn"]}: LocIndex: {$train["locationIndex"]} ActionIndex: {$train["actionIndex"]} rejected as impossible");
      break;
      case RSR_ARS_DISABLED:
        $train["blockedTimer"] = $now;
        $train["routeState"] = RS_ARS_DISABLED;
        $train["trnState"] = TRN_ARS_DISABLED;
      break;
      case RSR_INHIBITED: // Route inhibited
      case RSR_BLOCKED: // Route occupied or temporary blocked by other routes
        // Check for alternative routes in action table. Wait a while if no more alternatives.
        $tt = $tts[$train["trn"]];
        $location = $tt["locationTable"][$train["locationIndex"]];
        if ($train["actionIndex"] < count($location["actionTable"]) - 1) {
          $train["actionIndex"]++;
          $train["routeState"] = RS_PENDING;
          $train["trnState"] = TRN_NORMAL;
          $action = $location["actionTable"][$train["actionIndex"]];
          // FIXME need for check if action is route setting at all??
          print "G ";
          setRoute($trainIndex, $location["location"], $action["dest"]);
        } else {
          $train["blockedTimer"] = $now;
          $train["routeState"] = RS_BLOCKED;
          $train["trnState"] = TRN_BLOCKED;            
        }
      break;
      default:
        errLog("processRouteStatus - default ($routeSettingStatus)");
    }
    sendTRNstate($trainIndex, $train["trnState"]);
  }
}

function processNotificationRBC($data) {
global $tts, $trainData, $run, $PT2;
  $param = explode(" ",$data);
  switch ($param[0]) {
    case "setTRN": // Param: trainIndex (trn)
      if (isset($param[1])) {
        $trainIndex = $param[1];
        if (isset($param[2])) {
          $trn = $param[2];
          if (array_key_exists($trn,$tts)) { // trn known in time table
            resetTrainState($trainData[$trainIndex]);
            $trainData[$trainIndex]["trn"] = $trn;
            $trainData[$trainIndex]["trnState"] = TRN_NORMAL;
            $trainData[$trainIndex]["ttDir"] = $PT2[$tts[$trn]["locationTable"][0]["location"]]["element"] == "SU" ? D_UP : D_DOWN;
                // FIXME first entry in tt is assumed to be a signal from which the tt direction is determined - check needed??
            sendETD($trainIndex); // clear indication at HMI
          } else { // Unknown trn
            resetTrainState($trainData[$trainIndex]);
            $trainData[$trainIndex]["trn"] = $trn;
            $trainData[$trainIndex]["trnState"] = TRN_UDEF; 
            sendETD($trainIndex); // clear indication at HMI
          }
        } else { // Clear TRN for train
          resetTrainState($trainData[$trainIndex]);
          $trainData[$trainIndex]["trnState"] = TRN_UNASSIGNED; 
          sendETD($trainIndex); // clear indication at HMI
        }
        sendTRNstate($trainIndex, $trainData[$trainIndex]["trnState"]);
      } else {
        errLog("Warning: RBC command setTRN without valid train index");
      }
    break;
    case "Hello":
    break;
    case "trainLoc":
      processTrainLocaiton($param[1], $param[2], $param[3], $param[4]);
    break;
    case "routeStatus":
      processRouteStatus($param[1], $param[2]);
    break;
    case "loadTT":
      readTimeTables();
    break;
    case "exitTMS":
//  print_r($trainData);  
      $run = false;
    break;
    default:
      errLog("Ups, unimplemented notification from RBC {$param[0]}");
  }
}

function setRoute($trainIndex, $start, $destination) {
  print "setRoute | $trainIndex $start -> $destination\n";
  sendCommandRBC("setRoute|$trainIndex|$start|$destination");
}

function sendTRNstate($trainIndex, $status) {
  sendCommandRBC("trnStatus|$trainIndex|$status");
}

function setTRN($trainIndex, $trn) {
  sendCommandRBC("setTRN|$trainIndex|$trn");
}

function sendETD($trainIndex, $etd = 0) {
  sendCommandRBC("etd|$trainIndex|".($etd != 0 ? date("H:i:s", $etd) : ""));
}

function sendDepTxt($trainIndex, $depTxt) {
  sendCommandRBC("etd|$trainIndex|$depTxt");
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
    sendCommandRBC("Hello this is TMS version $version");
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

function versionInfo() { // Used?? FIXME
global $VERSION, $PT2_VERSION;
  fwrite(STDERR,"TMS Engine, version: $VERSION\n");
}

function CmdLineParam() {
global $debug, $background, $TMS_CONFIG, $VERSION, $argv, $RBC_SERVER_ADDR, $TT_FILE, $PT2_FILE, $DIRECTORY, $TRAIN_DATA_FILE;
  if (in_array("-h",$argv)) {
    fwrite(STDERR,"TMS Engine, version $VERSION
Usage:
-b, --background      Start as daemon
-f <file name>        Configuration file for TMS engine
-pt2 <file name>      PT2 data file
-tt <file name>       Time tables file
-D <directory>        use <directory> as working directory for all files. Must be given before -f -tt and -pt2 in order to take effect

-IP <IP-address>      IP-address of RBC to contact
-d                    Enable debug info, level all
");
    exit();
  }
  next($argv);
  while ($opt = next($argv)) {
    switch ($opt) {
    case "-f":
      $p = next($argv);
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
      $p = next($argv);
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
      $p = next($argv);
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
      $p = next($argv);
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
    case "-IP":
      $p = next($argv);
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
  if (is_readable("$DIRECTORY/$TMS_CONFIG")) {
    require("$DIRECTORY/$TMS_CONFIG");
  } else {
    fwrite(STDERR,"Warning: Cannot read TMS config file: $DIRECTORY/$TMS_CONFIG\n");
    Fwrite(STDERR,"Using default parameters...\n");
  } // config file is optional
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
  if (!is_readable("$DIRECTORY/$PT2_FILE")) {
    fwrite(STDERR,"Error: Cannot open PT2 data file: $DIRECTORY/$PT2_FILE\n");
    exit(1); // PT1 data is mandatory
  }
  if (!is_readable("$DIRECTORY/$TRAIN_DATA_FILE")) {
    fwrite(STDERR,"Error: Cannot open Train data file: $DIRECTORY/$TRAIN_DATA_FILE\n");
    exit(1);
  }
  include("$DIRECTORY/$PT2_FILE");
  $PT2 = $PT1;
  unset($PT1);
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
