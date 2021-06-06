<?php
// WinterTrain, RBC2
// HMI handlers

// For function processCommandHMI() see RBC2_RBC.php

function updateIndicationHMI() { // Update track indications for all HMI clients
global $trainData, $PT2, $HMI, $trackModel, $triggerHMIupdate;

// Track layout
  foreach ($PT2 as $name => $element) {
    $model = $trackModel[$name];
    switch ($element["element"]) {
      case "SU":
      case "SD":
        HMIindicationAll("signalState $name ".$model->vacancyState." ".$model->routeLockingState." ".$model->routeLockingType." ".
          $model->lockingState." ".$model->blockingState." ".$model->signalState." ".$model->arsState." ".$model->occupationTrainID."\n");
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
  
// Track indication
  foreach ($HMI["baliseTrack"] as $trackName => $baliseTrack) {
    $routeLockingState = R_IDLE;
    $vacancyState = V_CLEAR;
    foreach ($baliseTrack["balises"] as $baliseName ) {
      if ($trackModel[$baliseName]->vacancyState != V_CLEAR) {
        $vacancyState = V_OCCUPIED;
      }
      if ($trackModel[$baliseName]->routeLockingState != R_IDLE) {
        $routeLockingState = R_LOCKED;
      }
    }
    HMIindicationAll("trState $trackName $routeLockingState $vacancyState\n");
  }
  $triggerHMIupdate = false; 
}

function updateTrainDataHMI() { // Update train indications for all HMI clients
global $trainData, $TD_TXT_MODE, $TD_TXT_DIR, $TD_TXT_PWR, $TD_TXT_ACK, $TD_TXT_RTOMODE, $TD_TXT_UNAMB;
  foreach ($trainData as $index => &$train) {
    HMIindicationAll("SRmode ".$index." ".$train["SRallowed"]."\n");
    HMIindicationAll("SHmode ".$index." ".$train["SHallowed"]."\n");
    HMIindicationAll("FSmode ".$index." ".$train["FSallowed"]."\n");
    HMIindicationAll("ATOmode ".$index." ".$train["ATOallowed"]."\n");
    HMIindicationAll("trainDataD ".$index." {".$TD_TXT_MODE[$train["authMode"]]." (".$TD_TXT_MODE[$train["reqMode"]].")} {".$train["baliseName"].
      "} {".$train["distance"]."} {".$TD_TXT_UNAMB[$train["positionUnambiguous"]]."} {".$train["speed"]."} {".$TD_TXT_DIR[$train["nomDir"]].
      "} {".$TD_TXT_PWR[$train["pwr"]]."} {".
      $TD_TXT_ACK[$train["MAreceived"]]."} ".$train["dataValid"]." {".$TD_TXT_RTOMODE[$train["rtoMode"]]."} {".
      $train["MAbaliseName"]."} {".$train["MAdist"]."} {".$TD_TXT_DIR[$train["MAdir"]]."} {".$train["trn"]."} {".$train["trnStatus"]."} {".
      ($train["etd"] != 0 ? date("H:i:s",$train["etd"]) : "")."}\n");
  }
}

function HMIstartup($client) { // Initialise specific HMI client with static comfiguration, screen layout etc.
global $PT2, $HMI, $trainData, $triggerHMIupdate;

  $triggerHMIupdate = true; // Trigger a dynamic update afterwards
  HMIindication($client,".f.canvas delete all\n");
  HMIindication($client,"destroyTrainFrame\n");
  HMIindication($client,"dGrid\n");  
  HMIindication($client,"resetLabel\n");
// Track layout
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
  
// Track indication
  foreach ($HMI["baliseTrack"] as $trackName => $baliseTrack) {
    HMIindication($client,"track $trackName {$baliseTrack["x"]} {$baliseTrack["y"]} {$baliseTrack["l"]} {$baliseTrack["or"]}\n");
  }

// Train data
  foreach ($trainData as $index => &$train) {
    HMIindication($client, "trainFrame ".$index."\n");
    HMIindication($client, "trainDataS ".$index." {".$train["name"]." (".$train["ID"].")} ".$train["lengthFront"]."+".$train["lengthBehind"]."\n");
  }
}

function HMIindication($to, $msg) {// Send indication to specific HMI client
  fwrite($to,"$msg\n");
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

