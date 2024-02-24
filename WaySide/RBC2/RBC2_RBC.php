<?php
// WinterTrain, RBC2
// RBC core functionalty; route setting and releasing, MA generation, position determination

function generateModeAuthority($index) { // -------------------------------------- Generate mode authority for specific train
  global $trainData, $trackModel, $allowSR, $allowSH, $allowFS, $allowATO, $emergencyStop;
  $train = &$trainData[$index];
  switch ($train["reqMode"]) {
    case M_N:
      $train["authMode"] = M_N;
      voidMovementAuthority($index);
      if ($train["assignedRoute"] != "") { // If route assigned to train: unassign route
       print "Route {$train["assignedRoute"]} unassigned from train {$train["ID"]} due to mode N\n";
        $trackModel[$train["assignedRoute"]]->assignedTrain = "";
        $train["assignedRoute"] = "";
      }
    break;
    case M_SR:
      $train["authMode"] = M_N;
      voidMovementAuthority($index);
      if ($allowSR and $train["SRallowed"]) {
        $train["authMode"] = M_SR;
        $train["maxSpeed"] = $train["SRmaxSpeed"];
        $train["MAdir"] = MD_BOTH;
      }
      if ($train["assignedRoute"] != "") { // If route assigned to train: unassign route
        print "Route {$train["assignedRoute"]} unassigned from train {$train["ID"]} due to mode SR\n";
        $trackModel[$train["assignedRoute"]]->assignedTrain = "";
        $train["assignedRoute"] = "";
      }
    break;
    case M_SH:
      $train["authMode"] = M_N;
      voidMovementAuthority($index);
      if ($allowSH and $train["SHallowed"]) {
        $train["authMode"] = M_SH;
        $train["maxSpeed"] = $train["SHmaxSpeed"];
        $train["MAdir"] = MD_BOTH;
      }
      if ($train["assignedRoute"] != "") { // If route assigned to train: unassign route
        print "Route {$train["assignedRoute"]} unassigned from train {$train["ID"]} due to mode SH\n";
        $trackModel[$train["assignedRoute"]]->assignedTrain = "";
        $train["assignedRoute"] = "";
      }
    break;
    case M_FS:
      if  ($allowFS and $train["FSallowed"]) {
        $train["authMode"] = M_FS;
        $train["maxSpeed"] = $train["FSmaxSpeed"];
        if ($train["curPositionValid"]) {
          list($SPup,$SPdown) = searchSP($index); // search for possible SP in both directions and inform TMS
          if ($train["assignedRoute"] == "") {
            // if one of the SPs is set for this train by TMS, prioritize that---------------------------------------------- FIXME
            if ($SPup != "" and $trackModel[$SPup]->routeLockingState == R_LOCKED
              and ($trackModel[$SPup]->routeLockingType == RT_START_POINT or
                   $trackModel[$SPup]->routeLockingType == RT_VIA)) { // Locked SP found in direction UP, search for EP and assign.
              $routeEP = $trackModel[$SPup]->searchEP(true);
              if ($trackModel[$routeEP]->assignedTrain == "") {
                $train["assignedRoute"] = $routeEP;
                $trackModel[$routeEP]->assignedTrain = $train["ID"];
                debugPrint("RouteEP $routeEP assigned to train {$train["ID"]} FS");
                generateMovementAuthority($index);
              } else { // EP already assigned to another train
                debugPrint("Assigning routeEP $routeEP to train {$train["ID"]}, but route is already assigned to train ".
                  "{$trackModel[$routeEP]->assignedTrain} FS");
                voidMovementAuthority($index);              
              }
            } elseif ($SPdown != "" and $trackModel[$SPdown]->routeLockingState == R_LOCKED
              and ($trackModel[$SPdown]->routeLockingType == RT_START_POINT or
                   $trackModel[$SPdown]->routeLockingType == RT_VIA)) { // Locked SP found in direction DOWN, search for EP and assign.
              $routeEP = $trackModel[$SPdown]->searchEP(false);
              if ($trackModel[$routeEP]->assignedTrain == "") {
                $train["assignedRoute"] = $routeEP;
                $trackModel[$routeEP]->assignedTrain = $train["ID"];
                debugPrint("RouteEP $routeEP assigned to train {$train["ID"]} FS");
                generateMovementAuthority($index);
              } else { // EP already assigned to another train
                debugPrint("Assigning routeEP $routeEP to train {$train["ID"]}, but route is already assigned to train ".
                  "{$trackModel[$routeEP]->assignedTrain} FS");
                voidMovementAuthority($index);              
              }
            } else { // No SP route available for FS
//print "No SP route available for train {$train["ID"]} in FS\n";
              voidMovementAuthority($index);
            }
          } else { // A route is already assigned to this train
//print "Route {$train["assignedRoute"]} already assigned to train {$train["ID"]}\n";
            generateMovementAuthority($index);
          }
        } else { // Position ambiguous, reject MA request
          debugprint("Posiiton of train {$train["ID"]} is ambiguous");
          voidMovementAuthority($index);
        }          
      } else { // FS not allowed
        $train["authMode"] = M_N;
        voidMovementAuthority($index);
      }      
    break;
    case M_ATO:
      if ($allowATO and $train["ATOallowed"]) {
        $train["authMode"] = M_ATO;
        $train["maxSpeed"] = $train["ATOmaxSpeed"];
        if ($train["curPositionValid"]) { 
          list($SPup,$SPdown) = searchSP($index); // search for possible SP in both directions and inform TMS
          if ($train["assignedRoute"] == "") {
            // if one of the SPs is set for this train (ID) by TMS,  prioritize that FIXME
            if ($SPup != "" and $trackModel[$SPup]->routeLockingState == R_LOCKED
              and ($trackModel[$SPup]->routeLockingType == RT_START_POINT or
                   $trackModel[$SPup]->routeLockingType == RT_VIA)) { // Locked SP found in direction up, search for EP and assign.
              // FIXME could also be locked as VIA
              $routeEP = $trackModel[$SPup]->searchEP(true);
              if ($trackModel[$routeEP]->assignedTrain == "") {
                $train["assignedRoute"] = $routeEP;
                $trackModel[$routeEP]->assignedTrain = $train["ID"]; 
                debugPrint("RouteEP $routeEP assigned to train {$train["ID"]} ATO");
                generateMovementAuthority($index);
              } else { // EP already assigned to another train
                debugPrint("Assigning routeEP $routeEP to train {$train["ID"]}, but route is already assigned to train ".
                  "{$trackModel[$routeEP]->assignedTrain} FS");
                voidMovementAuthority($index);              
              }
            } elseif ($SPdown != "" and $trackModel[$SPdown]->routeLockingState == R_LOCKED
              and ($trackModel[$SPdown]->routeLockingType == RT_START_POINT or
                   $trackModel[$SPdown]->routeLockingType == RT_VIA)) { // Locked SP found in direction down, search for EP and assign.
              $routeEP = $trackModel[$SPdown]->searchEP(false);
              if ($trackModel[$routeEP]->assignedTrain == "") {
                $train["assignedRoute"] = $routeEP;
                $trackModel[$routeEP]->assignedTrain = $train["ID"]; 
                debugPrint("RouteEP $routeEP assigned to train {$train["ID"]} ATO");
                generateMovementAuthority($index);
              } else { // EP already assigned to another train
                debugPrint("Assigning routeEP $routeEP to train {$train["ID"]}, but route is already assigned to train ".
                  "{$trackModel[$routeEP]->assignedTrain} FS");
                voidMovementAuthority($index);              
              }
            } else { // No route available for ATO
//              print "No SP route available for train {$train["ID"]} in ATO\n";
              voidMovementAuthority($index);
            }
          } else { // A route already assigned to this train
//print "Route {$train["assignedRoute"]} already assigned to train {$train["ID"]}\n";
            generateMovementAuthority($index);
          }
        } else { // Position ambiguous, reject MA request
          debugprint("Posiiton of train {$train["ID"]} is ambiguous");
          voidMovementAuthority($index);
        } 
      } else { // ATO not allowed
        $train["authMode"] = M_N;
        voidMovementAuthority($index);
      }
      break;
  }
  sendMA($index);
}

function searchSP($index) { // Search for Start Point in both directions
  global $trainData, $trackModel;
  // Determine border elements of occupation i.e. high and low index of occupation array
  $train = &$trainData[$index];
  $extentUp = -1000;
  foreach ($train["curOccupation"] as $i => $pos) { 
    if ($i > $extentUp) $extentUp = $i;
  }
  $extentDown = 1000; 
  foreach ($train["curOccupation"] as $i => $pos) {
    if ($i < $extentDown) $extentDown = $i;
  }
  // FIXME test if extent > 1000
  $occupiedElementUp = $trackModel[$train["curOccupation"][$extentUp]];
  $occupiedElementDown = $trackModel[$train["curOccupation"][$extentDown]];
  
  // Check for SP in direction Up
  switch ($occupiedElementUp->elementType) {
    case "BSB":
      $SPup = $occupiedElementUp->neighbourUp->searchSP(true);
    break;
    case "BSE":
      $SPup = $occupiedElementUp->elementName; // Buffer stop occupied by the train is regarded as SP in order for TMS direction change to work
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
      $SPdown = $occupiedElementDown->elementName; // Buffer stop occupied by the train is regarded as SP in order for TMS direction change to work
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

  // ---------------------------------------------------------------------------- Notify TMS about train position
  if ($SPup != "") {
    trainLocationTMS($index, $SPup, 
      (($train["driveDir"] == D_STOP and $trackModel[$SPup]->neighbourDown->vacancyState == V_OCCUPIED 
        and $trackModel[$SPup]->neighbourDown->occupationTrainID == $train["ID"]) ? "S" : "A"),
      "U");
    if ($trackModel[$SPup]->elementType == "BSB" or $trackModel[$SPup]->elementType == "BSE")
      $SPup = ""; // Buffer stop as SP is only used by TMS, so delete before return
  }
  if ($SPdown != "") {
    trainLocationTMS($index, $SPdown,
      (($train["driveDir"] == D_STOP and $trackModel[$SPdown]->neighbourUp->vacancyState == V_OCCUPIED 
        and $trackModel[$SPdown]->neighbourUp->occupationTrainID == $train["ID"]) ? "S" : "A"),
      "D");
    if ($trackModel[$SPdown]->elementType == "BSB" or $trackModel[$SPdown]->elementType == "BSE")
      $SPdown = "";// Buffer stop as SP is only used by TMS, so delete before return
  }
  return array($SPup, $SPdown);
}

function voidMovementAuthority($index) {
  global $trainData;
  $train = &$trainData[$index];
  $train["maxSpeed"] = 0;
  $train["MAdir"] = MD_NODIR;
  $train["MAbalise"] = "00:00:00:00:00"; // FIXME due to flaw in OBU a balise known to the OBU must be used for void MA
  $train["MAbaliseName"] = "(00:00:00:00:00)";
}

function generateMovementAuthority($index) {
  global $trainData, $trackModel, $TD_TXT_MADIR, $balisesID, $SIGNALLING_TXT;
  $train = &$trainData[$index];
//print "\nGenerateMA Train {$train["ID"]}, Route {$train["assignedRoute"]}/";
  if ($train["assignedRoute"] != "") {
    $signalling = $trackModel[$train["assignedRoute"]]->updateSignalling();
//print " sig updated ";
    switch ($signalling) {
      case SIG_UDEF:
      case SIG_ERROR:
        errLog("Errorg: Movement Authority could not be determined for train ID {$train["ID"]} on route to EP {$train["assignedRoute"]}".
          " - SW error");
        voidMovementAuthority($index);
      break;
      case SIG_STOP:
        voidMovementAuthority($index);
      break;
      case SIG_PROCEED:
      case SIG_PROCEED_PROCEED:
        $train["MAdir"] = ($trackModel[$train["assignedRoute"]]->facingUp ? MD_UP : MD_DOWN);
        $train["MAbalise"] = $train["baliseID"];
        $train["MAbaliseName"] = $balisesID[$train["baliseID"]];
        $EOAdist = $trackModel[$train["assignedRoute"]]->computeEOAdist($index);
//        print "EOA comp ";
        if ($EOAdist !== false) {
          if ($trackModel[$train["assignedRoute"]]->facingUp) {
            $train["MAdist"] = $EOAdist - ($train["front"] == D_UP ? $train["lengthFront"] : $train["lengthBehind"]);
          } else {
            $train["MAdist"] = -$EOAdist + ($train["front"] == D_UP ? $train["lengthBehind"] : $train["lengthFront"]);
          }
        } else {
          errLog("Error: LRBG {$train["baliseName"]} ({$train["baliseID"]}) with distance {$train["distance"]} ".
            "not found while computing EOA dist for train {$train["ID"]} on route to EP {$train["assignedRoute"]}");
          voidMovementAuthority($index);
        }
      break;
      case SIG_NOT_LOCKED:
        errLog("Error: Signalling SIG_NOT_LOCKED in route EP {$train["assignedRoute"]} for train {$train["ID"]}");
      break;
      default:
        errLog("Error: unknown signalling in route EP {$train["assignedRoute"]} for train {$train["ID"]}");
    }
  } else {
    voidMovementAuthority($index);
    errLog("Error: GenerateMovementAuthority() called for train {$train["ID"]}, but no route was assigned to train");
  }
}

function sendMA($index) {
  global  $trainData, $TD_TXT_MODE, $radioInterface, $emergencyStop;
  $train = $trainData[$index];
  $authMode = ($emergencyStop ? M_ESTOP : $train["authMode"]); 
  $MAdir = $train["MAdir"];
  $balise = $train["MAbalise"]; 
  $dist = $train["MAdist"];
  $speed = $train["maxSpeed"];
  switch ($train["deployment"]) {
    case "R":  // Real train
    switch ($radioInterface) {
      case "USB":
        $baliseArray = explode(":",$balise);
        $distTurn = round($dist / $train["wheelFactor"]);
        $packet = "31,{$train["ID"]},".($authMode & 0x07 | $MAdir << 3  ).",";
        for ($b = 0; $b < 5; $b++) $packet .= hexdec($baliseArray[$b]).",";
        $packet .= ($distTurn & 0xFF).",".(($distTurn & 0xFF00) >> 8).",$speed,0s\n"; // "0s" is broadcast
        sendToRadioLink($packet);
      break;
      case "ABUS":
        fatalError("sendMA via EC/LINK not implemented");
      break;
    }
    break;
    case "S": // Simulated train
    break;
    default:
  }
}

function sendPosRestore($trainID, $balise, $distance) {
  global $radioInterface;
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

function processPositionReport( // ------------------ Process Point Position Report from OBU
  $trainID, $requestedMode, $MAreceived, $driveDir, $pwr, $frontUp, $baliseID, $distance,  $speed, $rtoMode) {
  global $TD_TXT_MODE, $TD_TXT_ACK, $TD_TXT_DIR, $TD_TXT_PWR, $TD_TXT_RTOMODE, $trainData, $trainIndex, $now, $PT2, $balisesID,
    $posRestoreEnabled, $triggerHMIupdate, $baliseStat, $MCeBaliseName, $MCeBaliseID, $MCeBaliseReader;
  $triggerHMIupdate = true; // posRep will likely result in new states
  if (isset($trainIndex[$trainID])) { // Train is known
    $index = $trainIndex[$trainID];
    $train = &$trainData[$index];
    if ($train["deployment"] == "R" ) $train["dataValid"] = "OK";
    $train["comTimeStamp"] = $now;
    $train["reqMode"] = $requestedMode;
    $train["prevDriveDir"] = $train["driveDir"];
    $train["driveDir"] = $driveDir; // Nominel driving direction UP, DOWN or STOP, determined by OBU
    $train["front"] = ($frontUp ? D_UP : D_DOWN); // Orientation of front of train (UP or DOWN), determined by OBU
    $train["pwr"] = $pwr;
//print "Train $trainID Power: $pwr, Orientation: {$train["front"]}\n";
    $train["MAreceived"] = $MAreceived;
    $train["rtoMode"] = $rtoMode;
    $train["speed"] = $speed;
    
    if ($train["name"] == $MCeBaliseReader) {
      $MCeBaliseName = isset($balisesID[$baliseID]) ? $balisesID[$baliseID] : "<udef>";
      $MCeBaliseID = $baliseID;
    }
    
    if ($baliseID == "01:00:00:00:01") { // ------------------------------------------------------------------- OBU indicates void position
//      $train["posTimeStamp"] = $now; // FIXME
      if ($posRestoreEnabled) {
        if (!$train["posRestored"]) {
          if ($now - $train["posTimeStamp"] <= POSITION_TIMEOUT) { // Pos can be restored
            $train["restoreCount"] +=1;
            errLog("Train ({$train["ID"]}): Void position restored to: {$train["baliseName"]} ({$train["baliseID"]}) {$train["distance"]} Stamped: ".
                date("Ymd H:i:s", $train["posTimeStamp"]));
            sendPosRestore($train["ID"], $train["baliseID"], (int)($train["distance"] / $train["wheelFactor"]));
            // New posRep from OBU is awaited before position is determined (to verify restore)
  // FIXME          $train["posRestored"] = true; // to prevent continuous restore
          } else { // Old pos outdated
            errLog("Train ({$train["ID"]}): RBC position {$train["baliseName"]} ({$train["baliseID"]}) not restored - outdated. Stamped: ".
              date("Ymd H:i:s", $train["posTimeStamp"]));
            $train["baliseName"] = "<void balise>";
            $train["distance"] = 0;
            $train["baliseID"] = $baliseID;
            $train["curPositionValid"] = false;
          }
        } else {
          $train["curPositionValid"] = false;
          if (!$train["posRestoredLogged"]) {
            errLog("Train ({$train["ID"]}): position void, but already restored. Awaiting new real position.");
            $train["posRestoredLogged"] = true;
          }
        }
      }
    } elseif (isset($balisesID[$baliseID])) { // -------------------------------------------------------------- OBU indicates known position
      $train["posTimeStamp"] = $now;
      $train["distance"] = (int)($distance * $train["wheelFactor"]);
      $train["baliseID"] = $baliseID;
      $train["baliseName"] = $balisesID[$baliseID];
      if ($train["baliseName"] != $train["prevBaliseName"]) { // Update balise statistics
        $baliseStat[$train["baliseName"]][$trainID] +=1;
        $train["prevBaliseName"] = $train["baliseName"];
      }
      $train["posRestored"] = false;
      $train["posRestoredLogged"] = false;
      determineOccupation($index);
      $train["curPositionValid"] = $train["curPositionUnambiguous"];
    } else { // ----------------------------------------------------------------------------------------------- OBU indicates unknown balise
      // Unknown balise, track occupation cannot be updated -  position report ignored
      if ($baliseID != "01:00:00:00:01") { // If different from OBU default balise
        msgLog("Warning: Unknown baliseID >$baliseID< provided in position report from train $trainID.".
          "Prev. posRep: {$train["baliseID"]} at distance {$train["distance"]}");
      }
    }
    generateModeAuthority($index); // Generate Mode Authority in any case - valid position or not
  } else {
    errLog("Unknown train ID ($trainID) in position report");
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
  if ($trainPositionDown < $baliseDistanceUp  and $trainPositionUp > -$baliseDistanceDown) { // Reference balise is occupied
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
  $newPositionUnambiguous = !isset($occupation["Ambiguous"]) and $occupation != array();;
  unset($occupation["Ambiguous"]); // Delete ambiguous flag in array
  if ($newPositionUnambiguous) { // ---------------------- Apply consequences of train movement (occupation and clearence)
    $train["curPositionUnambiguous"] = true; 
    $occupy = array_diff($occupation, $train["curOccupation"]);
    $release = array_diff($train["curOccupation"], $occupation);
    $train["curOccupation"] = $occupation; 
    switch ($train["driveDir"]) {
      case D_UP:
        ksort($occupy, SORT_NUMERIC); 
        ksort($release, SORT_NUMERIC);
        foreach ($occupy as $elementName) {
          $trackModel[$elementName]->occupyElementTrack($train["ID"], D_UP);
        }
        foreach ($release as $elementName) {
          $trackModel[$elementName]->releaseElementTrack(D_UP);
        }
      break;
      case D_DOWN:
        krsort($occupy, SORT_NUMERIC);
        krsort($release, SORT_NUMERIC);
        foreach ($occupy as $elementName) {
          $trackModel[$elementName]->occupyElementTrack($train["ID"], D_DOWN);
        }
        foreach ($release as $elementName) {
          $trackModel[$elementName]->releaseElementTrack(D_DOWN);
        }
      break;
      case D_STOP: // Even if at stop, train might have moved since previous posRep
        foreach ($occupy as $elementName) {
          $trackModel[$elementName]->occupyElementTrack($train["ID"], D_STOP);
        }
        switch ($train["prevDriveDir"]) {
          case D_UP:
            foreach ($release as $elementName) {
              $trackModel[$elementName]->releaseElementTrack(D_UP);
            }
          break;
          case D_DOWN:
            foreach ($release as $elementName) {
              $trackModel[$elementName]->releaseElementTrack(D_DOWN);
            }
          break;
        }
        releaseRouteEndPoint($index);          
      break;
      default:
        errLog("Error: Default driveDir {$train["driveDir"]} in function determineOccupation");
    }
   } else {// Keep previous occupation if new is ambiguous
     errLog("Warning: Position of trainID {$train["ID"]} at balise >$baliseName< distance {$train["distance"]} is ambiguous");
     $train["curPositionUnambiguous"] = false; 
  }
}

function releaseRouteEndPoint($index) {
// Check if train is at stand still in rear of route EP, then release route
// "in rear of" might be specified by a distance from EP instead of the before neighbour. ------------------------------------- FIXME
  global $trainData, $trackModel;
  $train = &$trainData[$index];
  if ($train["assignedRoute"] != "") {
    $EPelement = &$trackModel[$train["assignedRoute"]];
    switch ($EPelement->elementType) {
      case "BSB":
      case "BSE":
      case "SU":
      case "SD":
        if ($EPelement->routeLockingState != R_IDLE and $EPelement->routeLockingType == RT_END_POINT) {
          $appElement = ($EPelement->facingUp ? $EPelement->neighbourDown : $EPelement->neighbourUp);
          if ($appElement->vacancyState == V_OCCUPIED and $appElement->occupationTrainID == $train["ID"] and $train["driveDir"] == D_STOP) {
            debugPrint("Route {$train["assignedRoute"]} unassigned from train {$train["ID"]} due to EP release at {$EPelement->elementName}");
            $EPelement->assignedTrain = "";
            $train["assignedRoute"] = "";
            voidMovementAuthority($index);
            $EPelement->cmdReleaseRoute();
          }
        }
      break;
    }
  }
} 

function checkTrainTimeout() {
  global $trainData, $now, $triggerHMIupdate;
  foreach ($trainData as $index => &$train) {
    if ($now - $train["comTimeStamp"] > TRAIN_COM_TIMEOUT) {
      $train["dataValid"] = "VOID";
      $triggerHMIupdate = true;
    }
  }
}
    
function pumpSignal() { // Resend EC order to open light signals
  global $lightSignal;
  foreach ($lightSignal as $element) {
    if (($element->routeLockingType == RT_START_POINT or $element->routeLockingType == RT_VIA) 
      and ($element->signallingState == SIG_PROCEED or $element->signallingState == SIG_PROCEED_PROCEED)) 
      orderSignal($element->elementName, $element->signallingState);
  }
}

function checkTimers() {
  global $emgRelEPTimers, $PMretryTimers, $triggerHMIupdate, $now, $trackModel;
  foreach ($emgRelEPTimers as $key => $element) { // --------------- Emergency route release
    if ($now - $element->emgRelTimer >= EMG_REL_TIMEOUT) {
      $element->cmdReleaseRoute();
      $triggerHMIupdate = true;
      unset($emgRelEPTimers[$key]);
    }
  }
  foreach ($PMretryTimers as $key => $elementName) { // ---------------- Point Throw retry
    $element = &$trackModel[$elementName];
    if ($now - $element->retryTimer >= PM_RETRY_TIMEOUT) {
      if ($element->pointState != ($element->logicalLieRight ? P_SUPERVISED_RIGHT : P_SUPERVISED_LEFT)) {
        $element->throwPoint($element->logicalLieRight ? C_RIGHT : C_LEFT);
        $element->retryTimer = $now;
        $element->retryCount += 1;
        errLog("Notice: PM throw retry {$element->elementName}");
        if ($element->retryCount > PM_MAX_RETRY) {
          errLog("Warning: {$element->elementName} Max point throw retry reached");
          unset($PMretryTimers[$key]);
        }
      } else { // point state OK
        unset($PMretryTimers[$key]);      
      }
    }
  }
}

function processCommandRBC($command, $from) { // ------------------------------------------- Process commands from HMI clients
  global $inChargeHMI, $clientsData, $trackModel, $triggerHMIupdate, 
    $allowSR, $allowSH, $allowFS, $allowATO, $trainData, $trainIndex, $arsEnabled, $SIGNALLING_TXT, $now, $emgRelEPTimers;
  $triggerHMIupdate = true; // RBC command will likely result in new states, so trigger HMI update
  $param = explode(" ",$command);
  if ($param[0] == "Rq") {// Request operation
    if ($inChargeHMI) {
      HMIindication($from, "displayResponse {Rejected ".$clientsData[(int)$inChargeHMI]["addr"]." is in charge (since ".
        $clientsData[(int)$inChargeHMI]["inChargeSince"].")}"); // FIXME add userName
    } elseif (true or isset($param[1]) and $param[1] == "hej") {
      $inChargeHMI = $from;
      $clientsData[(int)$from]["inChargeSince"] = date("Ymd H:i:s");
      $clientsData[(int)$from]["activeAt"] = date("Ymd H:i:s");
      $clientsData[(int)$from]["userName"] = isset($param[1]) ? $param[1] : "<unknown>";
      HMIindication($from, "oprAllowed");
    } else {
      HMIindication($from, "displayResponse {Rejected}");
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
        $EP = $trackModel[$param[1]]->cmdSetRouteTo($param[2]);
        if (substr($EP, 0, 2)  == "__")  $EP = $trackModel[$param[2]]->cmdSetRouteTo($param[1]); // Try opposite signal order
        switch ($EP) {
          case "__R":
            HMIindication($from, "displayResponse {Rejected - no route possible}");
          break;
          case "__B":
            HMIindication($from, "displayResponse {Rejected - element(s) already locked}");
          break;
          case "__I":
            HMIindication($from, "displayResponse {Rejected - element(s) inhibited}");
          break;
          case "__O":
            HMIindication($from, "displayResponse {Rejected - track occupied}");
          break;
          default:
            HMIindication($from, "displayResponse {OK}");
        }
      break;
      case "rr": // Release route (without timer) given that route is not occupied or assigned train is at stand still
        $element = $trackModel[$param[1]];
        if ($element->routeLockingType == RT_END_POINT) {
          if ($element->assignedTrain == "") {
            if ($element->routeIsClear()) {
              $element->cmdCloseRoute();
              $element->cmdReleaseRoute();    
              HMIindication($from, "displayResponse {OK}");
            } else {
              HMIindication($from, "displayResponse {Rejected, route occupied}");        
            }
          } else {
            $train = &$trainData[$trainIndex[$element->assignedTrain]];
            if ($train["driveDir"] == D_STOP) { // Assigned train is at stand still
              debugPrint("Route {$train["assignedRoute"]} unassigned from train {$train["ID"]} due to cmd rr");
              $train["assignedRoute"] = "";              
              $train["maxSpeed"] = 0;
              $train["MAdir"] = MD_NODIR;
              $train["MAbaliseName"] = "(00:00:00:00:00)";
              $train["MAbalise"] = "00:00:00:00:00"; // FIXME due to a flaw in OBU, a balise known to OBU must be used in void MA
              $element->assignedTrain = "";
              $element->cmdCloseRoute();
              $element->cmdReleaseRoute();
              HMIindication($from, "displayResponse {OK}");
            } else {
              HMIindication($from, "displayResponse {Rejected - train running}");
            }
          }
        } else {
          HMIindication($from, "displayResponse {Ignored, not EP of route}");        
        }
      break;
      case "err": // Emergency route release - with timer
        $element = $trackModel[$param[1]];
        if ($element->routeLockingState == R_LOCKED and $element->routeLockingType == RT_END_POINT) {
          if ($element->assignedTrain != "") {
            $train = &$trainData[$trainIndex[$element->assignedTrain]];
            debugPrint("Route {$train["assignedRoute"]} unassigned from train {$train["ID"]} due to cmd err");
            $train["assignedRoute"] = "";
            $train["maxSpeed"] = 0;
            $train["MAdir"] = MD_NODIR;
            $train["MAbalise"] = "00:00:00:00:00"; // FIXME due to a flaw in OBU, a balise known to OBU must be used in void MA
            $train["MAbaliseName"] = "(00:00:00:00:00)";
            sendMA($trainIndex[$element->assignedTrain]);
            $element->assignedTrain = "";
          }
          $element->cmdCloseRoute();
          $element->emgRelTimer = $now;
          $emgRelEPTimers[] = $element;
          HMIindication($from, "displayResponse {OK}");
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
// TMS
      case "trnSet":
        $trainData[$param[1]]["trn"] = isset($param[2]) ? $param[2] : "";
        notifyTMS("setTRN {$param[1]} {$trainData[$param[1]]["trn"]}");
      break;
      case "loadTT":
        notifyTMS("loadTT");
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
        errLog("Error: Unknown command from HMI client: >$command<");
    break;
    }
  }
}

function toggleEmergencyStop() {
  global $trainData, $emergencyStop;
  $emergencyStop = !$emergencyStop;
  foreach ($trainData as $index => $train) {
    generateModeAuthority($index);
  }
}

function initRBC() {
  global $trainData;
  foreach ($trainData as $index => $train) {
    voidMovementAuthority($index);
    generateModeAuthority($index);
  }
}


?>
