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
  
  public function notLockedInRoute() {
    return $this->routeLockingState == R_IDLE and $this->routeLockingType == RT_IDLE; // More ?? FIXME
  }
  
  public function occupyElementTrack($trainID) {
    $this->vacancyState = V_OCCUPIED;
    $this->occupationTrainID = $trainID; // what if occupied by more trains?? ---------------------------------------------------- FIXME
    // Apply consequences like LX deactivation FIXME
  }

  public function routeIsClear() {
//  print "$this->elementName ";
    return $this->routeLockingState == R_IDLE or ($this->vacancyState == V_CLEAR and $this->routeLockingUp ? $this->neighbourDown->routeIsClear() : $this->neighbourUp->routeIsClear());
  }
  
  public function releaseElementTrack($drivingDirection) { // Sequentil route release
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
          print "Warning Train released element {$this->elementName} in unknown direction\n";
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

  public function routeIsClear() {
//  print "$this->elementName ";
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
          print "Warning Train released element {$this->elementName} in unknown direction\n";
        break;
      }
    }  // else route not locked - ignore element release
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
  
  protected function throwPoint($throwRight) { // Throw point to specific lie (param true: right, false: left)
    if ($this->throwLockedByConfiguration or $this->throwLockedByRoute or $this->throwLockedByCmd or $this->vacancyState != V_CLEAR) return false;
    if ($throwRight == $this->logicalLieRight) return true; // Point is already in requested lie
      $this->logicalLieRight = $throwRight;
      switch ($this->supervisionMode) {
        case "U":  // Point lie always P_UNSUPERVISED;
          return true;
        break;
        case "S": // Point lie simulated
          $this->pointState = $this->logicalLieRight ? P_SUPERVISED_RIGHT : P_SUPERVISED_LEFT;
          if ($this->routeLockingState != R_IDLE) { // Point is locked in route, check if throwing is to be locked
            if ($this->logicalLieRight == ($this->routeLockingType == RT_RIGHT)) $this->throwLockedByRoute = true;
          };
          return true;
        break;
        case "P":
          // $this->logicalLieRight ? send EC cmd throw rigth : send EC cmd throw left 
          // when status received if locked in route check if throw command is to be blocked FIXME
          return false; // As not implemented
        break;
        case "F":
          // $this->logicalLieRight ? send EC cmd throw rigth : send EC cmd throw left 
          // when status received if locked in route check if throw command is to be blocked FIXME
          return false; // As not implemented
        break;
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
print "default locking type {$this->routeLockingType} ";
              break;
            }
          } else {
print "Train moving against route direction ";} // else Warning train moved against route direction FIXME
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
          // Warning train released element in unknown direction - what to do FIXME
          print "Warning Train released element {$this->elementName} in unknown direction\n";
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
// print only if this was the train FIXME
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
// print only if this was the train FIXME
    }
    return $occupation;
  }

  public function searchSP($searchUp) {
    return ""; // Bufferstop cannot be a route start point
  }

  public function searchEP($searchUp) {
    return (($this->facingUp == $searchUp) and $this->routeLockingState == R_LOCKED and $this->routeLockingType == RT_END_POINT) ?
      $this->elementName
    :
      "";
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
global $trainData, $trackModel, $allowSR, $allowSH, $allowFS, $allowATO, $emergencyStop;
//print "Generate MA for train {$trainData[$index]["ID"]}\n";

// FS, ATO: if no route assigned to train: search for applicable route and assign to train
// if route assigned and MB open:
// max speed for train / wheel factor
// allowed driving direction of route for FS, ATO
// distance to EOA / wheel factor


  $train = &$trainData[$index];
  $train["authMode"] = M_N;
  $train["MAdir"] = MD_NODIR;
  $train["MAbalise"] = "00:00:00:00:00";
  switch ($train["reqMode"]) {
    case M_N:
      if ($train["assignedRoute"] != "") { // if route assigned to train: unassign route
        $trackModel[$train["assignedRoute"]]->assignedTrain = "";
        $train["assignedRoute"] = "";
      }
    break;
    case M_SR:
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
        if ($train["curPositionUnambiguous"]) {
          // search for SP in both directions - to be used by TMS   FIXME
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
  // What if occupied element (or next element) already is locked in route?----------------------------------- FIXME
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
print "SPup: $SPup, SPdown: $SPdown\n";
// inform TMS FIXME

          switch ($train["nomDir"]) {
            case D_UDEFF:
            case D_STOP:
              if ($train["assignedRoute"] != "") { // Deassign route if assigned
                $trackModel[$train["assignedRoute"]]->assignedTrain = "";
                $train["assignedRoute"] = "";
              }
            break;
            case D_UP:
              if ($train["assignedRoute"] == "") { // search for and assign route to train according to requested driving direction
            // then search for EP in requested driving direction starting from found SP
            
            
              if ($SPup != "") { // SP in requested direction 
              
                $train["assignedRoute"] = ;
                $trackModel[$route]->assignedTrain = $train["ID"];
                print "Route $route assigned to train {$train["ID"]}\n";
              }
            
            } else { // route already assigned to train
            // check if same direction as requested??
            // check route signalling
print "to be implemented\n";
            }
            
            break;
            case D_DOWN:
              if ($train["assignedRoute"] == "") { // search for and assign route to train according to requested driving direction
            // then search for EP in requested driving direction starting from found SP
            
            
              if (false) {
                $train["assignedRoute"] = $route;
                $trackModel[$route]->assignedTrain = $train["ID"];
                print "Route $route assigned to train {$train["ID"]}\n";
              }
            
            } else { // route already assigned: same direction as requested?? check route signalling
            }

            break;
            // $train["MAdir"] = ; FIXME
          }
        }
      }
    break;
    case M_ATO:
      if ($allowATO and $train["ATOallowed"]) {
        $train["authMode"] = M_ATO;
        $train["maxSpeed"] = $train["ATOmaxSpeed"];
        if ($train["assignedRoute"] == "") {
        // assign train to route according to requested driving direction
        // $train["MAdir"] = ; FIXME
        } else { // route already assigned: check route signalling
        }
      }
    break;
  }
  sendMA($index, ($emergencyStop ? M_ESTOP : $train["authMode"]), $train["MAdir"], $train["MAbalise"], $train["MAdist"], $train["maxSpeed"]);
}

function sendMA($index, $authMode, $MAdir, $balise, $dist, $speed) { // To be moved to generateMA() ?? FIXME
global  $trainData, $TD_TXT_MODE, $radioInterface;
//  print "sendMA: trainID {$trainData[$index]["ID"]}, authMode {$TD_TXT_MODE[$authMode]}, MAdir $MAdir, balise $balise, distance $dist, speed $speed\n";

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

function sendPosRestore($trainID, $balise, $distance) {  // Implemented ?? FIXME
global $radioLinkAddr, $radioLink, $radioInterface;
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

/*  print "processPosRep: TrainID: $trainID, reqMode: {$TD_TXT_MODE[$requestedMode]}, MAreceived: {$TD_TXT_ACK[$MAreceived]}, ".
  "nomDir: {$TD_TXT_DIR[$nomDir]}, pwr: {$TD_TXT_PWR[$pwr]}, Balise: $baliseID, Distance: $distance, Speed: $speed, ".
  "rtoMode: {$TD_TXT_RTOMODE[$rtoMode]} \n";
  */
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
      // $train["curOccupation"] = array(); Delete current occupation if position is void??? FIXME
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
      $train["distance"] = $distance;
      $train["baliseID"] = $baliseID;
      $train["baliseName"] = $balisesID[$baliseID];
      $train["posRestored"] = false;
      determineOccupation($index);      
    } else { // --------------------------------------------------- OBU indicates unknown balise
      // Unknown balise, track occupation cannot be updated -  position report ignored
      msgLog("Warning: Unknown baliseID >$baliseID< provided in position report from train $trainID.".
        "Prev. posRep: {$train["baliseID"]} at distance {$train["distance"]}");
    }
    generateMA($index); // Generate an MA in any case - valid position or not
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
  $occupation = array();
  if ($trainPositionUp > -$baliseDistanceDown and $trainPositionUp < $baliseDistanceUp 
      or $trainPositionDown > -$baliseDistanceDown and $trainPositionDown < $baliseDistanceUp) { // Reference balise is occupied
    $occupation[0] = $baliseName;
  }
  if ($trainPositionUp > $baliseDistanceUp) { // Up position located further Up, check neighbour Up
    $occupation = $occupation + $trackModel[$train["baliseName"]]->neighbourUp->
      checkOccupationUp($index, $trainPositionUp - $baliseDistanceUp, $trainPositionDown - $baliseDistanceUp, $trackModel[$baliseName], 1);
  }
  if ($trainPositionDown < -$baliseDistanceDown) { // Down position located further Down, check neighbour Down
    $occupation = $occupation + $trackModel[$train["baliseName"]]->neighbourDown->
      checkOccupationDown($index, $trainPositionUp + $baliseDistanceDown, $trainPositionDown + $baliseDistanceDown, $trackModel[$baliseName], -1);
  }
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
