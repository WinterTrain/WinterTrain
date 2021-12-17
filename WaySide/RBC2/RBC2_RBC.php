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
// Dynamic properties are stored in the object while static data are in PT2  
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
  
  public function notLockedInRoute() {
    return $this->routeLockingState == R_IDLE and $this->routeLockingType == RT_IDLE; // More ?? FIXME
  }
  
  public function routeIsClear() {
    return $this->routeLockingState == R_IDLE 
      or ($this->vacancyState == V_CLEAR and $this->routeLockingUp ? $this->neighbourDown->routeIsClear() : $this->neighbourUp->routeIsClear());
  }
  
  public function occupyElementTrack($trainID) {
    $this->vacancyState = V_OCCUPIED;
    $this->occupationTrainID = $trainID; // what if occupied by more trains?? ---------------------------------------------------- FIXME
    // Apply consequences like LX deactivation FIXME
  }

  public function releaseElementTrack($drivingDirection) { // Sequensial route release
    // $drivingDirection indicates in which direction  (UP, DOWN, Udef) the train left the (track) extent of the element
    $this->vacancyState = V_CLEAR;
    if ($this->routeLockingState != R_IDLE) {
      switch ($drivingDirection){
        case D_UP:
          if ($this->routeLockingUp and $this->neighbourDown->notLockedInRoute()) { // other conditions ?? FIXME
            $this->routeLockingState = R_IDLE;
            $this->routeLockingType = RT_IDLE;
            $this->occupationTrainID = "";
          } // else Warning train moved against route direction FIXME
        break;
        case D_DOWN:
          if (!$this->routeLockingUp and $this->neighbourUp->notLockedInRoute()) { // other conditions ?? FIXME
            $this->routeLockingState = R_IDLE;
            $this->routeLockingType = RT_IDLE;
            $this->occupationTrainID = "";
          } // else Warning train moved against route direction FIXME
        break;
        default:
          // Warning train released element in unknown direction - what to do FIXME
          errLog("Warning Train released element {$this->elementName} in unknown direction");
        break;
      }
    }  // else route not locked - ignore element release
  }
  
  public function checkOccupationUp($trainIndex, $trainPositionUp, $trainPositionDown, $caller, $reportIndex) {
  global $PT2;
    $elementLength = $PT2[$this->elementName]["U"]["dist"] + $PT2[$this->elementName]["D"]["dist"];
//    print "checkUp:  $this->elementName: Up $trainPositionUp, Down $trainPositionDown, Length $elementLength\n";
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
//    print "checkDown:  $this->elementName: Up $trainPositionUp, Down $trainPositionDown, Length $elementLength\n";
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
    if ($recCount > 100) die("Recursion"); // FIXME
    if ($this->routeLockingState != R_IDLE or $this->vacancyState != V_CLEAR) return false;
    if (!($directionUp ? 
      $this->neighbourUp->setRouteTo(true, $endPoint, $this) : $this->neighbourDown->setRouteTo(false, $endPoint, $this))) return false;
    $this->routeLockingState = R_LOCKED;
    $this->routeLockingType = RT_VIA;
    $this->routeLockingUp = $directionUp;
    return true;
  }

  public function searchSP($searchUp) { // Search for possible route Start Point
    return $searchUp ? $this->neighbourUp->searchSP($searchUp) : $this->neighbourDown->searchSP($searchUp);
  }

  public function searchEP($searchUp) { // Search route for End Point
    return $searchUp ? $this->neighbourUp->searchEP($searchUp) : $this->neighbourDown->searchEP($searchUp);
  }
  
  protected function signalling($signal, $trainID) { // Determine signalling within the locked route
  global $SIGNALLING_TXT;
    if ($this->routeLockingState == R_LOCKED) {
      if ($this->vacancyState == V_OCCUPIED and $this->occupationTrainID == $trainID) {
        // Element occupied by assigned train only. Don't search any further. Ignore occupation in signalling
        debugPrint("Signalling for train $trainID at {$this->elementName}: {$SIGNALLING_TXT[$signal]}");
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

  protected function EOAdist($LRBG, $EOAdist, $searchRoute, $caller) { // Compute distance between LRBG and EOA.
  // Starting from EOA (alias EP) follow route whilst locked ($searchRoute true), then search all tracks for LRBG
  global $PT2;
    $elementLength = $PT2[$this->elementName]["U"]["dist"] + $PT2[$this->elementName]["D"]["dist"];
    if ($this->routeLockingState != R_LOCKED) $searchRoute = false;
    return $caller == $this->neighbourUp ?
      $this->neighbourDown->EOAdist($LRBG, $EOAdist + $elementLength, $searchRoute, $this) :
      $this->neighbourUp->EOAdist($LRBG, $EOAdist + $elementLength, $searchRoute, $this);
  }

  protected function releaseRoute($caller) { // unconditional route release
  global $recCount;
    $recCount +=1;
    if ($recCount > 100) die("Recursion"); // FIXME
    if ($this->routeLockingState != R_IDLE) {
      $this->routeLockingUp ? $this->neighbourDown->releaseRoute($this) : $this->neighbourUp->releaseRoute($this);
      $this->routeLockingState = R_IDLE;
      $this->routeLockingType = RT_IDLE;
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

class Pelement extends genericElement { // -------------------------------------------------------------------------------------- Point
  public $neighbourTip;
  public $neighbourRight;
  public $neighbourLeft;
  
  public $supervisionMode;              // Read from PT2
  public $pointState = P_UNSUPERVISED;  // Functional state of point being it from static configuration or physical state as reported from EC
  public $logicalLieRight = true;       // Logical lie is right when true. Expected lie
  
  private $throwLockedByConfiguration = false;   // Point throw is locked by configuration
  private $throwLockedByRoute = false;           // Point throw is locked due to position in locked route
  private $throwLockedByCmd = false;             // Point throw is locked by command
  
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
    $this->vacancyState = V_CLEAR;
    if ($this->routeLockingState != R_IDLE) {
      switch ($drivingDirection){
        case D_UP:
          if ($this->routeLockingUp and // other conditions ?? FIXME
              ($this->facingUp ?
                $this->neighbourTip->notLockedInRoute()
              :
                ($this->routeLockingType == RT_RIGHT ? $this->neighbourRight->notLockedInRoute() : $this->neighbourLeft->notLockedInRoute())
              )) { 
            $this->routeLockingState = R_IDLE;
            $this->routeLockingType = RT_IDLE;
            $this->throwLockedByRoute = false;
            $this->occupationTrainID = "";
          } // else Warning train moved against route direction FIXME
        break;
        case D_DOWN:
          if (!$this->routeLockingUp and  // other conditions ?? FIXME
              (!$this->facingUp ?
                $this->neighbourTip->notLockedInRoute() 
              :
                ($this->routeLockingType == RT_RIGHT ? $this->neighbourRight->notLockedInRoute() : $this->neighbourLeft->notLockedInRoute() ))) {
            $this->routeLockingState = R_IDLE;
            $this->routeLockingType = RT_IDLE;
            $this->throwLockedByRoute = false;
            $this->occupationTrainID = "";
          } // else Warning train moved against route direction FIXME
        break;
        default:
          // Warning train released element in unknown direction - what to do FIXME
          errLog("Warning Train released element {$this->elementName} in unknown direction");
        break;
      }
    }  // else route not locked - ignore element release
    // Deactivaate optional Point Hold FIXME
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
//print "{$this->elementName} Ambiguous??\n";
        if ($trainData[$trainIndex]["curPositionUnambiguous"]) { // Current occupation might be used to determin new occupation
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
//print "checkUpR:  $this->elementName: Up $trainPositionUp, Down $trainPositionDown, Length $elementLength\n";
      } else { // occupation search reached this point via left branch
        $elementLength = $PT2[$this->elementName]["T"]["dist"] + $PT2[$this->elementName]["L"]["dist"];          
//print "checkUpL:  $this->elementName: Up $trainPositionUp, Down $trainPositionDown, Length $elementLength\n";
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
//print "checkDownR:  $this->elementName: Up $trainPositionUp, Down $trainPositionDown, Length $elementLength\n";
      } else { // occupation search reached this point via left branch
        $elementLength = $PT2[$this->elementName]["T"]["dist"] + $PT2[$this->elementName]["L"]["dist"];          
//print "checkDownL:  $this->elementName: Up $trainPositionUp, Down $trainPositionDown, Length $elementLength\n";
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
        if ($trainData[$trainIndex]["curPositionUnambiguous"]) { // Current occupation might be used to determin new occupation
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
  
  protected function throwPoint($command) { // Throw point according to command
  global $PT2;
    if ($this->throwLockedByConfiguration or $this->throwLockedByRoute or $this->throwLockedByCmd or $this->vacancyState != V_CLEAR) return false;
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
              //  generate MA?? FIXME  Right place??
            }
          break;
          case C_RIGHT:
            $this->pointState = P_SUPERVISED_RIGHT;
            $this->logicalLieRight = true;
            if ($this->routeLockingState != R_IDLE and $this->routeLockingType == RT_RIGHT) {
              $this->throwLockedByRoute = true;
              //  generate MA?? FIXME  Right place??
            }
          break;
          case C_LEFT:
            $this->pointState = P_SUPERVISED_LEFT;
            $this->logicalLieRight = false;
            if ($this->routeLockingState != R_IDLE and $this->routeLockingType == RT_LEFT) {
              $this->throwLockedByRoute = true;
              //  generate MA?? FIXME  Right place??
            }            
          break;
          default: // no change
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
        $element = $PT2[$this->elementName];
        switch ($element["EC"]["type"]) {
          case 10: // point without feedback; no hold
            orderEC($element["EC"]["addr"], $element["EC"]["index"], $order);
            return true;
          break;
          default: // type of point machine not assigned or not implemented
            errLog("Point throw: Point machine type {$element["EC"]["type"]} not implemented");
            return false;
        }
      break;
      default:
        return false;
    }
  }
  
  
  protected function setRouteTo($directionUp, $endPoint, $caller) { // Set route further - called by neighbour
  global $automaticPointThrowEnabled, $recCount;
    $recCount +=1;
    if ($recCount > 100) die("Recursion"); // FIXME
    if ($this->routeLockingState != R_IDLE or $this->vacancyState != V_CLEAR) return false;
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
            if ($automaticPointThrowEnabled) $this->throwPoint(C_LEFT); // Throw left
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
            if ($automaticPointThrowEnabled) $this->throwPoint(C_RIGHT); // Throw right
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
            if ($automaticPointThrowEnabled) $this->throwPoint(C_RIGHT); // Throw right
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
            if ($automaticPointThrowEnabled) $this->throwPoint(C_LEFT); // Throw left
          }
          return true;
        }
        return false;
      }
    }    
  }
  
  protected function releaseRoute($caller) {
  global $recCount;
    $recCount +=1;
    if ($recCount > 100) die("Recursion"); // FIXME
    if ($this->routeLockingState == R_IDLE) return;
    if ($this->routeLockingUp == $this->facingUp) { // Point is locked facing in route
      if (($caller == $this->neighbourRight and $this->routeLockingType == RT_RIGHT) or
        ($caller == $this->neighbourLeft and $this->routeLockingType == RT_LEFT)) {
        $this->neighbourTip->releaseRoute($this);
        $this->routeLockingState = R_IDLE;
        $this->routeLockingType = RT_IDLE;
        $this->throwLockedByRoute = false;
      }
    } else { // Point is locked trailing in route
      $this->routeLockingType == RT_RIGHT ? $this->neighbourRight->releaseRoute($this) : $this->neighbourLeft->releaseRoute($this);
      $this->routeLockingState = R_IDLE;
      $this->routeLockingType = RT_IDLE;
      $this->throwLockedByRoute = false;
    }
  }

  public function searchSP($searchUp) {
    return ""; // Points are not allowed between train and Start Point of route FIXME
  }
  
  public function searchEP($searchUp) {
    if ($this->routeLockingUp == $this->facingUp) { // Point is facing in route direction
      return $this->routeLockingType == RT_RIGHT ? 
        $this->neighbourRight->searchEP($searchUp) : $this->neighbourLeft->searchEP($searchUp);
    } else { // Point is trailing in route
      return $this->neighbourTip->searchEP($searchUp);
    }
  }
  
  protected function signalling($signal, $trainID) {
  global $SIGNALLING_TXT;
    if ($this->routeLockingState == R_LOCKED) {
      if ($this->vacancyState == V_OCCUPIED and $this->occupationTrainID == $trainID) {
        // Element occupied by assigned train only. Don't search any further. Ignore occupation
        debugPrint("Signalling for train $trainID at {$this->elementName}: {$SIGNALLING_TXT[$signal]}");
        return $signal;
      } else { // Element clear or occupied by another train
        $thisSignal = (($this->vacancyState == V_CLEAR and $this->throwLockedByRoute and 
          ($this->logicalLieRight ? $this->pointState == P_SUPERVISED_RIGHT : $this->pointState == P_SUPERVISED_LEFT)) ? 
            $signal : 
            SIG_STOP);
        return $this->routeLockingUp == $this->facingUp ?
        // Locked facing in route
          ($this->neighbourTip->signalling($thisSignal, $trainID)) :
        // Locked trailing
          ($this->routeLockingType == RT_RIGHT ?
            $this->neighbourRight->signalling($thisSignal, $trainID) :
            $this->neighbourLeft->signalling($thisSignal, $trainID));
      }
    } else {
      return SIG_NOT_LOCKED;
    }
  }

  protected function EOAdist($LRBG, $EOAdist, $searchRoute, $caller) {
  global $PT2;
    if ($this->routeLockingState != R_LOCKED) $searchRoute = false;
    if ($searchRoute) { // Search within the route
      return $this->routeLockingUp == $this->facingUp ? 
        // Locked facing in route
        ($this->neighbourTip->EOAdist($LRBG,
          $EOAdist + $PT2[$this->elementName]["T"]["dist"] +
          ($this->routeLockingType == RT_RIGHT ?
            $PT2[$this->elementName]["R"]["dist"] : $PT2[$this->elementName]["L"]["dist"]), $searchRoute, $this)) :
        // Locked trailing in route
        ($this->routeLockingType == RT_RIGHT ?
          $this->neighbourRight->EOAdist($LRBG,
            $EOAdist + $PT2[$this->elementName]["T"]["dist"] + $PT2[$this->elementName]["R"]["dist"], $searchRoute, $this) :
          $this->neighbourLeft->EOAdist($LRBG,
            $EOAdist + $PT2[$this->elementName]["T"]["dist"] + $PT2[$this->elementName]["L"]["dist"], $searchRoute, $this));
    } else { // Search in rear of (before) route
      if ($caller == $this->neigbourTip) { // search in both branches
        $EOAdistRight = $this->neighbourRight->EOAdist($LRBG, $EOAdist + $PT2[$this->elementName]["T"]["dist"] +
          $PT2[$this->elementName]["R"]["dist"], $searchRoute, $this);
        $EOAdistLeftt = $this->neighbourLeft->EOAdist($LRBG, $EOAdist + $PT2[$this->elementName]["T"]["dist"] +
          $PT2[$this->elementName]["L"]["dist"], $searchRoute, $this);
        if ($EOAdistRight === false) {
          if ($EOAdistleft === false) { // LRBG not found in any branch
            return false;
          } else {
            return $EOAdistLeft;
          }
        } else {
          if ($EOAdistleft === false) {
            return $EOAdistRight;
          } else {  // LRBG found in both branches - distance ambiguous          
            return false;
          }
        }
      } else { // Search via tip
        return $this->neighbourTip->EOAdist($LRBG, $EOAdist + $PT2[$this->elementName]["T"]["dist"] +
          ($caller == $this->neighbourRight ? $PT2[$this->elementName]["R"]["dist"] : $PT2[$this->elementName]["L"]["dist"]),
          $searchRoute, $this);
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
  global $trainData, $trainIndex, $trackModel;
    if ($this->routeLockingState != R_IDLE and $this->routeLockingType != RT_END_POINT) return false;
    if ($this->blockingState == B_BLOCKED_START_VIA) return false;
    if ($this->facingUp ? $this->neighbourUp->setRouteTo(true, $endPoint, $this) : $this->neighbourDown->setRouteTo(false, $endPoint, $this)) {
      if ($this->routeLockingType == RT_END_POINT) { // Already End Point
        $this->routeLockingState = R_LOCKED; // FIXME  state could already be != IDLE
        $this->routeLockingType = RT_VIA;
        if ($this->assignedTrain != "") { // EP already assigned to train. Update assignment
          $trainData[$trainIndex[$this->assignedTrain]]["assignedRoute"] = $endPoint;
          $trackModel[$endPoint]->assignedTrain = $this->assignedTrain;
          $this->assignedTrain = "";
        }
      } else {
        $this->routeLockingState = R_LOCKED;
        $this->routeLockingType = RT_START_POINT;
      }
      $this->routeLockingUp = $this->facingUp;;
      return true;
    } 
    return false;
  }
  
  public function cmdReleaseRoute() { // Unconditional route release
    if ($this->routeLockingType == RT_END_POINT) {
      $this->facingUp ? $this->neighbourDown->releaseRoute($this) : $this->neighbourUp->releaseRoute($this);
      $this->routeLockingState = R_IDLE;
      $this->routeLockingType = RT_IDLE;
      return true;
    } else {
      return false;
    }
  }
  
  public function routeIsClear() {
    return $this->routeLockingState == R_IDLE or ($this->vacancyState == V_CLEAR and
      ($this->routeLockingType == RT_START_POINT or
        $this->routeLockingUp ? $this->neighbourDown->routeIsClear() : $this->neighbourUp->routeIsClear()));
  }
  
  public function releaseElementTrack($drivingDirection) {
    $this->vacancyState = V_CLEAR;
    if ($this->routeLockingState != R_IDLE) {
      switch ($drivingDirection){
        case D_UP:
          if ($this->routeLockingUp) {
            switch ($this->routeLockingType) {
              case RT_START_POINT:
                // Close signal FIXME
                $this->routeLockingState = R_IDLE;
                $this->routeLockingType = RT_IDLE;
                $this->occupationTrainID = "";
              break;
              case RT_VIA:
                if ($this->neighbourDown->notLockedInRoute()) { // other conditions FIXME
                  // Close signal FIXME
                  $this->routeLockingState = R_IDLE;
                  $this->routeLockingType = RT_IDLE;
                  $this->occupationTrainID = "";
                } // else Warning: element release in the middle of a route - ignored
              break;
              case RT_VIA_REVERSE:
                if ($this->neighbourDown->notLockedInRoute()) { // other conditions FIXME
                  $this->routeLockingState = R_IDLE;
                  $this->routeLockingType = RT_IDLE;
                  $this->occupationTrainID = "";
                } // else Warning: element release in the middle of a route - ignored
              break;
              case RT_END_POINT:
                // train continued passing the end point signal - what to do?? FIXME
                if ($this->neighbourDown->notLockedInRoute()) { // other conditions FIXME
                  $this->routeLockingState = R_IDLE;
                  $this->routeLockingType = RT_IDLE;
                  $this->occupationTrainID = "";
                } // else Warning: element release in the middle of a route - ignored
              break;
              default: // FIXME
                errLog("Sgnal, releaseElementTrack: Default routelocking type {$this->routeLockingType} not implemented");
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
                // Close signal FIXME
                $this->routeLockingState = R_IDLE;
                $this->routeLockingType = RT_IDLE;
                $this->occupationTrainID = "";
              break;
              case RT_VIA:
                if ( $this->neighbourUp->notLockedInRoute() ) { // other conditions FIXME
                  // Close signal FIXME
                  $this->routeLockingState = R_IDLE;
                  $this->routeLockingType = RT_IDLE;
                  $this->occupationTrainID = "";
                } // else Warning: element release in the middle of a route - ignored
              break;
              case RT_VIA_REVERSE:
                if ( $this->neighbourUp->notLockedInRoute() ) { // other conditions FIXME
                  $this->routeLockingState = R_IDLE;
                  $this->routeLockingType = RT_IDLE;
                  $this->occupationTrainID = "";
                } // else Warning: element release in the middle of a route - ignored
              break;
              case RT_END_POINT:
                // train continued passing the end point signal - what to do?? FIXME
                if ( $this->neighbourUp->notLockedInRoute() ) { // other conditions FIXME
                  $this->routeLockingState = R_IDLE;
                  $this->routeLockingType = RT_IDLE;
                  $this->occupationTrainID = "";
                } // else Warning: element release in the middle of a route - ignored
              break;
              default: // FIXME
              break;
            }        
          } // else Warning train moved against route direction FIXME
        break;
        default: // moving direction unknown - ignore release
          errLog("Warning Train released element {$this->elementName} in unknown direction"); // what to do FIXME
        break;
      }
    } // else route not locked - ignore element release
  }

  protected function setRouteTo($directionUp, $endPoint, $caller) {
  global $recCount;
    $recCount +=1;
    if ($recCount > 100) die("Recursion"); // FIXME
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
        if ($this->routeLockingState != R_IDLE or $this->blockingState == B_BLOCKED_START_VIA or $this->vacancyState != V_CLEAR) return false;
        if ($this->facingUp ? $this->neighbourUp->setRouteTo(true, $endPoint, $this) : $this->neighbourDown->setRouteTo(false, $endPoint, $this)) {
          $this->routeLockingState = R_LOCKED;
          $this->routeLockingType = RT_VIA;
          $this->routeLockingUp = $directionUp;
          return true;
        } 
        return false; 
      }
    } else { // Signal is reverse in route direction
      if ($this->routeLockingState != R_IDLE or $this->vacancyState != V_CLEAR) return false;
      if ($directionUp ? $this->neighbourUp->setRouteTo(true, $endPoint, $this) : $this->neighbourDown->setRouteTo(false, $endPoint, $this)) {
        $this->routeLockingState = R_LOCKED;
        $this->routeLockingType = RT_VIA_REVERSE;
        $this->routeLockingUp = $directionUp;
        return true;
      } 
      return false;
    }
  }

  public function searchSP($searchUp) {
    if ($this->facingUp == $searchUp) { // Signal is facing
      return $this->elementName;
    } else {
      return $searchUp ? $this->neighbourUp->searchSP($searchUp) : $this->neighbourDown->searchSP($searchUp);
    }
  }

  public function searchEP($searchUp) {
    if (($this->facingUp == $searchUp) and $this->routeLockingState == R_LOCKED and $this->routeLockingType == RT_END_POINT) {
      return $this->elementName;
    } else {
      return $searchUp ? $this->neighbourUp->searchEP($searchUp) : $this->neighbourDown->searchEP($searchUp);
    }  
  }
  
  public function checkSignalling($trainID) {
    if ($this->routeLockingState == R_LOCKED and $this->routeLockingType == RT_END_POINT) {
      // Vacancy state not included as the signal has no extent on the facing side 
      return ($this->routeLockingUp ?
        $this->neighbourDown->signalling(SIG_PROCEED, $trainID) :
        $this->neighbourUp->signalling(SIG_PROCEED, $trainID));  
    } else {
      return SIG_NOT_LOCKED;
    }
  }
  
  protected function signalling($signal, $trainID) {
  global $SIGNALLING_TXT;
    if ($this->routeLockingState == R_LOCKED) {
      if ($this->vacancyState == V_OCCUPIED and $this->occupationTrainID == $trainID) {
      // Element occupied by assigned train only. Don't search any further. Ignore occupation
        debugPrint("Signalling for train $trainID at {$this->elementName}: {$SIGNALLING_TXT[$signal]}");
        return $signal;
      } else { // Element clear or occupied by another train    
        $thisSignal = ($this->vacancyState == V_CLEAR ? $signal : SIG_STOP);
        switch($this->routeLockingType) {
          case RT_START_POINT:
            debugPrint("Signalling for train $trainID at {$this->elementName}: {$SIGNALLING_TXT[$thisSignal]}");
// EC command
            return $thisSignal;
          break;
          case RT_VIA:
            debugPrint("Signalling for train $trainID at {$this->elementName} (via): {$SIGNALLING_TXT[$thisSignal]}");
// EC command
            switch($signal) {
              case SIG_STOP:
                $thisSignal = SIG_PROCEED;
              break;
              case SIG_PROCEED:
              case SIG_PROCEED_PROCEED:
                $thisSignal = SIG_PROCEED_PROCEED;          
              break;
            }
            return $this->routeLockingUp ?
              $this->neighbourDown->signalling($thisSignal, $trainID) :
              $this->neighbourUp->signalling($thisSignal, $trainID);
          
          break;
          case RT_VIA_REVERSE:
            $thisSignal = ($this->vacancyState == V_CLEAR ? $signal : SIG_STOP);
            return $this->routeLockingUp ?
              $this->neighbourDown->signalling($thisSignal, $trainID) :
              $this->neighbourUp->signalling($thisSignal, $trainID);
          break;
          default:
            return SIG_ERROR;
        }
      }
    } else {
      return SIG_NOT_LOCKED;
    }
  }
  
  public function computeEOAdist($index) {
    global $PT2, $trainData;
    return ($this->routeLockingUp ?
      $this->neighbourDown->EOAdist($trainData[$index]["baliseID"], $PT2[$this->elementName]["D"]["dist"], true, $this) :
      $this->neighbourUp->EOAdist($trainData[$index]["baliseID"], $PT2[$this->elementName]["U"]["dist"], true, $this));
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

class BSelement extends genericElement { // ----------------------------------------------------------------------- Buffer Stop

  protected function setRouteTo($directionUp, $endPoint, $caller) {
    if ($this->routeLockingState != R_IDLE or $this->vacancyState != V_CLEAR) return false;
    if ($endPoint == $this->elementName) { // End point found
      $this->routeLockingState = R_LOCKED;
      $this->routeLockingType = RT_END_POINT;
      $this->routeLockingUp = $directionUp;
      return true;
    }
    return false;
  }

  public function cmdReleaseRoute() {
    if ($this->routeLockingType == RT_END_POINT) {
      $this->facingUp ? $this->neighbourDown->releaseRoute($this) : $this->neighbourUp->releaseRoute($this);
      $this->routeLockingState = R_IDLE;
      $this->routeLockingType = RT_IDLE;
      return true;
    } else {
      return false;
    }
  }

  public function routeIsClear() {
  
    return $this->routeLockingState == R_IDLE or ($this->vacancyState == V_CLEAR and $this->routeLockingUp ? $this->neighbourDown->routeIsClear() : $this->neighbourUp->routeIsClear());
  }

  public function releaseElementTrack($drivingDirection) {
    // $drivingDirection indicates in which direction  (UP, DOWN, Udef) the train left the (track) extent of the element
    $this->vacancyState = V_CLEAR;
// Release approach area before start signal FIXME
  }
  
  public function checkOccupationUp($trainIndex, $trainPositionUp, $trainPositionDown, $caller, $reportIndex) {
  global $PT2, $trainData;
    $elementLength = $PT2[$this->elementName]["D"]["dist"];
//print "checkUp:  $this->elementName: Up $trainPositionUp, Down $trainPositionDown, Length $elementLength\n";
    $occupation = ($trainPositionDown < $elementLength ? array($reportIndex => $this->elementName) : array()); // train occupying this element?
    if ($trainPositionUp > $elementLength) { // Up position located further Up - train crashed into bufferstop
//      msgLog("Warning: Train {$trainData[$trainIndex]["ID"]} crashed into bufferstop {$this->elementName} according to position report "); 
// print only if this was the real train (and not the search function FIXME
    }
    return $occupation;
  }
  
  public function checkOccupationDown($trainIndex, $trainPositionUp, $trainPositionDown, $caller, $reportIndex) {
  global $PT2, $trainData;
    $elementLength = $PT2[$this->elementName]["U"]["dist"];
//print "checkDown:  $this->elementName: Up $trainPositionUp, Down $trainPositionDown, Length $elementLength\n";
    $occupation = ($trainPositionUp > -$elementLength ? array($reportIndex => $this->elementName) : array()); // train occupying this element?
    if ($trainPositionDown < -$elementLength) { // Down position located further Down - train has crashed the buffer stop
//      msgLog("Warning: Train {$trainData[$trainIndex]["ID"]} crashed into bufferstop {$this->elementName} according to position report ");
// print only if this was the real train (and not the search function) FIXME
    }
    return $occupation;
  }

  public function searchSP($searchUp) {
    return ""; // Bufferstop cannot be a route start point
  }

  public function searchEP($searchUp) {
    return (($this->facingUp == $searchUp) and $this->routeLockingState == R_LOCKED and $this->routeLockingType == RT_END_POINT) ?
      $this->elementName : "";
  } 
  
  public function checkSignalling($trainID) {
    if ($this->routeLockingState == R_LOCKED and $this->routeLockingType == RT_END_POINT) {
      $thisSignal = ($this->vacancyState == V_CLEAR ? SIG_PROCEED : SIG_STOP); // Vacancy state is included for BufferStop
      return ($this->routeLockingUp ?
        $this->neighbourDown->signalling($thisSignal, $trainID) :
        $this->neighbourUp->signalling($thisSignal, $trainID));
    } else {
      return SIG_NOT_LOCKED;
    }
  }
  
  public function computeEOAdist($index) {
  global $PT2, $trainData;
    return ($this->routeLockingUp ?
      $this->neighbourDown->EOAdist($trainData[$index]["baliseID"], $PT2[$this->elementName]["D"]["dist"], true, $this) :
      $this->neighbourUp->EOAdist($trainData[$index]["baliseID"], $PT2[$this->elementName]["U"]["dist"], true, $this));  
  }

  protected function EOAdist($LRBG, $EOAdist, $searchRoute, $caller) { 
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

class BGelement extends genericElement { // ------------------------------------------------------------------- Balise Group

  protected function EOAdist($LRBG, $EOAdist, $searchRoute, $caller) { // Compute distance between LRBG and EOA.
  // Starting from EOA (alias EP) follow route whilst locked, then search all tracks for LRBG. Return dist when LRBG found - false otherwise
  // LRBG is assumed to be located at the same side of EOA as the train. If not EOAdist cannot be determined.
  global $PT2;
    if ($this->routeLockingState != R_LOCKED) $searchRoute = false;
    if ($LRBG == $PT2[$this->elementName]["ID"]) { // LRBG found
      return $EOAdist + ($caller == $this->neighbourUp ? $PT2[$this->elementName]["U"]["dist"] : $PT2[$this->elementName]["D"]["dist"]) ;
    } else {
      $elementLength = $PT2[$this->elementName]["U"]["dist"] + $PT2[$this->elementName]["D"]["dist"];
      return $caller == $this->neighbourUp ?
        $this->neighbourDown->EOAdist($LRBG, $EOAdist + $elementLength, $searchRoute, $this) :
        $this->neighbourUp->EOAdist($LRBG, $EOAdist + $elementLength, $searchRoute, $this);
    }
  }

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

  public function occupyElementTrack($trainID) {
    $this->vacancyState = V_OCCUPIED;
    $this->occupationTrainID = $trainID; // what if occupied by more trains?? ---------------------------------------------------- FIXME
    // Activate Point Hold FIXME
  }


  public function __construct($consName) {
  global $PT2;
    $this->elementName = $consName;
    $this->elementType = $PT2[$consName]["element"];
  }
}
// -------- End of object oriented Track Model ----------------------------------------------------------------------------------------------


function generateModeAuthority($index) { // -------------------------------------- Generate mode authority for specific train
global $trainData, $trackModel, $allowSR, $allowSH, $allowFS, $allowATO, $emergencyStop;
//print "Generate MA for train {$trainData[$index]["ID"]}\n";
  $train = &$trainData[$index];
  switch ($train["reqMode"]) {
    case M_N:
      $train["authMode"] = M_N;
      $train["MAdir"] = MD_NODIR;
      $train["MAbalise"] = "00:00:00:00:00";
      $train["MAbaliseName"] = "(00:00:00:00:00)";  // Assign MAbaliseName  and dist based on MAbalise at end of function FIXME
      $train["MAdist"] = 0;
      if ($train["assignedRoute"] != "") { // if route assigned to train: unassign route
        $trackModel[$train["assignedRoute"]]->assignedTrain = "";
        $train["assignedRoute"] = "";
      }
    break;
    case M_SR:
      $train["authMode"] = M_N;
      $train["MAdir"] = MD_NODIR;
      $train["MAbalise"] = "00:00:00:00:00";
      $train["MAbaliseName"] = "(00:00:00:00:00)";
      $train["MAdist"] = 0;
      if ($allowSR and $train["SRallowed"]) {
        $train["authMode"] = M_SR;
        $train["maxSpeed"] = $train["SRmaxSpeed"];
        $train["MAdir"] = MD_BOTH;
      }
      if ($train["assignedRoute"] != "") { // if route assigned to train: unassign route
        $trackModel[$train["assignedRoute"]]->assignedTrain = "";
        $train["assignedRoute"] = "";
      }
    break;
    case M_SH:
      $train["authMode"] = M_N;
      $train["MAdir"] = MD_NODIR;
      $train["MAbalise"] = "00:00:00:00:00";
      $train["MAbaliseName"] = "(00:00:00:00:00)";
      $train["MAdist"] = 0;
      if ($allowSH and $train["SHallowed"]) {
        $train["authMode"] = M_SH;
        $train["maxSpeed"] = $train["SHmaxSpeed"];
        $train["MAdir"] = MD_BOTH;
      }
      if ($train["assignedRoute"] != "") { // if route assigned to train: unassign route
        $trackModel[$train["assignedRoute"]]->assignedTrain = "";
        $train["assignedRoute"] = "";
      }
    break;
    case M_FS:
      if  ($allowFS and $train["FSallowed"]) {
        $train["authMode"] = M_FS;
        $train["maxSpeed"] = $train["FSmaxSpeed"];
        if ($train["curPositionUnambiguous"]) { // search for possible SP in both directions - to be used also by TMS
          $extentUp = -1000; 
          foreach ($train["curOccupation"] as $i => $pos) { 
            if ($i > $extentUp) $extentUp = $i;
          }
          $extentDown = 1000; 
          foreach ($train["curOccupation"] as $i => $pos) {
            if ($i < $extentDown) $extentDown = $i;
          }
          $occupiedElementUp = $trackModel[$train["curOccupation"][$extentUp]];
          $occupiedElementDown = $trackModel[$train["curOccupation"][$extentDown]];
          // Check for SP in direction Up
          switch ($occupiedElementUp->elementType) {
            case "BSB":
              $SPup = $occupiedElementUp->neighbourUp->searchSP(true);
            break;
            case "BSE":
              $SPup = "";
            break;
            case "PF":
              switch ($occupiedElementUp->pointState) {
                case P_SUPERVISED_RIGHT:
                  $SPup = $occupiedElementUp->neighbourRight->searchSP(true);
                break;
                case P_SUPERVISED_LEFT:
                  $SPup = $occupiedElementUp->neighbourLeft->searchSP(true);
                break;
                default:
                  $SPup = "";
                break;
              }
            break;
            case "PT":
              $SPup = $occupiedElementUp->neighbourTip->searchSP(true);
            break;
            default:
              $SPup = $occupiedElementUp->neighbourUp->searchSP(true);
            break;
          }
          // Check for SP in direction Down
          switch ($occupiedElementDown->elementType) {
            case "BSB":
              $SPdown = "";
            break;
            case "BSE":
              $SPdown = $occupiedElementDown->neighbourDOwn->searchSP(false);
            break;
            case "PT":
              switch ($occupiedElementDown->pointState) {
                case P_SUPERVISED_RIGHT:
                  $SPdown = $occupiedElementDown->neighbourRight->searchSP(false);
                break;
                case P_SUPERVISED_LEFT:
                  $SPdown = $occupiedElementDown->neighbourLeft->searchSP(false);
                break;
                default:
                  $SPdown = "";
                break;
              }
            break;
            case "PF":
              $SPdown = $occupiedElementDown->neighbourTip->searchSP(false);
            break;
            default:
              $SPdown = $occupiedElementDown->neighbourDown->searchSP(false);
            break;
          }
//print "SPup: $SPup, SPdown: $SPdown\n";   // inform TMS FIXME
  // What if occupied element (or next element) already is locked in route assigned to another train?----------------------------------- FIXME
          
          switch ($train["nomDir"]) { // Assign route according to requested nomDir
            case D_UDEF:
            case D_STOP: // End of Mission
              $train["MAdir"] = MD_NODIR;
              $train["MAbalise"] = "00:00:00:00:00";
              $train["MAbaliseName"] = "(00:00:00:00:00)";
              $train["MAdist"] = 0;
              if ($train["assignedRoute"] != "") { // Deassign route if assigned
                $trackModel[$train["assignedRoute"]]->assignedTrain = "";
                $train["assignedRoute"] = "";
              }
              $routeEP = "";
            break;
            case D_UP: 
              if ($train["assignedRoute"] != "" and $train["MAdir"] == MD_DOWN) { // Deassign route for direction down
                $trackModel[$train["assignedRoute"]]->assignedTrain = "";
                $train["assignedRoute"] = "";
              }
              if ($train["assignedRoute"] == "") { // Start of Mission Up
                if ($SPup != "" and $trackModel[$SPup]->routeLockingState == R_LOCKED
                  and $trackModel[$SPup]->routeLockingType == RT_START_POINT) { // locked SP found in req. direction, search for EP and assign.
                  $routeEP = $trackModel[$SPup]->searchEP(true);
                  $train["assignedRoute"] = $routeEP;
                  $trackModel[$routeEP]->assignedTrain = $train["ID"];
//print "RouteEP $routeEP assigned to train {$train["ID"]}\n";
                  generateMovementAuthority($index);
                } else { //SP not locked, skip MA request
                  $train["MAdir"] = MD_NODIR;
                  $train["MAbalise"] = "00:00:00:00:00";
                  $train["MAbaliseName"] = "(00:00:00:00:00)";
                  $train["MAdist"] = 0;
                }
              } else { // route already assigned, update MA
                generateMovementAuthority($index);
              }
            break;
            case D_DOWN: 
              if ($train["assignedRoute"] != "" and $train["MAdir"] == MD_UP) { // Deassign route for direction up
                $trackModel[$train["assignedRoute"]]->assignedTrain = "";
                $train["assignedRoute"] = "";
              }
              if ($train["assignedRoute"] == "") { // Start of Mission Down
                if ($SPdown != "" and $trackModel[$SPdown]->routeLockingState == R_LOCKED
                  and $trackModel[$SPdown]->routeLockingType == RT_START_POINT) { // locked SP found in req. direction, search for EP and assign.
                  $routeEP = $trackModel[$SPdown]->searchEP(false);
                  $train["assignedRoute"] = $routeEP;
                  $trackModel[$routeEP]->assignedTrain = $train["ID"];
//print "RouteEP $routeEP assigned to train {$train["ID"]}\n";
                  generateMovementAuthority($index);
                } else { //SP not locked, skip MA request
                  $train["MAdir"] = MD_NODIR;
                  $train["MAbalise"] = "00:00:00:00:00";
                  $train["MAbaliseName"] = "(00:00:00:00:00)";
                  $train["MAdist"] = 0;
                }
              } else { // route already assigned, update MA
                generateMovementAuthority($index);
              }
            break;
          }            
        } else { // Position ambiguous, reject MA request
          $train["MAdir"] = MD_NODIR;
          $train["MAbalise"] = "00:00:00:00:00";
          $train["MAbaliseName"] = "(00:00:00:00:00)";
          $train["MAdist"] = 0;
        }          
      } else { // FS not allowed
        $train["authMode"] = M_N;
        $train["MAdir"] = MD_NODIR;
        $train["MAbalise"] = "00:00:00:00:00";
        $train["MAbaliseName"] = "(00:00:00:00:00)";
        $train["MAdist"] = 0;
      }      
    break;
    case M_ATO:
      if ($allowATO and $train["ATOallowed"]) {
        $train["authMode"] = M_ATO;
        $train["maxSpeed"] = $train["ATOmaxSpeed"];
        if ($SPup != "" and $trackModel[$SPup]->routeLockingState == R_LOCKED
          and $trackModel[$SPup]->routeLockingType == RT_START_POINT) { // locked SP found in req. direction, search for EP and assign.
          $routeEP = $trackModel[$SPup]->searchEP(true);
          $train["assignedRoute"] = $routeEP;
          $trackModel[$routeEP]->assignedTrain = $train["ID"];
          debugPrint("RouteEP $routeEP assigned to train {$train["ID"]} ATO");
          generateMovementAuthority($index);
        } elseif ($SPdown != "" and $trackModel[$SPdown]->routeLockingState == R_LOCKED
          and $traeckModel[$SPdown]->routeLockingType == RT_START_POINT) { // locked SP found in req. direction, search for EP and assign.
          $routeEP = $trackModel[$SPdown]->searchEP(false);
          $train["assignedRoute"] = $routeEP;
          $trackModel[$routeEP]->assignedTrain = $train["ID"];
          debugPrint("RouteEP $routeEP assigned to train {$train["ID"]} ATO");
          generateMovementAuthority($index);
        } // else no route available for ATO
      } else { // ATO not allowed
        $train["authMode"] = M_N;
        $train["MAdir"] = MD_NODIR;
        $train["MAbalise"] = "00:00:00:00:00";
        $train["MAbaliseName"] = "(00:00:00:00:00)";
        $train["MAdist"] = 0;
      }  
    break;
  }
  sendMA($index, ($emergencyStop ? M_ESTOP : $train["authMode"]), $train["MAdir"], $train["MAbalise"], $train["MAdist"], $train["maxSpeed"]);
}

function generateMovementAuthority($index) {
  global $trainData, $trackModel, $TD_TXT_MADIR, $balisesID;
  $train = &$trainData[$index];
  if ($train["assignedRoute"] != "") {
    switch ($trackModel[$train["assignedRoute"]]->checkSignalling($train["ID"])) {
      case SIG_UDEF:
      case SIG_ERROR:
        $train["MAdir"] = MD_NODIR;
        $train["MAbalise"] = "00:00:00:00:00";
        $train["MAbaliseName"] = "(00:00:00:00:00)";
        $train["MAdist"] = 0;
        errLog("Errorg: Movement Authority could not be determined for train ID {$train["ID"]} on route to EP {$train["assignedRoute"]}".
          " - SW error");
      break;
      case SIG_STOP:
        $train["MAdir"] = MD_NODIR;
        $train["MAbalise"] = "00:00:00:00:00";
        $train["MAbaliseName"] = "(00:00:00:00:00)";
        $train["MAdist"] = 0;
      break;
      case SIG_PROCEED:
      case SIG_PROCEED_PROCEED:
        $train["MAdir"] = ($trackModel[$train["assignedRoute"]]->facingUp ? MD_UP : MD_DOWN);
        $train["MAbalise"] = $train["baliseID"];
        $train["MAbaliseName"] = $balisesID[$train["baliseID"]];
        $EOAdist = $trackModel[$train["assignedRoute"]]->computeEOAdist($index);
        if ($EOAdist !== false) {
          $train["MAdist"] = $EOAdist - ($train["front"] == D_UP ? $train["lengthFront"] : $train["lengthBehind"]);
        } else {
          errLog("Error: LRBG ({$train["baliseID"]}) not found while computing EOA dist for train {$train["ID"]}".
            " on route to EP {$train["assignedRoute"]}");
          $train["MAdir"] = MD_NODIR;
          $train["MAdist"] = 0;
          $train["MAbalise"] = "00:00:00:00:00";
          $train["MAbaliseName"] = "(00:00:00:00:00)";
        }
//print "MA: Train: {$train["ID"]} MAbalise: {$train["MAbalise"]} MAdist: {$train["MAdist"]} MAdir: ".$TD_TXT_MADIR[$train["MAdir"]]."\n";
      break;
      default:
        errLog("Error: unknown signalling in route EP {$train["assignedRoute"]} for train {$train["ID"]}");
    }
  } else {
    $train["MAdir"] = MD_NODIR;
    $train["MAbalise"] = "00:00:00:00:00";
    $train["MAbaliseName"] = "(00:00:00:00:00)";
    $train["MAdist"] = 0;
    errLog("Error: GenerateMovementAuthority() called for train {$train["ID"]}, but no route was assigned to train");
  }
}

function sendMA($index, $authMode, $MAdir, $balise, $dist, $speed) { // To be moved to generateMA() ?? FIXME
  global  $trainData, $TD_TXT_MODE, $radioInterface;
//  print "sendMA: trainID {$trainData[$index]["ID"]}, authMode {$TD_TXT_MODE[$authMode]}, MAdir $MAdir, balise $balise, distance $dist, speed $speed\n";

  if ($trainData[$index]["deployment"] == "R") { // Real train
    switch ($radioInterface) {
      case "USB":
      $baliseArray = explode(":",$balise);
      $distTurn = round($dist / $trainData[$index]["wheelFactor"]);
      $packet = "31,{$trainData[$index]["ID"]},".($authMode & 0x07 | $MAdir << 3  ).",";
      for ($b = 0; $b < 5; $b++) $packet .= hexdec($baliseArray[$b]).",";
      $packet .= ($distTurn & 0xFF).",".(($distTurn & 0xFF00) >> 8).",$speed,0s\n"; // "0s" is broadcast

print "Send MA pcaktet: >$packet<\n";      
      
        sendToRadioLink($packet);
      break;
      case "ABUS":
        fatalError("sendMA via EC/LINK not implemented");
      break;
    }
  } // else no MA send to simulated or ignored trains 
}

function sendPosRestore($trainID, $balise, $distance) {  // Implemented ?? FIXME
global $radioInterface;
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

function sendRTO($trainID, $cmd, $mode, $drive, $dir) { 
global $radioInterface;
  switch ($radioInterface) { 
    case "USB":
      $data = "32,$trainID,$cmd,".($mode | ($dir << 3) | ($drive << 5)).",0s";
      sendToRadioLink($data);
    break;
    case "ABUS":
      print "Warning: RTO via EC Link not implemented\n";
    break;
  }
}

function processPositionReport($trainID, $requestedMode, $MAreceived, $nomDir, // ---------------------Process Point Position Report from OBU
  $pwr, $baliseID, $distance,  $speed, $rtoMode) {
global $TD_TXT_MODE, $TD_TXT_ACK, $TD_TXT_DIR, $TD_TXT_PWR, $TD_TXT_RTOMODE, $trainData, $trainIndex, $now, $PT2, $balisesID,
  $posRestoreEnabled;

//  print "processPosRep: TrainID: $trainID, reqMode: {$TD_TXT_MODE[$requestedMode]}, MAreceived: {$TD_TXT_ACK[$MAreceived]}, ".
//  "nomDir: {$TD_TXT_DIR[$nomDir]}, pwr: {$TD_TXT_PWR[$pwr]}, Balise: $baliseID, Distance: $distance, Speed: $speed, ".
//  "rtoMode: {$TD_TXT_RTOMODE[$rtoMode]} \n";
  
  $triggerHMIupdate = true; // posRep will likely result in new states
  if (isset($trainIndex[$trainID])) { // Train is known
    $index = $trainIndex[$trainID];
    $train = &$trainData[$index];
    if ($train["deployment"] == "R" ) $train["dataValid"] = "OK";
    $train["comTimeStamp"] = $now;
    $train["reqMode"] = $requestedMode;
    $train["nomDir"] = $nomDir; // Nominel driving direction UP, DOWN or STOP, determined by OBU
    $train["pwr"] = $pwr;
    $train["MAreceived"] = $MAreceived;
    if ($train["pwr"] == P_R) { // Determin orientation of train front
      $train["front"] = D_UP;
    } elseif ($train["pwr"] == P_L) {
      $train["front"] = D_DOWN;
    } else { // orientation undefined by OBU - what to do? FIXME
      $train["front"] = D_UP;
      msgLog("Warning: Unknown orientation of train, assuming front Up");
    }
    $train["rtoMode"] = $rtoMode;
    $train["speed"] = $speed;
    if ($baliseID == "00:00:00:00:00") { // ----------------------- OBU indicates void position
      $train["baliseName"] = "<void balise>";
      $train["distance"] = 0;
      $train["baliseID"] = $baliseID;
      $train["posTimeStamp"] = $now;
      $train["curPositionUnambiguous"] = false;
      // $train["curOccupation"] = array(); Delete current occupation if position is void???                                FIXME
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
    } elseif (isset($balisesID[$baliseID])) { // -------------------------------------------------------------- OBU indicates known position
      $train["posTimeStamp"] = $now;
      $train["distance"] = $distance * $train["wheelFactor"];
      $train["baliseID"] = $baliseID;
      $train["baliseName"] = $balisesID[$baliseID];
      $train["posRestored"] = false;
      determineOccupation($index);      
    } else { // --------------------------------------------------- OBU indicates unknown balise
      // Unknown balise, track occupation cannot be updated -  position report ignored
      msgLog("Warning: Unknown baliseID >$baliseID< provided in position report from train $trainID.".
        "Prev. posRep: {$train["baliseID"]} at distance {$train["distance"]}");
    }
    generateModeAuthority($index); // Generate Mode Authority in any case - valid position or not
  } else {
    errLog("Unknown train ID ($trainID) in posRep");
  }
}

function determineOccupation($index) {
global $trainData, $trackModel, $PT2;

  $train = &$trainData[$index];
  $baliseName = $train["baliseName"];
  $trainPositionUp = $train["distance"] + ($train["front"] == D_UP ? $train["lengthFront"] : $train["lengthBehind"]);
  $trainPositionDown = $train["distance"] - ($train["front"] == D_UP ? $train["lengthBehind"] : $train["lengthFront"]);
  $baliseDistanceUp = $PT2[$baliseName]["U"]["dist"];
  $baliseDistanceDown = $PT2[$baliseName]["D"]["dist"];
//  print "TrainUp: $trainPositionUp TrainDown: $trainPositionDown BaliseUp: $baliseDistanceUp BaliseDown: $baliseDistanceDown\n";
  $occupation = array();
  if ($trainPositionDown < $baliseDistanceUp  and $trainPositionUp > -$baliseDistanceDown) { // Reference balise is occupied
    $occupation[0] = $baliseName;
//    print "Reference balise $baliseName occupied\n";
  }
  if ($trainPositionUp > $baliseDistanceUp) { // Up position located further Up, check neighbour Up
    $occupation = $occupation + $trackModel[$train["baliseName"]]->neighbourUp->
      checkOccupationUp($index, $trainPositionUp - $baliseDistanceUp, $trainPositionDown - $baliseDistanceUp, $trackModel[$baliseName], 1);
  }
  if ($trainPositionDown < -$baliseDistanceDown) { // Down position located further Down, check neighbour Down
    $occupation = $occupation + $trackModel[$train["baliseName"]]->neighbourDown->
      checkOccupationDown($index, $trainPositionUp + $baliseDistanceDown, $trainPositionDown + $baliseDistanceDown, $trackModel[$baliseName], -1);
  }
//  print_r($occupation);
  // How to indicate ambiguous occupation in track layout FIXME
  $newPositionUnambiguous = !isset($occupation["Ambiguous"]);
  unset($occupation["Ambiguous"]); // Delete ambiguous flag in array
  if ($newPositionUnambiguous) {
    $train["curPositionUnambiguous"] = true; 
    foreach (array_diff($occupation, $train["curOccupation"]) as $elementName) {
      $trackModel[$elementName]->occupyElementTrack($train["ID"]);
    }
    foreach (array_diff($train["curOccupation"], $occupation) as $elementName) {
      $trackModel[$elementName]->releaseElementTrack($train["nomDir"]);
      // Order of element release must reflect actual driving direction - not array order ---- check reportIndex ------------------- FIXME
    }
    $train["curOccupation"] = $occupation; 
   } else {// Keep previous occupation if new is ambiguous
    $train["curPositionUnambiguous"] = false; 
  }
}
    
function processCommandRBC($command, $from) { // ------------------------------------------- Process commands from HMI clients
global $inChargeHMI, $clientsData, $trackModel, $recCount, $triggerHMIupdate, 
  $allowSR, $allowSH, $allowFS, $allowATO, $trainData, $trainIndex, $arsEnabled;
  
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
        HMIindication($from, "displayResponse {".($trackModel[$param[1]]->cmdToggleElement() ? "OK" : "Rejected")."}");
      break;
      case "pb": // Block point throw
        HMIindication($from, "displayResponse {".($trackModel[$param[1]]->cmdToggleBlocking() ? "OK" : "Rejected")."}");
      break;
      case "sb": // Block locking signal as START or VIA
        HMIindication($from, "displayResponse {".($trackModel[$param[1]]->cmdToggleBlocking() ? "OK" : "Rejected")."}");
      break;
      case "ars": // Toggle ARS for signal
        HMIindication($from, "displayResponse {".($trackModel[$param[1]]->cmdToggleARS() ? "OK" : "Rejected")."}");
      break;
// Routes
      case "tr": // Set route
        $recCount = 0; // FIXME
        if ($trackModel[$param[1]]->cmdSetRouteTo($param[2])) {
          // assign route to train if any, then send MA. Next position report will trigger this anyway, needed? FIXME
          HMIindication($from, "displayResponse {OK}");
        } else {
          HMIindication($from, "displayResponse {Rejected - no route possible}");
        }
      break;
      case "rr": // Release route (without timer) if not occupied or assigned train is at stand still
        $model = $trackModel[$param[1]];
        if ($model->routeLockingType == RT_END_POINT) {
          $recCount = 0; // FIXME
          if ($model->assignedTrain == "") {
            if ($model->routeIsClear()) {
              $model->cmdReleaseRoute();    
              HMIindication($from, "displayResponse {OK}");
            } else { // release occupied route
              print "Release of occupied route not implemented\n";
              HMIindication($from, "displayResponse {Rejected, route occupied}");        
            }
          } else {
            if ($trainData[$trainIndex[$model->assignedTrain]]["nomDir"] == D_STOP) { // Assigned train is at stand still
              $model->cmdReleaseRoute();
              $trainData[$trainIndex[$model->assignedTrain]]["assignedRoute"] = "";
              $model->assignedTrain = "";
              HMIindication($from, "displayResponse {OK}");
            } else { // reject due to assigned train driving
              HMIindication($from, "displayResponse {Rejected - train running}");
            }
          }
        } else {
          HMIindication($from, "displayResponse {Ignored, not EP of route}");        
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
// Train RTO 
      case "reqRto":
        sendRTO($trainData[$param[1]]["ID"], 1, 5, 1, 2);
      break;
      case "relRto":
        sendRTO($trainData[$param[1]]["ID"], 2, 5, 1, 2);
      break;
      case "txRto":
        sendRTO($trainData[$param[1]]["ID"], 0, $param[2], $param[3], $param[4]); // FIXME to be repeated like commands from DMI are
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
      errLog("Error: Unknown command from HMI client: >$command<");
    break;
    }
  }
}

function toggleEmergencyStop() {
global $trainData, $emergencyStop;

  $emergencyStop = !$emergencyStop;
  foreach ($trainData as $index => $train) {
    generateModeAuthority($index);  // or is generateMovementAuth sufficient ?? FIXME
  }
}

function initRBC() {
global $trainData;

  foreach ($trainData as $index => $train) {
    generateModeAuthority($index);
  }
// More?? FIXME
}

function pumpSignal() { // ------------------- FIXME

}


?>
