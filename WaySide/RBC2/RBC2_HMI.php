<?php
// WinterTrain, RBC2
// HMI handlers

// For function processCommandHMI() see RBC2_RBC.php

function updateIndicationHMI() { // Update general and track indications for all HMI clients
  global $trainData, $PT2, $HMI, $trackModel, $triggerHMIupdate, $tmsStatus, $allowSR, $allowSH, $allowFS, $allowATO, $emergencyStop,
    $arsEnabled, $TMS_STATUS_TXT;
  $triggerHMIupdate = false;
// General data
  HMIindicationAll("srGeneral {{$allowSR}}");
  HMIindicationAll("shGeneral {{$allowSH}}");
  HMIindicationAll("fsGeneral {{$allowFS}}");
  HMIindicationAll("atoGeneral {{$allowATO}}");
  HMIindicationAll("set ::tmsStatus {{$TMS_STATUS_TXT[$tmsStatus]}}\n");
// Track layoyt / indicators
  HMIindicationAll("eStopInd ".($emergencyStop ? "true" : "false"));
  HMIindicationAll("arsAllInd ".($arsEnabled ? "true" : "false"));
// Track layout / Element state
  foreach ($PT2 as $name => $element) {
    $model = $trackModel[$name];
    switch ($element["element"]) {
      case "SU":
      case "SD":
        $signalState = $model->routeLockingType == RT_VIA_REVERSE ? SIG_STOP : $model->signallingState;
        HMIindicationAll("signalState $name ".$model->vacancyState." ".$model->routeLockingState." ".$model->routeLockingType." ".
          $model->lockingState." ".$model->blockingState." ".$signalState." ".$model->arsState." ".$model->occupationTrainID."\n");
      break;
      case "PF":
      case "PT":
        HMIindicationAll("pointState $name ".$model->vacancyState." ".$model->routeLockingState." ".$model->routeLockingType." ".
          $model->lockingState." ".$model->blockingState." ".$model->pointState." ".$model->occupationTrainID."\n");
      break;
      case "BSB":
      case "BSE":
        HMIindicationAll("bufferStopState $name  ".$model->vacancyState." ".$model->routeLockingState." ".$model->routeLockingType." ".
          $model->lockingState." ".$model->blockingState." ".$model->occupationTrainID."\n");
      break;
    }
  }
// Track state
  foreach ($HMI["baliseTrack"] as $trackName => $baliseTrack) {
    $routeLockingState = R_IDLE;
    $vacancyState = V_CLEAR;
    $occupationTrainID = "";
    foreach ($baliseTrack["balises"] as $baliseName ) {
      if ($trackModel[$baliseName]->vacancyState != V_CLEAR) {
        $vacancyState = V_OCCUPIED;
        if ($occupationTrainID == "") {
          $occupationTrainID = $trackModel[$baliseName]->occupationTrainID;
        } else if ($occupationTrainID != $trackModel[$baliseName]->occupationTrainID) {
          $occupationTrainID .= "/".$trackModel[$baliseName]->occupationTrainID;
        }
      }
      if ($trackModel[$baliseName]->routeLockingState != R_IDLE) {
        $routeLockingState = R_LOCKED;
      }
    }
    HMIindicationAll("trState $trackName $routeLockingState $vacancyState {$occupationTrainID}\n");
  }
}

function updateTrainDataHMI() { // Update train indications for all HMI clients
  global $trainData, $TD_TXT_MODE, $TD_TXT_DIR, $TD_TXT_MADIR, $TD_TXT_PWR, $TD_TXT_ACK, $TD_TXT_RTOMODE, $TD_TXT_UNAMB,
    $TD_TXT_POS_VALID, $TD_TXT_FRONT;
  foreach ($trainData as $index => &$train) {
    HMIindicationAll("SRmode ".$index." {".$train["SRallowed"]."}");
    HMIindicationAll("SHmode ".$index." {".$train["SHallowed"]."}");
    HMIindicationAll("FSmode ".$index." {".$train["FSallowed"]."}");
    HMIindicationAll("ATOmode ".$index." {".$train["ATOallowed"]."}");
    $posStatus = $train["curPositionValid"] ? "" :
      ($train["curPositionUnambiguous"] ?
        $TD_TXT_POS_VALID[false] :
        $TD_TXT_UNAMB[false]);
    HMIindicationAll("trainDataD ".$index." {".$TD_TXT_MODE[$train["authMode"]]." (".$TD_TXT_MODE[$train["reqMode"]].")} {".$train["baliseName"].
      "} {".$train["distance"]."} {{$posStatus}} {".$train["speed"]."} {".$TD_TXT_DIR[$train["driveDir"]].
      "} {".$TD_TXT_PWR[$train["pwr"]]." ".$TD_TXT_FRONT[$train["front"]].($train["restoreCount"] > 0 ?  " {$train["restoreCount"]}" : "").
      "} {".$TD_TXT_ACK[$train["MAreceived"]]."} ".$train["dataValid"]." {".$TD_TXT_RTOMODE[$train["rtoMode"]].
      "} {".$train["MAbaliseName"]."} {".$train["MAdist"]."} {".$TD_TXT_MADIR[$train["MAdir"]]."} {".$train["trn"].
      "} {".$train["trnStatus"]."} {".$train["etd"]."}");
  }
}

function HMIstartup($client) { // Initialise specific HMI client with static comfiguration, screen layout etc.
  global $PT2, $HMI, $trainData, $triggerHMIupdate, $allowSR, $allowSH, $allowFS, $allowATO, $emergencyStop, $arsEnabled;
  $triggerHMIupdate = true; // Trigger a dynamic update afterwards
  HMIindication($client,".f.canvas delete all");
  HMIindication($client,"destroyTrainFrame");
  HMIindication($client,"dGrid");
  HMIindication($client,"resetLabel");
// General data
  HMIindication($client,"srGeneral {{$allowSR}}");
  HMIindication($client,"shGeneral {{$allowSH}}");
  HMIindication($client,"fsGeneral {{$allowFS}}");
  HMIindication($client,"atoGeneral {{$allowATO}}");
// Track layoyt / lables and indicators
  foreach ($HMI["label"] as $label) {
    HMIindication($client,"label {".$label["text"]."} ".$label["x"]." ".$label["y"]."\n");  
  }
  if (isset($HMI["eStopIndicator"]))
    HMIindication($client,"eStopIndicator ".$HMI["eStopIndicator"]["x"]." ".$HMI["eStopIndicator"]["y"]."\n");  
  if (isset($HMI["arsIndicator"]))
    HMIindication($client,"arsIndicator ".$HMI["arsIndicator"]["x"]." ".$HMI["arsIndicator"]["y"]."\n");  
  HMIindication($client, "eStopInd ".($emergencyStop ? "true" : "false"));
  HMIindication($client, "arsAllInd ".($arsEnabled ? "true" : "false"));
// Track layout / elements
  foreach ($PT2 as $name => $element) {
    switch ($element["element"]) {
    case "PF":
    case "PT":
      HMIindication($client,"point $name ".$element["HMI"]["x"]." ".$element["HMI"]["y"]." {$element["HMI"]["or"]}\n");
    break;
    case "SU":
      HMIindication($client,"signal $name ".$element["HMI"]["x"]." ".$element["HMI"]["y"]." f {$element["HMI"]["l"]}\n");
    break;
    case "SD":
      HMIindication($client,"signal $name ".$element["HMI"]["x"]." ".$element["HMI"]["y"]." r {$element["HMI"]["l"]}\n");
    break;
    case "BSB":
      HMIindication($client,"bufferStop $name ".$element["HMI"]["x"]." ".$element["HMI"]["y"]." b {$element["HMI"]["l"]}\n");
    break;
    case "BSE":
      HMIindication($client,"bufferStop $name ".$element["HMI"]["x"]." ".$element["HMI"]["y"]." e {$element["HMI"]["l"]}\n");
    break;
    case "LX":
      HMIindication($client,"levelcrossing $name ".$element["HMI"]["x"]." ".$element["HMI"]["y"]."\n");
    break;
    }
  }
  HMIindication($client,".f.canvas raise button [.f.canvas create text 0 0]\n"); // Ensure that all element buttons are on the top layer
// Track state indication
  foreach ($HMI["baliseTrack"] as $trackName => $baliseTrack) {
    HMIindication($client,"track $trackName {$baliseTrack["x"]} {$baliseTrack["y"]} {$baliseTrack["l"]} {$baliseTrack["or"]}\n");
  }
// Train data
// If client already is in charge, initialize train frame with various buttons enabled FIXME
  foreach ($trainData as $index => &$train) {
    HMIindication($client, "trainFrame ".$index."\n");
    HMIindication($client, "trainDataS ".$index." {".$train["name"]." (".$train["ID"].")} ".$train["lengthFront"]."+".$train["lengthBehind"]."\n");
  }
}

function HMIindication($to, $msg) {// Send indication to specific HMI client
  @fwrite($to,"$msg\n");// FIXME Consider a better solution
//  print ">$msg<\n";
}

function HMIindicationAll($msg) {// Send indication to all HMI client
  global $clients, $clientsData;
  foreach ($clients as $client) {
    if ($clientsData[(int)$client]["type"] == "HMI") {
      HMIindication($client, $msg);
    }
  }
}

?>

