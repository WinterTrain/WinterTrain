#!/usr/bin/php
<?php
//--------------------------------------- Abus configuration
$arduino = 0x33; // CBU Master, I2C addresse
$ABUS = "";

//--------------------------------------- Default Configuration
$VERSION = "02P02";  // What does this mean with git??
$HMIport = 9900;
$HMIaddress = "0.0.0.0";
$ABUS_GATEWAYaddress = "10.0.0.201";
$ABUS_GATEWAYport = 9200;

// File names
$RBCIL_CONFIG = "RBCILconf.php";
#$PT1_DATA = "data/RBCIL_PT1.php";
#$TRAIN_DATA = "data/RBCIL_TRAIN.php";
$DATA_FILE = "../SiteData/W57/W57.php";
$ERRLOG = "log/RBCIL_ErrLog.txt";
$MSGLOG = "log/RBCIL.log";

// ------------------------------------------------------------------------ To be moved to conf. file
$radioLinkAddr = 150;
$SR_MAX_SPEED = 100;
$SH_MAX_SPEED = 60;
$ATO_MAX_SPEED = 40;

// ---------------------------------------- Timing
define("EC_TIMEOUT",5);
define("TRAIN_DATA_TIMEOUT",5);
define("LX_WARNING_TIME",2);

// ----------------------------------------Enummerations
// Route
define("R_IDLE", 0);
// Interlocking Element state
define("E_UNSUPERVISED",0);
define("E_OPEN",10); // Signal
define("E_CLOSED",11);
define("E_LEFT",20); // point
define("E_RIGHT",21);
define("E_LX_DEACTIVATED",50); // Normal state
define("E_LX_WARNING",51); // Warning signals flashing
define("E_LX_ACTIVATED",52); // 
define("E_LX_OPENING",53); // Deaktiveret, opening
// Direction
define("D_UDEF",0);
define("D_DOWN",1);
define("D_UP",2);
define("D_STOP",3);
// Track status
define("T_UNSUPERVISED",0);
define("T_OCCUPIED_DOWN",1);
define("T_OCCUPIED_UP",2);
define("T_OCCUPIED_STOP",3);
define("T_CLEAR",5);
// Train mode
define("M_UDEF",0);
define("M_N",5);
define("M_SR",1);
define("M_SH",2);
define("M_FS",3);
define("M_ATO",4);
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

// Status
define("S_UNSUPERVISED",0);
define("S_CLOSED",1);
define("S_PROCEED",2);
define("S_PROCEEDPROCEED",3);
define("S_BARRIER_CLOSED",1);
define("S_BARRIER_OPEN",2);
define("S_U_RIGHT",5);
define("S_U_LEFT",6);

//Track traversal feedback
define("TRACK_TRAVERSALE_REJECT", 0);
define("TRACK_TRAVERSALE_ACCEPT_DO_NOTHING", 1);
define("TRACK_TRAVERSALE_ACCEPT_ACTIVATE_PAYLOAD", 2);

// -------------------------------------- Txt
$MODE_TXT = [0 => "Udef", 1 => "SR", 2 => "SH", 3 => "FS", 4 => "ATO", 5 => "N", ];
$DIR_TXT = [0 => "Udef", 1 => "Down", 2 => "Up", 3 => "Stop",];
$PWR_TXT = [0 => "NoComm", 1 => "R", 2 => "L", 3 => "No PWR",];
$ACK_TXT = [0 => "NO_MA", 1 => "MA_ACK"];

//--------------------------------------- System variable
$debug = FALSE; $background = FALSE; $run = true;
$pollTimeout = 0;
$timeoutUr = 0;

//--------------------------------------- HMI variable
$clients = array();
$clientsData = array();
$inCharge = false;

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

$errorFound = false;
$totalElement = 0;

$SHallowed = 0;
$FSallowed = 0;
$ATOallowed = 0;

//--------------------------------------- System 
cmdLineParam();
if ($ABUS == "cbu") {
  include '/home/jb/scripts/AbusMasterLib.php'; // must be included at global level
}
prepareMainProgram();
versionInfo();
processPT1();
initRBCIL();
forkToBackground();
initMainProgram();
AbusInit();
initEC();
initHMIServer();
do {
  $now = time();
  if ($now != $pollTimeout) {
//  print "$now: ";
    $pollTimeout = $now;
  
  foreach ($EC as $addr => $ec) {
    if ($now > $ec["validTimer"]) { // EC not providing status
    // EC offline
      foreach ($ec["index"] as $name) {
        $PT1[$name]["status"] = S_UNSUPERVISED; // barrier and road signal status to be set to unsupervised as well  FIXME
        HMIindicationAll("signalState $name ".S_UNSUPERVISED." ".$PT1[$name]["trackState"]."\n");
      }
      if ($addr == $radioLinkAddr) {
        // position report UDEF------------------------------------- FIXME
      }
    }
  }
  foreach ($trainData as $index => &$train) {
    if ($now > $train["validTimer"]) { // Train not sending position reports
      $train["dataValid"] = "VOID";
      updateTrainDataHMI($index);
    }
  }
//  print " a";
    processLX();
//  print " b";
    pollEC();
//  print " c";
    pollRadioLink();
//  print " d";
    IL();
//  print " e";
    RBC();
//  print " f";
    updateHMI();
//  print " g\n";
  }
  if ($ABUS == "cbu" and $timeoutUr <= $now) {
    $urTimeout = $now + 60;
    CBUupdate();
  }
  HMIserver();
} while ($run);
msgLog("Exitting...");

//--------------------------------------------------------------------------------------  RBCIL
function processPT1() {
global $DATA_FILE, $PT1_VERSION, $PT1, $HMI, $errorFound, $totalElement, $points, $signals, $levelCrossings,
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
                print "Error: ($prevName => $name) Inconsistancy in branch reference\n";
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
                print "Error: ($prevName => $name) Inconsistancy in branch reference\n";
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
          print "Error: ($prevName => $name) Inconsistancy in reference\n";
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
  foreach ($PT1 as $name => &$element) {  // Check each node and generate various lists
    $element["checked"] = false;
    $element["routeState"] = R_IDLE;
    $element["trackState"] = T_CLEAR;
//>>JP:TRAIN_ID
    $element["trainIDs"] = [];
//<<JP:TRAIN_ID
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
      case "LX":
        $levelCrossings[] = $name;
        $element["state"] = E_LX_DEACTIVATED;
        $element["status"] = S_UNSUPERVISED; // combined status
        $element["signalStatus"] = S_UNSUPERVISED;
        $element["barrierStatus"] = S_UNSUPERVISED;
        $element["prevTrackState"] = T_CLEAR;
      break;
      case "PF":
      case "PT":
        $points[] = $name;
        $element["state"] = E_LEFT; // logical state, initial state to reflect physical state FIXME
        $element["status"] = S_UNSUPERVISED; // physical state from EC
      break;
      case "SU":
      case "SD":
        $signals[] = $name;
        $element["state"] = E_CLOSED; // purpose? FIXME
        $element["status"] = $element["type"] == "MB" ? S_CLOSED : S_UNSUPERVISED;
      break;
      case "BSB":
      case "BSE":
        $bufferstops[] = $name;
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
    errLog("Error: Track network not OK. Source: $DATA_FILE");
    exit(1);
  } else {
    msgLog("Found $totalElement in PT1 data file: $DATA_FILE");
  }
// HMI data
  if (array_key_exists("", $HMI["baliseTrack"])) { unset($HMI["baliseTrack"][""]); } // delete any remaining template entry
  foreach ($HMI["baliseTrack"] as $trackName => $baliseTrack ) {
    $baliseTrack["trackState"] = T_CLEAR;
    foreach ($baliseTrack["balises"] as $baliseName ) {
      if (!isset($PT1[$baliseName])) {
        $errorFound = true;
        print "Unknown balise \"$baliseName\" in HMI baliseTrack: $trackName\n";
      }
    }
  }
  if ($errorFound) {
    print "Error: HMI data not OK. Source: $DATA_FILE\n";
    errLog("Error: HMI data not OK. Source: $DATA_FILE");
    exit(1);
  }

//  print_r($LX);
}

// --------------------------------------------------------------------- RadioLink

function pollRadioLink() {
global $trainData;
  foreach ($trainData as $index => $train) {
    sendMA($train["ID"], $train["authMode"], $train["MAbalise"], $train["MAdist"], $train["maxSpeed"]);
  }
}

function sendMA($trainID, $authMode, $balise, $dist, $speed) { // send mode and movement authorization. Request status and position report
global $radioLinkAddr;
  $packet[2] = 03;
  $packet[3] = $trainID; 
  $packet[4] = $authMode;
  for ($b = 0; $b < 5; $b++) $packet[$b + 5] = hexdec($balise[$b]);
  $packet[10] = $dist & 0xFF;
  $packet[11] = ($dist & 0xFF00) >> 8;
  $packet[12] = $speed;
  AbusSendPacket($radioLinkAddr, $packet, 13);
} 

//---------------------------------------------------------------------- EC interface
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
            $EC[$addr]["index"] = array();
            $EC[$addr]["validTimer"] = 0;
            resetEC($addr);
          }
//        print "$name: addr:$addr type:".$element["EC"]["type"]." device1:".$element["EC"]["device1"]."\n";
          configureEC($addr, $element["EC"]["type"], $element["EC"]["device1"]);
          $element["EC"]["index"] = count($EC[$addr]["index"]);
          $EC[$addr]["index"][] = $name;
        }
        break;
        case "SU":
        case "SD":
        if ($element["EC"]["type"] != 0) {
          $addr = $element["EC"]["addr"];
          if (!isset($EC[$addr])) {
            $EC[$addr]["index"] = array();
            $EC[$addr]["validTimer"] = 0;
            resetEC($addr);
          }
//        print "$name: addr:$addr type:".$element["EC"]["type"]." device1:".$element["EC"]["device1"]."\n";
          configureEC($addr, $element["EC"]["type"], $element["EC"]["device1"]);
          $element["EC"]["index"] = count($EC[$addr]["index"]);
          $EC[$addr]["index"][] = $name;
        }
        break;
        case "LX":
        if ($element["ECbarrier"]["type"] != 0) {
          $addr = $element["ECbarrier"]["addr"];
          if (!isset($EC[$addr])) {
            $EC[$addr]["index"] = array();
            $EC[$addr]["validTimer"] = 0;
            resetEC($addr);
          }
//        print "LXbarrier $name: addr:$addr type:".$element["ECbarrier"]["type"]." device1:".$element["ECbarrier"]["device1"]."\n";
          configureEC($addr, $element["ECbarrier"]["type"], $element["ECbarrier"]["device1"]);
          $element["ECbarrier"]["index"] = count($EC[$addr]["index"]);
          $EC[$addr]["index"][] = $name;
        }
        if ($element["ECsignal"]["type"] != 0) {
          $addr = $element["ECsignal"]["addr"];
          if (!isset($EC[$addr])) {
            $EC[$addr]["index"] = array();
            $EC[$addr]["validTimer"] = 0;
            resetEC($addr);
          }
//        print "LXsignal $name: addr:$addr type:".$element["ECsignal"]["type"]." device1:".$element["ECsignal"]["device1"]."\n";
          configureEC($addr, $element["ECsignal"]["type"], $element["ECsignal"]["device1"]);
          $element["ECsignal"]["index"] = count($EC[$addr]["index"]);
          $EC[$addr]["index"][] = $name;
        }
        break;
      }
    }
  }
//  print_r($EC);
//  print_r($PT1);
}

function pollEC() {
global $PT1, $EC;
  foreach ($EC as $addr => $ec) {
    requestElementStatusEC($addr);
    foreach ($ec["index"] as $index => $name) {
      if ($PT1[$name]["state"] == E_OPEN)  { // O_PROCEED_EXTENDED to be add for next version FIXME
        orderEC($addr, $index, O_PROCEED);
      }
    }
  }
}

function resetEC($addr) {
//print "resetEC $addr\n";
  $packet[2] = 20;
  $packet[3] = 00;
  AbusSendPacket($addr, $packet, 4);
}

function configureEC($addr, $elementType, $device1, $device2 = 0) {
  $packet[2] = 20;
  $packet[3] = 01;
  $packet[4] = $elementType;
  $packet[5] = $device1;
  $packet[6] = $device2;
  AbusSendPacket($addr, $packet, 7);
 }

function orderEC($addr, $index, $order) {
//print "orderEC $addr, $index, $order\n";
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
  if ($addr) {
    switch ($data[2]) { // packet type
      case 01: // status
      case 10: // status
        elementStatusEC($addr, $data);
        break;
      case 02: // EC status
        break;  
      case 03: // position report
        positionReport($data);
        break;
      case 20: // configuration
        if ($data[3] > 0) {
          print "Configuration error: ".$data[3]."\n";
        } else {
          print "Configuration OK\n";
        }
        break;
      default:
        print "Unknown packet: ".$data[2]."\n";
    }
  } else {
    // log or ignore timeout?? ------- Might be due to timeout when wrong EC address is used. FIXME
  }
}

function elementStatusEC($addr, $data) { // Analyse element status from EC
global $EC, $PT1;
  if (isset($EC[$addr]) and $data[3] < count($EC[$addr]["index"])) {
    print "Error: EC not configured\n"; //---------------------------------------------------------------------FIXME
    initEC($addr);
  } else {
    $EC[$addr]["validTimer"] = time() + EC_TIMEOUT;
//    print_r($data);
    foreach ($EC[$addr]["index"] as $index => $name) {
      $element = &$PT1[$name];
    $status = $index % 2 ? ((int)$data[$index/2 +4] & 0xF0) >> 4 : (int)$data[$index/2 +4] & 0x0F ;
      switch ($element["element"]) {
        case "SD":
        case "SU":
        case "PT":
        case "PF":
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

//------------------------------------------------------------------- RBC-IL
// New helper functions for setting routes

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
      if ($direction === "U") {
        if ($PT1[$element]["R"]["name"] === $previousElement) {
          $elts[] = $PT1[$element]["T"] + ["direction" => "T", "position" => "R"];
        } elseif ($PT1[$element]["L"]["name"] === $previousElement) {
          $elts[] = $PT1[$element]["T"] + ["direction" => "T", "position" => "L"];
        } else {
          $elts[] = $PT1[$element]["T"] + ["direction" => "T", "position" => "UNKNOWN"];
        }
      } else { #Direction down
          $elts[] = $PT1[$element]["R"] + ["direction" => "F", "position" => "R"];
          $elts[] = $PT1[$element]["L"] + ["direction" => "F", "position" => "L"];
      }
      break;
    case "PF":
      if ($direction === "D") {
        if ($PT1[$element]["R"]["name"] === $previousElement) {
          $elts[] = $PT1[$element]["T"] + ["direction" => "T", "position" => "R"];
        } elseif ($PT1[$element]["L"]["name"] === $previousElement) {
          $elts[] = $PT1[$element]["T"] + ["direction" => "T", "position" => "L"];
        } else {
          $elts[] = $PT1[$element]["T"] + ["direction" => "T", "position" => "UNKNOWN"];
        }
      } else { #Direction up
          $elts[] = $PT1[$element]["R"] + ["direction" => "F", "position" => "R"];
          $elts[] = $PT1[$element]["L"] + ["direction" => "F", "position" => "L"];
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

  if (call_user_func($acceptionCriteria, $element, $direction)) {
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
          $substate = trackRecTraverse($acceptionCriteria, $terminalCriteria, $payLoad, $next["name"], $element, $direction, $sharedVar, $stateMerger);
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

function acceptAll($element, $direction) { return True;}

function addToListPayload($element, &$sharedVar, $direction, $next) {
  #Push $element into list
  $sharedVar[] = $element;
  return TRACK_TRAVERSALE_ACCEPT_DO_NOTHING; #Only the terminal element must be added
}

function lockingPayload($element, &$sharedVar, $direction, $next) {
  #Push $element into list
  $pos = "";
  if (array_key_exists("position", $next)) {
    $pos = $next["position"];
  }
  print "Locking $element in position $pos\n";
  return TRACK_TRAVERSALE_ACCEPT_ACTIVATE_PAYLOAD; #Lock all elments in route
}

function findSignalsFrom($element, $direction) {
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
    print "$sig is next signal in direction $direction of $element\n";
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
  $direction = getSignalDirection($s1);
  if ($direction != getSignalDirection($s2)) {
    print "$s1 and $s2 are not in the same direction. Route does not exist\n";
    return;
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
        case "SU":
        case "BSE":
          return TRACK_TRAVERSALE_ACCEPT_DO_NOTHING; # wrong end. No compound route support
        default:
          return TRACK_TRAVERSALE_REJECT; #Not end of route
      }
    } else {
      switch ($PT1[$elt]["element"]) {
        case "SD":
        case "BSB":
          return TRACK_TRAVERSALE_ACCEPT_DO_NOTHING; # wrong end. No compound route support
        default:
          return TRACK_TRAVERSALE_REJECT; #Not end of route
      }
    }
  };
  $dummy = array();
  trackRecTraverse("acceptAll", $isTerminal, "lockingPayload", $s1,"", $direction, $dummy);
}
// End of addition of new helpers
function initRBCIL() {
global $trainData, $trainIndex, $DATA_FILE, $SHallowed, $FSallowed, $ATOallowed;
  require($DATA_FILE);
  foreach ($trainData as $index => &$train) {
    $train["SRallowed"] = 0;
    $train["SHallowed"] = $SHallowed;
    $train["FSallowed"] = $FSallowed;
    $train["ATOallowed"] = $ATOallowed;
    $train["reqMode"] = M_UDEF;
    $train["authMode"] = M_N;
    $train["balise"] = "00:00:00:00:00";
    $train["baliseName"] = "(00:00:00:00:00)";
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
    $train["validTimer"] = 0;
    $train["MAbalise"] = array(0,0,0,0,0);
    $train["MAdist"] = 0;
    $trainIndex[$train["ID"]] = $index;
  }
}

function positionReport($data) { // analyse received position report
global $trainIndex, $trainData, $balisesID, $SR_MAX_SPEED, $SH_MAX_SPEED, $ATO_MAX_SPEED, $now;
  if (isset($trainIndex[$data[4]])) {
    $index = $trainIndex[$data[4]];
    $train = &$trainData[$index];
    if ($data[3] == 1) { // valid report
      $train["dataValid"] = "OK";
      $train["validTimer"] = $now + TRAIN_DATA_TIMEOUT;
      $train["distance"] = round(toSigned($data[10], $data[11]) * $train["wheelFactor"]);
      $train["speed"] = $data[12];
      $train["reqMode"] = $data[13] & 0x07;
      $train["nomDir"] = ($data[13] & 0x18) >> 3;
      $train["pwr"] = ($data[13] & 0x60) >> 5;
      $train["MAreceived"] = ($data[13] & 0x80) >> 7;
      $train["balise"] = sprintf("%02X:%02X:%02X:%02X:%02X",$data[5],$data[6],$data[7],$data[8],$data[9]);
      if ($train["pwr"] == P_R) { // determin orientation of train front end
        $train["front"] = D_UP;
      } elseif ($train["pwr"] == P_L) {
        $train["front"] = D_DOWN;
      }
      // ----- track occupation
      if (isset($balisesID[$train["balise"]])) {
        $train["baliseName"] = $balisesID[$train["balise"]];
        if ($train["prevBaliseName"] != "00:00:00:00:00") {
          trackOccupation(T_CLEAR, $train["prevBaliseName"], $train["prevDistance"],
            ($train["front"] == D_UP ? $train["lengthFront"] : $train["lengthBehind"]),
            ($train["front"] == D_UP ? $train["lengthBehind"] : $train["lengthFront"]),
            $train["ID"]); //>>JP:TRAIN_ID
        }
        trackOccupation($train["nomDir"], $train["baliseName"], $train["distance"],
          ($train["front"] == D_UP ? $train["lengthFront"] : $train["lengthBehind"]),
          ($train["front"] == D_UP ? $train["lengthBehind"] : $train["lengthFront"]),
           $train["ID"]); //>>JP:TRAIN_ID
          $train["prevBaliseName"] = $train["baliseName"];
          $train["prevDistance"] = $train["distance"];
      } else {
        $train["baliseName"] = "(".$train["balise"].")";
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
    } else { // invalid position report
      $train["balise"] = "--:--:--:--:--";
      $train["reqMode"] = M_UDEF;
      $train["nomDir"] = D_UDEF;
      $train["pwr"] = P_UDEF;
      $train["speed"] = "--";
      $train["dist"] = "--";
    }
    updateTrainDataHMI($index);
//  print_r($trainData);
  } // else unknown trainID in posRep
}

function trackOccupation($trackState, $baliseName, $distance, $lengthUp, $lengthDown, $trainID) {#>>JP:TRAIN_ID
global $PT1;
  if (isset($PT1[$baliseName])) {
    $element = &$PT1[$baliseName];
//>>JP:TRAIN_ID
    #first remove the train from the occupying train list. If we needed to clear the track, the it is done, else it will be re-added below (and we avoid duplication)
    if ($key = array_search($trainID, $element["trainIDs"])) {
      unset($element["trainIDs"][$key]);
    }
//<<JP:TRAIN_ID
    $oUp = $distance + $lengthUp;
    $oDown = $distance - $lengthDown;
//print "BaliseName: $baliseName Up: $oUp Down: $oDown \n";
    if ($oDown >= 0) { // train is fully in direction UP
      if ($oDown < $element["U"]["dist"]) {
        $element["trackState"] = $trackState;
//>>JP:TRAIN_ID
      if ($trackState !== T_CLEAR) {
        $element["trainIDs"][] = $trainID;
      }
//<<JP:TRAIN_ID
      }
      traverseTrackUp($trackState, $element["U"]["name"], $baliseName, $oUp - $element["U"]["dist"], $oDown - $element["U"]["dist"], $trainID); //JP:TRAIN_ID
    } elseif ($oUp <= 0) { // train is fully in direction down
      if (-$oUp < $element["D"]["dist"]) {
        $element["trackState"] = $trackState;
//>>JP:TRAIN_ID
      if ($trackState !== T_CLEAR) {
        $element["trainIDs"][] = $trainID;
      }
//<<JP:TRAIN_ID
      }
      traverseTrackDown($trackState, $element["D"]["name"], $baliseName, $oUp + $element["D"]["dist"], $oDown + $element["D"]["dist"], $trainID); //>>JP:TRAIN_ID
    } else { // train is occuping the balise
      $element["trackState"] = $trackState;
//>>JP:TRAIN_ID
      if ($trackState !== T_CLEAR) {
        $element["trainIDs"][] = $trainID;
      }
//<<JP:TRAIN_ID
      traverseTrackUp($trackState, $element["U"]["name"], $baliseName, $oUp - $element["U"]["dist"], $oDown - $element["U"]["dist"], $trainID); //>>JP:TRAIN_ID
      traverseTrackDown($trackState, $element["D"]["name"], $baliseName, $oUp + $element["D"]["dist"], $oDown + $element["D"]["dist"], $trainID); //>>JP:TRAIN_ID
    }
  } else {
    print "Unknown location of train \n";
  }
}

function traverseTrackUp($trackState, $name, $prevName, $oUp, $oDown, $trainID) { //>>JP:TRAIN_ID
global $PT1;
  if ($oUp > 0) {
//print "traverseUp $name Up: $oUp Down $oDown\n";
    $element = &$PT1[$name];
//>>JP:TRAIN_ID
    #first remove the train from the occupying train list. If we needed to clear the track, the it is done, else it will be re-added below (and we avoid duplication)
    if ($key = array_search($trainID, $element["trainIDs"])) {
      unset($element["trainIDs"][$key]);
    }
//<<JP:TRAIN_ID
    switch ($element["element"]) { // element type
      case "BL":
      case "SU":
      case "SD":
      case "TK":
      case "TG":
      case "LX":
        if ($oDown <= $element["D"]["dist"] + $element["U"]["dist"]) {
          $element["trackState"] = $trackState;
//>>JP:TRAIN_ID
          #Add train to occupying train list if state != 
          if ($trackState !== T_CLEAR) {
            $element["trainIDs"][] = $trainID;
          }
//<<JP:TRAIN_ID
        }
        if ($oUp > $element["D"]["dist"] + $element["U"]["dist"]) {
          traverseTrackUp($trackState, $element["U"]["name"], $name, $oUp - ($element["D"]["dist"] + $element["U"]["dist"]),
            $oDown - ($element["D"]["dist"] + $element["U"]["dist"]), $trainID); //>>JP:TRAIN_ID
        }
      break;
      case "PF":
        $branch = $element["state"] == E_LEFT ? "L" : "R"; // Physical state to be observed FIXME
        if ($oDown <= $element["T"]["dist"] + $element[$branch]["dist"]) {
          $element["trackState"] = $trackState;
//>>JP:TRAIN_ID
          #Add train to occupying train list if state != 
          if ($trackState !== T_CLEAR) {
            $element["trainIDs"][] = $trainID;
          }
//<<JP:TRAIN_ID
        }
        if ($oUp > $element[$branch]["dist"] + $element["T"]["dist"]) {
          traverseTrackUp($trackState, $element[$branch]["name"], $name, $oUp - ($element[$branch]["dist"] + $element["T"]["dist"]),
            $oDown - ($element[$branch]["dist"] + $element["T"]["dist"]), $trainID); //>>JP:TRAIN_ID
        }
      break;
      case "PT":
        $branch = $element["L"]["name"] == $prevName ? "L" : "R";
        if ($oDown <= $element[$branch]["dist"] + $element["T"]["dist"]) {
          $element["trackState"] = $trackState;
//>>JP:TRAIN_ID
          if ($trackState !== T_CLEAR) {
            $element["trainIDs"][] = $trainID;
          }
//<<JP:TRAIN_ID
        }
      if ($oUp > $element[$branch]["dist"] + $element["T"]["dist"]) {
        traverseTrackUp($trackState, $element["T"]["name"], $name, $oUp - ($element[$branch]["dist"] + $element["T"]["dist"]),
          $oDown - ($element[$branch]["dist"] + $element["T"]["dist"]), $trainID);//>>JP:TRAIN_ID
      }
      break;
      case "BSE":
        if ($oDown <= $element["D"]["dist"]) {
          $element["trackState"] = $trackState;
//>>JP:TRAIN_ID
          if ($trackState !== T_CLEAR) {
            $element["trainIDs"][] = $trainID;
          }
//<<JP:TRAIN_ID
        } // else warning position out of range
      break;
      default:
        print "Warning, occupation traverseUp: unknown element type at $name\n";
      break;
    }
  }
}

function traverseTrackDown($trackState, $name, $prevName, $oUp, $oDown, $trainID) {
global $PT1;
  if ($oDown < 0) {
//print "traverseDown $name Up: $oUp Down $oDown\n";
  $element = &$PT1[$name];
//>>JP:TRAIN_ID
  #first remove the train from the occupying train list. If we needed to clear the track, the it is done, else it will be re-added below (and we avoid duplication)
  if ($key = array_search($trainID, $element["trainIDs"])) {
    unset($element["trainIDs"][$key]);
  }
//<<JP:TRAIN_ID
  switch ($element["element"]) { // element type
    case "BL":
    case "SU":
    case "SD":
    case "TK":
    case "TG":
    case "LX":
      if (-$oUp <= $element["D"]["dist"] + $element["U"]["dist"]) {
        $element["trackState"] = $trackState;
//>>JP:TRAIN_ID
        if ($trackState !== T_CLEAR) {
          $element["trainIDs"][] = $trainID;
        }
//<<JP:TRAIN_ID
      }
      if (-$oDown > $element["D"]["dist"] + $element["U"]["dist"]) {
        traverseTrackDown($trackState, $element["D"]["name"], $name, $oUp + ($element["D"]["dist"] + $element["U"]["dist"]),
          $oDown + ($element["D"]["dist"] + $element["U"]["dist"]), $trainID);//>>JP:TRAIN_ID
      }
    break;
    case "PT": // i.e. facing in dir down
      $branch = $element["state"] == E_LEFT ? "L" : "R"; // Physical state to be included FIXME
      if (-$oUp <= $element["T"]["dist"] + $element[$branch]["dist"]) {
        $element["trackState"] = $trackState;
//>>JP:TRAIN_ID
        if ($trackState !== T_CLEAR) {
          $element["trainIDs"][] = $trainID;
        }
//<<JP:TRAIN_ID
      }
      if (-$oDown > $element[$branch]["dist"] + $element["T"]["dist"]) {
        traverseTrackDown($trackState, $element[$branch]["name"], $name, $oUp + ($element[$branch]["dist"] + $element["T"]["dist"]),
          $oDown + ($element[$branch]["dist"] + $element["T"]["dist"]), $trainID);//>>JP:TRAIN_ID
      }
    break;
    case "PF": // i.e. trailing in dir down
      $branch = $element["L"]["name"] == $prevName ? "L" : "R";
      if (-$oUp <= $element[$branch]["dist"] + $element["T"]["dist"]) {
        $element["trackState"] = $trackState;
//>>JP:TRAIN_ID
        if ($trackState !== T_CLEAR) {
          $element["trainIDs"][] = $trainID;
        }
//<<JP:TRAIN_ID
      }
      if (-$oDown > $element[$branch]["dist"] + $element["T"]["dist"]) {
        traverseTrackDown($trackState, $element["T"]["name"], $name, $oUp + ($element[$branch]["dist"] + $element["T"]["dist"]),
          $oDown + ($element[$branch]["dist"] + $element["T"]["dist"]), $trainID);//>>JP:TRAIN_ID
      }
    break;
    case "BSB":
      if (-$oUp <= $element["U"]["dist"]) {
        $element["trackState"] = $trackState;
//>>JP:TRAIN_ID
        if ($trackState !== T_CLEAR) {
          $element["trainIDs"][] = $trainID;
        }
//<<JP:TRAIN_ID
      } // else warning position out of range
    break;
    default:
      print "Warning, occupation traverseDown: unknown element type at $name\n";
    break;
  }
  }
}

function processCommand($command, $from) { // process HMI command
global $PT1, $clients, $clientsData, $inCharge, $trainData, $EC, $now, $balises;
print ">$command< \n";
  $param = explode(" ",$command);
  switch ($param[0]) {
  case "so": // signal order
    if ($from == $inCharge) {
      $element = &$PT1[$param[1]];
      $state = ($element["state"] == E_CLOSED ? E_OPEN : E_CLOSED); // toggle state
      $element["state"] = $state;
      if ($element["type"] == "LS1") {
        orderEC($element["EC"]["addr"], $element["EC"]["index"],$state == E_OPEN ? O_PROCEED : O_STOP);
      }
      HMIindication($from, "displayResponse {OK}\n");
    } else {
      HMIindication($from, "displayResponse {Rejected}\n");
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
  case "pt": // point order
    if ($from == $inCharge) {
      $element = &$PT1[$param[1]];
      $state = ($element["state"] == E_LEFT ? E_RIGHT : E_LEFT); // toggle state
      $element["state"] = $state;
      if ($element["EC"]["type"] != 0 ) {
        orderEC($element["EC"]["addr"], $element["EC"]["index"],$state == E_RIGHT ? O_RIGHT : O_LEFT);
      } else {
        $element["status"] = $state == E_LEFT ? S_U_LEFT : S_U_RIGHT;
      }
      HMIindication($from, "displayResponse {OK}\n");
//      HMIindicationAll("pointState ".$param[1]." $state ".$element["trackState"]."\n");
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
  case "MA":
    if ($from == $inCharge) {
      if (isset($param[2]) and isset($PT1[$param[2]]["ID"])) {
        $trainData[$param[1]]["MAbalise"] = explode(":", $PT1[$param[2]]["ID"]);
        $trainData[$param[1]]["MAdist"] = isset($param[3]) ? round($param[3] / $trainData[$param[1]]["wheelFactor"]) : 0;
        HMIindication($from, "displayResponse {OK}\n");
      } else { // unknown balise name
        HMIindication($from, "displayResponse {Unknown balise}\n");
      }
    } else {
      HMIindication($from, "displayResponse {Rejected}\n");
    }
  break;
  case "tr": // Try to lock the route
    if ($from == $inCharge) {
      print "setting route  $param[1] $param[2]\n";
      lockRoute($param[1], $param[2]);
    }
  break;
  case "test":
//$PT1["S9"]["trackState"] = T_OCCUPIED; 
//>>JP:TRAIN_ID
$PT1["04"]["trackState"] = T_OCCUPIED_STOP;
$PT1["04"]["trainIDs"] = ["CIRCUS", "CARGO"];
//<<JP:TRAIN_ID
//$PT1["S8"]["trackState"] = T_OCCUPIED; 
//$PT1["BG022"]["trackState"] = T_OCCUPIED; 
//$PT1["P3"]["trackState"] = T_OCCUPIED; 
//$PT1["BG026"]["trackState"] = T_OCCUPIED; 
//$PT1["BG024"]["trackState"] = T_OCCUPIED; 
//$PT1["LX1"]["trackState"] = T_OCCUPIED; 
//print_r($PT1);
//print_r($trainData);
//print_r($EC);
//        HMIindicationAll("pointState P1 ".$PT1["P1"]["state"]." 2\n");
  break;
  default :
    errLog("Unknown command from client: >$command<");
    print "Unknown command from client: >$command<\n";
  break;
  }
}

function RBC() {

}

function IL() {

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

//------------------------------------------------------------------------------------  HMI
function initHMIserver() {
global $HMIport, $HMIaddress, $listener;

  $listener = @stream_socket_server("tcp://$HMIaddress:".$HMIport, $errno, $errstr);
  if (!$listener) {
    fwrite(STDERR,"Cannot create server socket: $errstr ($errno)\n");
    die();
  }
  stream_set_blocking($listener,false);
}

function HMIserver() {
global $ABUS, $listener, $clients, $clientsData, $inCharge;
  $read = $clients;
  $read[] = $listener;
  if ($ABUS == "genie") {
    global $fromGenie;
    $read[] = $fromGenie;
  }
  $except = NULL;
  $write = NULL;
  if (stream_select($read, $write, $except, 0, 500000 )) {
    foreach ($read as $r) {
      if ($r == $listener) {
        if ($newClient = stream_socket_accept($listener,0,$clientName)) {
          msgLog("Client $clientName signed in");
          $clients[] = $newClient;
          $clientsData[(int)$newClient] = [
            "addr" => $clientName,
            "signIn" => date("Ymd H:i:s"),
            "inChargeSince" => ""];
          HMIstartup($newClient);
        } else {
          fatalError("HMI: accept failed");
        }
      } elseif ($ABUS == "genie" and $r == $fromGenie) {
        if ($data = fgets($r)) {
          AbusReceivedPacketGenie($data);
        }       
      } else { // exsisting client
        if ($data = fgets($r)) {
//          print "ClientData: $data";
          processCommand(trim($data),$r);
        } else { // Connection closed by client
          msgLog("Client ".stream_socket_get_name($r,true)." signed out");
          fclose($r);
          unset($clientsData[(int)$r]);
          unset($clients[array_search($r, $clients, TRUE)]);
          if ($r == $inCharge) {
            $inCharge = false;
          }
        }
      }
    }
  }
}

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
      HMIindication($client,"pointState $name ".$element["state"]." ".$element["trackState"]."\n"); // Physical state to be used FIXME
    break;
    case "SU":
      HMIindication($client,"signal $name ".$element["HMI"]["x"]." ".$element["HMI"]["y"]." f\n");
      HMIindication($client,"signalState $name ".$element["status"]." ".$element["trackState"]."\n");
    break;
    case "SD":
      HMIindication($client,"signal $name ".$element["HMI"]["x"]." ".$element["HMI"]["y"]." r\n");
      HMIindication($client,"signalState $name ".$element["status"]." ".$element["trackState"]."\n");
    break;
    case "BSB":
      HMIindication($client,"bufferStop $name ".$element["HMI"]["x"]." ".$element["HMI"]["y"]." b ".$element["HMI"]["l"]."\n");
      HMIindication($client,"bufferStopState $name ".$element["trackState"]."\n");
    break;
    case "BSE":
      HMIindication($client,"bufferStop $name ".$element["HMI"]["x"]." ".$element["HMI"]["y"]." e ".$element["HMI"]["l"]."\n");
      HMIindication($client,"bufferStopState $name ".$element["trackState"]."\n");
    break;
    case "LX":
      HMIindication($client,"levelcrossing $name ".$element["HMI"]["x"]." ".$element["HMI"]["y"]."\n");
      HMIindication($client,"levelcrossingState $name ".$element["status"]." ".$element["trackState"]."\n");
    break;
    }
  }
  HMIindication($client,".f.canvas raise button [.f.canvas create text 0 0]\n"); // Ensure that all element buttons are on the top layer
// HMI data
  foreach ($HMI["baliseTrack"] as $trackName => $baliseTrack) {
    HMIindication($client,"track $trackName ".$baliseTrack["x"]." ".$baliseTrack["y"]." ".$baliseTrack["l"]."\n");
    HMIindication($client,"trState $trackName ".$baliseTrack["trackState"]." ".$baliseTrack["trainID"]."\n");  //>>JP:TRAIN_ID
  }
// train data
  foreach ($trainData as $index => &$train) { // train data
    HMIindication($client, "trainFrame ".$index."\n");
    HMIindication($client, "trainDataS ".$index." ".$train["name"]." ".$train["lengthFront"]."+".$train["lengthBehind"]."\n");
    HMIindication($client, "SRmode ".$index." ".$train["SRallowed"]."\n");
    HMIindication($client, "SHmode ".$index." ".$train["SHallowed"]."\n");
    HMIindication($client, "FSmode ".$index." ".$train["FSallowed"]."\n");
    HMIindication($client, "ATOmode ".$index." ".$train["ATOallowed"]."\n");
    updateTrainDataHMI($index);
  }
}

function updateHMI() {
global $HMI, $PT1;
  foreach ($HMI["baliseTrack"] as $name => &$baliseTrack) {
    $baliseTrack["trackState"] = T_CLEAR;
    $baliseTrack["trainID"] = ""; //>>JP:TRAIN_ID
    foreach ($baliseTrack["balises"] as $baliseName ) {
      if ($PT1[$baliseName]["trackState"] != T_CLEAR) {
        $baliseTrack["trackState"] = $PT1[$baliseName]["trackState"];
//>>JP:TRAIN_ID
        foreach ($PT1[$baliseName]["trainIDs"] as $trainID ) {
          #Giving the name to the HMI and "??" in case of multiple occupation
          if (($baliseTrack["trainID"] != "")  and ($baliseTrack["trainID"] != $trainID)) {
            $baliseTrack["trainID"] = "??";
          } elseif ($baliseTrack["trainID"] != "??") {
            $baliseTrack["trainID"] = $trainID;
          }
        }
//<<JP:TRAIN_ID
        break;
      }
    }
    HMIindicationAll("trState $name ".$baliseTrack["trackState"]." ".$baliseTrack["trainID"]."\n"); //>>JP:TRAIN_ID
  }
  unset($name);
  foreach ($PT1 as $name => $element) {
//>>JP:TRAIN_ID
    $displayedTrainID = "";
    foreach ($element["trainIDs"] as $trainID ) {
      #Giving the name to the HMI and "??" in case of multiple occupation
      if (($displayedTrainID != "")  and ($displayedTrainID != $trainID)) {
        $displayedTrainID = "??";
      } elseif ($displayedTrainID != "??") {
        $displayedTrainID = $trainID;
      }
    }
//<<JP:TRAIN_ID
    switch ($element["element"]) {
      case "SU":
      case "SD":
        HMIindicationAll("signalState $name ".$element["status"]." ".$element["trackState"]." ".$displayedTrainID."\n");
      break;
      case "PF":
      case "PT":
        HMIindicationAll("pointState $name ".$element["status"]." ".$element["trackState"]." ".$displayedTrainID."\n");
      break;
      case "BSB":
      case "BSE":
        HMIindicationAll("bufferStopState $name  ".$element["trackState"]." ".$displayedTrainID."\n");
      break;
      case "LX":
        HMIindicationAll("levelcrossingState $name ".$element["barrierStatus"]." ".$element["trackState"]." ".$displayedTrainID."\n"); // Not only barrier status FIXME
      break;
    }
  }
}

function updateTrainDataHMI($index) {
global $trainData, $MODE_TXT, $DIR_TXT, $PWR_TXT, $ACK_TXT;
  $train = $trainData[$index];
  HMIindicationAll("trainDataD ".$index." {".$MODE_TXT[$train["authMode"]]." (".$MODE_TXT[$train["reqMode"]].")} ".$train["baliseName"]." ".
        $train["distance"]." ".$train["speed"]." ".$DIR_TXT[$train["nomDir"]]." {".$PWR_TXT[$train["pwr"]]."} {".
        $ACK_TXT[$train["MAreceived"]]."} ".$train["dataValid"]."\n");
}
        
function HMIindicationAll($msg) {// Send indication to all clients
global $clients;
  foreach ($clients as $w) {
    fwrite($w,$msg);
  }
}

function HMIindication($to, $msg) {// Send indication to specific client
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

function AbusReceivedPacketGenie($line) {
  print "Abus: >$line<\n";
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
global $debug, $background, $RBCIL_CONFIG, $DATA_FILE, $SYSTEM_NAME, $VERSION, $argv, $ABUS, $SHallowed, $FSallowed, $ATOallowed;
  if (in_array("-h",$argv)) {
    fwrite(STDERR,"Usage:
-b, --background  start as daemon
-c                select CBUMaster as Abus gateway
-f <conf_file>    configuration of $SYSTEM_NAME
-g                select GenieMaster as Abus gateway 
-n                do not connect to Abus gateway
-D <Data_file>    PT1 and Train data
-d                enable debug info
-sh               enable Shunt Mode for all trains
-fs               enable Full Supervision Mode for all trains
-ato              enable ATO Mode for all trains
");
    exit();
  }
  next($argv);
  while (list(,$opt) = each($argv)) {
    switch ($opt) {
    case "-sh":
      $SHallowed = 1;
    break;
    case "-fs":
      $FSallowed = 1;
    break;
    case "-ato":
      $ATOallowed = 1;
    break;
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
      fwrite(STDERR,"No Abus gateway selected\n");
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
    case "-d":
    case "--debug";
      $debug = TRUE;
      fwrite(STDERR,"Debugging mode\n");
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

function msgLog($txt) {
global $logFh;
  fwrite($logFh,date("Ymd H:i:s")." $txt\n");
}

function errLog($txt) {
global $errFh;
  fwrite($errFh,date("Ymd H:i:s")." $txt\n");
}

function fatalError($txt) {
  fwrite($logFh,date("Ymd H:i:s")." Fatal Error: $txt\n");
  fwrite($errFh,date("Ymd H:i:s")." Fatal Error: $txt\n");
  exit(1);
}

?>
