<?php
// WinterTrain, RBC2
// TrainData handlers

function ProcessTrainData() { //-------------------------------------------------  Analyse Train Data and optional simulation scripts
global $trainData, $trainIndex, $DIRECTORY, $TRAIN_DATA_FILE, $SRallowed, $SHallowed, $FSallowed, $ATOallowed, $simTrain;


  require("$DIRECTORY/$TRAIN_DATA_FILE");
  $simTrain = array();
  foreach($trainData as $index => &$train) {
    $train["SRallowed"] = $SRallowed;
    $train["SHallowed"] = $SHallowed;
    $train["FSallowed"] = $FSallowed;
    $train["ATOallowed"] = $ATOallowed;
    $train["reqMode"] = M_UDEF;
    $train["authMode"] = M_N;
    $train["baliseName"] = "(00:00:00:00:00)"; // PT1 name
    $train["distance"] = 0;
    $train["positionUnambiguous"] = false;
    $train["speed"] = 0;
    $train["nomDir"] = D_UDEF; // nominel driving direction (UP/DOWN)
    $train["pwr"] = P_UDEF;
    $train["MAreceived"] = 0;
    $train["rtoMode"] = RTO_UDEF;
    $train["MAbaliseName"] = "(00:00:00:00:00)";
    $train["MAdist"] = 0;
    $train["MAdir"] = D_UDEF;
    $train["trn"] = "";
    $train["trnStatus"] = TRN_UDEF;
    $train["etd"] = 0;
    $train["index"] = $index; // to know index in functions where only one train data set is handed over. Used? FIXME
    $trainIndex[$train["ID"]] = $index;
        
    switch($train["deployment"]) {
      case "R":
        $train["dataValid"] = "VOID";
      break;
      case "S":
        $train["dataValid"] = "SIM";
        $sim = array();
        $sim["ID"] = $train["ID"]; 
        $sim["name"] = $train["name"];
        $sim["simFile"] = $train["simFile"];
        $simTrain[] = $sim;        
      break;
      case "I":
        unset($trainData[$index]);
      break;
      default:
        print "Warning: Unknown train deployment state: {$train["deployment"]}\n";
    }
  }
  foreach($simTrain as $index => $sim) {
    ProcessSimScript($index);
  }
  $totalTrain = count($trainData);
  $trainData = array_merge($trainData); // reindex trainData to be continuous starting from 0
  print "Count of trains: $totalTrain\n"; // Print or log? FIXME
}

function ProcessSimScript($index) {
global $simTrain, $DIRECTORY, $PT2;
  $sim = &$simTrain[$index];
  $sim["scriptIndex"] = 0;
  $sim["script"][0]["lineNo"] = "<no script>";
  $sim["script"][0]["baliseName"] = "<void balise>";
  $sim["script"][0]["baliseID"] = "00:00:00:00:00";
  $sim["script"][0]["dist"] = 0;
  if(is_readable("$DIRECTORY/{$sim["simFile"]}")) {
    $fh = fopen("$DIRECTORY/{$sim["simFile"]}", "r");
    $lineNo = 0;
    $scriptIndex = 0;
    while ($line = fgets($fh)) {
      $lineNo +=1;
      list($line) = explode("//", trim($line));
      if ($line) {
        list($sim["script"][$scriptIndex]["baliseName"], $sim["script"][$scriptIndex]["dist"]) = explode(" ", $line); // FIXME syntax and semantics check
        if (isset($PT2[$sim["script"][$scriptIndex]["baliseName"]]) and $PT2[$sim["script"][$scriptIndex]["baliseName"]]["element"] == "BL") {
          $sim["script"][$scriptIndex]["baliseID"] = $PT2[$sim["script"][$scriptIndex]["baliseName"]]["ID"];
        } else {
          msgLog("Warning: Unknown balise name >{$sim["script"][$scriptIndex]["baliseName"]}< in simData, file {$simTrain[$index]["simFile"]}.");
        }
        $sim["script"][$scriptIndex]["lineNo"] = $lineNo;
        $scriptIndex +=1;
      }
    }
//    print_r($sim);
  }
}

?>
