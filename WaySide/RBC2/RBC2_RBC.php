<?php
// WinterTrain, RBC2
// RBC core functionalty


// ------------------------------------------------------------------------------------------- Class definitions for track model

abstract class genericElement { // ---------------------------------------------------------------------------- Generic Element
  public $elementName;
  public $neighbourUp;
  public $neighbourDown;
  public $elementType = "GEN";
  public $facingUp = true;
  
  public $vacancyState = V_CLEAR;
  public $routeLockingState = R_IDLE;
  public $routeLockingType = RT_IDLE;
  public $routeLockingUp = true; // Only applicable when routeLockingState != R_IDLE
  public $occupationTrainID = "";
  public $lockingState = L_NOT_LOCKED; // in use?? FIXME
  public $blockingState = B_NOT_BLOCKED;

  public function cmdSetRouteTo($endPoint) {
    return false;
  }
  
  public function cmdReleaseRoute() {
    return false;
  }
    
  public function cmdToggleElement() {
    return false;
  }
    
  public function cmdToggleBlocking() {
    return false;
  }

  public function cmdToggleARS() {
    return false;
  }
  
  protected function setRouteTo($directionUp, $endPoint, $caller) {
    if ($this->routeLockingState != R_IDLE) return false;
    if (!($directionUp ? 
      $this->neighbourUp->setRouteTo(true, $endPoint, $this) : $this->neighbourDown->setRouteTo(false, $endPoint, $this))) return false;
    $this->routeLockingState = R_LOCKED;
    $this->routeLockingType = RT_VIA;
    $this->routeLockingUp = $directionUp;
    return true;
  }
  
  protected function releaseRoute() {
    if ($this->routeLockingState == R_IDLE) return true;
    if ($this->routeLockingUp ? $this->neighbourDown->releaseRoute() : $this->neighbourUp->releaseRoute()) {
      $this->routeLockingState = R_IDLE;
      $this->routeLockingType = RT_IDLE;
      return true;
    }
    return false;
  }
  
  public function __construct($consName) {
    $this->elementName = $consName;
  }
}

class Pelement extends genericElement { // -------------------------------------------------------------------------------------- Point
  public $neighbourTip;
  public $neighbourRight;
  public $neighbourLeft;
  
  public $supervisionMode;              // Read from PT2
  public $pointState = P_UNSUPERVISED;  // Functional state of point being it from static configuration or physical state
  public $logicalLieRight = true;       // Logical lie is right
  
  private $throwLockedByConfiguration = false;   // Point throw is locked by configuration
  private $throwLockedByRoute = false;           // Point throw is locked due to position in locked route
  private $throwLockedByCmd = false;             // Point throw is locked by command
  
  public function cmdToggleElement() { // Throw point to opposite lie
    return $this->throwPoint(!$this->logicalLieRight);
  }
  
  public function cmdToggleBlocking() { // Block point throw
    if ($this->throwLockedByConfiguration) return false;
    $this->throwLockedByCmd = !$this->throwLockedByCmd;
    if ($this->throwLockedByCmd) {
      $this->blockingState = $this->logicalLieRight ? B_BLOCKED_RIGHT : B_BLOCKED_LEFT;
    } else {
      $this->blockingState = B_NOT_BLOCKED;
    }
    return true;
  }

  protected function throwPoint($throwRight) { // Throw point to specific lie (param true: right, false: left)
    if ($this->throwLockedByConfiguration or $this->throwLockedByRoute or $this->throwLockedByCmd) return false;
    if ($throwRight == $this->logicalLieRight) return true; // Point is already in requested lie
      $this->logicalLieRight = $throwRight;
      switch ($this->supervisionMode) {
        case "U":  // Point lie always P_UNSUPERVISED;
          return true;
        break;
        case "S": // Point lie simulated
          $this->pointState = $this->logicalLieRight ? P_SUPERVISED_RIGHT : P_SUPERVISED_LEFT;
          if ($this->routeLockingState != R_IDLE) { // Point is locked in route, check if throw is to be locked
            if ($this->logicalLieRight == ($this->routeLockingType == RT_RIGHT)) $this->throwLockedByRoute = true;
          };
          return true;
        break;
        case "P":
          // $this->logicalLieRight ? send EC cmd throw rithg : send EC cmd throw left 
          // when status received if locked in route check if throw command is to be locked FIXME
          return false; // As not implemented
        break;
        case "F":
          // $this->logicalLieRight ? send EC cmd throw rithg : send EC cmd throw left 
          // when status received if locked in route check if throw command is to be locked FIXME
          return false; // As not implemented
        break;
    }
  }
  
  protected function setRouteTo($directionUp, $endPoint, $caller) { // Set route further - called by neighbour
  global $automaticPointThrowEnabled;
    if ($this->routeLockingState != R_IDLE) return false;
    $this->routeLockingUp = $directionUp;
    if ($directionUp == $this->facingUp) { // Point is facing in route direction
      if ($this->logicalLieRight) { // Follow logical lie right
        if ($this->neighbourRight->setRouteTo($directionUp, $endPoint, $this)) {
          $this->routeLockingState = R_LOCKED;
          $this->routeLockingType = RT_RIGHT;
          $this->throwLockedByRoute = true;
          return true;
        } else {
          if ($this->throwLockedByCmd) return false; // Point throw is locked by cmd so no reason to try
          if ($this->neighbourLeft->setRouteTo($directionUp, $endPoint, $this)) {
            $this->routeLockingState = R_LOCKED;
            $this->routeLockingType = RT_LEFT;
            if ($automaticPointThrowEnabled) $this->throwPoint(false); // Throw left
            return true;
          }
          return false;
        }
      } else {// Follow logical lie left
        if ($this->neighbourLeft->setRouteTo($directionUp, $endPoint, $this)) {
          $this->routeLockingState = R_LOCKED;
          $this->routeLockingType = RT_LEFT;
          $this->throwLockedByRoute = true;
          return true;
        } else {
          if ($this->throwLockedByCmd) return false; // Point throw is locked by cmd so no reason to try
          if ($this->neighbourRight->setRouteTo($directionUp, $endPoint, $this)) {
            $this->routeLockingState = R_LOCKED;
            $this->routeLockingType = RT_RIGHT;
            if ($automaticPointThrowEnabled) $this->throwPoint(true); // Throw right
            return true;
          }
          return false; 
        }
      }
    } else { // Point is trailing in route direction
      if ($caller === $this->neighbourRight) { // route search reached point via right branch
        if (!$this->logicalLieRight and $this->throwLockedByCmd) return false; // Point throw is locked by cmd so no reason to try
        if ($this->neighbourTip->setRouteTo($directionUp, $endPoint, $this)) {
          $this->routeLockingState = R_LOCKED;
          $this->routeLockingType = RT_RIGHT;
          if ($this->logicalLieRight) {
            $this->throwLockedByRoute = true;
          } else {
            if ($automaticPointThrowEnabled) $this->throwPoint(true); // Throw right
          }
          return true;
        }
        return false;
      } else { // route search via left branch
        if ($this->logicalLieRight and $this->throwLockedByCmd) return false; // Point throw is locked by cmd so no reason to try
        if ($this->neighbourTip->setRouteTo($directionUp, $endPoint, $this)) {
          $this->routeLockingState = R_LOCKED;
          $this->routeLockingType = RT_LEFT;
          if (!$this->logicalLieRight) {
            $this->throwLockedByRoute = true;
          } else {
            if ($automaticPointThrowEnabled) $this->throwPoint(false); // Throw left
          }
          return true;
        }
        return false;
      }
    }    
  }
  
  protected function releaseRoute() {
    if ($this->routeLockingState == R_IDLE) return true;
    if ($this->routeLockingUp == $this->facingUp) { // Locked facing in route
      if ($this->neighbourTip->releaseRoute()) {
        $this->routeLockingState = R_IDLE;
        $this->routeLockingType = RT_IDLE;
        $this->throwLockedByRoute = false;
        return true;
      }
      return false;
    } else { // Locked trailing in route
      if ($this->routeLockingType == RT_RIGHT ? $this->neighbourRight->releaseRoute() : $this->neighbourLeft->releaseRoute()) {
        $this->routeLockingState = R_IDLE;
        $this->routeLockingType = RT_IDLE;
        $this->throwLockedByRoute = false;
        return true;
      }
      return false;
    }
  }

  public function __construct($consName) {
  global $PT2;
    $this->elementName = $consName;
    $this->elementType = $PT2[$consName]["element"];
    $this->facingUp = ($this->elementType == "PF");
    $this->supervisionMode = $PT2[$consName]["supervisionState"];
    switch ($this->supervisionMode) {
      case "S": // Simulated
        $this->pointState = P_SUPERVISED_RIGHT;
        $this->logicalLieRight = true;
      break;
      case "CR":
        $this->throwLockedByConfiguration = true;
        $this->pointState = P_SUPERVISED_RIGHT;
        $this->logicalLieRight = true;
        $this->blockingState = B_CLAMPED_RIGHT;
      break;
      case "CL":
        $this->throwLockedByConfiguration = true;
        $this->pointState = P_SUPERVISED_LEFT;
        $this->logicalLieRight = false;
        $this->blockingState = B_CLAMPED_LEFT;
      break;
    }
  }
}

class Selement extends genericElement { // ------------------------------------------------------------------------- Signal
  public $signalState = S_STOP; // Functional state of signal
  public $arsState = ARS_ENABLED;
  
  public function cmdToggleBlocking() { // Block locking signal in route as SP or VIA
    if ($this->blockingState == B_NOT_BLOCKED) {
      $this->blockingState = B_BLOCKED_START_VIA;
    } else {
      $this->blockingState = B_NOT_BLOCKED;
    }    
    return true;
  }

  public function cmdToggleARS() {
    if ($this->arsState == ARS_ENABLED) {
      $this->arsState = ARS_DISABLED;
    } else {
      $this->arsState = ARS_ENABLED;
    }    
    return true;
  }
    
  public function cmdSetRouteTo($endPoint) {
    if ($this->routeLockingState != R_IDLE and $this->routeLockingType != RT_END_POINT) return false;
    if ($this->blockingState == B_BLOCKED_START_VIA) return false;
    if ($this->facingUp ? $this->neighbourUp->setRouteTo(true, $endPoint, $this) : $this->neighbourDown->setRouteTo(false, $endPoint, $this)) {
      if ($this->routeLockingType == RT_END_POINT) { // Already End Point
        $this->routeLockingState = R_LOCKED; // FIXME  state could already be != IDLE
        $this->routeLockingType = RT_VIA;
      } else {
        $this->routeLockingState = R_LOCKED;
        $this->routeLockingType = RT_START_POINT;
      }
      $this->routeLockingUp = $this->facingUp;;
      return true;
    } 
    return false;
  }
  
  public function cmdReleaseRoute() {
    if ($this->routeLockingState == R_IDLE or $this->routeLockingType != RT_END_POINT) return false;
    if ($this->facingUp ? $this->neighbourDown->releaseRoute() : $this->neighbourUp->releaseRoute()) {
      $this->routeLockingState = R_IDLE;
      $this->routeLockingType = RT_IDLE;
      return true;
    }
    return false;
  }

  protected function setRouteTo($directionUp, $endPoint, $caller) {
  global $recCount;
  $recCount +=1;
  if ($recCount > 10) die("Recursion"); // FIXME
    if ($directionUp == $this->facingUp) { // Signal is facing in route direction
      if ($endPoint == $this->elementName) { // End point found
        if ($this->routeLockingState != R_IDLE and $this->routeLockingType != RT_START_POINT) return false;
        if ($this->routeLockingType == RT_START_POINT) { // Already START POINT
          $this->routeLockingState = R_LOCKED; // FIXME Check if state != IDLE
          $this->routeLockingType = RT_VIA;
        } else {
          $this->routeLockingState = R_LOCKED;
          $this->routeLockingType = RT_END_POINT;
        }
        $this->routeLockingUp = $directionUp;
        return true;
      } else {
        if ($this->routeLockingState != R_IDLE or $this->blockingState == B_BLOCKED_START_VIA) return false;
        if ($this->facingUp ? $this->neighbourUp->setRouteTo(true, $endPoint, $this) : $this->neighbourDown->setRouteTo(false, $endPoint, $this)) {
          $this->routeLockingState = R_LOCKED;
          $this->routeLockingType = RT_VIA;
          $this->routeLockingUp = $directionUp;
          return true;
        } 
        return false; 
      }
    } else { // Signal is reverse in route direction
      if ($this->routeLockingState != R_IDLE) return false;
      if ($directionUp ? $this->neighbourUp->setRouteTo(true, $endPoint, $this) : $this->neighbourDown->setRouteTo(false, $endPoint, $this)) {
        $this->routeLockingState = R_LOCKED;
        $this->routeLockingType = RT_VIA_REVERSE;
        $this->routeLockingUp = $directionUp;
        return true;
      } 
      return false;
    }
  }
  
  public function __construct($consName) {
  global $PT2;
    $this->elementName = $consName;
    $this->elementType = $PT2[$consName]["element"];
    $this->blockingState = B_NOT_BLOCKED;
    $this->facingUp = ($this->elementType == "SU");
  }
}

class BSelement extends genericElement { // ----------------------------------------------------------------------- Buffer Stop

  protected function setRouteTo($directionUp, $endPoint, $caller) {
    if ($this->routeLockingState != R_IDLE) return false;
    if ($endPoint == $this->elementName) { // End point found
      $this->routeLockingState = R_LOCKED;
      $this->routeLockingType = RT_END_POINT;
      $this->routeLockingUp = $directionUp;
      return true;
    }
    return false;
  }

  public function cmdReleaseRoute() {
    if ($this->routeLockingState == R_IDLE) return false;
    if ($this->facingUp ? $this->neighbourDown->releaseRoute() : $this->neighbourUp->releaseRoute()) {
      $this->routeLockingState = R_IDLE;
      $this->routeLockingType = RT_IDLE;
      return true;
    }
    return false;
  }
  
  public function __construct($consName) {
  global $PT2;
    $this->elementName = $consName;
    $this->elementType = $PT2[$consName]["element"];
    $this->facingUp = ($this->elementType == "BSE"); // BufferStop End is seen as facing (end point)
  }
}

class BGelement extends genericElement { // ------------------------------------------------------------------- Balise Group
  public function __construct($consName) {
    $this->elementName = $consName;
    $this->elementType = "BG";
  }
}

class TGelement extends genericElement { // ------------------------------------------------------------------- Trigger
  public function __construct($consName) {
    $this->elementName = $consName;
    $this->elementType = "TG";
  }
}

class PHTelement extends genericElement { // ------------------------------------------------------------------- Point Hold Trigger
  public function __construct($consName) {
    $this->elementName = $consName;
    $this->elementType = $PT2[$consName]["element"];
  }
}
// -------- End of object oriented Track Model ----------------------------------------------------------------------------------------------


function generateMA($index) { // -------------------------------------------------- Generate mode and movement authority for specific train
global $trainData, $allowSR, $allowSH, $allowFS, $allowATO, $emergencyStop;
print "Generate MA for train {$trainData[$index]["ID"]}\n";

// FS, ATO
// if no route assigned to train: search for applicable route and assign to train
// if route assigned and MB open:
// max speed for train / wheel factor
// allowed driving direction of route for FS, ATO
// distance to EOA / wheel factor


  $train = &$trainData[$index];

  $train["authMode"] = M_N;
  $train["MAdir"] = MD_NODIR;
  switch ($train["reqMode"]) {
    case M_SR:
      if ($allowSR and $train["SRallowed"]) {
        $train["authMode"] = M_SR;
        $train["maxSpeed"] = $train["SRmaxSpeed"];
        $train["MAdir"] = MD_BOTH;
// if route assigned to train: unassign route
      }
    break;
    case M_SH:
      if ($allowSH and $train["SHallowed"]) {
        $train["authMode"] = M_SH;
        $train["maxSpeed"] = $train["SHmaxSpeed"];
        $train["MAdir"] = MD_BOTH;
// if route assigned to train: unassign route
      }
    break;
    case M_FS:
      if  ($allowFS and $train["FSallowed"]) {
        $train["authMode"] = M_FS;
        $train["maxSpeed"] = $train["FSmaxSpeed"];
        // $train["MAdir"] = ; FIXME
      }
    break;
    case M_ATO:
      if ($allowATO and $train["ATOallowed"]) {
        $train["authMode"] = M_ATO;
        $train["maxSpeed"] = $train["ATOmaxSpeed"];
        // $train["MAdir"] = ; FIXME
      }
    break;
  }

  sendMA($index, ($emergencyStop ? M_ESTOP : $train["authMode"]), $train["MAdir"], $train["MAbalise"], $train["MAdist"], $train["maxSpeed"]);
}

function sendMA($index, $authMode, $MAdir, $balise, $dist, $speed) { // To be moved in generateMA() ?? FIXME
global  $trainData, $TD_TXT_MODE, $radioInterface;
  print "MA packet: trainID {$trainData[$index]["ID"]}, authMode { $TD_TXT_MODE[$authMode]}, MAdir $MAdir, balise $balise, distance $dist, speed $speed\n";

  if ($trainData[$index]["deployment"] == "R") { // Real train
    switch ($radioInterface) {
      case "USB":
      // assemble packet               including MAdir  FIXME
        sendToRadioLink($packet);
      break;
      case "ABUS":
        fatalError("sendMA via EC/LINK not implemented");
      break;
    }
  } // else no MA send to simulated or ignored trains 
}

function sendPosRestore($trainID, $balise, $distance) {  // FIXME
global $radioLinkAddr, $radioLink, $radio;
print "PosRestore to be implemented\n";

  switch ($radioInterface) { 
    case "USB":
      $data = "33,$trainID,";
      $baliseArray = explode(":", $balise);
      for ($b = 0; $b < 5; $b++) 
        $data .= hexdec($baliseArray[$b]).",";
      $data .=  ($distance & 0xFF).",".(($distance & 0xFF00) >> 8).",0s";
      sendToRadioLink($data);
    break;
    case "ABUS":
      fatalError("Position restore via EC/Link not implemented");
    break;
  }
}

function processPositionReport($trainID, $requestedMode, $MAreceived, $nomDir, // ---------------------Process Point Position Report from OBU
  $pwr, $baliseID, $distance,  $speed, $rtoMode) {
global $TD_TXT_MODE, $TD_TXT_ACK, $TD_TXT_DIR, $TD_TXT_PWR, $TD_TXT_RTOMODE, $trainData, $trainIndex, $now, $PT2, $balisesID,
  $posRestoreEnabled;

  print "PosRep: TrainID: $trainID, reqMode: {$TD_TXT_MODE[$requestedMode]}, MAreceived: {$TD_TXT_ACK[$MAreceived]}, ".
  "nomDir: {$TD_TXT_DIR[$nomDir]}, pwr: {$TD_TXT_PWR[$pwr]}, Balise: $baliseID, Distance: $distance, Speed: $speed, ".
  "rtoMode: {$TD_TXT_RTOMODE[$rtoMode]} \n";
  
  $triggerHMIupdate = true; // posRep will likely result in new states
  if (isset($trainIndex[$trainID])) { // Train is known
    $index = $trainIndex[$trainID];
    $train = &$trainData[$index];
    $train["dataValid"] = "OK";
    $train["comTimeStamp"] = $now;
    $train["reqMode"] = $requestedMode;
    $train["nomDir"] = $nomDir; // Up or Down - Computed by OBU ------------- ??? concept of up/down is not known to OBU FIXME
    $train["pwr"] = $pwr;
    $train["MAreceived"] = $MAreceived;
    if ($train["pwr"] == P_R) { // determin orientation of train front end
      $train["front"] = D_UP;
    } elseif ($train["pwr"] == P_L) {
      $train["front"] = D_DOWN;
    } // else orientation undefined by OBU - what to do? FIXME
    $train["rtoMode"] = $rtoMode;
    $train["speed"] = $speed;
    if ($baliseID == "00:00:00:00:00") { // ----------------------- OBU indicates void position
      $train["baliseName"] = "<void balise>";
      $train["distance"] = 0;
      $train["baliseID"] = $baliseID;
      $train["posTimeStamp"] = $now;
      if ($posRestoreEnabled) {
        if (!$train["posRestored"]) {
          if ($now - $train["posTimeStamp"] <= POSITION_TIMEOUT) {
            errLog("Train ({$train["ID"]}): Position restored to: {$train["baliseID"]} {$train["distance"]} Stamped: ".
                date("Ymd H:i:s", $train["posTimeStamp"]));
            sendPosRestore($train["ID"], $train["baliseID"], (int)($train["distance"] / $train["wheelFactor"]));
            // New posRep from OBU is awaited before position is determined (to verify restore)
            $train["posRestored"] = true; // to prevent continuous restore
          } else {
            errLog("Train ({$train["ID"]}): RBC position ({$train["baliseID"]}) not restored - outdated. Stamped: ".
              date("Ymd H:i:s", $train["posTimeStamp"]));
          }
        } else {
          errLog("Train ({$train["ID"]}): position void, but already restored. Awaiting new real position.");
        }
      } 
    } elseif (isset($balisesID[$baliseID])) { // ------------------ OBU indicates known position
      $train["posTimeStamp"] = $now;
      $train["distance"] = $distance;
      $train["baliseID"] = $baliseID;
      $train["baliseName"] = $balisesID[$baliseID];
      $train["posRestored"] = false;
      // Determine track occupation FIXME
      
      
      
    } else { // --------------------------------------------------- OBU indicates unknown balise
      // Unknown balise, track occupation cannot be updated -  position report ignored
      msgLog("Warning: Unknown baliseID >$baliseID< provided in position report from train $trainID.".
        "Prev. posRep: {$train["baliseID"]} at distance {$train["distance"]}");
    }
    generateMA($index);
  } else {
    errLog("Unknown train ID ($trainID) in posRep");
  }
}

function processCommandRBC($command, $from) { // ------------------------------------------- Process commands from HMI clients
global $inChargeHMI, $clientsData, $trackModel, $recCount, $triggerHMIupdate, 
  $allowSR, $allowSH, $allowFS, $allowATO, $trainData, $arsEnabled;
  
  $triggerHMIupdate = true; // RBC command will likely result in new states, so trigger HMI update
  $param = explode(" ",$command);
  if ($param[0] == "Rq") {// Request operation
    if ($inChargeHMI) {
      HMIindication($from, "displayResponse {Rejected ".$clientsData[(int)$inChargeHMI]["addr"]." is in charge (since ".
        $clientsData[(int)$inChargeHMI]["inChargeSince"].")}");
    } else {
      $inChargeHMI = $from;
      $clientsData[(int)$from]["inChargeSince"] = date("Ymd H:i:s");
      HMIindication($from, "oprAllowed");
    }
    return;
  }
  if ($from == $inChargeHMI) {
    switch ($param[0]) {
      case "eStop": // Toggle emergency STOP
        toggleEmergencyStop();
      break;
      case "arsAll": // Toggle overall ARS state
        $arsEnabled = !$arsEnabled;
      break;
// Elements
      case "pt": // Point Throw
        HMIindication($from, "displayResponse {".($trackModel[$param[1]]->cmdTogglePoint() ? "OK" : "Rejected")."}");
      break;
      case "pb": // Block point throw
        HMIindication($from, "displayResponse {".($trackModel[$param[1]]->cmdToggleBlocking() ? "OK" : "Rejected")."}");
      break;
      case "sb": // Block locking signal as START or VIA
        HMIindication($from, "displayResponse {".($trackModel[$param[1]]->cmdToggleBlocking() ? "OK" : "Rejected")."}");
      break;
      case "ars": // Toggle ARS for signal FIXME
        HMIindication($from, "displayResponse {".($trackModel[$param[1]]->cmdToggleARS() ? "OK" : "Rejected")."}");
      break;
// Routes
      case "tr": // Set route
        $recCount = 0; // FIXME
        if ($trackModel[$param[1]]->cmdSetRouteTo($param[2])) {
          HMIindication($from, "displayResponse {OK}");
          // assign route to train if any, then send MA FIXME
        } else {
          HMIindication($from, "displayResponse {Rejected - no route possible}");
        }
      break;
      case "rr": // Release route if no train assigned or train is at stand still (without timer)
        $recCount = 0; // FIXME
        if (true) { //if train assigned to route and at stand still or no train assigned to route FIXME
          if ($trackModel[$param[1]]->cmdReleaseRoute()) {
            HMIindication($from, "displayResponse {OK}");
          } else {
            HMIindication($from, "displayResponse {Rejected - no route}");
          }
        } else { // reject due to train driving
          HMIindication($from, "displayResponse {Rejected - train running}");
        }
      break;
// Train mode
      case "SR":
        $trainData[$param[1]]["SRallowed"] = $param[2];
      break;
      case "SH":
        $trainData[$param[1]]["SHallowed"] = $param[2];
      break;
      case "FS":
        $trainData[$param[1]]["FSallowed"] = $param[2];
      break;
      case "ATO":
        $trainData[$param[1]]["ATOallowed"] = $param[2];
      break;
// General
      case "SRallowed":
        $allowSR = $param[1];
      break;
      case "SHallowed":
        $allowSH = $param[1];
      break;
      case "FSallowed":
        $allowFS = $param[1];
      break;
      case "ATOallowed":
        $allowATO = $param[1];
      break;
      case "Rl": // Release operation
        $inChargeHMI = false;
        HMIindication($from, "oprReleased");
      break;
      default :
  //    errLog("Unknown command from HMI client: >$command<"); // FIXME
      print "Warning: Unknown command from HMI client: >$command<\n";
    break;
    }
  }
}

function toggleEmergencyStop() {
global $trainData, $emergencyStop;

  $emergencyStop = !$emergencyStop;
  foreach ($trainData as $index => $train) {
    generateMA($index);
  }
}

function initRBC() {
global $trainData;

  foreach ($trainData as $index => $train) {
    generateMA($index);
  }
// More?? FIXME
}

?>
