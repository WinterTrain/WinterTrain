<?php
// ------------------------------------------------------------------------------------------- Class definitions for track model

abstract class genericElement { // ---------------------------------------------------------------------------- Generic Element

  public $elementName;
  public $neighbourUp;
  public $neighbourDown;
  public $elementType = "GEN";
  public $facingUp = true;
// Dynamic properties are stored in the object while static data are kept in PT2  
  public $vacancyState = V_CLEAR;
  public $occupationTrainID = "";
  public $routeLockingState = R_IDLE;
  public $routeLockingType = RT_IDLE;
  public $routeLockingUp = true; // Only applicable when routeLockingState != R_IDLE
  public $blockingState = B_NOT_BLOCKED;
  public $lockingState = L_NOT_LOCKED; // Not used, but still part of HMI interface
  
  public function cmdSetRouteTo($endPoint) { // Only relevant for signal FIXME
    return false;
  }
  
  public function cmdReleaseRoute() { // Only relevant for EP FIXME
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
  
  public function notLockedInRoute() {
    return $this->routeLockingState == R_IDLE and $this->routeLockingType == RT_IDLE;
  }
  
  public function routeIsClear() {
    return $this->routeLockingState == R_IDLE 
      or ($this->vacancyState == V_CLEAR and $this->routeLockingUp ? $this->neighbourDown->routeIsClear() : $this->neighbourUp->routeIsClear());
  }
  
  public function occupyElementTrack($trainID, $drivingDirection) {
  global $trackModel;
    $this->vacancyState = V_OCCUPIED;
    $this->occupationTrainID = $trainID; // what if occupied by more trains?? ---------------------------------------------------- FIXME
    // Apply consequences like LX deactivation
  }

  public function releaseElementTrack($drivingDirection) { // Element clerance and possible sequential route release
    // $drivingDirection indicates in which direction  (UP, DOWN, Udef) the train left the (track) extent of the element
  global $TD_TXT_DIR;
    $this->vacancyState = V_CLEAR;
    if ($this->routeLockingState != R_IDLE) {
      switch ($drivingDirection){
        case D_UP:
          if ($this->routeLockingUp and $this->neighbourDown->notLockedInRoute()) {
            $this->routeLockingState = R_IDLE;
            $this->routeLockingType = RT_IDLE;
            $this->occupationTrainID = ""; // Check if more trains occupied the track FIXME
          } else {
            errLog("Warning: Train moving against route direction, element {$this->elementName}");
          }
        break;
        case D_DOWN:
          if (!$this->routeLockingUp and $this->neighbourUp->notLockedInRoute()) {
            $this->routeLockingState = R_IDLE;
            $this->routeLockingType = RT_IDLE;
            $this->occupationTrainID = "";
          } else {
            errLog("Warning: Train moving against route direction, element {$this->elementName}");
          }
        break;
        default:
          // Warning train released element in unknown direction - what to do FIXME
          errLog("Warning Train released element {$this->elementName} in unknown direction");
        break;
      }
    } // else route not locked - ignore element clerance
  }
  
  public function checkOccupationUp($trainIndex, $trainPositionUp, $trainPositionDown, $caller, $reportIndex) {
  global $PT2;
    $elementLength = $PT2[$this->elementName]["U"]["dist"] + $PT2[$this->elementName]["D"]["dist"];
    $occupation = ($trainPositionDown < $elementLength ? array($reportIndex => $this->elementName) : array()); // train occupying this element?
    if ($trainPositionUp > $elementLength) { // Up position located further Up, check neighbour Up
      $occupation = $occupation + $this->neighbourUp->
        checkOccupationUp($trainIndex, $trainPositionUp - $elementLength, $trainPositionDown - $elementLength, $this, $reportIndex + 1);
    }    
    return $occupation;
  }
  
  public function checkOccupationDown($trainIndex, $trainPositionUp, $trainPositionDown, $caller, $reportIndex) {
  global $PT2;
    $elementLength = $PT2[$this->elementName]["U"]["dist"] + $PT2[$this->elementName]["D"]["dist"];
    $occupation = ($trainPositionUp > -$elementLength ? array($reportIndex => $this->elementName) : array()); // train occupying this element?
    if ($trainPositionDown < -$elementLength) { // Down position located further Down, check neighbour DOwn
      $occupation = $occupation + $this->neighbourDown->
        checkOccupationDown($trainIndex, $trainPositionUp + $elementLength, $trainPositionDown + $elementLength, $this, $reportIndex - 1);
    }
    return $occupation;
  }
    
  protected function setRouteTo($directionUp, $endPoint, $caller) {
  global $recCount;
    $recCount +=1;
    if ($recCount > 1000) die("Recursion A This: {$this->elementName} EP: $endPoint ".($directionUp ? "U" : "D")."  **************\n"); // FIXME
    if ($this->routeLockingState != R_IDLE or $this->vacancyState != V_CLEAR) return "";
    $EP = $directionUp ? $this->neighbourUp->setRouteTo(true, $endPoint, $this) : $this->neighbourDown->setRouteTo(false, $endPoint, $this);
    if ($EP != "") {
      $this->routeLockingState = R_LOCKED;
      $this->routeLockingType = RT_VIA;
      $this->routeLockingUp = $directionUp;
      return $EP;
    } else {
      return "";
    }
  }

  public function searchSP($searchUp) { // Search for possible route Start Point
    return $this->vacancyState == V_CLEAR ?
      ($searchUp ? $this->neighbourUp->searchSP($searchUp) : $this->neighbourDown->searchSP($searchUp)) :
      "";
  }

  public function searchEP($searchUp) { // Search for route End Point
    return $searchUp ? $this->neighbourUp->searchEP($searchUp) : $this->neighbourDown->searchEP($searchUp);
  }
  
  protected function signalling($signal, $trainID) { // Determine signalling within the locked route
  global $SIGNALLING_TXT;
    if ($this->routeLockingState == R_LOCKED) {
      if ($this->vacancyState == V_OCCUPIED and $this->occupationTrainID == $trainID) {
        // Element occupied by assigned train only. Don't search any further. Ignore occupation in signalling
        return $signal;
      } else { // Element clear or occupied by another train
        $thisSignal = ($this->vacancyState == V_CLEAR ? $signal : SIG_STOP);
        return $this->routeLockingUp ?
          $this->neighbourDown->signalling($thisSignal, $trainID) :
          $this->neighbourUp->signalling($thisSignal, $trainID);
      }
    } else {
      return SIG_NOT_LOCKED;
    }
  }

  protected function EOAdist($train, $EOAdist, $searchRoute, $caller) { // Compute distance between LRBG and EOA.
  // Starting from EOA (alias EP) follow route whilst locked ($searchRoute true), then search all tracks for LRBG
  global $PT2;
    $elementLength = $PT2[$this->elementName]["U"]["dist"] + $PT2[$this->elementName]["D"]["dist"];
    if ($this->routeLockingState != R_LOCKED) $searchRoute = false;
    return $caller == $this->neighbourUp ?
      $this->neighbourDown->EOAdist($train, $EOAdist + $elementLength, $searchRoute, $this) :
      $this->neighbourUp->EOAdist($train, $EOAdist + $elementLength, $searchRoute, $this);
  }

  protected function releaseRoute($caller) { // unconditional route release
  global $recCount;
    $recCount +=1;
    if ($recCount > 1000) die("Recursion B {$this->elementName} **********************\n"); // FIXME
    if ($this->routeLockingState != R_IDLE and $caller == ($this->routeLockingUp ? $this->neighbourUp : $this->neighbourDown)) {
      $this->routeLockingUp ? $this->neighbourDown->releaseRoute($this) : $this->neighbourUp->releaseRoute($this);
      $this->routeLockingState = R_IDLE;
      $this->routeLockingType = RT_IDLE;
      $this->signallingState = SIG_NOT_LOCKED;
    }
  }
  
  protected function closeRoute($caller) { // Close signalling and await emergency release
    if ($this->routeLockingState != R_IDLE and $caller == ($this->routeLockingUp ? $this->neighbourUp : $this->neighbourDown)) {
      $this->routeLockingUp ? $this->neighbourDown->closeRoute($this) : $this->neighbourUp->closeRoute($this);    
      $this->routeLockingState = R_RELEASING;
    }
  }
  
  public function dumpRoute() {
    return $this->routeLockingState == R_IDLE ? "" :
      ($this->routeLockingUp ? $this->neighbourDown->dumpRoute() : $this->neighbourUp->dumpRoute())." $this->elementName";
  }

  public function __construct($consName) {
    $this->elementName = $consName;
  }
}

// ==========================================================================================================================================


class Pelement extends genericElement { // -------------------------------------------------------------------------------------- Point
  public $neighbourTip;
  public $neighbourRight;
  public $neighbourLeft;
  
  public $supervisionMode;              // Read from PT2
  public $pointState = P_UNSUPERVISED;  // Functional state of point being it from static configuration or physical state as reported from EC
  public $logicalLieRight = true;       // Logical lie is right when true. Expected lie
  public $pointHeld = false;            // Point held in position triggered by optional PHTU / PHTD
  
  private $throwLockedByConfiguration = false;  // Point throw is locked by configuration
  public $throwLockedByRoute = false;           // Point throw is locked due to position in locked route
  private $throwLockedByCmd = false;            // Point throw is locked by command
  
  public function cmdToggleElement() { // Throw point to opposite lie
    return $this->throwPoint(C_TOGGLE);
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

  public function routeIsClear() {
    return $this->routeLockingState == R_IDLE or ($this->vacancyState == V_CLEAR and
      $this->routeLockingUp ? 
        ($this->facingUp ? 
          $this->neighbourTip->routeIsClear()
        :
          ($this->routeLockingType == RT_RIGHT ?
            $this->neighbourRight->routeIsClear() : $this->neighbourLeft->routeIsClear()))
      : 
        ($this->facingUp ? 
          ($this->routeLockingType == RT_RIGHT ?
            $this->neighbourRight->routeIsClear() : $this->neighbourLeft->routeIsClear())
          :
          $this->neighbourTip->routeIsClear()));
  }
  
  public function releaseElementTrack($drivingDirection) {
    // $drivingDirection indicates in which direction  (UP, DOWN, Udef) the train left the (track) extent of the element
    global $TD_TXT_DIR;
    $this->vacancyState = V_CLEAR;
    if ($this->routeLockingState != R_IDLE) {
      switch ($drivingDirection){
        case D_UP:
          if ($this->routeLockingUp and
              ($this->facingUp ?
                $this->neighbourTip->notLockedInRoute()
              :
                ($this->routeLockingType == RT_RIGHT ? $this->neighbourRight->notLockedInRoute() : $this->neighbourLeft->notLockedInRoute())
              )) { 
            $this->routeLockingState = R_IDLE;
            $this->routeLockingType = RT_IDLE;
            $this->throwLockedByRoute = false;
            $this->occupationTrainID = "";
          } else {
            errLog("Warning: Train moving against route direction, element {$this->elementName}");
          }
        break;
        case D_DOWN:
          if (!$this->routeLockingUp and
              (!$this->facingUp ?
                $this->neighbourTip->notLockedInRoute() 
              :
                ($this->routeLockingType == RT_RIGHT ? $this->neighbourRight->notLockedInRoute() : $this->neighbourLeft->notLockedInRoute() ))) {
            $this->routeLockingState = R_IDLE;
            $this->routeLockingType = RT_IDLE;
            $this->throwLockedByRoute = false;
            $this->occupationTrainID = "";
          } else {
            errLog("Warning: Train moving against route direction, element {$this->elementName}");
          }
        break;
        default:
          errLog("Warning Train released element {$this->elementName} in unknown direction");
        break;
      }
    } // else route not locked - ignore element release
    if ($this->pointHeld) {
      $this->pointHeld = false;
      $this->throwPoint(C_RELEASE);
print "C_RELEASE {$this->elementName}\n";
    }
  }
  
  public function checkOccupationUp($trainIndex, $trainPositionUp, $trainPositionDown, $caller, $reportIndex) {
  global $PT2, $trainData;
    $occupation = array();
    if ($this->facingUp) { // Point is facing in search direction
      $elementLengthRight = $PT2[$this->elementName]["T"]["dist"] + $PT2[$this->elementName]["R"]["dist"];
      $elementLengthLeft = $PT2[$this->elementName]["T"]["dist"] + $PT2[$this->elementName]["L"]["dist"];
      if ($trainPositionDown < ($this->logicalLieRight ? $elementLengthRight : $elementLengthLeft)) {
        // Point is occupied by this train in the logical lie. Hence throwing is blocked, lie can be trusted and position is unambiguous
        if ($this->logicalLieRight) {
          $occupation = array($reportIndex => $this->elementName);
          if ($trainPositionUp > $elementLengthRight) { // Up position located further Up, check neighbour at Right
            $occupation = $occupation + $this->neighbourRight->
              checkOccupationUp($trainIndex, $trainPositionUp - $elementLengthRight, $trainPositionDown - $elementLengthRight,
                $this, $reportIndex + 1);
          }
        } else {
          $occupation = array($reportIndex => $this->elementName);
          if ($trainPositionUp > $elementLengthRight) { // Up position located further Up, check neighbour at Left
            $occupation = $occupation + $this->neighbourLeft->
              checkOccupationUp($trainIndex, $trainPositionUp - $elementLengthLeft, $trainPositionDown - $elementLengthLeft,
                $this, $reportIndex + 1);
          }
        }
      } else { // Position might be ambiguous
        if ($trainData[$trainIndex]["curPositionValid"]) { // Current occupation might be used to determin new occupation
          $occupationRight = $this->neighbourRight->
            checkOccupationUp($trainIndex, $trainPositionUp - $elementLengthRight, $trainPositionDown - $elementLengthRight,
              $this, $reportIndex + 1);
          $occupationLeft = $this->neighbourLeft->
            checkOccupationUp($trainIndex, $trainPositionUp - $elementLengthLeft, $trainPositionDown - $elementLengthLeft,
              $this, $reportIndex +1);
          $overlapRight = array_intersect($trainData[$trainIndex]["curOccupation"], $occupationRight);
          $overlapLeft = array_intersect($trainData[$trainIndex]["curOccupation"], $occupationLeft);
          if ($overlapRight) {
            if ($overlapLeft) { // The new occupations are both overlapping current occupation - Ambiguous
              $occupation = $occupationRight + $occupationLeft + array("Ambiguous" => "");
            } else {
              $occupation = $occupationRight;
            }
          } else {
            if ($overlapLeft) {
              $occupation = $occupationLeft;
            } else { // New occupation is not overlapping current occupation - Ambiguous
              $occupation = $occupationRight + $occupationLeft + array("Ambiguous" => "");
            }
          }               
        } else {
          $occupation = array("Ambiguous" => "");
        }
      }
    } else { // Point is trailing in search direction
      if ($caller === $this->neighbourRight) { // occupation search reached this point via right branch
        $elementLength = $PT2[$this->elementName]["T"]["dist"] + $PT2[$this->elementName]["R"]["dist"];
      } else { // occupation search reached this point via left branch
        $elementLength = $PT2[$this->elementName]["T"]["dist"] + $PT2[$this->elementName]["L"]["dist"];          
      }
      $occupation = ($trainPositionDown < $elementLength ? array($reportIndex => $this->elementName) : array()); // train occupying this element?
      if ($trainPositionUp > $elementLength) { // Up position located further Up, check neighbour at Tip
        $occupation = $occupation + $this->neighbourTip->
          checkOccupationUp($trainIndex, $trainPositionUp - $elementLength, $trainPositionDown - $elementLength,
            $this, $reportIndex + 1);
      }
    }
    return $occupation;
  }
  
  public function checkOccupationDown($trainIndex, $trainPositionUp, $trainPositionDown, $caller, $reportIndex) {
  global $PT2, $trainData;
    $occupation = array();
    if ($this->facingUp) { // Point is trailing in search direction
      if ($caller === $this->neighbourRight) { // occupation search reached this point via right branch
        $elementLength = $PT2[$this->elementName]["T"]["dist"] + $PT2[$this->elementName]["R"]["dist"];      
      } else { // occupation search reached this point via left branch
        $elementLength = $PT2[$this->elementName]["T"]["dist"] + $PT2[$this->elementName]["L"]["dist"];          
      }
      $occupation = ($trainPositionUp > -$elementLength ? array($reportIndex => $this->elementName) : array()); // train occupying this element?
      if ($trainPositionDown < -$elementLength) { // Down position located further Down, check neighbour at Tip
        $occupation = $occupation + $this->neighbourTip->
          checkOccupationDown($trainIndex, $trainPositionUp + $elementLength, $trainPositionDown + $elementLength,
            $this, $reportIndex - 1);
      }
    } else { // Point is facing in search direction
      $elementLengthRight = $PT2[$this->elementName]["T"]["dist"] + $PT2[$this->elementName]["R"]["dist"];
      $elementLengthLeft = $PT2[$this->elementName]["T"]["dist"] + $PT2[$this->elementName]["L"]["dist"];
      if ($trainPositionUp > -($this->logicalLieRight ? $elementLengthRight : $elementLengthLeft)) {
        // Point is occupied by this train in the logical lie. Hence throwing is blocked, lie can be trusted and position is unambiguous
        if ($this->logicalLieRight) {
          $occupation = array($reportIndex => $this->elementName);
          if ($trainPositionDown < -$elementLengthRight) { // Down position located further Down, check neighbour at Right
            $occupation = $occupation + $this->neighbourRight->
              checkOccupationDown($trainIndex, $trainPositionUp + $elementLengthRight, $trainPositionDown + $elementLengthRight,
                $this, $reportIndex - 1);
          }
        } else {
          $occupation = array($reportIndex => $this->elementName);
          if ($trainPositionDown < -$elementLengthRight) { // Down position located further Down, check neighbour at Left
            $occupation = $occupation + $this->neighbourLeft->
              checkOccupationDown($trainIndex, $trainPositionUp + $elementLengthLeft, $trainPositionDown + $elementLengthLeft,
                $this, $reportIndex - 1);
          }
        }
      } else { // Position might be ambiguous
        if ($trainData[$trainIndex]["curPositionValid"]) { // Current occupation might be used to determin new occupation
          $occupationRight = $this->neighbourRight->
            checkOccupationDown($trainIndex, $trainPositionUp + $elementLengthRight, $trainPositionDown + $elementLengthRight,
              $this, $reportIndex - 1);
          $occupationLeft = $this->neighbourLeft->
            checkOccupationDown($trainIndex, $trainPositionUp + $elementLengthLeft, $trainPositionDown + $elementLengthLeft,
              $this, $reportIndex - 1);
          $overlapRight = array_intersect($trainData[$trainIndex]["curOccupation"], $occupationRight);
          $overlapLeft = array_intersect($trainData[$trainIndex]["curOccupation"], $occupationLeft);
          if ($overlapRight) {
            if ($overlapLeft) { // The new occupations are both overlapping current occupation - Ambiguous
              $occupation = $occupationRight + $occupationLeft + array("Ambiguous" => "");
            } else {
              $occupation = $occupationRight;
            }
          } else {
            if ($overlapLeft) {
              $occupation = $occupationLeft;
            } else { // New occupation is not overlapping current occupation - Ambiguous
              $occupation = $occupationRight + $occupationLeft + array("Ambiguous" => "");
            }
          }               
        } else {
          $occupation = array("Ambiguous" => "");
        }
      }
    }
    return $occupation;
  }
  
  public function throwPoint($command) { // Throw point according to command
  global $PT2;
    if ($this->throwLockedByConfiguration or $this->throwLockedByRoute or $this->throwLockedByCmd or $this->vacancyState != V_CLEAR) return false;
    // FIXME detailed info on rejection reason to be provided
    switch ($this->supervisionMode) {
      case "U": // Permanetly unsupervised
        return false;
      break;
      case "CR": // Clamped right
      case "CL": // Clamped left
        return false; // Point throw rejected
      break;
      case "S": // Simulated
        switch($command) {
          case C_TOGGLE:
            $this->logicalLieRight = !$this->logicalLieRight;
            $this->pointState = $this->logicalLieRight ? P_SUPERVISED_RIGHT : P_SUPERVISED_LEFT;
            if ($this->routeLockingState != R_IDLE) {
              $this->throwLockedByRoute = ($this->routeLockingType == RT_RIGHT and $this->logicalLieRight
                or $this->routeLockingType == RT_LEFT and !$this->logicalLieRight);
            }
          break;
          case C_RIGHT:
            $this->pointState = P_SUPERVISED_RIGHT;
            $this->logicalLieRight = true;
            if ($this->routeLockingState != R_IDLE and $this->routeLockingType == RT_RIGHT) {
              $this->throwLockedByRoute = true;
            }
          break;
          case C_LEFT:
            $this->pointState = P_SUPERVISED_LEFT;
            $this->logicalLieRight = false;
            if ($this->routeLockingState != R_IDLE and $this->routeLockingType == RT_LEFT) {
              $this->throwLockedByRoute = true;
            }            
          break;
          default:
        }
        return true;
      break;
      case "P": // Real point machine
        switch ($command) {
          case C_TOGGLE:
            $this->logicalLieRight = !$this->logicalLieRight;
            $order = ($this->logicalLieRight ? O_RIGHT : O_LEFT);
          break;
          case C_RIGHT:
            $this->logicalLieRight = true;
            $order = O_RIGHT;
          break;
          case C_LEFT:
            $order = O_LEFT;
            $this->logicalLieRight = false;
          break;
          case C_HOLD:
            $order = ($this->logicalLieRight ? O_RIGHT_HOLD : O_LEFT_HOLD);
          break;
          case C_RELEASE:
            $order = O_RELEASE;
          break; 
          default:
            return false;
        }
        orderElement($this->elementName, $order);
        return true;
      break;
      default:
        return false;
    }
  }
  
  protected function setRouteTo($directionUp, $endPoint, $caller) { // Set route further - called by neighbour
  global $automaticPointThrowEnabled, $recCount;
    $recCount +=1;
    if ($recCount > 1000) die("Recursion C {$this->elementName} EP: $endPoint ".($directionUp ? "U" : "D"." **********n")); // FIXME
    if ($this->routeLockingState != R_IDLE or $this->vacancyState != V_CLEAR) return "";
    $this->routeLockingUp = $directionUp;
    if ($directionUp == $this->facingUp) { // Point is facing in route direction
      if ($this->logicalLieRight) { // Follow logical lie right
        $EP = $this->neighbourRight->setRouteTo($directionUp, $endPoint, $this);
        if ($EP != "") {
          $this->routeLockingState = R_LOCKED;
          $this->routeLockingType = RT_RIGHT;
          if ($this->pointState == P_SUPERVISED_RIGHT) $this->throwLockedByRoute = true;
          return $EP;
        } else {
          if ($this->throwLockedByCmd) return ""; // Point throw is locked by cmd so no reason to try
          $EP = $this->neighbourLeft->setRouteTo($directionUp, $endPoint, $this);
          if ($EP != "") {
            $this->routeLockingState = R_LOCKED;
            $this->routeLockingType = RT_LEFT;
            if ($automaticPointThrowEnabled) $this->throwPoint(C_LEFT); // Throw left
            return $EP;
          }
          return "";
        }
      } else {// Follow logical lie left
        $EP = $this->neighbourLeft->setRouteTo($directionUp, $endPoint, $this);
        if ($EP != "") {
          $this->routeLockingState = R_LOCKED;
          $this->routeLockingType = RT_LEFT;
          if ($this->pointState == P_SUPERVISED_LEFT) $this->throwLockedByRoute = true;
          return $EP;
        } else {
          if ($this->throwLockedByCmd) return ""; // Point throw is locked by cmd so no reason to try
          $EP = $this->neighbourRight->setRouteTo($directionUp, $endPoint, $this);
          if ($EP != "") {
            $this->routeLockingState = R_LOCKED;
            $this->routeLockingType = RT_RIGHT;
            if ($automaticPointThrowEnabled) $this->throwPoint(C_RIGHT); // Throw right
            return $EP;
          }
          return ""; 
        }
      }
    } else { // Point is trailing in route direction
      if ($caller === $this->neighbourRight) { // route search reached point via right branch
        if (!$this->logicalLieRight and $this->throwLockedByCmd) return false; // Point throw is locked by cmd so no reason to try
        $EP = $this->neighbourTip->setRouteTo($directionUp, $endPoint, $this);
        if ($EP != "") {
          $this->routeLockingState = R_LOCKED;
          $this->routeLockingType = RT_RIGHT;
          if ($this->logicalLieRight and $this->pointState == P_SUPERVISED_RIGHT) {
            $this->throwLockedByRoute = true;
          } else {
            if ($automaticPointThrowEnabled) $this->throwPoint(C_RIGHT); // Throw right
          }
          return $EP;
        }
        return "";
      } else { // route search via left branch
        if ($this->logicalLieRight and $this->throwLockedByCmd) return false; // Point throw is locked by cmd so no reason to try
        $EP = $this->neighbourTip->setRouteTo($directionUp, $endPoint, $this);
        if ($EP != "") {
          $this->routeLockingState = R_LOCKED;
          $this->routeLockingType = RT_LEFT;
          if (!$this->logicalLieRight and $this->pointState == P_SUPERVISED_LEFT) {
            $this->throwLockedByRoute = true;
          } else {
            if ($automaticPointThrowEnabled) $this->throwPoint(C_LEFT); // Throw left
          }
          return $EP;
        }
        return "";
      }
    }    
  }
  
  protected function releaseRoute($caller) {
  global $recCount;
    $recCount +=1;
    if ($recCount > 1000) die("Recursion D {$this->elementName}\n"); // FIXME
    if ($this->routeLockingState != R_IDLE) {
      if ($this->routeLockingUp == $this->facingUp) { // Point is locked facing in route
        if (($caller == $this->neighbourRight and $this->routeLockingType == RT_RIGHT) or
          ($caller == $this->neighbourLeft and $this->routeLockingType == RT_LEFT)) {
          $this->neighbourTip->releaseRoute($this);
          $this->routeLockingState = R_IDLE;
          $this->routeLockingType = RT_IDLE;
          $this->throwLockedByRoute = false;
          $this->signallingState = SIG_NOT_LOCKED;
        } // else This point is not part of the route to be released
      } else {                                        // Point is locked trailing in route
        if ($caller == $this->neighbourTip) {
          $this->routeLockingType == RT_RIGHT ? $this->neighbourRight->releaseRoute($this) : $this->neighbourLeft->releaseRoute($this);
          $this->routeLockingState = R_IDLE;
          $this->routeLockingType = RT_IDLE;
          $this->throwLockedByRoute = false;
          $this->signallingState = SIG_NOT_LOCKED;
        } // else This point is not part of the route to be released
      }
    } // else point not locked
  }
  
  protected function closeRoute($caller) { // Close signalling and await emergency release
    if ($this->routeLockingState != R_IDLE) {
      if ($this->routeLockingUp == $this->facingUp) { // Point is locked facing in route
        if (($caller == $this->neighbourRight and $this->routeLockingType == RT_RIGHT) or
          ($caller == $this->neighbourLeft and $this->routeLockingType == RT_LEFT)) {
          $this->neighbourTip->closeRoute($this);
          $this->routeLockingState = R_RELEASING;
//          $this->signallingState = SIG_CLOSED; // Used for point?? FIXME
        } // else This point is not part of the route to be closed
      } else { // Point is locked trailing in route
        if ($caller == $this->neighbourTip) {
          $this->routeLockingType == RT_RIGHT ? $this->neighbourRight->closeRoute($this) : $this->neighbourLeft->closeRoute($this);
          $this->routeLockingState = R_RELEASING;
//          $this->signallingState = SIG_CLOSED; // Used for point?? FIXME
        } // else This point is not part of the route to be closed
      }
    } // else point not locked
  }
 

  public function searchSP($searchUp) {
    return ""; // Points are not allowed between train and Start Point of route
  }
  
  public function searchEP($searchUp) {
    if ($this->routeLockingUp == $this->facingUp) { // Point is facing in route direction
      return $this->routeLockingType == RT_RIGHT ? 
        $this->neighbourRight->searchEP($searchUp) : $this->neighbourLeft->searchEP($searchUp);
    } else { // Point is trailing in route
      return $this->neighbourTip->searchEP($searchUp);
    }
  }
  
  protected function signalling($signal, $trainID) { // Point
  global $SIGNALLING_TXT;
    if ($this->routeLockingState == R_LOCKED) {
      if ($this->vacancyState == V_OCCUPIED and $this->occupationTrainID == $trainID) {
        // Element occupied by assigned train only. Don't search any further. Ignore occupation
        return $signal;
      } else { // Element clear or occupied by another train
        $thisSignal = (($this->vacancyState == V_CLEAR and $this->throwLockedByRoute and 
          ($this->logicalLieRight ? $this->pointState == P_SUPERVISED_RIGHT : $this->pointState == P_SUPERVISED_LEFT)) ? 
            $signal : 
            SIG_STOP);
        return $this->routeLockingUp == $this->facingUp ?
        // Locked facing in route
          ($this->neighbourTip->signalling($thisSignal, $trainID)) :
        // Locked trailing in route
          ($this->routeLockingType == RT_RIGHT ?
            $this->neighbourRight->signalling($thisSignal, $trainID) :
            $this->neighbourLeft->signalling($thisSignal, $trainID));
      }
    } else {
      return SIG_NOT_LOCKED;
    }
  }

  protected function EOAdist($train, $EOAdist, $searchRoute, $caller) { // Point
  global $PT2;
    if ($this->routeLockingState != R_LOCKED) $searchRoute = false;
    if ($searchRoute) { // Search within the route
      return $this->routeLockingUp == $this->facingUp ? 
        // Locked facing in route
        ($this->neighbourTip->EOAdist($train,
          $EOAdist + $PT2[$this->elementName]["T"]["dist"] +
          ($this->routeLockingType == RT_RIGHT ?
            $PT2[$this->elementName]["R"]["dist"] : $PT2[$this->elementName]["L"]["dist"]), $searchRoute, $this)) :
        // Locked trailing in route
        ($this->routeLockingType == RT_RIGHT ?
          $this->neighbourRight->EOAdist($train,
            $EOAdist + $PT2[$this->elementName]["T"]["dist"] + $PT2[$this->elementName]["R"]["dist"], $searchRoute, $this) :
          $this->neighbourLeft->EOAdist($train,
            $EOAdist + $PT2[$this->elementName]["T"]["dist"] + $PT2[$this->elementName]["L"]["dist"], $searchRoute, $this));
    } else { // Search in rear of (before) route
      if ($caller == $this->neighbourTip) { // search in both branches
        $EOAdistRight = $this->neighbourRight->EOAdist($train, $EOAdist + $PT2[$this->elementName]["T"]["dist"] +
          $PT2[$this->elementName]["R"]["dist"], $searchRoute, $this);
        $EOAdistLeft = $this->neighbourLeft->EOAdist($train, $EOAdist + $PT2[$this->elementName]["T"]["dist"] +
          $PT2[$this->elementName]["L"]["dist"], $searchRoute, $this);
        if ($EOAdistRight === false) {
          if ($EOAdistLeft === false) { // LRBG not found in any branch - distance ambiguous
            return false;
          } else {
            return $EOAdistLeft;
          }
        } else {
          if ($EOAdistLeft === false) {
            return $EOAdistRight;
          } else {  // LRBG found in both branches - distance ambiguous          
            return false;
          }
        }
      } else { // Search via tip
        return $this->neighbourTip->EOAdist($train, $EOAdist + $PT2[$this->elementName]["T"]["dist"] +
          ($caller == $this->neighbourRight ? $PT2[$this->elementName]["R"]["dist"] : $PT2[$this->elementName]["L"]["dist"]),
          $searchRoute, $this);
      }
    }
  }
  
  public function supervisionUpdate($supervisionState) {
  global $trackModel;
    if ($supervisionState != $this->pointState) { // Supervision state changed
      $this->pointState = $supervisionState;
      switch ($supervisionState) {
        case P_SUPERVISED_RIGHT:
          if ($this->logicalLieRight) {
            if ($this->routeLockingState != R_IDLE and $this->routeLockingType == RT_RIGHT) {
              $this->throwLockedByRoute = true;
            }
          } else {
            errLog("Warning: Point {$this->elementName} reported position (right), differs from expected lie (left)");
          }
        break;
        case P_SUPERVISED_LEFT;
          if (!$this->logicalLieRight) {
            if ($this->routeLockingState != R_IDLE and $this->routeLockingType == RT_LEFT) {
              $this->throwLockedByRoute = true;
            }
          } else {
            errLog("Warning: Point {$this->elementName} reported position (left), differs from expected lie (right)");
          }   
        break;
        default:
      }
    }
  }

  public function dumpRoute() {
    return $this->routeLockingState == R_IDLE ? "" :
      ($this->routeLockingUp ?
        ($this->facingUp ?
          $this->neighbourTip->dumpRoute()
        :
          ($this->routeLockingType == RT_RIGHT ? $this->neighbourRight->dumpRoute() : $this->neighbourLeft->dumpRoute()))
      :
        ($this->facingUp ?
          ($this->routeLockingType == RT_RIGHT ? $this->neighbourRight->dumpRoute() : $this->neighbourLeft->dumpRoute())
        :
          $this->neighbourTip->dumpRoute()
        ))." $this->elementName".($this->routeLockingType == RT_RIGHT ? "(R)" : "(L)");
  }
  
public function __construct($consName) {
  global $PT2;
    $this->elementName = $consName;
    $this->elementType = $PT2[$consName]["element"];
    $this->facingUp = ($this->elementType == "PF");
    $this->supervisionMode = $PT2[$consName]["supervisionState"];
    switch ($this->supervisionMode) {
      case "S": // Simulated
        $this->pointState = P_SUPERVISED_RIGHT;   // pointState reflects physical lie
        $this->logicalLieRight = true;            // logicalLieRight reflects expected lie
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

// ========================================================================================================================================

class Selement extends genericElement { // ----------------------------------------------------------------------------------------- Signal

  public $signallingState = SIG_NOT_LOCKED; // Logical signalling in route, used as well by EOA distance determination and HMI
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
  // Set route from called signal to $endPoint. Return value is realised EP or ""
  global $trainData, $trainIndex, $trackModel, $recCount;
    $recCount = 0; // FIXME
    if ($this->routeLockingState != R_IDLE and $this->routeLockingType != RT_END_POINT) return "";
    if ($this->blockingState == B_BLOCKED_START_VIA or $this->vacancyState != V_CLEAR) return "";
    $EP = $this->facingUp ? $this->neighbourUp->setRouteTo(true, $endPoint, $this) : $this->neighbourDown->setRouteTo(false, $endPoint, $this);
    if ($EP != "") {
      if ($this->routeLockingType == RT_END_POINT) { // Already End Point
        $this->routeLockingState = R_LOCKED; // FIXME  state could already be != IDLE
        $this->routeLockingType = RT_VIA;
        if ($this->assignedTrain != "") { // EP already assigned to train. Update assignment
          $trainData[$trainIndex[$this->assignedTrain]]["assignedRoute"] = $EP;
          $trackModel[$EP]->assignedTrain = $this->assignedTrain;
          $this->assignedTrain = "";
        }
      } else {
        $this->routeLockingState = R_LOCKED;
        $this->routeLockingType = RT_START_POINT;
      }
      $this->routeLockingUp = $this->facingUp;;
      $trackModel[$EP]->updateSignalling();
      return $EP;
    }
    return "";
  }
  
  public function cmdReleaseRoute() { // Unconditional route release, signal
    // Calling funciton must deassign train - if any - before calling
  global $recCount;
    $recCount = 0; // FIXME
    if ($this->routeLockingState != R_IDLE and $this->routeLockingType == RT_END_POINT and $this->assignedTrain == "") {
      $this->facingUp ? $this->neighbourDown->releaseRoute($this) : $this->neighbourUp->releaseRoute($this);
      $this->routeLockingState = R_IDLE;
      $this->routeLockingType = RT_IDLE;
      $this->signallingState = SIG_NOT_LOCKED;
      orderSignal($this->elementName, SIG_STOP);
    }
  }
  
  public function cmdCloseRoute() { // Close signalling and await emergency release
    // Calling funciton must deassign train - if any - before calling
    if ($this->routeLockingState != R_IDLE and $this->routeLockingType == RT_END_POINT and $this->assignedTrain == "") {
      $this->facingUp ? $this->neighbourDown->closeRoute($this) : $this->neighbourUp->closeRoute($this);
      $this->routeLockingState = R_RELEASING;
      $this->signallingState = SIG_CLOSED;
      orderSignal($this->elementName, SIG_STOP);
    }
  }

  public function routeIsClear() {
    return $this->routeLockingState == R_IDLE or ($this->vacancyState == V_CLEAR and
      ($this->routeLockingType == RT_START_POINT or
        $this->routeLockingUp ? $this->neighbourDown->routeIsClear() : $this->neighbourUp->routeIsClear()));
  }
  
  public function occupyElementTrack($trainID, $drivingDirection) {
    $this->vacancyState = V_OCCUPIED;
    $this->occupationTrainID = $trainID; // what if occupied by more trains?? ---------------------------------------------------- FIXME
    if ($this->routeLockingState != R_IDLE and ($this->routeLockingType == RT_START_POINT or $this->routeLockingType == RT_VIA)) {
      orderSignal($this->elementName, SIG_STOP);
      $this->signallingState = SIG_STOP; // or SIG_CLOSED ??? FIXME
    }
  }
  
  public function releaseElementTrack($drivingDirection) { // Signal
  global $TD_TXT_DIR;
    $this->vacancyState = V_CLEAR;
    if ($this->routeLockingState != R_IDLE) {
      switch ($drivingDirection){
        case D_UP:
          if ($this->routeLockingUp) {
            switch ($this->routeLockingType) {
              case RT_START_POINT:
                $this->routeLockingState = R_IDLE;
                $this->routeLockingType = RT_IDLE;
                $this->occupationTrainID = "";
                $this->signallingState = SIG_NOT_LOCKED;
              break;
              case RT_VIA:
                if ($this->neighbourDown->notLockedInRoute()) {
                  $this->routeLockingState = R_IDLE;
                  $this->routeLockingType = RT_IDLE;
                  $this->occupationTrainID = "";
                  $this->signallingState = SIG_NOT_LOCKED;
                } // else Warning: element release in the middle of a route - ignored
              break;
              case RT_VIA_REVERSE:
                if ($this->neighbourDown->notLockedInRoute()) {
                  $this->routeLockingState = R_IDLE;
                  $this->routeLockingType = RT_IDLE;
                  $this->occupationTrainID = "";
                  $this->signallingState = SIG_NOT_LOCKED;
                } // else Warning: element release in the middle of a route - ignored
              break;
              case RT_END_POINT:
                // train continued passing the route EP
                if ($this->neighbourDown->notLockedInRoute()) { // other conditions??  FIXME
                  $this->routeLockingState = R_IDLE;
                  $this->routeLockingType = RT_IDLE;
                  $this->occupationTrainID = "";
                  $this->signallingState = SIG_NOT_LOCKED;
                } // else Warning: element release in the middle of a route - ignored
              break;
              default:
                errLog("Error: Signal, releaseElementTrack UP: Default routelocking type {$this->routeLockingType} not implemented");
              break;
            }
          } else {
          errLog("Train moving against route direction, element {$this->elementName}");
          }
        break;
        case D_DOWN:
          if (!$this->routeLockingUp) {
            switch ($this->routeLockingType) {
              case RT_START_POINT:
                $this->routeLockingState = R_IDLE;
                $this->routeLockingType = RT_IDLE;
                $this->occupationTrainID = "";
                $this->signallingState = SIG_NOT_LOCKED;
              break;
              case RT_VIA:
                if ( $this->neighbourUp->notLockedInRoute() ) { // other conditions FIXME
                  $this->routeLockingState = R_IDLE;
                  $this->routeLockingType = RT_IDLE;
                  $this->occupationTrainID = "";
                  $this->signallingState = SIG_NOT_LOCKED;
                } // else Warning: element release in the middle of a route - ignored
              break;
              case RT_VIA_REVERSE:
                if ( $this->neighbourUp->notLockedInRoute() ) { // other conditions FIXME
                  $this->routeLockingState = R_IDLE;
                  $this->routeLockingType = RT_IDLE;
                  $this->occupationTrainID = "";
                  $this->signallingState = SIG_NOT_LOCKED;
                } // else Warning: element release in the middle of a route - ignored
              break;
              case RT_END_POINT:
                // train continued passing the route EP
                if ( $this->neighbourUp->notLockedInRoute() ) { // other conditions FIXME
                  $this->routeLockingState = R_IDLE;
                  $this->routeLockingType = RT_IDLE;
                  $this->occupationTrainID = "";
                  $this->signallingState = SIG_NOT_LOCKED;
                } // else Warning: element release in the middle of a route - ignored
              break;
              default:
                errLog("Error: Signal, releaseElementTrack DOWN: Default routelocking type {$this->routeLockingType} not implemented");
              break;
            }        
          } else {
            errLog("Train moving against route direction, element {$this->elementName}");
          }
        break;
        default: // moving direction unknown - ignore release
          errLog("Warning Train released element {$this->elementName} in unknown direction");
        break;
      }
    } // else route not locked - ignore element release
  }
  
  public function notLockedInRoute() {
    return $this->routeLockingState == R_IDLE and $this->routeLockingType == RT_IDLE;
  }

  protected function closeRoute($caller) { // Close signalling and await emergency release
    if ($this->routeLockingState != R_IDLE and $caller == ($this->routeLockingUp ? $this->neighbourUp : $this->neighbourDown)) {
      $this->routeLockingUp ? $this->neighbourDown->closeRoute($this) : $this->neighbourUp->closeRoute($this);    
      $this->routeLockingState = R_RELEASING;
      $this->signallingState = SIG_CLOSED;
      orderSignal($this->elementName, SIG_STOP);
    }
  }
  
  protected function setRouteTo($directionUp, $endPoint, $caller) {
  global $recCount;
    $recCount +=1;
    if ($recCount > 1000) die("Recursion E {$this->elementName} EP: $endPoint ".($directionUp ? "U" : "D")." *****************\n"); // - FIXME
    if ($directionUp == $this->facingUp) { // Signal is facing in route direction
      if ($endPoint == $this->elementName) { // End point found
        if ($this->routeLockingState != R_IDLE and $this->routeLockingType != RT_START_POINT) return "";
        if ($this->routeLockingType == RT_START_POINT) { // Already START POINT
          $this->routeLockingState = R_LOCKED; // FIXME Check if state != IDLE
          $this->routeLockingType = RT_VIA;
          $this->signallingState = SIG_STOP;
          $EP = $this->searchEP($directionUp); 
        } else {                                         // Not locked
          if ($directionUp ? $this->neighbourUp->routeLockingState == R_LOCKED : $this->neighbourDown->routeLockingState == R_LOCKED) {
            return ""; // Preventing this new EP to be related to any old route behind the signal
          }
          $this->routeLockingState = R_LOCKED;
          $this->routeLockingType = RT_END_POINT;
          $this->signallingState = SIG_STOP;
          $EP = $this->elementName;
        }
        $this->routeLockingUp = $directionUp;
        return $EP;
      } else {
        if ($this->routeLockingState != R_IDLE or $this->blockingState == B_BLOCKED_START_VIA or $this->vacancyState != V_CLEAR) return "";
        $EP = $this->facingUp ?
          $this->neighbourUp->setRouteTo(true, $endPoint, $this) :
          $this->neighbourDown->setRouteTo(false, $endPoint, $this);
        if ($EP != "") {
          $this->routeLockingState = R_LOCKED;
          $this->routeLockingType = RT_VIA;
          $this->routeLockingUp = $directionUp;
          $this->signallingState = SIG_STOP;
          return $EP;
        } 
        return ""; 
      }
    } else { // Signal is reverse in route direction
      if ($endPoint == $this->elementName) return ""; // End point found, but with wrong orientation
      if ($this->routeLockingState != R_IDLE or $this->vacancyState != V_CLEAR) return "";
      $EP = $directionUp ? $this->neighbourUp->setRouteTo(true, $endPoint, $this) : $this->neighbourDown->setRouteTo(false, $endPoint, $this);
      if ($EP != "") {
        $this->routeLockingState = R_LOCKED;
        $this->routeLockingType = RT_VIA_REVERSE;
        $this->routeLockingUp = $directionUp;
        return $EP;
      } 
      return "";
    }
  }

  public function searchSP($searchUp) {
    if ($this->facingUp == $searchUp) { // Signal is facing
      return $this->elementName;
    } else {
      return $this->vacancyState == V_CLEAR ?
        ($searchUp ? $this->neighbourUp->searchSP($searchUp) : $this->neighbourDown->searchSP($searchUp)) :
        "";
    }
  }

  public function searchEP($searchUp) {
    if (($this->facingUp == $searchUp) and $this->routeLockingState == R_LOCKED and $this->routeLockingType == RT_END_POINT) {
      return $this->elementName;
    } else {
      return $searchUp ? $this->neighbourUp->searchEP($searchUp) : $this->neighbourDown->searchEP($searchUp);
    }  
  }
  
  public function updateSignalling() { // To be called for EP, signal
    if ($this->routeLockingState == R_LOCKED and $this->routeLockingType == RT_END_POINT) {
      // Vacancy state not included as the signal has no extent on the facing side 
      $this->signallingState = SIG_STOP;
      return ($this->routeLockingUp ?
        $this->neighbourDown->signalling(SIG_PROCEED, $this->assignedTrain) :
        $this->neighbourUp->signalling(SIG_PROCEED, $this->assignedTrain));  
    } else {
      $this->signallingState = SIG_NOT_LOCKED;
      return SIG_NOT_LOCKED;
    }
  }
  
  protected function signalling($signal, $trainID) { // Determine signalling for any signal in the route
  global $SIGNALLING_TXT;
    if ($this->routeLockingState == R_LOCKED) {
      if ($this->vacancyState == V_OCCUPIED and $this->occupationTrainID == $trainID) { // Check?? if $trainID != "" FIXME
      // Element occupied by assigned train only. Signalling calculation ends at assigned train. Don't search any further. Ignore occupation
        return $signal;
      } else { // Element clear or occupied by another train  
        $prevSignallingState = $this->signallingState;  
        $this->signallingState = ($this->vacancyState == V_CLEAR ? $signal : SIG_STOP);
        switch($this->routeLockingType) {
          case RT_START_POINT:
            if ($this->signallingState != $prevSignallingState) orderSignal($this->elementName, $this->signallingState);
            return $this->signallingState;
          break;
          case RT_VIA:
            switch($this->signallingState) {
              case SIG_STOP:
                $furtherSignalling = SIG_PROCEED;
              break;
              case SIG_PROCEED:
              case SIG_PROCEED_PROCEED:
                $furtherSignalling = SIG_PROCEED_PROCEED;          
              break;
              default:
                errLog("Error: Default value for signalling State in signalling/signal {$this->elementName} not defined.");
            }
            if ($this->signallingState != $prevSignallingState) orderSignal($this->elementName, $this->signallingState);
            return $this->routeLockingUp ?
              $this->neighbourDown->signalling($furtherSignalling, $trainID) :
              $this->neighbourUp->signalling($furtherSignalling, $trainID);
          break;
          case RT_VIA_REVERSE:
            return $this->routeLockingUp ?
              $this->neighbourDown->signalling($this->signallingState, $trainID) :
              $this->neighbourUp->signalling($this->signallingState, $trainID);
          break;
          default:
            $this->signallingState = SIG_ERROR;
            return $this->signallingState ;
        }
      }
    } else {
      $this->signallingState = SIG_NOT_LOCKED;
      return $this->signallingState;
    }
  }
  
  public function computeEOAdist($index) {
  global $PT2, $trainData;
    $train = $trainData[$index];
    return ($this->routeLockingUp ?
      $this->neighbourDown->EOAdist($train, $PT2[$this->elementName]["D"]["dist"], true, $this) :
      $this->neighbourUp->EOAdist($train, $PT2[$this->elementName]["U"]["dist"], true, $this));
  }

  protected function EOAdist($train, $EOAdist, $searchRoute, $caller) { // Signal
  // Starting from EOA (alias EP) follow route whilst locked ($searchRoute true), then search all tracks for LRBG ($searchRoute false)
  // Why not follow all tracks even in route?? FIXME
  global $PT2;
    $elementLength = $PT2[$this->elementName]["U"]["dist"] + $PT2[$this->elementName]["D"]["dist"];
    if ($searchRoute) { // searching within the route
      if ($this->routeLockingState != R_LOCKED) {
        return $caller == $this->neighbourUp ?
          $this->neighbourDown->EOAdist($train, $EOAdist + $elementLength, false, $this) :
          $this->neighbourUp->EOAdist($train, $EOAdist + $elementLength, false, $this);
      } else {
        if ($this->vacancyState == V_OCCUPIED and $this->occupationTrainID == $train["ID"]) { // Check?? if $trainID != "" FIXME
          // Signal occupied by the train - ignore signal state
          return $caller == $this->neighbourUp ?
            $this->neighbourDown->EOAdist($train, $EOAdist + $elementLength, $searchRoute, $this) :
            $this->neighbourUp->EOAdist($train, $EOAdist + $elementLength, $searchRoute, $this);          
        } else {
          switch ($this->routeLockingType) {
            case RT_START_POINT:
            case RT_VIA:
              if ($this->signallingState == SIG_PROCEED or $this->signallingState == SIG_PROCEED_PROCEED) {
                return $caller == $this->neighbourUp ?
                  $this->neighbourDown->EOAdist($train, $EOAdist + $elementLength, $searchRoute, $this) :
                  $this->neighbourUp->EOAdist($train, $EOAdist + $elementLength, $searchRoute, $this);          
              } else { // Signal is on stop, restart measurement of EOA
                return $caller == $this->neighbourUp ?
                  $this->neighbourDown->EOAdist($train, $PT2[$this->elementName]["D"]["dist"], $searchRoute, $this) :
                  $this->neighbourUp->EOAdist($train, $PT2[$this->elementName]["U"]["dist"], $searchRoute, $this);                  
              }
            break;
            case RT_VIA_REVERSE:
              return $caller == $this->neighbourUp ?
                $this->neighbourDown->EOAdist($train, $EOAdist + $elementLength, $searchRoute, $this) :
                $this->neighbourUp->EOAdist($train, $EOAdist + $elementLength, $searchRoute, $this);
            break;
            case RT_END_POINT:
              errLog("Error: EOAdist for signal {$this->elementName} locked as RT_END_POINT called from {$caller->elementName}");
              return false;
            break;
            default:
              print "Warning: EOAdist: default routeLockingType ({$this->routeLockingType}) not specified\n";
              return false;
          }
        }
      }
    } else { // search outside the route, ignoring locking state as that would be for another route
      return $caller == $this->neighbourUp ?
        $this->neighbourDown->EOAdist($train, $EOAdist + $elementLength, false, $this) :
        $this->neighbourUp->EOAdist($train, $EOAdist + $elementLength, false, $this);
    }
  }

  public function __construct($consName) {
  global $PT2;
    $this->elementName = $consName;
    $this->elementType = $PT2[$consName]["element"];
    $this->blockingState = B_NOT_BLOCKED;
    $this->facingUp = ($this->elementType == "SU");
    $this->assignedTrain = ""; // Valid only when locked as EP
  }
}

// =======================================================================================================================================

class BSelement extends genericElement { // ----------------------------------------------------------------------- Buffer Stop

  protected function setRouteTo($directionUp, $endPoint, $caller) {
    if ($this->routeLockingState != R_IDLE or $this->vacancyState != V_CLEAR) return false;
    if ($endPoint == $this->elementName) { // End point found
      $this->routeLockingState = R_LOCKED;
      $this->routeLockingType = RT_END_POINT;
      $this->routeLockingUp = $directionUp;
      $this->signallingState = SIG_STOP;
      return $this->elementName;
    }
    return "";
  }

  public function cmdReleaseRoute() { // Unconditional route release, Buffer Stop
  global $recCount;
    $recCount = 0; // FIXME
    if ($this->routeLockingState != R_IDLE and $this->routeLockingType == RT_END_POINT and $this->assignedTrain == "") {
      $this->facingUp ? $this->neighbourDown->releaseRoute($this) : $this->neighbourUp->releaseRoute($this);
      $this->routeLockingState = R_IDLE;
      $this->routeLockingType = RT_IDLE;
      $this->signallingState = SIG_NOT_LOCKED;
    }
  }

  public function cmdCloseRoute() { // Close signalling and await emergency release
    // Calling funciton must deassign train - if any - before calling
    if ($this->routeLockingState != R_IDLE and $this->routeLockingType == RT_END_POINT and $this->assignedTrain == "") {
      $this->facingUp ? $this->neighbourDown->closeRoute($this) : $this->neighbourUp->closeRoute($this);
      $this->routeLockingState = R_RELEASING;
      $this->signallingState = SIG_CLOSED;
      orderSignal($this->elementName, SIG_STOP);
    }
  }

  public function routeIsClear() {
    return $this->routeLockingState == R_IDLE or ($this->vacancyState == V_CLEAR and $this->routeLockingUp ?
      $this->neighbourDown->routeIsClear() : $this->neighbourUp->routeIsClear());
  }

  public function releaseElementTrack($drivingDirection) {
    // $drivingDirection indicates in which direction  (UP, DOWN, Udef) the train left the (track) extent of the element
    $this->vacancyState = V_CLEAR;
  }
  
  public function checkOccupationUp($trainIndex, $trainPositionUp, $trainPositionDown, $caller, $reportIndex) {
  global $PT2, $trainData;
    $elementLength = $PT2[$this->elementName]["D"]["dist"];
    $occupation = ($trainPositionDown < $elementLength ? array($reportIndex => $this->elementName) : array()); // train occupying this element?
    // if ($trainPositionUp > $elementLength) // Up position located further Up - train crashed into bufferstop
    return $occupation;
  }
  
  public function checkOccupationDown($trainIndex, $trainPositionUp, $trainPositionDown, $caller, $reportIndex) {
  global $PT2, $trainData;
    $elementLength = $PT2[$this->elementName]["U"]["dist"];
    $occupation = ($trainPositionUp > -$elementLength ? array($reportIndex => $this->elementName) : array()); // train occupying this element?
    // if ($trainPositionDown < -$elementLength) // Down position located further Down - train has crashed the buffer stop
    return $occupation;
  }

  public function searchSP($searchUp) {
    return $this->elementName; // Bufferstop cannot be a route start point, but is indicated as possible SP to be used by TMS
  }

  public function searchEP($searchUp) {
    return (($this->facingUp == $searchUp) and $this->routeLockingState == R_LOCKED and $this->routeLockingType == RT_END_POINT) ?
      $this->elementName : "";
  } 
  
  public function updateSignalling() { // To be called for EP, Buffer Stop
    if ($this->routeLockingState == R_LOCKED and $this->routeLockingType == RT_END_POINT) {
      // Vacancy state not included as the BS has no extent
      $this->signallingState = SIG_STOP;
      return ($this->routeLockingUp ?
        $this->neighbourDown->signalling(SIG_PROCEED, $this->assignedTrain) :
        $this->neighbourUp->signalling(SIG_PROCEED, $this->assignedTrain));  
    } else {
      $this->signallingState = SIG_NOT_LOCKED;
      return SIG_NOT_LOCKED;
    }
  }
  
  public function computeEOAdist($index) {
  global $PT2, $trainData;
    $train = $trainData[$index];
    return ($this->routeLockingUp ?
      $this->neighbourDown->EOAdist($train, $PT2[$this->elementName]["D"]["dist"], true, $this) :
      $this->neighbourUp->EOAdist($train, $PT2[$this->elementName]["U"]["dist"], true, $this));  
  }

  protected function EOAdist($train, $EOAdist, $searchRoute, $caller) { 
    return false; // LRBG search failed as track ends here
  }
      
  public function __construct($consName) {
  global $PT2;
    $this->elementName = $consName;
    $this->elementType = $PT2[$consName]["element"];
    $this->facingUp = ($this->elementType == "BSE"); // BufferStop End is seen as a facing (end point) signal
    $this->assignedTrain = ""; // Valid only when locked as EP
  }
}

// =======================================================================================================================================

class BGelement extends genericElement { // ------------------------------------------------------------------- Balise Group

  protected function EOAdist($train, $EOAdist, $searchRoute, $caller) { // Compute distance between LRBG and EOA.
  // Starting from EOA (alias EP) follow route whilst locked, then search all tracks for LRBG. Return dist when LRBG found - false otherwise
  // LRBG is assumed to be located at the same side of EOA as the train. If not EOAdist cannot be determined.
  global $PT2;
    if ($this->routeLockingState != R_LOCKED) $searchRoute = false;
    if ($train["baliseID"] == $PT2[$this->elementName]["ID"]) { // LRBG found
      return $EOAdist + ($caller == $this->neighbourUp ? $PT2[$this->elementName]["U"]["dist"] : $PT2[$this->elementName]["D"]["dist"]) ;
    } else {
      $elementLength = $PT2[$this->elementName]["U"]["dist"] + $PT2[$this->elementName]["D"]["dist"];
      return $caller == $this->neighbourUp ?
        $this->neighbourDown->EOAdist($train, $EOAdist + $elementLength, $searchRoute, $this) :
        $this->neighbourUp->EOAdist($train, $EOAdist + $elementLength, $searchRoute, $this);
    }
  }

  public function __construct($consName) {
    $this->elementName = $consName;
    $this->elementType = "BG";
  }
}

// =======================================================================================================================================

class TGelement extends genericElement { // ------------------------------------------------------------------- Trigger
  public function __construct($consName) {
    $this->elementName = $consName;
    $this->elementType = "TG";
  }
}

// =======================================================================================================================================

class PHTelement extends genericElement { // ------------------------------------------------------------------- Point Hold Trigger

  public function occupyElementTrack($trainID, $drivingDirection) {
    $this->vacancyState = V_OCCUPIED;
    $this->occupationTrainID = $trainID; // what if occupied by more trains?? ---------------------------------------------------- FIXME
    // Activate Point Hold FIXME
    switch ($drivingDirection) {
      case D_UP:
        if ($this->facingUp and !$this->pointToHold->pointHeld) {
          $this->pointToHold->pointHeld = true;
          $this->pointToHold->throwPoint(C_HOLD);
print "C_HOLD {$this->pointToHold->elementName}\n";
        }
      break;
      case D_DOWN:
        if (!$this->facingUp and !$this->pointToHold->pointHeld) {
          $this->pointToHold->pointHeld = true;
          $this->pointToHold->throwPoint(C_HOLD);
print "C_HOLD {$this->pointToHold->elementName}\n";
        }
      break;
      case D_STOP: // Ignore occupation
      break;
    }
  }

  public function __construct($consName) {
  global $PT2;
    $this->elementName = $consName;
    $this->elementType = $PT2[$consName]["element"];
    $this->facingUp = ($this->elementType == "PHTU"); // Point to be hold is located further up
  }
}

?>
