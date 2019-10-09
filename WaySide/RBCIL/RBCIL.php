#!/usr/bin/php
<?php
// WinterTrain, RBC/IL

//--------------------------------------- Abus configuration
$arduino = 0x33; // CBU Master, I2C addresse
$ABUS = "";

//--------------------------------------- Default Configuration
$VERSION = "02P03";  // What does this mean with git?? FIXME
$HMIport = 9900;
$MCePort = 9901;
$HMIaddress = "0.0.0.0";
$ABUS_GATEWAYaddress = "10.0.0.201";
$ABUS_GATEWAYport = 9200;

$radio = "USB"; // USB, ABUS or "" (none)
$RADIO_DEVICE = "/dev/ttyUSB0";
//$RADIO_DEVICE = "/dev/null";
//$RADIO_DEVICE = "/dev/serial/by-path/platform-3f980000.usb-usb-0:1.2:1.0-port0"; // Path to radio module (JeeLink) connected via USB
// RF12 group
$RF12GROUP = 101;
$RBC_RADIO_ID = 10;
$MaxAbusBuf = 20;
$AbusMasterI2Caddress = 0x33; // Address of AbusMaster (arduino) connected to RasPI via I2C
define("N_I2CSET",2);

// File names
$RBCIL_CONFIG = "RBCILconf.php";
$DATA_FILE = "../SiteData/CBU/CBU.php";
//$DATA_FILE = "../SiteData/W57/W57.php";
$ERRLOG = "Log/RBCIL_ErrLog.txt";
$MSGLOG = "Log/RBCIL.log";

// ------------------------------------------------------------------------ To be moved to conf. file
$radioLinkAddr = 150;
$SR_MAX_SPEED = 100;
$SH_MAX_SPEED = 48;
$ATO_MAX_SPEED = 45;
$FS_MAX_SPEED = 50;
$restorePos = true;

// ---------------------------------------- Timing
define("EC_TIMEOUT",5);
define("TRAIN_COM_TIMEOUT",5);
define("LX_WARNING_TIME",2);
define("POSITION_TIMEOUT", 10);
define("PUMP_TIMEOUT",4);
define("EC_RETRY_DELAY", 120000); // delat before Abus retry in uS

// ----------------------------------------Enummerations
// Route
define("R_UNSUPERVISED", 0);
define("R_IDLE", 1);
define("R_LOCKED", 2);
// Interlocking Element state
define("E_UNSUPERVISED",0);       // All
define("E_STOP",10);              //Signal
define("E_PROCEED",11);
define("E_PROCEEDPROCEED",12);
define("E_STOP_FACING",13);       //Signal
define("E_LEFT",20);              // Point, supervised left
define("E_RIGHT",21);             // supervised right
define("E_MOVING",22);            //   point is (supposed to be) movingng
define("E_LX_DEACTIVATED",50);    // Normal state
define("E_LX_WARNING",51);        // Warning signals flashing
define("E_LX_ACTIVATED",52);      // Barrier closed
define("E_LX_OPENING",53);        // Deaktiveret, opening
// Interlocking Element blocking
define("B_UNBLOCKED",10);         // Element not blocked
define("B_BLOCKED_RIGHT",11);     // Blocked via command
define("B_BLOCKED_LEFT",12);
define("B_CLAMPED_RIGHT",13);     // Physically clamped, marked as clamped in PT1
define("B_CLAMPED_LEFT",14);
define("B_BLOCKED_STOP",20);
// Interlocking Element commands
define("C_TOGGLE",10);            // Point throw commands
define("C_LEFT",20);
define("C_RIGHT",21);
define("C_HOLD",22);              // Hold in expectedLie
define("C_RELEASE",23);           // Release held point
// Direction
define("D_UDEF",0);
define("D_DOWN",1);
define("D_UP",2);
define("D_STOP",3);
// Track status   
define("T_UNSUPERVISED",0);       // Track state unknown
define("T_OCCUPIED_DOWN",1);      // Track occupied, train moving in direction down
define("T_OCCUPIED_UP",2);
define("T_OCCUPIED_STOP",3);
//define("T_LOCKED", 4);          // Route locking state implemented by $element["routeState"]
define("T_CLEAR",5);
// Train mode
define("M_UDEF",0);
define("M_N",5);
define("M_SR",1);
define("M_SH",2);
define("M_FS",3);
define("M_ATO",4);
define("M_ESTOP",7);
// Train power mode
define("P_UDEF",0);
define("P_R",1);
define("P_L",2);
define("P_NOPWR",3);
// Interlocking orders (internal)
define("IL_P_RIGHT",10);
define("IL_P_LEFT",11);
define("IL_LX_ACTIVATE",12);
define("IL_LX_DEACTIVATE",13);

// -------------------------------------- EC enummerations
// Order
define("O_ROADPASS",41);
define("O_ROADSTOP",42);
define("O_STOP",31);
define("O_PROCEED",32);
define("O_PROCEEDPROCEED",33);
define("O_CLOSE_BARRIER",21);
define("O_OPEN_BARRIER",22);
define("O_RIGHT",11);
define("O_LEFT",12);
define("O_RIGHT_HOLD",13);
define("O_LEFT_HOLD",14);
define("O_RELEASE",19);

// Physical status from EC
define("S_UNSUPERVISED",0);
define("S_STOP",1);
define("S_PROCEED",2);
define("S_PROCEEDPROCEED",3);
define("S_VOID",10);            // No physical signal connected (i.e. type marker board)
define("S_BARRIER_CLOSED",1);
define("S_BARRIER_OPEN",2);
define("S_U_RIGHT",5);          // Point, unsupervised, previous command was throw right
define("S_U_LEFT",6);           // Point, unsupervised, previous command was throw left
define("S_U_RIGHT_HOLDING",7);  // Point, unsupervised, previous command was throw right, holding
define("S_U_LEFT_HOLDING",8);   // Point, unsupervised, previous command was throw left, holding

//Track traversal feedback
define("TRACK_TRAVERSALE_REJECT", 0);
define("TRACK_TRAVERSALE_ACCEPT_DO_NOTHING", 1);
define("TRACK_TRAVERSALE_ACCEPT_ACTIVATE_PAYLOAD", 2);

// -------------------------------------- Txt
$MODE_TXT = [0 => "Udef", 1 => "SR", 2 => "SH", 3 => "FS", 4 => "ATO", 5 => "N", ];
$DIR_TXT = [0 => "Udef", 1 => "Down", 2 => "Up", 3 => "Stop",];
$PWR_TXT = [0 => "NoComm", 1 => "R", 2 => "L", 3 => "No PWR",];
$ACK_TXT = [0 => "NO_MA", 1 => "MA_ACK"];
$TOSTATE_TXT = [0 => "Udef.", 1 => "DMI", 2 => "Remote take-over", 3 => "Pending take-over", 4 => "Pending release"];

//--------------------------------------- System variable
$debug = 0x00; $background = FALSE; $run = true; $doInitEC = true;
$pollTimeout = 0;
$pumpTimeout = 0;
$timeoutUr = 0;
$startTime = time();

//--------------------------------------- Server variable
$clients = array();
$clientsData = array();
$inCharge = false;
$inChargeMCe = false;
$radioBuf = "";

//--------------------------------------- RBCIL variable
$PT1 = array();
$HMI = array();
$points = array();
$balises = array();
$balisesID = array();
$signals = array();
$bufferstops = array();
$levelCrossings = array();
$triggers = array();
$EC = array();
$lockedRoutes = array();
$emergencyStop = false;

$errorFound = false;
$totalElement = 0;

$SRallowed = 0;
$SHallowed = 0;
$FSallowed = 0;
$ATOallowed = 0;


$testStep = 0;
$testBg = "BG06";
$testDist = "30";
//---------------------------------------------------------------------------------------------------------- System 
cmdLineParam();
if ($ABUS == "cbu") {
//  include '/home/jabe/scripts/AbusMasterLib.php'; // must be included at global level
}
prepareMainProgram();
versionInfo();
processPT1();
initRBCIL();
forkToBackground();
initMainProgram();
AbusInit();
initEC();
initServer();
do {
  $now = time();
  if ($now != $pollTimeout) { // every 1 second
    $pollTimeout = $now;
    checkECtimeout();
    checkTrainTimeout();
    processLX();
    pollEC();
    pollRadioLink();
    RBC();
    updateHMI();
    updateMCe();
  }
  if ($now - $pumpTimeout >= PUMP_TIMEOUT) {
    $pumpTimeout = $now;
    pumpSignal();
  }
//  if ($ABUS == "cbu" and $timeoutUr <= $now) {
//    $urTimeout = $now + 60;
//    CBUupdate();
//  }
  Server();
} while ($run);
msgLog("Exitting...");

//-----------------------------------------------------------------------------------------------------------------------  Analyse PT1
function processPT1() {
global $DATA_FILE, $trainData, $PT1_VERSION, $PT1, $HMI, $errorFound, $totalElement, $points, $signals, $levelCrossings,
  $balises, $balisesID, $bufferstops, $triggers, $tracks;

  function inspect($this, $prevName, $up) { // check each edge in the graph
  global $PT1, $nInspection, $totalElement, $errorFound;
    $nInspection +=1;
    $name = $this["name"];
//  print "Inspecting node $name ($nInspection) ".($up ? "Up" : "Down")."\n";
    if ($nInspection < 3 * $totalElement) {
      if (array_key_exists($this["name"], $PT1)) {
        $thisNode = $PT1[$name];
        if ($up) { // ----------------------- UP
          switch ($thisNode["element"]) {
            case "BL":
            case "TK":
            case "TG":
            case "LX":
            case "PHTU":
            case "PHTD":
              $neighbor = $thisNode["D"];
              $PT1[$name]["checked"] = true;
              inspect($thisNode["U"], $name, true);
            break;
            case "PF":
              $neighbor = $thisNode["T"];
              $PT1[$name]["checked"] = true;
              inspect($thisNode["R"], $name, true);
              inspect($thisNode["L"], $name, true);
            break;
            case "PT":
              if ($prevName == $thisNode["R"]["name"]) {
                $neighbor = $thisNode["R"];
                if (!$thisNode["checked"]) {
                  $PT1[$name]["checked"] = true;
                  inspect($thisNode["T"], $name, true);
                  inspect($thisNode["L"], $name, false);
                }
              } elseif ($prevName == $thisNode["L"]["name"]) {
                $neighbor = $thisNode["L"];
                if (!$thisNode["checked"]) {
                  $PT1[$name]["checked"] = true;
                  inspect($thisNode["T"], $name, true);
                  inspect($thisNode["R"], $name, false);
                }
              } else {
                $PT1[$name]["checked"] = true;
                inspect($thisNode["T"], $name, true);
                $neighbor = ["name" => "","dist" => 0];
                print "Error: ($prevName => $name) Inconsistant branch reference\n";
                $errorFound = true;
              }
            break;
            case "SU":
              $neighbor = $thisNode["D"];
              $PT1[$name]["checked"] = true;
              inspect($thisNode["U"], $name, true);
            break;
            case "SD":
              $neighbor = $thisNode["D"];
              $PT1[$name]["checked"] = true;
              inspect($thisNode["U"], $name, true);
            break;
            case "BSB":
              print "Error: ($prevName => $name) BSB cannot be used as end of track for direction up.\n";
              $neighbor = ["name" => "","dist" => 0];
              $errorFound = true;
            break;
            case "BSE":
              $PT1[$name]["checked"] = true;
              $neighbor = $thisNode["D"];
            break;
            default :
              print "Error: ($prevName => $name) Unknown element type: ".$thisNode["element"]."\n";
              $errorFound = true;
          }
        } else { //------------------------------------------- DOWN
          switch ($thisNode["element"]) {
            case "BL":
            case "TR":
            case "TG":
            case "LX":
            case "PHTU":
            case "PHTD":
              $neighbor = $thisNode["U"];
              $PT1[$name]["checked"] = true;
              inspect($thisNode["D"], $name, false);
            break;
            case "PT":
              $neighbor = $thisNode["T"];
              $PT1[$name]["checked"] = true;
              inspect($thisNode["R"], $name, false);
              inspect($thisNode["L"], $name, false);
            break;
            case "PF":
              if ($prevName == $thisNode["R"]["name"]) {
                $neighbor = $thisNode["R"];
                if (!$thisNode["checked"]) {
                  $PT1[$name]["checked"] = true;
                  inspect($thisNode["T"], $name, false);
                  inspect($thisNode["L"], $name, true);
                }
              } elseif ($prevName == $thisNode["L"]["name"]) {
                $neighbor = $thisNode["L"];
                if (!$thisNode["checked"]) {
                  $PT1[$name]["checked"] = true;
                  inspect($thisNode["T"], $name, false);
                  inspect($thisNode["R"], $name, true);
                }
              } else {
                inspect($thisNode["T"], $name, false);
                $neighbor = ["name" => "","dist" => 0];
                print "Error: ($prevName => $name) Inconsistant branch reference\n";
                $errorFound = true;
              }
            break;
            case "SU":
              $neighbor = $thisNode["U"];
              $PT1[$name]["checked"] = true;
              inspect($thisNode["D"], $name, false);
            break;
            case "SD":
              $neighbor = $thisNode["U"];
              $PT1[$name]["checked"] = true;
              inspect($thisNode["D"], $name, false);
            break;
            case "BSB":
              $PT1[$name]["checked"] = true;
              $neighbor = $thisNode["U"];
            break;
            case "BSE":
              print "Error: ($prevName => $name) BSE cannot be used as end of track for direction down.\n";
              $neighbor = ["name" => "","dist" => 0];
              $errorFound = true;
            break;
              default :
              print "Error: ($prevName => $name) Unknown element type: ".$thisNode["element"]."\n";
              $errorFound = true;
          }
        }
        if ($neighbor["name"] != $prevName) {
          print "Error: ($prevName => $name) Inconsistant reference\n";
          $errorFound = true;
        }
      } else {
        print "Error: ($prevName => $name) Unknown element $name\n";
        $errorFound = true;
      }
    } else {
      print "Error: Looping references detected.\n";
      $errorFound = true;
    }
//  print "Done with node $name ".($up ? "Up" : "Down")."\n";
  }

  require($DATA_FILE);

  if (array_key_exists("", $PT1)) { unset($PT1[""]); } // delete any remaining template entry
  $totalElement = count($PT1);
  foreach ($PT1 as $name => &$element) {  // Check each node and generate various lists and states
    $element["checked"] = false;
    $element["routeState"] = R_IDLE;
    $element["trackState"] = T_CLEAR;
    $element["trainIDs"] = [];
//    $element["locked"] = False;
    switch ($element["element"]) {
      case "BL":
        $balises[] = $name;
        $balisesID[$element["ID"]] = $name;
      break;
      case "TK":
        $tracks[] = $name;
      break;
      case "TG":
        $triggers[] = $name;
      break;
      case "PHTU":
      case "PHTD":
        if (!isset($PT1[$element["holdPoint"]])) {
          $errorFound = true;
          print "Error: Unknown point \"{$element["holdPoint"]}\" specified in PHTU/PHTD: $name\n";
        }
      break;
      case "LX":
        $levelCrossings[] = $name;
        $element["state"] = E_LX_DEACTIVATED; // Logical state
        $element["status"] = S_UNSUPERVISED; // Combined physical status
        $element["signalStatus"] = S_UNSUPERVISED;
        $element["barrierStatus"] = S_UNSUPERVISED;
        $element["prevTrackState"] = T_CLEAR;
      break;
      case "PF":
      case "PT":
        $points[] = $name;
        $element["pointHeld"] = false;
        switch($element["supervisionState"]) {
          case "U": // Unsupervised
            $element["state"] = E_UNSUPERVISED;
            $element["expectedLie"] = E_RIGTH;  // FIXME expectedLie must be either E_RIGHT or E_LEFT, 
            $element["blockingState"] = B_UNBLOCKED;
          break;
          case "S": // Suprvision state simulated
            $element["state"] = E_RIGHT;
            $element["expectedLie"] = E_RIGHT; 
            $element["blockingState"] = B_UNBLOCKED;
          break;
          case "P": // real point machine
            $element["state"] = E_UNSUPERVISED; // Logical state
            $element["expectedLie"] = E_RIGHT; // Just to start somewhere
            $element["blockingState"] = B_UNBLOCKED;
          break;
          case "CR": // Clamped right
            $element["state"] = E_RIGHT;
            $element["expectedLie"] = E_RIGHT;
            $element["blockingState"] = B_CLAMPED_RIGHT;
          break;
          case "CL": // Clamped left
            $element["state"] = E_LEFT;
            $element["expectedLie"] = E_LEFT;
            $element["blockingState"] = B_CLAMPED_LEFT;
          break;
          default:
            print "Element $name: Unknown supervision state.\n";
            $errorFound = true;
          break;
        }
      break;
      case "SU":
      case "SD":
        $signals[] = $name;
        $element["state"] = E_STOP; // Logical state
        $element["status"] = $element["type"] == "MB" ? S_VOID : S_UNSUPERVISED; // Physical status from EC
        $element["blockingState"] = B_UNBLOCKED;
      break;
      case "BSB":
      case "BSE":
        $element["state"] = E_STOP; // Logical state, used for HMI indication only
        $bufferstops[] = $name;
      break;
      default:
        print "Element $name: Unknown element.\n";
        $errorFound = true;
      break;
    }
  }
  unset($element); // otherwise next foreach is not working, see PHP manual
  
  print "Count of elements: $totalElement\n";
// Find a beginning Bufferstop as starting point for checking the graph
  $start = "";
  foreach ($bufferstops as $name) {
    if ($PT1[$name]["element"] == "BSB") {
      $start = $name;
      break;
    }
  }
  if ($start) {
    $PT1[$start]["checked"] = true;
    $nInspection = 0;
    inspect($PT1[$start]["U"],$start,true);
  } else {
    print "Error: At least one element of type 'BSB' (Bufferstop begin) is required in the track network.\n";
    $errorFound = true;
  }
  foreach ($PT1 as $name => $element) { // Check that all nodes are connected
   if (!$element["checked"] and $name != "") {
      print "Warning: Element $name is not connected to main network. Element ignored.\n";
    }
  }
  if ($errorFound) {
    print "Error: Track network not OK. Source: $DATA_FILE\n";
    fatalError("Track network not OK. Source: $DATA_FILE");
  } else {
    msgLog("Found $totalElement elements in PT1 data file: $DATA_FILE");
  }
// HMI data
  if (array_key_exists("", $HMI["baliseTrack"])) { unset($HMI["baliseTrack"][""]); } // delete any remaining template entry
  foreach ($HMI["baliseTrack"] as $trackName => $baliseTrack ) {
    $baliseTrack["trackState"] = T_CLEAR;
    foreach ($baliseTrack["balises"] as $baliseName ) {
      if (!isset($PT1[$baliseName])) {
        $errorFound = true;
        print "Error: Unknown balise \"$baliseName\" in HMI baliseTrack: $trackName\n";
      }
    }
  }
  if ($errorFound) {
    print "Error: HMI data not OK. Source: $DATA_FILE\n";
    fatalError("HMI data not OK. Source: $DATA_FILE");
  }
}

// --------------------------------------------------------------------------------------------------------------------------- RadioLink
function pollRadioLink() {
global $trainData, $emergencyStop;
  foreach ($trainData as $index => &$train) {
    if ($emergencyStop) {
      sendMA($train["ID"], M_ESTOP, $train["MAbalise"], $train["MAdist"], $train["maxSpeed"]);
    } else {
      sendMA($train["ID"], $train["authMode"], $train["MAbalise"], $train["MAdist"], $train["maxSpeed"]);
    }
    $train["dist"] = 0;
    $train["MAbalise"] = "00:00:00:00:00"; // Clear balise after each transmission
    $train["MAbaliseName"] = "(00:00:00:00:00)";
  }
}

function sendMA($trainID, $authMode, $balise, $dist, $speed) { // $balise as : sep. string
// Send mode and movement authorization. Request position report for same train from Abus Master / RadioLink
global $radioLinkAddr, $radioLink, $radio, $trainData, $trainIndex;
//print "SendMA: $trainID $balise $dist\n";
  $baliseArray = explode(":",$balise);
  $distTurn = round($dist / $trainData[$trainIndex[$trainID]]["wheelFactor"]);
  switch ($radio) {
    case "USB":
      fwrite($radioLink,"31,$trainID,$authMode,");
      for ($b = 0; $b < 5; $b++) fwrite($radioLink, hexdec($baliseArray[$b]).",");
      fwrite($radioLink, ($distTurn & 0xFF).",".(($distTurn & 0xFF00) >> 8).",$speed,0s\n"); // "0s" is broadcast
    break;
    case "ABUS":
      $packet[2] = 03;
      $packet[3] = $trainID; 
      $packet[4] = $authMode;
      for ($b = 0; $b < 5; $b++) $packet[$b + 5] = hexdec($baliseArray[$b]);
      $packet[10] = $distTurn & 0xFF;
      $packet[11] = ($distTurn & 0xFF00) >> 8;
      $packet[12] = $speed;
      AbusSendPacket($radioLinkAddr, $packet, 13);
    break;
  }
}

function sendPosRestore($trainID, $balise, $distance) { 
global $radioLinkAddr, $radioLink, $radio;
//    debugPrint ("Position restore ID: $trainID, Balise: >$balise< Distance: $distance");
  switch ($radio) { 
    case "USB":
      fwrite($radioLink,"33,$trainID,");
      $baliseArray = explode(":", $balise);
      for ($b = 0; $b < 5; $b++) 
        fwrite($radioLink, hexdec($baliseArray[$b]).",");
      fwrite($radioLink, ($distance & 0xFF).",".(($distance & 0xFF00) >> 8).",0s\n");
    break;
    case "ABUS":
      print "position restore via Abus not implemented\n";
    break;
  }
}

function checkTrainTimeout() {
global $trainData, $now;
  foreach ($trainData as $index => &$train) {
    if ($now - $train["comTimeStamp"] > TRAIN_COM_TIMEOUT) { // Train not sending position reports
      $train["dataValid"] = "VOID";
      updateTrainDataHMI($index);
    }
  }
}

function receivedFromRadioLink($data) {  // position report received via USB
  $RF12_ID_MASK = 0x1f;
  $POSREP = 10; 
  $res = explode(" ",$data);
  if ($res[0] == "OK" and $res[2] == $POSREP) {
    $packet[3] = 1; // report valid
    $packet[4] = $res[1] & $RF12_ID_MASK;
    for ($b = 3; $b < 12; $b++) {
      $packet[$b + 2] = $res[$b];
    }
    positionReport($packet);
  }
}


//---------------------------------------------------------------------------------------------------------------------- EC interface

function addEC($addr) {
global $EC;
  $EC[$addr]["index"] = array();
  $EC[$addr]["validTimer"] = 0;
  $EC[$addr]["EConline"] = false;
  $EC[$addr]["uptime"] = "*";
  $EC[$addr]["elementConf"] = "*";
  $EC[$addr]["N_ELEMENT"] = "*";
  $EC[$addr]["N_UDEVICE"] = "*";
  $EC[$addr]["N_LDEVICE"] = "*";
  $EC[$addr]["N_PDEVICE"] = "*";
  resetEC($addr);
}

function initEC($specificEC = "") {
global $PT1, $EC;

  foreach ($PT1 as $name => &$element) {
    if ($specificEC == "" or (isset($element["EC"]["addr"]) and $element["EC"]["addr"] == $specificEC)) {
      switch ($element["element"]) {
        case "PF":
        case "PT":
        if ($element["EC"]["type"] != 0) {
          $addr = $element["EC"]["addr"];
          if (!isset($EC[$addr])) {
            addEC($addr);
          }
          configureEC($addr, $element["EC"]["type"], $element["EC"]["majorDevice"]);
          $element["EC"]["index"] = count($EC[$addr]["index"]);
          $EC[$addr]["index"][] = $name;
        }
        break;
        case "SU":
        case "SD":
        if ($element["EC"]["type"] != 0) {
          $addr = $element["EC"]["addr"];
          if (!isset($EC[$addr])) {
            addEC($addr);
          }
          configureEC($addr, $element["EC"]["type"], $element["EC"]["majorDevice"]);
          $element["EC"]["index"] = count($EC[$addr]["index"]);
          $EC[$addr]["index"][] = $name;
        }
        break;
        case "LX":
        if ($element["ECbarrier"]["type"] != 0) {
          $addr = $element["ECbarrier"]["addr"];
          if (!isset($EC[$addr])) {
            addEC($addr);
          }
          configureEC($addr, $element["ECbarrier"]["type"], $element["ECbarrier"]["majorDevice"]);
          $element["ECbarrier"]["index"] = count($EC[$addr]["index"]);
          $EC[$addr]["index"][] = $name;
        }
        if ($element["ECsignal"]["type"] != 0) {
          $addr = $element["ECsignal"]["addr"];
          if (!isset($EC[$addr])) {
            addEC($addr);
          }
          configureEC($addr, $element["ECsignal"]["type"], $element["ECsignal"]["majorDevice"]);
          $element["ECsignal"]["index"] = count($EC[$addr]["index"]);
          $EC[$addr]["index"][] = $name;
        }
        break;
      }
    }
  }
}

function pollEC() {
global $PT1, $EC;
  foreach ($EC as $addr => $ec) {
    requestElementStatusEC($addr);
  }
}

function pumpSignal() {
global $PT1, $EC;
  foreach ($EC as $addr => $ec) {
    foreach ($ec["index"] as $index => $name) { // Refresh signal order (otherwise EC will change back to STOP on timeout)
      $element = $PT1[$name];
      switch ($element["element"]) {
        case "SU":
        case "SD":
          switch ($element["type"])  {
            case "SE":
            case "MS2":
              if ($element["state"] == E_PROCEED or $element["state"] == E_PROCEEDPROCEED) {
                orderEC($addr, $index, O_PROCEED);
              }
            break;
            case "MS3":
              if ($element["state"] == E_PROCEED) {
                orderEC($addr, $index, O_PROCEED);
              } elseif ($element["state"] == E_PROCEEDPROCEED) {
                orderEC($addr, $index, O_PROCEEDPROCEED);
              }
            break;
          }
        break;
      }
    }
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

function requestElementStatusEC($addr) {
  $packet[2] = 01;
  AbusSendPacket($addr, $packet, 3);
}

function receivedFromEC($addr, $data) {
global $EC, $radio;
  if ($addr) {
    switch ($data[2]) { // packet type
      case 01: // status
      case 10: // status
        elementStatusEC($addr, $data);
        // log msg: EC back online, but only once FIXME
        break;
      case 02: // EC status
        $uptime = 0;
        for ($i = 3; $i >= 0; $i--) {
          $uptime = 256 * $uptime + (int)$data[$i + 3];
        }
        $EC[$addr]["uptime"] = round($uptime / 1000);
        $EC[$addr]["elementConf"] = $data[7];
        $EC[$addr]["N_ELEMENT"] = $data[8];
        $EC[$addr]["N_UDEVICE"] = $data[9];
        $EC[$addr]["N_LDEVICE"] = $data[10];
        $EC[$addr]["N_PDEVICE"] = $data[11];
        break;  
      case 03: // position report
        if ($radio == "ABUS") positionReport($data);
        break;
      case 20: // configuration
        if ($data[3] > 0) {
          errLog("EC ($addr), Configuration error: ".$data[3]);
        } else {
          debugPrint ("EC ($addr), Configuration OK");
        }
        break;
      default:
        errLog("EC ($addr) Unknown Abus packet: ".$data[2]);
    }
  } else {
    // log or ignore timeout?? ------- Might be due to timeout when wrong EC address is used. FIXME
  }
}

function elementStatusEC($addr, $data) { // Analyse element status from EC
global $EC, $PT1;
  if (isset($EC[$addr]) and $data[3] < count($EC[$addr]["index"])) {
    errLog("Error: EC ($addr) not configured: #conf. element EC: {$data[3]}, RBC: ".count($EC[$addr]["index"]));
    unset($EC[$addr]);
    initEC($addr);
  } else {
    $EC[$addr]["validTimer"] = time() + EC_TIMEOUT;
    $EC[$addr]["EConline"] = true;
    foreach ($EC[$addr]["index"] as $index => $name) {
      $element = &$PT1[$name];
      $status = $index % 2 ? (int)$data[$index/2 +4] & 0x0F :  ((int)$data[$index/2 +4] & 0xF0) >> 4 ;
      switch ($element["element"]) {
        case "PT":
        case "PF":
          if ($element["supervisionState"] == "P") {
            switch ($element["EC"]["type"]) {
              case 10: // point machine without feedback FIXME check if state != expectedLie
                switch ($status) {
                  case S_U_RIGHT:
                  case S_U_RIGHT_HOLDING:
                    $element["state"] = E_RIGHT;
                  break;
                  case S_U_LEFT:
                  case S_U_LEFT_HOLDING:
                    $element["state"] = E_LEFT;
                  break;
                  default:
                    $element["state"] = E_UNSUPERVISED;
                  break;
                }
                break;
              default:
              $element["state"] = E_UNSUPERVISED;
            }
          }
        break;
        case "SD":
        case "SU":
          $element["status"] = $status;
        break;
        case "LX":
          if ($index == $element["ECsignal"]["index"]) { // road signal status
            $element["signalStatus"] = $status; 
//            print "Signal: $status\n";
          } else { // barrier status
            $element["barrierStatus"] = $status; 
//            print "Bom: $status\n";
            if ($element["state"] == E_LX_OPENING and $status == S_BARRIER_OPEN) {
              $element["state"] = E_LX_DEACTIVATED;
              orderEC($element["ECsignal"]["addr"], $element["ECsignal"]["index"],O_ROADPASS);
            }
          }
        break;
      }
    }
  }
}

function checkECtimeout() {
global $PT1, $EC, $now, $radioLinkAddr;
  foreach ($EC as $addr => &$ec) {
    if ($now > $ec["validTimer"]) { // EC not providing status - EC assumed offline
      if ($ec["EConline"]) { // was online
        errLog("EC ($addr) off-line");
      }
      $ec["EConline"] = false;
      foreach ($ec["index"] as $name) {
        $element = $PT1[$name];
        switch ($element["element"]) {
          case "SU":
          case "SD":
            if ($PT1[$name]["type"] != "MB") {
              $PT1[$name]["status"] = S_UNSUPERVISED;
            }
          break;
          case "PT":
          case "PF":
            if ($element["supervisionState"] == "P") {
              $PT1[$name]["state"] = E_UNSUPERVISED;
            }
          break;
          case "LX":
            // FIXME
          break;
        }
      }
      if ($addr == $radioLinkAddr) {
        // position report from EC-link UDEF FIXME
      }
    }
  }
}

//---------------------------------------------------------------------------------------------------------------- RBC-IL
function RBC_IL_DebugPrint($msg) {
global $debug;
  if ($debug & 0x02) {
    print "RBC:".date('h:i:s').": ".$msg."\n";
  }
}

function isMoveablePoint($eltName) {
  global $PT1;
  $type = $PT1[$eltName]["element"];
  if ($type == "PF" or $type == "PT") {
    return  ($PT1[$eltName]["supervisionState"] == "P" or $PT1[$eltName]["supervisionState"] == "S");
  } else {
    return False;
  }
}

function nextElements($element, $direction, $previousElement) {
  global $PT1;
  $elts = array();
  switch ($PT1[$element]["element"]) {
    case "BSB":
      if ($direction === "U") {
        $elts[] = $PT1[$element][$direction] + ["direction" => $direction, "position" => ""];
      }
      break;
    case "BSE":
      if ($direction === "D") {
        $elts[] = $PT1[$element][$direction] + ["direction" => $direction, "position" => ""];
      }
      break;
    case "PT":
      if (isMoveablePoint($element)) {
        if ($direction === "U") {
          if ($PT1[$element]["R"]["name"] === $previousElement) {
            $elts[] = $PT1[$element]["T"] + ["direction" => "T", "position" => C_RIGHT];
          } elseif ($PT1[$element]["L"]["name"] === $previousElement) {
            $elts[] = $PT1[$element]["T"] + ["direction" => "T", "position" => C_LEFT];
          } else {
            $elts[] = $PT1[$element]["T"] + ["direction" => "T", "position" => "UNKNOWN"];
          }
        } else { #Direction down
            $elts[] = $PT1[$element]["R"] + ["direction" => "F", "position" => C_RIGHT];
            $elts[] = $PT1[$element]["L"] + ["direction" => "F", "position" => C_LEFT];
        }
      } else {
        $elts[] = ["name" => getNextEltName($element, $direction)] + ["direction" => $direction, "position" => ""];
      }
      break;
    case "PF":
      if (isMoveablePoint($element)) {
        if ($direction === "D") {
          if ($PT1[$element]["R"]["name"] === $previousElement) {
            $elts[] = $PT1[$element]["T"] + ["direction" => "T", "position" => C_RIGHT];
          } elseif ($PT1[$element]["L"]["name"] === $previousElement) {
            $elts[] = $PT1[$element]["T"] + ["direction" => "T", "position" => C_LEFT];
          } else {
            $elts[] = $PT1[$element]["T"] + ["direction" => "T", "position" => "UNKNOWN"];
          }
        } else { #Direction up
            $elts[] = $PT1[$element]["R"] + ["direction" => "F", "position" => C_RIGHT];
            $elts[] = $PT1[$element]["L"] + ["direction" => "F", "position" => C_LEFT];
        }
      } else {
        $elts[] = ["name" => getNextEltName($element, $direction)] + ["direction" => $direction, "position" => ""];
      }
      break;
    default:
        $elts[] = $PT1[$element][$direction] + ["direction" => $direction, "position" => ""];
  }
  return $elts;
}

function defaultStateMerger($state1, $state2) {
  if ($state1 === TRACK_TRAVERSALE_ACCEPT_ACTIVATE_PAYLOAD or $state2 === TRACK_TRAVERSALE_ACCEPT_ACTIVATE_PAYLOAD) {
    return TRACK_TRAVERSALE_ACCEPT_ACTIVATE_PAYLOAD;
  } elseif ($state1 === TRACK_TRAVERSALE_ACCEPT_DO_NOTHING or $state2 === TRACK_TRAVERSALE_ACCEPT_DO_NOTHING) {
    return TRACK_TRAVERSALE_ACCEPT_DO_NOTHING;
  } else {
    return TRACK_TRAVERSALE_REJECT;
  }
}
#Making a track traversal generic function based on callbacks
function trackRecTraverse($acceptionCriteria, #Name of function used to determine if $element can be accepted
                          $terminalCriteria, #Name of function to determine if $element is terminal or not
                          $payLoad, #Name of function to be called in case action is required
                          $element, #Name of the element trackRecTraverse is called from
                          $previousElement, #usefull when meeting trailing points (let empty if unknown).
                          $direction, #Direction of the traversal
                          &$sharedVar, #Varaible passed by reference to the payload
                          $stateMerger = "defaultStateMerger"){ #Name of function to help tuning the backpropagation of the action) {
  if (call_user_func($acceptionCriteria, $element, $direction, $previousElement)) {
    #The element is acceptable
    switch (call_user_func($terminalCriteria, $element, $direction)) {
      case TRACK_TRAVERSALE_ACCEPT_ACTIVATE_PAYLOAD:
        #This is the last element and action is required
        return call_user_func_array($payLoad, array($element, &$sharedVar, $direction, []));
      case TRACK_TRAVERSALE_ACCEPT_DO_NOTHING:
        #This is the last element and no action is required
        return TRACK_TRAVERSALE_ACCEPT_DO_NOTHING;
      case TRACK_TRAVERSALE_REJECT:
        #This is not the last element, search for next elements
        $state = TRACK_TRAVERSALE_REJECT;
        foreach (nextElements($element, $direction, $previousElement) as $next) {
          $substate = trackRecTraverse($acceptionCriteria, $terminalCriteria, $payLoad, $next["name"], $element, $direction, $sharedVar,
            $stateMerger);
          if ($substate === TRACK_TRAVERSALE_ACCEPT_ACTIVATE_PAYLOAD) {
            call_user_func_array($payLoad, array($element, &$sharedVar, $direction, $next));
          }
          $state = call_user_func($stateMerger, $state, $substate);
        }
        return $state;
      default:
        return TRACK_TRAVERSALE_REJECT;
    }
  } else {
    #Not acceptable. Do nothing
    return TRACK_TRAVERSALE_REJECT;
  }
}

#Test Looking for all next signal

function acceptAll($element, $direction, $previousElement) { return True;}

function addToListPayload($element, &$sharedVar, $direction, $next) { // FIXME Currently not used
  #Push $element into list
  $sharedVar[] = $element;
  return TRACK_TRAVERSALE_ACCEPT_DO_NOTHING; #Only the terminal element must be added
}

function lockingPayload($eltName, &$sharedVar, $direction, $next) {
global $PT1;
//print_r($sharedVar);
  //Set point in position
  if (isMoveablePoint($eltName)) {
    $pos = $next["position"];
    RBC_IL_DebugPrint("Throwing point $eltName in position $pos");
    $point = &$PT1[$eltName];
    pointThrow($point, $pos); // ----------------------------------------------------Check return status, might be rejected FIXME
  }
  lock($eltName);
  return TRACK_TRAVERSALE_ACCEPT_ACTIVATE_PAYLOAD; #Lock all elments in route
}

function findSignalsFrom($element, $direction) { // FIXME Currently not used
  $isTerminal = function($elt, $dir) use ($element) {
    global $PT1;
    if ($elt === $element) {
      return TRACK_TRAVERSALE_REJECT; #Do not stop if first elt is a signal
    }
    if ($dir === "U") {
      switch ($PT1[$elt]["element"]) {
        case "SU":
        case "BSE":
          return TRACK_TRAVERSALE_ACCEPT_ACTIVATE_PAYLOAD;
        default:
          return TRACK_TRAVERSALE_REJECT;
      }
    } else {
      switch ($PT1[$elt]["element"]) {
        case "SD":
        case "BSB":
          return TRACK_TRAVERSALE_ACCEPT_ACTIVATE_PAYLOAD;
        default:
          return TRACK_TRAVERSALE_REJECT;
      }
    }
  };
  $signals = array();
  trackRecTraverse("acceptAll", $isTerminal, "addToListPayload", $element,"", $direction, $signals);
  foreach ($signals as $sig) {
    RBC_IL_DebugPrint("$sig is next signal in direction $direction of $element");
  }
}

function getSignalDirection($element) {
  global $PT1;
  switch ($PT1[$element]["element"]) {
    case "SD":
    case "BSB":
      return "D";
    case "SU":
    case "BSE":
      return "U";
    default:
    print "Warning: Calling getSignalDirection with $element that is not a signal\n";
      return "U";
  }
}

function lockRoute($s1, $s2) {
global $PT1;
  $direction = getSignalDirection($s1);
  if ($direction != getSignalDirection($s2)) {
    RBC_IL_DebugPrint("$s1 and $s2 are not in the same direction. Route does not exist");
    return false;
  }
  $isTerminal = function($elt, $dir) use ($s1, $s2) {
    global $PT1;
    if ($elt === $s2) {
      return TRACK_TRAVERSALE_ACCEPT_ACTIVATE_PAYLOAD; #Correct end signal found
    }
    if ($elt === $s1) {
      return TRACK_TRAVERSALE_REJECT; #Do not stop if first elt is a signal
    }
    if ($dir === "U") {
      switch ($PT1[$elt]["element"]) {
        //case "SU":(commented to Allow locking route over signal in one command)
        case "BSE":
          return TRACK_TRAVERSALE_ACCEPT_DO_NOTHING; # wrong end. No compound route support
        default:
          return TRACK_TRAVERSALE_REJECT; #Not end of route
      }
    } else {
      switch ($PT1[$elt]["element"]) {
        //case "SD":(commented to Allow locking route over signal in one command)
        case "BSB":
          return TRACK_TRAVERSALE_ACCEPT_DO_NOTHING; # wrong end. No compound route support
        default:
          return TRACK_TRAVERSALE_REJECT; #Not end of route
      }
    }
  };

  $canBeLocked = function($element, $direction, $previousElement) use ($s1){
    global $PT1;
      if ($element == $s1) {return True;} //Train is allowed to already occupy it and it can be locked in ptrevious route or clear
//      print "canBeLocked: element: $element, prev: $previousElement, direction: $direction isLocked:".isLocked($element)." isClear:".isClear($element)."\n";
      if (isLocked($element) or !isClear($element)) {return false;} 
      if ($direction == "U") {
        if ($PT1[$element]["element"] == "PT") { 
          return (($PT1[$element]["R"]["name"] == $previousElement and ($PT1[$element]["blockingState"] == B_UNBLOCKED or 
                $PT1[$element]["blockingState"] == B_BLOCKED_RIGHT or $PT1[$element]["blockingState"] == B_CLAMPED_RIGHT)) or
            ($PT1[$element]["L"]["name"] == $previousElement and ($PT1[$element]["blockingState"] == B_UNBLOCKED or 
                $PT1[$element]["blockingState"] == B_BLOCKED_LEFT or $PT1[$element]["blockingState"] == B_CLAMPED_LEFT)));
        }
        return ($previousElement == "" or
          ($PT1[$previousElement]["element"] != "PF") or
          ($element == $PT1[$previousElement]["R"]["name"] and ($PT1[$previousElement]["blockingState"] == B_UNBLOCKED or 
            $PT1[$previousElement]["blockingState"] == B_BLOCKED_RIGHT or
            $PT1[$previousElement]["blockingState"] == B_CLAMPED_RIGHT)) or
          ($element == $PT1[$previousElement]["L"]["name"] and ($PT1[$previousElement]["blockingState"] == B_UNBLOCKED or 
             $PT1[$previousElement]["blockingState"] == B_BLOCKED_LEFT or
            $PT1[$previousElement]["blockingState"] == B_CLAMPED_LEFT)));
      } else {
        if ($PT1[$element]["element"] == "PF") { 
          return (($PT1[$element]["R"]["name"] == $previousElement and ($PT1[$element]["blockingState"] == B_UNBLOCKED or 
                $PT1[$element]["blockingState"] == B_BLOCKED_RIGHT or $PT1[$element]["blockingState"] == B_CLAMPED_RIGHT)) or
            ($PT1[$element]["L"]["name"] == $previousElement and ($PT1[$element]["blockingState"] == B_UNBLOCKED or 
                $PT1[$element]["blockingState"] == B_BLOCKED_LEFT or $PT1[$element]["blockingState"] == B_CLAMPED_LEFT)));
        }
        return ($previousElement == "" or
          ($PT1[$previousElement]["element"] != "PT") or
          ($element == $PT1[$previousElement]["R"]["name"] and ($PT1[$previousElement]["blockingState"] == B_UNBLOCKED or 
            $PT1[$previousElement]["blockingState"] == B_BLOCKED_RIGHT or
            $PT1[$previousElement]["blockingState"] == B_CLAMPED_RIGHT)) or
          ($element == $PT1[$previousElement]["L"]["name"] and ($PT1[$previousElement]["blockingState"] == B_UNBLOCKED or 
            $PT1[$previousElement]["blockingState"] == B_BLOCKED_LEFT or
            $PT1[$previousElement]["blockingState"] == B_CLAMPED_LEFT)));    
      }
  };
  $dummy = array();
  if (trackRecTraverse($canBeLocked, $isTerminal, "lockingPayload", $s1,"", $direction, $dummy) == TRACK_TRAVERSALE_ACCEPT_ACTIVATE_PAYLOAD ) {
    return True;
  } else {
    return False;
  }
}

function setSignalState($signal, $state) {
global $PT1;
  $PT1[$signal]["state"]= $state;
  $prettyState = "";
  switch ($state) {
    case E_STOP:
      $prettyState = "E_STOP";
      break;
    case E_STOP_FACING:
      $prettyState = "E_STOP_FACING";
      break;
    case E_PROCEED:
      $prettyState = "E_PROCEED";
      break;
    case E_PROCEEDPROCEED:
      $prettyState = "E_PROCEEDPROCEED";
      break;
    default:
      $prettyState = "UNKNOWN";
      break;
  }
  RBC_IL_DebugPrint("Setting signal $signal in state $prettyState");
}

function openSignalsInRoute($destinationSignal) {
// FIXME check for correct state of points in route. Do not open if point not supervised in correct lie
global $PT1;
  $dir = getSignalDirection($destinationSignal);
  $revDir = getReverseDir($dir);
  $elt = $destinationSignal;
  $signalState = E_PROCEED;
  while (isLocked($elt)) {
    if ($elt == $destinationSignal) {
      setSignalState($elt, E_STOP_FACING);
    } else {
      if (isSignalInDirection($elt, $dir)) {
        if ($PT1[$elt]["trackState"] == T_CLEAR) {
          setSignalState($elt, $signalState);
        }
        $signalState = E_PROCEEDPROCEED;
      }
    }
    $elt = getNextEltName($elt, $revDir); 
    if ($elt == "") {break;}
  }
}

function getEltLength($eltName) {
  global $PT1;
  $elt = $PT1[$eltName];
  switch ($elt["element"]) {
    case "PF":
    case "PT":
        return ($elt["T"]["dist"] + ($elt["expectedLie"] == E_RIGHT ? $elt["R"]["dist"]: $elt["L"]["dist"]));
    case "BSE":
        return $elt["D"]["dist"];
    case "BSB":
        return $elt["U"]["dist"];
    default:
        return ($elt["D"]["dist"] + $elt["U"]["dist"]);
  }
}

function recUpdateTrainPosition(&$train, $dir, $x, $eltName, $trackState) {
  global $PT1;
  $elt = &$PT1[$eltName];
  $length = getEltLength($eltName);
  // Define  element coordinates [a,b]
  $a = ($dir == "U" ? $x : $x - $length);
  $b = ($dir == "U" ? $x + $length: $x);

  // Check if train is occupying the element
  if (($a <= $train["upFront"]) and ($b >= $train["downFront"])) {
    $elt["trackState"] = $trackState;
    //Clear trainID from element occupation list (it will be re-added below if needed)
    if (FALSE !== ($key = array_search($train["ID"], $elt["trainIDs"]))) {
      unset($elt["trainIDs"][$key]);
    }
    if ($trackState !== T_CLEAR) {
      //Element is occupied
      $elt["trainIDs"][] = $train["ID"];
      // Add elt to train info if this is the "further one up or down" (usefull to search for MA)
      if ($a <= $train["downEltDist"]) {
          $train["downEltDist"] = $a;
          $train["downElt"] = $eltName;
      }
      if ($b >= $train["upEltDist"]) {
          $train["upEltDist"] = $b;
          $train["upElt"] = $eltName;
      }
      //checkIfRouteRelease($eltName); TODO: probably delete
      // activate occupation triggered functions when element gets occupied
      switch($elt["element"]) {
        case "SU":
        case "SD":
          if ($elt["state"] == E_PROCEED or $elt["state"] == E_PROCEEDPROCEED) { // signal is open
            $elt["state"] = E_STOP_FACING;
            if ($elt["EC"]["addr"] != 0) { // real signal
              orderEC($elt["EC"]["addr"], $elt["EC"]["index"], O_STOP);
            }
          }
        break;
        case "PHTU":
          if ($trackState == D_UP and !$PT1[$elt["holdPoint"]]["pointHeld"]) { // only activate pointHold once
            debugPrint ("PHTU Throw and hold point {$elt["holdPoint"]} to the ".
              ($PT1[$elt["holdPoint"]]["expectedLie"] == E_RIGHT ? "right" : 
                ($PT1[$elt["holdPoint"]]["expectedLie"] == E_LEFT ? "left" : $PT1[$elt["holdPoint"]]["expectedLie"])).
              " for train {$train["ID"]}");
            $PT1[$elt["holdPoint"]]["pointHeld"] = true; 
            pointThrow($PT1[$elt["holdPoint"]], C_HOLD);
          }
        break;
        case "PHTD":
          if ($trackState == D_DOWN and !$PT1[$elt["holdPoint"]]["pointHeld"]) {
            debugPrint ("PHTD Throw and hold point {$elt["holdPoint"]} to the ".
              ($PT1[$elt["holdPoint"]]["expectedLie"] == E_RIGHT ? "right" : 
                ($PT1[$elt["holdPoint"]]["expectedLie"] == E_LEFT ? "left" : $PT1[$elt["holdPoint"]]["expectedLie"])).
              " for train {$train["ID"]}");
            $PT1[$elt["holdPoint"]]["pointHeld"] = true; // only activate pointHold once
            pointThrow($PT1[$elt["holdPoint"]], C_HOLD);
          }
        break;
      }
    }
  }

  //Check if further element in this direction may be occupied
  if (($dir == "U") and ($b <= $train["upFront"])) {
    //keep looking up unless it was facing point
    switch ($elt["element"]) {
      case "PF": // --------------------FIXME position gets ambiguous if point is thrown behind the train before train reads a new balise
/* JP:       //stop and invalidate position
        $train["positionUnambiguous"] = False;
        RBC_IL_DebugPrint("Invalidating postion in $eltName due to b=".$b." <= train[upFront]=".$train["upFront"]);
        return;
*/
        recUpdateTrainPosition($train, $dir, $b, $elt[($elt["expectedLie"] == E_RIGHT ? "R" : "L")]["name"], $trackState);
        return;
      case "PT":
        recUpdateTrainPosition($train, $dir, $b, $elt["T"]["name"], $trackState);
        return;
      case "BSE":
        return; //Reached end of track... Train derailed?
      default:
        recUpdateTrainPosition($train, $dir, $b, $elt["U"]["name"], $trackState);
    }
  } elseif (($dir == "D") and ($a >= $train["downFront"])) {
    //keep looking dow unless it was facing point
    switch ($elt["element"]) {
      case "PT":
/* JP:        //stop and invalidate position
        $train["positionUnambiguous"] = False;
        RBC_IL_DebugPrint("Invalidating postion in $eltName due to a=".$a." >= train[downFront]=".$train["downFront"]);
        return;
*/
        recUpdateTrainPosition($train, $dir, $a, $elt[($elt["expectedLie"] == E_RIGHT ? "R" : "L")]["name"], $trackState);
        return;
      case "PF":
        recUpdateTrainPosition($train, $dir, $a, $elt["T"]["name"], $trackState);
        return;
      case "BSB":
        return; //Reached end of track... Train derailed?
      default:
        recUpdateTrainPosition($train, $dir, $a, $elt["D"]["name"], $trackState);
    }
  }
}

function updateTrainPosition(&$train, $baliseName, $dist, $trackState) {
  global $PT1;
  #TODO Add uncertainty margin here??
  if ($train["front"] == D_UP ) {
    $train["upFront"] = $dist + $train["lengthFront"];
    $train["downFront"] = $dist - $train["lengthBehind"];
  } else {
    $train["upFront"] = $dist + $train["lengthBehind"];
    $train["downFront"] = $dist - $train["lengthFront"];
  }
  $train["positionUnambiguous"] = True;
  if ($trackState !== T_CLEAR) {
    //init data representing element up and down of train that will be found during recUpdateTrainPosition
    $train["upElt"] = "";
    $train["downElt"] = "";
    $train["upEltDist"] = $train["upFront"];
    $train["downEltDist"] = $train["downFront"];
  }
  recUpdateTrainPosition($train, "U", -$PT1[$baliseName]["D"]["dist"], $baliseName, $trackState);
  recUpdateTrainPosition($train, "D", -$PT1[$baliseName]["D"]["dist"], $PT1[$baliseName]["D"]["name"], $trackState); // ???
}

function getNextEltName($eltName, $dir) {
  global $PT1;
  $elt = $PT1[$eltName];
  if ($dir == "U") {
    switch ($elt["element"]) {
      case "PF":
        if ($elt["state"] == E_RIGHT) { // FIXME "state" or "expectedLie"??
          return $elt["R"]["name"];
        } elseif ($elt["state"] == E_LEFT) {
          return $elt["L"]["name"];
        } else {
          RBC_IL_DebugPrint("Cannot find next element after point ".$eltName." Point is moving or unsupervized");
          return "";
         }
        break;
      case "PT":
        return $elt["T"]["name"];
        break;
      case "BSE":
        RBC_IL_DebugPrint("Cannot find next element after  ".$eltName." : Buffer stop");
        return "";
      default:
        return $elt["U"]["name"];
    }
  } else {
    switch ($elt["element"]) {
      case "PT":
        if ($elt["state"] == E_RIGHT) {
          RBC_IL_DebugPrint("Point ".$eltName." right");
          return $elt["R"]["name"];
        } elseif ($elt["state"] == E_LEFT) {
          RBC_IL_DebugPrint("Point ".$eltName." left");
          return $elt["L"]["name"];
        } else {
          RBC_IL_DebugPrint("Cannot find next element after point ".$eltName." Point is moving or unsupervized");
          return "";
         }
        break;
      case "PF":
        return $elt["T"]["name"]; 
        break;
      case "BSB":
        RBC_IL_DebugPrint("Cannot find next element after  ".$eltName." : Buffer stop");
        return "";
      default:
        return $elt["D"]["name"];
    }
  }
}

//TODO: improve to reject case when unkow balise was read, not just if no balise read
function isKnownBalise($bgName) {
  return (($bgName != "00:00:00:00:00") and ($bgName !== "") ); //and (isset($PT1[$bgName]["ID"])));
}

function getMA($trainID, $signal) { // FIXME check for correct point state
  global $PT1, $trainData, $trainIndex;
  $train = $trainData[$trainIndex[$trainID]];
  RBC_IL_DebugPrint("Trying to build MA for train $trainID until signal $signal");
  $MA = ["bg" => "", "dist" => 0]; //default MA (position of the train)
  if (isKnownBalise($train["baliseName"])) {
    if ($train["positionUnambiguous"]) {
      $MA["bg"] = $train["baliseName"]; //MA from LRBG
      $dir = getSignalDirection($signal);
      $eltName = ($dir == "U" ? $train["upElt"]: $train["downElt"]);
      $dist = ($dir == "U" ? $train["upEltDist"]: $train["downEltDist"]);

      while ($eltName !== $signal) {
        $eltName = getNextEltName($eltName, $dir);
        if ($eltName == "") {RBC_IL_DebugPrint("Failed to compute MA because could not find next element"); $MA["bg"] =""; return $MA;}
        $dist += ($dir == "U" ? getEltLength($eltName) : -getEltLength($eltName));
      }
      //take into account the fact that the signal is in the "middle" of its segment if not buffer stop
      if (($PT1[$signal]["element"] == "SU") or ($PT1[$signal]["element"] == "SD")) {
        $dist += ($dir == "U"  ? -$PT1[$signal]["U"]["dist"] : $PT1[$signal]["D"]["dist"]);
      }
      //take into account balise reader position in the train
      if ($dir == "U") {
        $dist -= ($train["front"] == D_UP  ? $train["lengthFront"] : $train["lengthBehind"]);
      } else {
        $dist += ($train["front"] == D_UP  ? $train["lengthBehind"] : $train["lengthFront"]);
      }
      $MA["dist"] = $dist;
      RBC_IL_DebugPrint("Built MA ".$MA["bg"]." : ".$MA["dist"]." for train $trainID");
      giveMAtoTrain($MA, $trainID);
      return $MA;
    } else {
      RBC_IL_DebugPrint("Cannot compute MA for ".$train["ID"]." : Position is ambiguous");
      return $MA;
    }
  } else {
    RBC_IL_DebugPrint($train["baliseName"]." is not linked. Cannot compute MA to $signal for train $trainID based on this balise");
  }
}

function searchTrainForRoute($routeDestinationSignal) {
global $PT1;
  $dir = getSignalDirection($routeDestinationSignal);
  $searchDir = ($dir == "U" ? "D" : "U"); //reverse direction
  $eltName = $routeDestinationSignal;
  $lastLockedEltName;
  $approachArea = [];
  $routeElts = [];
  $unlockedElementMet = False; //used to avoid merging routes through approach area.
  while (True) {
    $routeElts[] = $eltName;
    If ($num = count($PT1[$eltName]["trainIDs"])){
      If ($num > 1) {
        //Ambiguous situation. Need precise train position to solve the matter. For now reject
        RBC_IL_DebugPrint("Cannot associate route to signal ".$routeDestinationSignal." : ".$num." trains found in ".$eltName);
        return "";
      } else {
        //check facing point in route are correctly set
        for ($i = 0; $i<(count($routeElts)-1); $i++) {
          if ($routeElts[$i] !== getNextEltName($routeElts[$i+1], $dir)) {
            RBC_IL_DebugPrint("Point ".$routeElts[$i+1]." not set in correct position to allocate route to signal ".
              $routeDestinationSignal." to a train".
              $routeElts[$i]." !== ".getNextEltName($routeElts[$i+1], $dir));
            return "";
          }
        }
        //If element is occupied by train, the train should get the MA for the route
        //Should the direction/state of the train be considered?
        //Lock approach area
        foreach($approachArea as $approachElt) {
          RBC_IL_DebugPrint("Adding ".$approachElt." to approach area");
          lock($approachElt);
        }
        //Train gets the associated to route
        //get train ID this is weird but to avoid having issue with the index being different from 0
        $trainID = "Undefined";
        foreach ($PT1[$eltName]["trainIDs"] as $id) {
          $trainID = $id;
        }
        RBC_IL_DebugPrint("Route towards ".$routeDestinationSignal." can be associated to train ".$trainID);
        return $trainID;
        break;
      }
    }
    //If not locked -> add to approach area locking list. Stop if signal unlocked.
    if (!isLocked($eltName)) {
      $approachArea[] = $eltName;
      $unlockedElementMet = True; //used to avoid merging routes through approach area.
    }

    //Do not build an approach area over a signal in the same direction
    if ($unlockedElementMet) {
      $eltType = $PT1[$eltName]["element"];
      if ((( $dir == "U" and  $eltType == "SU") or ($dir == "D" and $eltType == "SD")) and ($eltName != $routeDestinationSignal)) {
        RBC_IL_DebugPrint("Could not find train for route to signal : ".$routeDestinationSignal." search stoped at closed signal ".$eltName);
        return "";
      }
    }
    $nextEltName = getNextEltName($eltName, $searchDir);
    if ($nextEltName == "") {
      RBC_IL_DebugPrint("Could not associate train to route. No next element found after ".$eltName." in direction ".$searchDir);
      return "";
    } else {
      $eltName = $nextEltName;
    }
  }
  return ""; //This is not reachable. Added for robustness
}

//Made a wrapper to ease locking state represantation change
function isLocked($eltName) {
global $PT1;
  //return ($PT1[$eltName]["trackState"] === T_LOCKED);
  return ($PT1[$eltName]["routeState"] == R_LOCKED);
}

function isClear($eltName) {
global $PT1;
  return ($PT1[$eltName]["trackState"] === T_CLEAR);
}

function lock($eltName) {
global $PT1;
  RBC_IL_DebugPrint("Locking $eltName");
//  $PT1[$eltName]["trackState"] = T_LOCKED;
  $PT1[$eltName]["routeState"] = R_LOCKED;
}

function unlock($eltName) {
global $PT1;
  //Set track as cleared (unless occupied)
//  if ($PT1[$eltName]["trackState"] == T_LOCKED) {
//    $PT1[$eltName]["trackState"] = T_CLEAR; //Set to clear only if not occupied
//  }
  //Close signals
  $type = $PT1[$eltName]["element"];
  if ($type == "SU" or $type == "SD" or $type == "BSB" or $type == "BSE" ) {
    setSignalState($eltName, E_STOP); // FIXME send command to EC here in stead of in recUpdateTrainPosition???
  }
  //Remove locked flag
  $PT1[$eltName]["routeState"] = R_IDLE;
}

function isLockedRoute($s) { // signal is locked as destination of route
global $lockedRoutes;
  foreach($lockedRoutes as $dest_sig => $route) {
    if ($dest_sig == $s) {
      return True;
    }
  }
  return False;
}

function createNewRoute($s) {
global $lockedRoutes;
  RBC_IL_DebugPrint("Creating route $s");
  $lockedRoutes[$s] = [
      "train" => "",
      "MA" => []];
}

function associateTrainToRoute($s, $trainID) {
global $lockedRoutes;
  RBC_IL_DebugPrint("Associating train $trainID to route $s");
  //first check if train already had a route if this is a case cancel it
  foreach($lockedRoutes as $signal => $route) {
    if ($route["train"] == $trainID) {
      RBC_IL_DebugPrint("Release route $signal because $trainID is getting new route towards $s");
      routeRelease($signal);
    }
  }
  $lockedRoutes[$s]["train"] = $trainID;
  $lockedRoutes[$s]["MA"] = getMA($trainID, $s);
}

function getTrainLRBG($trainID) {
global $trainData, $trainIndex;
  $train = $trainData[$trainIndex[$trainID]];
  return $train["baliseName"];
}

function giveMAtoTrain($MA, $trainID) {
global $PT1, $trainData, $trainIndex;
  if (isset($PT1[$MA["bg"]]["ID"])) {
    $trainData[$trainIndex[$trainID]]["MAbalise"] = $PT1[$MA["bg"]]["ID"];
    $trainData[$trainIndex[$trainID]]["MAbaliseName"] = $MA["bg"];
    $trainData[$trainIndex[$trainID]]["MAdist"] = $MA["dist"];
    RBC_IL_DebugPrint("Giving MA from {$MA["bg"]} (".$PT1[$MA["bg"]]["ID"].") with distance ".
      $MA["dist"]." cm to train $trainID");
  } else {
    RBC_IL_DebugPrint("Cannot send MA to train $trainID: balise {$MA["bg"]} has no ID set in PT1");
  }
}

function getReverseDir($dir) {
  return ($dir == "U"? "D": "U");
}
//Partial release function
function partialRelease($trainID, $signal) {
global $trainData, $trainIndex;
  $dir = getSignalDirection($signal);
  $revDir = getReverseDir($dir);
  $train = $trainData[$trainIndex[$trainID]];
  if ($train["positionUnambiguous"]) {
    $elt = ($dir = "U"? $train["upElt"]: $train["downElt"]); //Looking for element occupied by train Front
    while (isLocked($elt)) {
      if ($elt == $signal) {
        clearFromRoutes($signal);
        unlock($signal);
      } else {
        unlock($elt);
        $elt = getNextEltName($elt, $revDir); //Look for element to release behind train
        if ($elt == "") {break;}
      }
    }
  } else {
    RBC_IL_DebugPrint("Skiping partial route release because train $trainID is ambiguous");
  }
}

//Try to allocate train to route without train / update MA of trains 
function updateRoutesStatus() {// FIXME check for correct point state
global $lockedRoutes;
  foreach($lockedRoutes as $signal => $route) {
    $trainID = $route["train"];
    if ($trainID !== "") {
      //route allocated to train see if recompute MA based on potential new balise reported by train
      if ($route["MA"]["bg"] != getTrainLRBG($trainID)) {
        RBC_IL_DebugPrint("Updating MA from balise ".$route["MA"]["bg"]." to balise ".getTrainLRBG($trainID));
	      $newMA = getMA($trainID, $signal);
	      if ($newMA["bg"] != "") {
	        $lockedRoutes[$signal]["MA"] = getMA($trainID, $signal);
	      } else {
          RBC_IL_DebugPrint("Updating MA from balise ".$route["MA"]["bg"]." to balise ".getTrainLRBG($trainID)." failed. Keeping previous MA");
	      }
      } else {
        //refresh MA for train in case it did not received it
        giveMAtoTrain($route["MA"], $trainID);
      }
      //update Signals in case unset points prevented the function to set them (yes, something nicer could be imagined performancewise)
      openSignalsInRoute($signal);
      //Perform Partial Release
      partialRelease($trainID, $signal);
    } else {
      //Look for train
      searchAndAssociateTrainToRoute($signal);
    }
  }
}

function checkIfRouteRelease($eltName) {
global $PT1;
  switch ($PT1[$eltName]["element"] ) {
    case "SD":
    case "BSB":
    case "SU":
    case "BSE":
      clearFromRoutes($eltName);
      return;
    default:
      return;
  }
}

function clearFromRoutes($s) {
global $lockedRoutes;
  if (isset($lockedRoutes[$s])) {
    RBC_IL_DebugPrint("Clearing Route $s");
    unset($lockedRoutes[$s]);
  }
}

function extendRoute($s1, $s2) {
global $lockedRoutes;
  RBC_IL_DebugPrint("Extending route $s1 to $s2");
  $lockedRoutes[$s2] = $lockedRoutes[$s1];
  unset($lockedRoutes[$s1]);
  //Update MA if applicable
  if ($lockedRoutes[$s2]["train"] != "") {
    RBC_IL_DebugPrint("Updating MA of train ".$lockedRoutes[$s2]["train"]." until $s2");
    $lockedRoutes[$s2]["MA"] = getMA($lockedRoutes[$s2]["train"], $s2);
  }
}

function searchAndAssociateTrainToRoute($s) {
global $trainData, $trainIndex;
  $trainID = searchTrainForRoute($s);
  if ($trainID !== "") {
    $train = $trainData[$trainIndex[$trainID]];
//    if ($train["authMode"] == M_FS or $train["authMode"] == M_ATO) {
    if (True) {
      associateTrainToRoute($s, $trainID);
    } else {
      RBC_IL_DebugPrint("Found train $trainID for route to $s but train is not in FS or ATO. Route will not be associated");
    }
  }
}

function setRoute($s1, $s2) {
  RBC_IL_DebugPrint("Trying to set route  $s1 $s2");
  //If $s2 already locked do nothing
  if (isLocked($s2)) {
    RBC_IL_DebugPrint("Target signal $s2 is already locked. Aborting Route setting");
    return "Route rejected, $s2 already locked";
  }
  if (lockRoute($s1, $s2)) {
    //check if this is an extension
    if (isLockedRoute($s1)) {
      //Update route table
      extendRoute($s1, $s2);
    } else {
      //Create new element in route table
      createNewRoute($s2);
      searchAndAssociateTrainToRoute($s2);
    }
    openSignalsInRoute($s2);
    return "OK";
  } else {
    RBC_IL_DebugPrint("Route from $s1 to $s2 cannot be locked");
    return "Route rejected";
  }
}

function stopTrainWithRoute($s) {
global $trainData, $trainIndex, $lockedRoutes;
  $id = $lockedRoutes[$s]["train"];
  if ( $id != "") {
     RBC_IL_DebugPrint("Stooping train $id");
     $train =  $trainData[$trainIndex[$id]];
     giveMAtoTrain(["bg" => $train["baliseName"],"dist" => $train["distance"]], $id);
  }
}

function routeRelease($s) { // FIXME order signals to stop
  if (isLockedRoute($s)) {
    $eltName = $s;
    $dir = (getSignalDirection($s) == "U"? "D": "U");

    while (isLocked($eltName)) {
      unlock($eltName);
      RBC_IL_DebugPrint("Manually unlocking $eltName");
      $eltName = getNextEltName($eltName, $dir);
      if ($eltName == "") {return;}
    }
    stopTrainWithRoute($s);
    clearFromRoutes($s);
  } else {
    RBC_IL_DebugPrint("cannot release route $s: Route was not locked");
  }
}

function startRouteRelease($s) {
//set timer and call routeRelease
//FixMe: route should not be extented during timer??
  routeRelease($s);
}

function isSignal($eltName) {
  switch ($PT1[$eltName]["element"]) {
    case "SU":
    case "BSE":
    case "SD":
    case "BSB":
      return True;
    default:
      return False;
  }
}

function isSignalInDirection($eltName, $dir) {
global $PT1;
  switch ($PT1[$eltName]["element"]) {
    case "SU":
    case "BSE":
      return ($dir == "U");
    case "SD":
    case "BSB":
      return ($dir == "D");
    default:
      return False;
  }
}

function isBufferStop($eltName) {
global $PT1;
  $type = $PT1[$eltName]["element"];
  return ($type == "BSE" or $type == "BSB");
}

function findStartSignalForTrain($trainID, $dir) {
//TODO: SEVERAL_TRAIN :if several train, should consider avoiding finding signal if other train inbetween
global $trainData, $trainIndex;
  $train = $trainData[$trainIndex[$trainID]];
  $elt = ($dir == "U" ? $train["upElt"] : $train["downElt"]);
  while ($elt !== "") {
    //check if element is a signal in the correct direction
    if (isSignalInDirection($elt, $dir)) {
      //reject buffer stop as start signal
      if (isBufferStop($elt)) {
        return "";
      } else {
        return $elt;
      }
    }
    $elt = getNextEltName($elt, $dir);
  }
  return ""; // If this is reached. No start signal were met.
}

/// ----------- TMS part -------------
function TMS_DebugPrint($msg) {
global $debug;
  if ($debug & 0x04) {
    print "TMS:".date('h:i:s').": ".$msg."\n";
  }
}

function hasSchedule($trainID) {
  return False;
}

function cancelSchedule($trainID) {
  TMS_DebugPrint("Canceling Schedule for train $trainID");
  return;
}

function continueSchedule($trainID) {
  TMS_DebugPrint("Continuing Schedule for train $trainID");
  return;
}

function createSchedule($trainID) {
//For now, consider releasing all routes to avoid ressource conflict?
global $trainData, $trainIndex;
  TMS_DebugPrint("Trying to create Schedule for train $trainID");
  $train = $trainData[$trainIndex[$trainID]];
  //Choose direction and find start signal.
  //For now, always try to start ATO in the up direction
  $dir = "U";
  //Find signal
  $start = findStartSignalForTrain($trainID, $dir);
  if ($start != "") {
    TMS_DebugPrint("Will try to start schedule of train $trainID from signal $start");
  } else {
    TMS_DebugPrint("Could not find start signal in direction $dir of train $trainID");
    $dir = getReverseDir($dir);
    $start = findStartSignalForTrain($trainID, $dir);
    if ($start != "") {
      TMS_DebugPrint("Will try to start schedule of train $trainID from signal $start");
    } else {
      TMS_DebugPrint("Could not find start signal in direction $dir of train $trainID. Aborting schedule creation");
      return;
    }
  }
  //TODO: Start signal was found. Launch schedule accordingly
  //Create routes until next stop (disregarding the start as a stop)
  //Record schedule table
  return;
}

function TMS() {
global $trainData;
  //Check if a train is in ATO
  foreach($trainData as $train) {
    //Check if train is in ATO
    $trainID = $train["ID"];
    if ($train["authMode"] == M_ATO) {
      //check if train already has a schedule
      if (hasSchedule($trainID)) {
        //Continue schedule
        continueSchedule($trainID);
      } else {
        //Create schedule
        createSchedule($trainID);
      }
    } else {
      //check if train has a schedule
      if (hasSchedule($trainID)) {
        //Train is not anymore in ATO. Cancel schedule
        cancelSchedule($trainID);
      }
    }
  }
}
/// ------------ End of TMS ----------
// End of addition of new helpers

function initRBCIL() {
global $trainData, $trainIndex, $DATA_FILE, $SRallowed, $SHallowed, $FSallowed, $ATOallowed;
  //require($DATA_FILE);
  foreach ($trainData as $index => &$train) {
    $train["SRallowed"] = $SRallowed;
    $train["SHallowed"] = $SHallowed;
    $train["FSallowed"] = $FSallowed;
    $train["ATOallowed"] = $ATOallowed;
    $train["reqMode"] = M_UDEF;
    $train["authMode"] = M_N;
    $train["balise"] = "00:00:00:00:00"; 
    $train["baliseName"] = "(00:00:00:00:00)"; // PT1 name
    $train["distance"] = 0;
    $train["speed"] = 0;
    $train["nomDir"] = D_UDEF; // nominel driving direction (UP/DOWN)
    $train["front"] = D_UDEF;
    $train["pwr"] = 0;
    $train["MAreceived"] = 0;
    $train["maxSpeed"] = 0;
    $train["prevBaliseName"] = "00:00:00:00:00";
    $train["prevDistance"] = 0;
    $train["dataValid"] = "VOID";
    $train["toStatus"] = 0;
    $train["pr0"] = false;
    $train["posTimeStamp"] = 0;
    $train["comTimeStamp"] = 0;
    $train["MAbalise"] = "00:00:00:00:00";
    $train["MAbaliseName"] = "(00:00:00:00:00)";
    $train["MAdist"] = 0;
    $trainIndex[$train["ID"]] = $index;
  }
}

function positionReport($data) { // analyse received position report
global $trainIndex, $trainData, $balisesID, $SR_MAX_SPEED, $SH_MAX_SPEED, $ATO_MAX_SPEED, $FS_MAX_SPEED, $now, 
  $posTimeout, $restorePos, $PT1,$points;
  
  if (isset($trainIndex[$data[4]])) {
    $index = $trainIndex[$data[4]];
    $train = &$trainData[$index];
    $balise = sprintf("%02X:%02X:%02X:%02X:%02X",$data[5],$data[6],$data[7],$data[8],$data[9]);
    if ($data[3] == 1) { // valid report
      $train["dataValid"] = "OK";
      $train["comTimeStamp"] = $now;
      $train["reqMode"] = $data[13] & 0x07;
      $train["nomDir"] = ($data[13] & 0x18) >> 3;
      $train["pwr"] = ($data[13] & 0x60) >> 5;
      $train["MAreceived"] = ($data[13] & 0x80) >> 7;
      if ($train["pwr"] == P_R) { // determin orientation of train front end
        $train["front"] = D_UP;
      } elseif ($train["pwr"] == P_L) {
        $train["front"] = D_DOWN;
      }
      // ------ Mode and MA request
      switch ($train["reqMode"]) {
        case M_SR:
          $train["authMode"] = $train["SRallowed"] ? M_SR : M_N;
          $train["maxSpeed"] = $SR_MAX_SPEED;
        break;
        case M_SH:
          $train["authMode"] = $train["SHallowed"] ? M_SH : M_N;
          $train["maxSpeed"] = $SH_MAX_SPEED;
        break;
        case M_FS:
          $train["authMode"] = $train["FSallowed"] ? M_FS : M_N;
          $train["maxSpeed"] = $FS_MAX_SPEED;
          if (!$train["MAreceived"]) { // MA request
          // generate MA  
          }
        break;
        case M_ATO:
          $train["authMode"] = $train["ATOallowed"] ? M_ATO : M_N;
          $train["maxSpeed"] = $ATO_MAX_SPEED;
          if (!$train["MAreceived"]) { // MA request
          // generate MA  
          }
        break;
      }
      if ($balise == "01:00:00:00:01" or $balise == "00:00:00:00:00") { // void position
        if (!$train["pr0"]) {
          $train["pr0"] = true;
          errLog("Train ({$train["ID"]}): Position unknown by train: $balise");
          if ($restorePos) {
            if ($now - $train["posTimeStamp"] <= POSITION_TIMEOUT 
              and $train["balise"] != "00:00:00:00:00" and $train["balise"] != "01:00:00:00:01") {
              errLog("Train ({$train["ID"]}): Position restored to: {$train["balise"]} {$train["distance"]} Stamped: ".
                date("Ymd H:i:s", $train["posTimeStamp"]));
              sendPosRestore($train["ID"], $train["balise"], (int)($train["distance"] / $train["wheelFactor"]));
            } else {
              errLog("Train ({$train["ID"]}): RBC position ({$train["balise"]}) not restored - outdated. Stamped: ".
                date("Ymd H:i:s", $train["posTimeStamp"]));
            }
          }
          $train["balise"] = $balise;
          $train["baliseName"] = "(".$train["balise"].")";
          $train["posTimeStamp"] = $now;   
        }
      } else { // valid position
        $train["pr0"] = false;
        $train["posTimeStamp"] = $now;        
        $train["distance"] = round(toSigned($data[10], $data[11]) * $train["wheelFactor"]);
        $train["speed"] = $data[12];
        $train["balise"] = $balise; 
        // ----- track occupation
        if (isset($balisesID[$train["balise"]])) {
          $train["baliseName"] = $balisesID[$train["balise"]];
          if ($train["prevBaliseName"] != "00:00:00:00:00" and $train["prevBaliseName"] != "01:00:00:00:01") {
            updateTrainPosition($train, $train["prevBaliseName"], $train["prevDistance"], T_CLEAR);
          }
          updateTrainPosition($train, $train["baliseName"], $train["distance"], $train["nomDir"]);
            // mark by which direction (nomDir) train is moving
          $train["prevBaliseName"] = $train["baliseName"];
          $train["prevDistance"] = $train["distance"];
          // At this stage occupation computation is (supposed to be) stable so release pointHold where point is no longer occupied
          foreach ($points as $name) {
//          print "Point $name: TrackState: {$PT1[$name]["trackState"]} | ";
            if ($PT1[$name]["trackState"] == T_CLEAR and $PT1[$name]["pointHeld"]) {
              $PT1[$name]["pointHeld"] = false;
              debugPrint ("Release pointHold for $name");
              pointThrow($PT1[$name],C_RELEASE);
            }
//            print"\n";
          }
        } else {
          $train["baliseName"] = "(".$train["balise"].")";
          errLog("Unknown balise ({$train["balise"]}) reported by train {$train["ID"]}");
        }
      }
    } else { // void position report FIXME
      $train["dataValid"] = "VOID";
      $train["balise"] = "--:--:--:--:--";
      $train["reqMode"] = M_UDEF;
      $train["nomDir"] = D_UDEF;
      $train["pwr"] = P_UDEF;
      $train["speed"] = "--";
      $train["dist"] = "--";
    }
    updateTrainDataHMI($index);
  } // else unknown trainID in posRep
}

function pointThrow(&$element, $command) {
  switch ($element["supervisionState"]) {
  case "U": // Permanetly unsupervised
    return true;
  break;
  case "CR": // Clamped right
  case "CL": // Clamped left
    return false;
  break;
  case "S": // Simulated
    if ($element["trackState"] != T_CLEAR or $element["blockingState"] != B_UNBLOCKED or $element["routeState"] == R_LOCKED) { return false;}
      $element["state"] = $element["expectedLie"] = 
        ($command == C_TOGGLE ? ($element["expectedLie"] == E_LEFT ? E_RIGHT : E_LEFT) : ($command == C_RIGHT ? E_RIGHT : E_LEFT));
    return true;
  break;
  case "P": // Point machine
    switch ($command) {
      case C_TOGGLE:
        if ($element["trackState"] != T_CLEAR or $element["blockingState"] != B_UNBLOCKED or $element["routeState"] == R_LOCKED) { return false;}
        $order = ($element["expectedLie"] == E_LEFT ? O_RIGHT : O_LEFT);    
        $element["state"] = E_MOVING;
        $element["expectedLie"] = ($element["expectedLie"] == E_LEFT ? E_RIGHT : E_LEFT);
      break;
      case C_RIGHT:
        if ($element["trackState"] != T_CLEAR or $element["blockingState"] != B_UNBLOCKED or $element["routeState"] == R_LOCKED) { return false;}
        $order = O_RIGHT;
        $element["state"] = E_MOVING;
        $element["expectedLie"] = E_RIGHT;
      break;
      case C_LEFT:
        if ($element["trackState"] != T_CLEAR or $element["blockingState"] != B_UNBLOCKED or $element["routeState"] == R_LOCKED) { return false;}
        $order = O_LEFT;
        $element["state"] = E_MOVING;
        $element["expectedLie"] = E_LEFT;
      break;
      case C_HOLD:
        $order = ($element["expectedLie"] == E_RIGHT ? O_RIGHT_HOLD : O_LEFT_HOLD);
        $element["state"] = E_MOVING;
      break;
      case C_RELEASE:
        $order = O_RELEASE;
      break; 
      default:
        return false;
    }
    switch ($element["EC"]["type"]) {
      case 10: // point without feedback; no hold
//      print "orderEC: {$element["EC"]["addr"]}, {$element["EC"]["index"]}, $order\n";
        orderEC($element["EC"]["addr"], $element["EC"]["index"], $order);
        return true;
      break;
      default: // type of point machine not assigned or not implemented
        errLog("Point throw: Point machine type {$element["EC"]["type"]} not implemented");
        return false;
    }
  break;
  }
  return false;
}

//Fake train for simulation purpose
function moveTestTrain($dir, $dist) {
global $testBg, $testDist, $trainData, $PT1;
  $train = &$trainData[0];
  $train["baliseName"] = $testBg;
  $train["distance"] = $testDist;
  updateTrainPosition($train, $train["baliseName"], $train["distance"], T_CLEAR);
  $testDist += $dist;

  $d = $testDist;
  $d -= ($dir == "U"? $PT1[$testBg]["U"]["dist"]: $PT1[$testBg]["D"]["dist"]);
  $elt = $testBg;
  //Check if new balise read
  while ($d>0) {
    $elt = getNextEltName($elt, $dir);
    if ($elt == "") {break;}
    if ($PT1[$elt]["element"] == "BL") {
      $delta = $d;
      $delta -= ($dir == "U"? $PT1[$elt]["D"]["dist"]: $PT1[$elt]["U"]["dist"]);
      if ($delta > 0) {
        $testBg = $elt;
        $testDist = $delta;
      }
      $d -= getEltLength($elt);
    }
  }
  $train["baliseName"] = $testBg;
  $train["distance"] = $testDist;
  updateTrainPosition($train, $train["baliseName"], $train["distance"], T_OCCUPIED_UP);
}

function processCommand($command, $from) { // process HMI command
global $PT1, $clients, $clientsData, $inCharge, $trainData, $EC, $now, $balises, $run, $emergencyStop;
//  debugPrint ("HMI command: >$command<");
  $param = explode(" ",$command);
  switch ($param[0]) {
  case "rr": // releaseRoute
    if ($from == $inCharge) {
      startRouteRelease($param[1]);
    }
    break;
  case "lo": // LX order
    if ($from == $inCharge) {
      $element = $PT1[$param[1]];
      switch ($element["state"]) {
        case E_LX_DEACTIVATED:
          ILlevelCrossing($param[1], IL_LX_ACTIVATE);
        break;
        case E_LX_ACTIVATED:
          ILlevelCrossing($param[1], IL_LX_DEACTIVATE);
        break;
      }
      HMIindication($from, "displayResponse {OK}\n");
    } else {
      HMIindication($from, "displayResponse {Rejected}\n");
    }
  break;
  case "pt": // point throw
    if ($from == $inCharge) {
      if (pointThrow($PT1[$param[1]], C_TOGGLE)) {
        HMIindication($from, "displayResponse {OK}\n");
      } else {
        HMIindication($from, "displayResponse {Point Throw Rejected}\n"); // to be detailed FIXME
      }
    } else {
      HMIindication($from, "displayResponse {Rejected}\n");
    }
  break;
  case "pb": // point toggle block
    if ($from == $inCharge) {
      switch ($PT1[$param[1]]["blockingState"]) {
        case B_CLAMPED_LEFT:
        case B_CLAMPED_RIGHT:
          HMIindication($from, "displayResponse {Rejected}\n");
        break;
        case B_BLOCKED_LEFT:
        case B_BLOCKED_RIGHT:
          $PT1[$param[1]]["blockingState"] = B_UNBLOCKED;
          HMIindication($from, "displayResponse {OK}\n");
        break;
        case B_UNBLOCKED:
          $PT1[$param[1]]["blockingState"] = ($PT1[$param[1]]["expectedLie"] == E_RIGHT ? B_BLOCKED_RIGHT : B_BLOCKED_LEFT); 
          HMIindication($from, "displayResponse {OK}\n");
        break;
      }
    } else {
      HMIindication($from, "displayResponse {Rejected}\n");
    }
  break;
  case "SR": // set allowed SR mode for train
    if ($from == $inCharge) {
      if (isset($trainData[$param[1]])) { 
        $trainData[$param[1]]["SRallowed"] = $param[2];
        HMIindicationAll("SRmode ".$param[1]." ".$param[2]."\n");
      } // else invalid index
      HMIindication($from, "displayResponse {OK}\n");
    } else {
      HMIindication($from, "displayResponse {Rejected}\n");
    }
  break;
  case "SH": // set allowed SH mode for train
    if ($from == $inCharge) {
      if (isset($trainData[$param[1]])) { 
        $trainData[$param[1]]["SHallowed"] = $param[2];
        HMIindicationAll("SHmode ".$param[1]." ".$param[2]."\n");
      } // else invalid index
      HMIindication($from, "displayResponse {OK}\n");
    } else {
      HMIindication($from, "displayResponse {Rejected}\n");
    }
  break;
  case "FS": // set allowed FS mode for train
    if ($from == $inCharge) {
      if (isset($trainData[$param[1]])) { 
        $trainData[$param[1]]["FSallowed"] = $param[2];
        HMIindicationAll("FSmode ".$param[1]." ".$param[2]."\n");
      } // else invalid index
      HMIindication($from, "displayResponse {OK}\n");
    } else {
      HMIindication($from, "displayResponse {Rejected}\n");
    }
  break;
  case "ATO": // set allowed ATO mode for train
    if ($from == $inCharge) {
      if (isset($trainData[$param[1]])) { 
        $trainData[$param[1]]["ATOallowed"] = $param[2];
        HMIindicationAll("ATOmode ".$param[1]." ".$param[2]."\n");
      } // else invalid index
      HMIindication($from, "displayResponse {OK}\n");
    } else {
      HMIindication($from, "displayResponse {Rejected}\n");
    }
  break;
  case "eStop": // Toggle emergency stop state
    $emergencyStop = !$emergencyStop;
  break;
  case "Rq": // request operation
    if ($inCharge) {
      HMIindication($from, "displayResponse {Rejected ".$clientsData[(int)$inCharge]["addr"]." is in charge (since ".
        $clientsData[(int)$inCharge]["inChargeSince"].")}\n");
    } else {
      $inCharge = $from;
      $clientsData[(int)$from]["inChargeSince"] = date("Ymd H:i:s");
      HMIindication($from, "oprAllowed\n");
    }
  break;
  case "Rl": // release operation
    $inCharge = false;
    HMIindication($from, "oprReleased\n");
  break;
  case "tr": // Try to lock the route
    if ($from == $inCharge) {
      HMIindication($from, "displayResponse {".setRoute($param[1], $param[2])."}\n");
    }
  break;
  case "reqTo":
    if ($inCharge) {
      print "Not impl. Request take-over of train {$param[1]}\n";
    }
  break;
  case "relTo":
    if ($inCharge) {
      print "Not impl. Release take-over of train {$param[1]}\n";
    }
  break;
  case "txTo":
    if ($inCharge) {
      print "Not impl. Send to train {$param[1]} Mode: {$param[2]} Drive: {$param[3]} Dir: {$param[4]}\n";
    }
  break;
  case "test": 
    print "TEST: no function assigned\n";
  break;
  case "exitRBC":
    if ($from == $inCharge) {
      $run = false;
    }
  break;
  default :
    errLog("Unknown command from client: >$command<");
//    print "Warning: Unknown command from client: >$command<\n";
  break;
  }
}

function RBC() {
  updateRoutesStatus();
}

function ILlevelCrossing($name, $ILorder) {
global $PT1, $now;
  $element = &$PT1[$name];
  switch ($ILorder) {
    case IL_LX_ACTIVATE:
      if ($element["state"] == E_LX_DEACTIVATED) { // & IL conditions like route
        $element["state"] = E_LX_WARNING;
        $element["timer"] = $now + LX_WARNING_TIME;
        orderEC($element["ECsignal"]["addr"], $element["ECsignal"]["index"],O_ROADSTOP);
      } // else reject/ignore
    break;
    case IL_LX_DEACTIVATE:
      if ($element["state"] == E_LX_ACTIVATED) { // & IL conditions
        $element["state"] = E_LX_OPENING;
        orderEC($element["ECbarrier"]["addr"], $element["ECbarrier"]["index"],O_OPEN_BARRIER);
      } // else reject/ignore
    break;
  }
}

function processLX() {
global $now, $levelCrossings, $triggers, $PT1;
  foreach ($triggers as $name) {
    switch ($PT1[$name]["trackState"]) {
      case T_OCCUPIED_UP:
        switch ($PT1[$name]["dir"]) {
          case "U":
            ILlevelCrossing($PT1[$name]["LX"], IL_LX_ACTIVATE);
//print "TG activate, traindir UP \n";
          break;
          case "D":
            ILlevelCrossing($PT1[$name]["LX"], IL_LX_DEACTIVATE);
//print "TG deactivate, traindir UP \n";
          break;
          default :
            print "Warning: unknown direction in trigger $name\n";  
          break;
        }
      break;
      case T_OCCUPIED_DOWN:
        switch ($PT1[$name]["dir"]) {
          case "U":
            ILlevelCrossing($PT1[$name]["LX"], IL_LX_DEACTIVATE);
//print "TG deactivate, traindir DOWN \n";
          break;
          case "D":
            ILlevelCrossing($PT1[$name]["LX"], IL_LX_ACTIVATE);
//print "TG activate, traindir DOWN \n";
            break;
          default :
            print "Warning: unknown direction in trigger $name\n";
          break;
        }      
      break;
    }
  }
  foreach ($levelCrossings as $name) {
    $element = &$PT1[$name];
    // timing
    switch ($element["state"]) {
      case E_LX_WARNING:
        if ($element["timer"] < $now) {
          $element["state"] = E_LX_ACTIVATED;
          orderEC($element["ECbarrier"]["addr"], $element["ECbarrier"]["index"],O_CLOSE_BARRIER);
        }
      break;
    }
    // deactivation by train
    if ($element["trackState"] == T_CLEAR and 
      ($element["prevTrackState"] == T_OCCUPIED_UP or $element["prevTrackState"] == T_OCCUPIED_DOWN or
         $element["prevTrackState"] == T_OCCUPIED_STOP )) {
      ILlevelCrossing($name, IL_LX_DEACTIVATE);
//      print "LX deactivate\n";
    }
    $element["prevTrackState"] = $element["trackState"];
  }
}

//---------------------------------------------------------------------------------------------------------------------------  Server
function initServer() {
global $HMIport, $MCePort, $HMIaddress, $listener, $listenerMCe, $RADIO_LINK_PORT, $RADIO_DEVICE, $radioLink, $RF12GROUP, $RBC_RADIO_ID, $radio;

  $listener = @stream_socket_server("tcp://$HMIaddress:".$HMIport, $errno, $errstr);
  if (!$listener) {
    fwrite(STDERR,"Cannot create server socket for HMI connection: $errstr ($errno)\n");
    die();
  }
  stream_set_blocking($listener,false);
  
  $listenerMCe = @stream_socket_server("tcp://$HMIaddress:".$MCePort, $errno, $errstr);
  if (!$listenerMCe) {
    fwrite(STDERR,"Cannot create server socket for MCe connection: $errstr ($errno)\n");
    die();
  }
  stream_set_blocking($listenerMCe,false);
  
  if ($radio == "USB") {
    $radioLink = fopen($RADIO_DEVICE,"r+");
    if (!$radioLink) {
      fwrite(STDERR,"Cannot create server socket for radioLink: $errstr ($errno)\n");
      die();
    }
    stream_set_blocking($radioLink,false);
// init radioLink (JeeLink)
    fwrite($radioLink,"{$RF12GROUP}g\n"); // Set radio group
    fwrite($radioLink,"1q\n"); // Don't report bad packets
    fwrite($radioLink,"{$RBC_RADIO_ID}i\n");
  } else {
    $radioLink = null;
  }
}

function Server() {
global $ABUS, $listener, $listenerMCe, $clients, $clientsData, $inCharge, $inChargeMCe, $radioLink, $radioBuf, $radio;
  $read = $clients;
  $read[] = $listener;
  $read[] = $listenerMCe;
  if ($radio == "USB") $read[] = $radioLink; 
  if ($ABUS == "genie") {
    global $fromGenie;
    $read[] = $fromGenie;
  }
  $except = NULL;
  $write = NULL;
  if (stream_select($read, $write, $except, 0, 200000 )) {
    foreach ($read as $r) {
      if ($r == $listener) { // new HMI client
        if ($newClient = stream_socket_accept($listener,0,$clientName)) {
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
      } elseif ($ABUS == "genie" and $r == $fromGenie) {
        if ($data = fgets($r)) {
          AbusReceivedPacketGenie($data);
        }
      } elseif ($r == $radioLink) { // ---------------------------- Radio Link
        while ($res = fgets($r)) { // Get all available data
        $radioBuf = $radioBuf.$res;
        if (false !== strpos($res,"\n")) {
          receivedFromRadioLink($radioBuf); // process data from radio
          $radioBuf = "";
        }
        }
      } else { // exsisting client
        if ($data = fgets($r)) {
          switch ($clientsData[(int)$r]["type"]) {
            case "HMI":
              processCommand(trim($data),$r);
            break;
            case "MCe":
              processCommandMCe(trim($data),$r);
            break;
          }
        } else { // Connection closed by client
          msgLog("Client ".stream_socket_get_name($r,true)." signed out");
          fclose($r);
          unset($clientsData[(int)$r]);
          unset($clients[array_search($r, $clients, TRUE)]);
          if ($r == $inCharge) {
            $inCharge = false;
          } elseif ($r == $inChargeMCe) {
            $inCharge = false;
          }
        }
      }
    }
  }
}

// ------------------------------------------------------------------------------------ HMI

function HMIstartup($client) { // Initialize specific client and send track layout, status, train data, version info
global $PT1, $HMI, $trainData, $VERSION, $PT1_VERSION;
// HMI screen layout
  HMIindication($client,"RBCversion $VERSION $PT1_VERSION\n");
  HMIindication($client,".f.canvas delete all\n");
  HMIindication($client,"destroyTrainFrame\n");
  HMIindication($client,"dGrid\n");  
  HMIindication($client,"resetLabel\n");  
  foreach ($HMI["color"] as $param => $color) {
    HMIindication($client,"set ::$param $color\n");  
  }
  if (isset($HMI["scale"])) {
    HMIindication($client,"set ::scale ".$HMI["scale"]."\n");
  }
  foreach ($HMI["label"] as $label) {
    HMIindication($client,"label {".$label["text"]."} ".$label["x"]." ".$label["y"]."\n");  
  }
// track layout
  foreach ($PT1 as $name => $element) {
    switch ($element["element"]) {
    case "PF":
    case "PT":
      HMIindication($client,"point $name ".$element["HMI"]["x"]." ".$element["HMI"]["y"]." ".$element["HMI"]["or"]."\n");
      HMIindication($client,"pointState $name ".$element["state"]." ".$element["routeState"]." ".$element["trackState"]." ".
        $element["blockingState"]."\n");
    break;
    case "SU":
      HMIindication($client,"signal $name ".$element["HMI"]["x"]." ".$element["HMI"]["y"]." f\n");
      HMIindication($client,"signalState $name ".$element["state"]." ".$element["routeState"]." ".$element["trackState"]."\n");
    break;
    case "SD":
      HMIindication($client,"signal $name ".$element["HMI"]["x"]." ".$element["HMI"]["y"]." r\n");
      HMIindication($client,"signalState $name ".$element["state"]." ".$element["routeState"]." ".$element["trackState"]."\n");
    break;
    case "BSB":
      HMIindication($client,"bufferStop $name ".$element["HMI"]["x"]." ".$element["HMI"]["y"]." b ".$element["HMI"]["l"]."\n");
      HMIindication($client,"bufferStopState $name ".$element["state"]." ".$element["routeState"]." ".$element["trackState"]."\n");
    break;
    case "BSE":
      HMIindication($client,"bufferStop $name ".$element["HMI"]["x"]." ".$element["HMI"]["y"]." e ".$element["HMI"]["l"]."\n");
      HMIindication($client,"bufferStopState $name ".$element["state"]." ".$element["routeState"]." ".$element["trackState"]."\n");
    break;
    case "LX":
      HMIindication($client,"levelcrossing $name ".$element["HMI"]["x"]." ".$element["HMI"]["y"]."\n");
      HMIindication($client,"levelcrossingState $name ".$element["status"]." ".$element["routeState"]." ".$element["trackState"]."\n");
    break;
    }
  }
  HMIindication($client,".f.canvas raise button [.f.canvas create text 0 0]\n"); // Ensure that all element buttons are on the top layer
// HMI data
  foreach ($HMI["baliseTrack"] as $trackName => $baliseTrack) {
    HMIindication($client,"track $trackName ".$baliseTrack["x"]." ".$baliseTrack["y"]." ".$baliseTrack["l"]." ".$baliseTrack["or"]."\n");
    HMIindication($client,"trState $trackName ".$baliseTrack["routeState"]." ".$baliseTrack["trackState"]." ".$baliseTrack["trainID"]."\n");
  }
// train data
  foreach ($trainData as $index => &$train) { // train data
    HMIindication($client, "trainFrame ".$index."\n");
    HMIindication($client, "trainDataS ".$index." {".$train["name"]." (".$train["ID"].")} ".$train["lengthFront"]."+".$train["lengthBehind"]."\n");
    HMIindication($client, "SRmode ".$index." ".$train["SRallowed"]."\n");
    HMIindication($client, "SHmode ".$index." ".$train["SHallowed"]."\n");
    HMIindication($client, "FSmode ".$index." ".$train["FSallowed"]."\n");
    HMIindication($client, "ATOmode ".$index." ".$train["ATOallowed"]."\n");
    updateTrainDataHMI($index);
  }
}

function updateHMI() {
global $HMI, $PT1, $emergencyStop;
  HMIindicationAll("eStopInd ".($emergencyStop ? "true" : "false")."\n");
  foreach ($HMI["baliseTrack"] as $name => &$baliseTrack) { // compute indication of HMI track segment only representating balises
    $baliseTrack["trackState"] = T_CLEAR;
    $baliseTrack["routeState"] = R_IDLE;
    $baliseTrack["trainID"] = "";
    foreach ($baliseTrack["balises"] as $baliseName ) {
      if ($PT1[$baliseName]["routeState"] == R_LOCKED) {
        $baliseTrack["routeState"] = R_LOCKED;
      } 
      if ($PT1[$baliseName]["trackState"] != T_CLEAR) {
        switch ($baliseTrack["trackState"]) {
          case T_CLEAR:
//          case T_LOCKED:
            $baliseTrack["trackState"] = $PT1[$baliseName]["trackState"];
          break;
          default:
            break;
          //already occupied. Occupy has priority on the rest.
        }
        foreach ($PT1[$baliseName]["trainIDs"] as $trainID ) {
          #Giving the name to the HMI and "??" in case of multiple occupation
          if (($baliseTrack["trainID"] != "")  and ($baliseTrack["trainID"] != $trainID)) {
            $baliseTrack["trainID"] = "??";
          } elseif ($baliseTrack["trainID"] != "??") {
            $baliseTrack["trainID"] = $trainID;
          }
        }
      }
    }
    HMIindicationAll("trState $name ".$baliseTrack["routeState"]." ".$baliseTrack["trackState"]." ".$baliseTrack["trainID"]."\n");
  }
  unset($name);
  foreach ($PT1 as $name => $element) {
    $displayedTrainID = "";
    foreach ($element["trainIDs"] as $trainID ) {
      #Giving the name to the HMI and "??" in case of multiple occupation
      if (($displayedTrainID != "")  and ($displayedTrainID != $trainID)) {
        $displayedTrainID = "??";
      } elseif ($displayedTrainID != "??") {
        $displayedTrainID = $trainID;
      }
    }
    switch ($element["element"]) {
      case "SU":
      case "SD":
//        if (isLockedRoute($name)) {
//          //13 is used in HMI to highlight destination signals FIXME
//          HMIindicationAll("signalState $name 13 ".$element["routeState"]." ".$element["trackState"]." ".$displayedTrainID."\n");
//        } else {
          HMIindicationAll("signalState $name ".$element["state"]." ".$element["routeState"]." ".$element["trackState"]." ".
            $displayedTrainID."\n");
//        }
      break;
      case "PF":
      case "PT":
        HMIindicationAll("pointState $name ".$element["state"]." ".$element["routeState"]." ".$element["trackState"]." ".
          $element["blockingState"]." ".$displayedTrainID."\n");
      break;
      case "BSB":
      case "BSE":
        HMIindicationAll("bufferStopState $name  ".$element["state"]." ".$element["routeState"]." ".$element["trackState"]." "
          .$displayedTrainID."\n");
      break;
      case "LX":
        HMIindicationAll("levelcrossingState $name ".$element["barrierStatus"]." ".$element["routeState"]." ".$element["trackState"]." ".
          $displayedTrainID."\n"); // Not only barrier status FIXME
      break;
    }
  }
}

function updateTrainDataHMI($index) {
global $trainData, $MODE_TXT, $DIR_TXT, $PWR_TXT, $ACK_TXT, $TOSTATE_TXT;
  $train = $trainData[$index];

  HMIindicationAll("trainDataD ".$index." {".$MODE_TXT[$train["authMode"]]." (".$MODE_TXT[$train["reqMode"]].")} ".$train["baliseName"]." {".
        $train["distance"]."} {".$train["speed"]."} {".$DIR_TXT[$train["nomDir"]]."} {".$PWR_TXT[$train["pwr"]]."} {".
        $ACK_TXT[$train["MAreceived"]]."} ".$train["dataValid"]." {".$TOSTATE_TXT[$train["toStatus"]]."} {".$train["MAbaliseName"]."} ".$train["MAdist"]."\n");
}

function HMIindicationAll($msg) {// Send indication to all clients
global $clients, $clientsData;
  foreach ($clients as $w) {
    if ($clientsData[(int)$w]["type"] == "HMI") {
      fwrite($w,$msg);
    }
  }
}

function HMIindication($to, $msg) {// Send indication to specific client
  fwrite($to,$msg);
}

// ------------------------------------------------------------- MCe

function MCeStartup($client) {
global $EC;
  MCeIndication($client,"serverFrames\n");
  foreach ($EC as  $addr => $ec) {
    MCeIndication($client,"ECframe $addr\n");
  }
}

function updateMCe() {
global $EC, $startTime;
  MCeIndicationAll("set ::serverUptime {".trim(`/usr/bin/uptime`)."}\n");
  MCeIndicationAll("set ::RBCuptime {".(time() - $startTime)."}\n");
  foreach($EC as $addr => $ec) {
    MCeIndicationAll("set ::EConline($addr) ".($ec["EConline"] ? "Online" : "Offline")."\n");
    MCeIndicationAll("set ::ECuptime($addr) {$ec["uptime"]}\n");
    MCeIndicationAll("set ::elementConf($addr) {$ec["elementConf"]}\n");
    MCeIndicationAll("set ::N_ELEMENT($addr) {$ec["N_ELEMENT"]}\n");
    MCeIndicationAll("set ::N_PDEVICE($addr) {$ec["N_PDEVICE"]}\n");
    MCeIndicationAll("set ::N_UDEVICE($addr) {$ec["N_UDEVICE"]}\n");
    MCeIndicationAll("set ::N_LDEVICE($addr) {$ec["N_LDEVICE"]}\n");
  }
}

function processCommandMCe($command, $from) {
global $EC, $clients, $clientsData, $inChargeMCe, $run, $lockedRoutes, $trainData;

//  print "MCe command: $command\n";
  $param = explode(" ",$command);
  switch ($param[0]) {
    case "test1": // Dump train data
    foreach ($trainData as $trainID => $data) {
      print "TrainID $trainID:\n";
      print_r ($data);
    }
    break;
    case "test2": // Dump lockedRoutes
      print "Dump lockedRoutes:\n";
      print_r($lockedRoutes);
    break;
    case "ECstatus":
      foreach($EC as $addr => $ec) {
        requestECstatus($addr);
      }
    break;
    case "Rq": // request operation
      if ($inChargeMCe) {
        MCeIndication($from, "displayResponse {Rejected ".$clientsData[(int)$inChargeMCe]["addr"]." is in charge (since ".
          $clientsData[(int)$inChargeMCe]["inChargeSince"].")}\n");
      } else {
        $inChargeMCe = $from;
        $clientsData[(int)$from]["inChargeSince"] = date("Ymd H:i:s");
        MCeIndication($from, "oprAllowed\n");
      }
    break;
    case "Rl": // release operation
      $inChargeMCe = false;
      MCeIndication($from, "oprReleased\n");
    break;
    case "exitRBC":
      if ($from == $inChargeMCe) {
        $run = false;
      }
    break;
    default:
      errLog("Unknown MCe command: {$param[0]}");
  }
}

function MCeIndicationAll($msg) {// Send indication to all MCe clients
global $clients, $clientsData;
  foreach ($clients as $w) {
    if ($clientsData[(int)$w]["type"] == "MCe") {
      fwrite($w,$msg);
    }
  }
}

function MCeIndication($to, $msg) {// Send indication to specific MCe client
  fwrite($to,$msg);
}

// -------------------------------------- CBU clock
// Clock connected to ABUS
function CBUupdate() {
global $arduino;
  $time = localtime();
  $temp = 51; // ugyldig CBU temp
/*  if ($fp = fopen("/mnt/1wire/$tempID/temperature","r")) {
    if (!is_numeric($temp = fgets($fp))) {
      $temp = 51; // ugyldig CBU temp
      logMsg("Ikke-numerisk temp");
    }
  } else {
    $temp = 51; // ugyldig CBU temp
    logMsg("Kan ikke åbne temp");   
  }  
*/
  $x = sprintf("/usr/sbin/i2cset -y 1 %d 1 %d %d %d %d 255 255 255 255 i",$arduino, $time[2], $time[1], ($temp >= 0 ? 1 : 0), round(abs($temp) * 5)); // CBU Master kommando for AbusBroadcast
  I2C($x);
//  @fclose($fpTemperatur);
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
      fwrite($toGenie,$TXbuf);
    break;
    case "cbu":
      $packet[0] = $addr;
      $packet[1] = 0; // dummy
      $data = AbusGateway($packet,20);
      for ($x = 0; $x < count($data); $x++) {
        $data[$x] = hexdec($data[$x]);
      }
      if ($data[0] != 0) { // timeout
        errLog("EC ($addr) Abus Time out: {$data[0]} Packet type: {$packet[2]}");
        $addr = false;
        $data = array();
      }
      receivedFromEC($addr, $data);
    break;
  }
}

function AbusGateway($AbusPackage, $PackageLength = 20) {
global $MaxAbusBuf, $AbusMasterI2Caddress;

//  fputs($gr,"1"); // flash green RasPI indicator

  $cmd = "/usr/sbin/i2cset -y 1 $AbusMasterI2Caddress 101";
  for ($x = 0; $x < count($AbusPackage); $x++) {
    $cmd .= " ".$AbusPackage[$x];
  }
  $cmd .= " i";
  $n = 0;
  do {
    if ($n > 0) {
      errLog("AbusGateway retry $n");
    }
    exec($cmd,$output,$wStat);
    usleep(120000); //  Afvent evt. timeout på Abus
    $res = array();
    for ($t = 0; $t < $PackageLength; $t++) {
      exec("/usr/sbin/i2cget -y 1 $AbusMasterI2Caddress",$res,$rStat);
      if ($rStat) errLog("AbusGateway: Error reading AbusMaster. Status: $rStat");
    }
    $n += 1;
  } while ($wStat > 0 and $n < N_I2CSET);
  if ($wStat) {
    errLog("AbusGateway: Error writing AbusMaster. Status: $wStat after $n retry");
  }
//  fputs($gr,"0");
  return $res;
}


function AbusReceivedPacketGenie($line) {
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
//  print_r($data);
  receivedFromEC($addr, $data);
}

function I2C($cmd) {
//global $grInd;
//  fputs($grInd,"1"); // blink grøn
  exec($cmd);
//  fputs($grInd,"0"); // blink grøn
}
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
global $debug, $background, $RBCIL_CONFIG, $DATA_FILE, $SYSTEM_NAME, $VERSION, $argv, $ABUS, $SRallowed, $SHallowed, $FSallowed, $ATOallowed, $doInitEC, $radio;
  if (in_array("-h",$argv)) {
    fwrite(STDERR,"Usage:
-b, --background  start as daemon
-c                select CBUMaster as Abus gateway
-f <conf_file>    configuration of $SYSTEM_NAME
-g                select GenieMaster as Abus gateway 
-n                do not connect to Abus gateway
-D <Data_file>    PT1 and Train data
-d                enable debug info, level all
-dg               enable debug info, general
-dr               enable debug info, RBC
-dt               enable debug info, TMS
-sr               enable Staff Responsible for all trains
-sh               enable Shunt Mode for all trains
-fs               enable Full Supervision Mode for all trains
-ato              enable ATO Mode for all trains
-nr               No radio - do not poll radio
-ni               Not impl.: do not init EC
");
    exit();
  }
  next($argv);
  while (list(,$opt) = each($argv)) {
    switch ($opt) {
    case "-sr":
      $SRallowed = 1;
    break;
    case "-sh":
      $SHallowed = 1;
    break;
    case "-fs":
      $FSallowed = 1;
    break;
    case "-ato":
      $ATOallowed = 1;
    break;
//    case "-ni":
//      $doInitEC = false;
//    break;
    case "-c":
      $ABUS = "cbu";
      fwrite(STDERR,"Abus gateway: CBUMaster\n");
      break;
    case "-g":
      $ABUS = "genie";
      fwrite(STDERR,"Abus gateway: GenieMaster\n");
      break;
    case "-n":
      $ABUS = "none";
      fwrite(STDERR,"Warning: No Abus gateway selected\n");
      break;
    case "-nr":
      $radio = "none";
      fwrite(STDERR,"Warning: Radio polling disabled\n");
      break;
    case "-f":
      list(,$p) = each($argv);
      if ($p) {
        $RBCIL_CONFIG = $p;
        if (!is_readable($RBCIL_CONFIG)) {
          fwrite(STDERR,"Error: option -f: Cannot read $RBCIL_CONFIG \n");
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
    case "-dg":
    case "--debug";
      $debug = $debug | 0x01;
      fwrite(STDERR,"Debug general mode\n");
    break;
    case "-dr":
    case "--debugRBC";
      $debug = $debug | 0x02;
      fwrite(STDERR,"Debug RBC mode\n");
    break;
    case "-dt":
    case "--debugTMS";
      $debug = $debug | 0x04;
      fwrite(STDERR,"Debug TMS mode\n");
    break;
    default :
      fwrite(STDERR,"Unknown option: $opt\n");
      exit(1);
    }
  }
}

function prepareMainProgram() {
global $logFh, $errFh, $debug, $ERRLOG, $MSGLOG, $RBCIL_CONFIG, $DATAFh, $DATA_FILE, $ABUS;
  if ($debug) {
    error_reporting(E_ALL);
  } else {
    error_reporting(0);
  }
  if (!$ABUS) {
    die("Error: no Abus gateway selected\n");
  }
  if (!($errFh = fopen($ERRLOG,"a"))) {
    fwrite(STDERR,"Warning: Cannot open Error log file: $ERRLOG\n");
    $errFh = fopen("/dev/null","w");
  }
  if (!($logFh = fopen($MSGLOG,"a"))) {
    fwrite(STDERR,"Warning: Cannot open Log file: $MSGLOG\n");
    $logFh = fopen("/dev/null","w");
  }
/*  if (!is_writable($ERRLOG)) {
    fwrite(STDERR,"Warning: Cannot open Error log file: $ERRLOG\n");
  }
  if (!is_writable($MSGLOG)) {
    fwrite(STDERR,"Warning: Cannot open Log file: $MSGLOG\n");
  }
*/
  if (!is_readable($DATA_FILE)) {
    fwrite(STDERR,"Error: Cannot read data file: $DATA_FILE\n");
    exit(1); // PT1 and Train data is mandatory
  }
  if (is_readable($RBCIL_CONFIG)) {
    require($RBCIL_CONFIG);
  } else {
    fwrite(STDERR,"Warning: Cannot read RBCIL config file: $RBCIL_CONFIG\n");
    Fwrite(STDERR,"Using default parameters...\n");
  } // config file is optional
}

function initMainProgram() {
global $logFh, $errFh, $debug, $ERRLOG, $MSGLOG, $RBCIL_CONFIG, $DATAFh, $DATA_FILE, $ABUS, $background;

//$gr = fopen("/sys/class/gpio/gpio17/value","w"); // green indicator at RasPI
  if (!($errFh = fopen($ERRLOG,"a"))) {
    fwrite(STDERR,"Warning: Cannot open Error log file: $ERRLOG\n");
    $errFh = fopen("/dev/null","w");
  }
  if (!($logFh = fopen($MSGLOG,"a"))) {
    fwrite(STDERR,"Warning: Cannot open Log file: $MSGLOG\n");
    $logFh = fopen("/dev/null","w");
  }
  if (!(@$DATAFh = fopen($DATA_FILE,"r"))) { // file exsist FIXME
    fwrite(STDERR,"Error: Cannot open PT1 and Train data file: $DATA_FILE\n");
    exit(1); // PT1 data is mandatory
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
