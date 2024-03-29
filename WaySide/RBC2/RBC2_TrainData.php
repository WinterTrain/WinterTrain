<?php
// WinterTrain, RBC2
// TrainData handlers

function ProcessTrainData() { //-------------------------------------------------  Analyse Train Data and optional simulation scripts
  global $trainData, $trainIndex, $DIRECTORY, $TRAIN_DATA_FILE, $SRallowed, $SHallowed, $FSallowed, $ATOallowed, $simTrain;
  $simTrain = array();
  require("$DIRECTORY/$TRAIN_DATA_FILE");
  foreach($trainData as $index => &$train) { // FIXME check completeness of train data
    $train["reqMode"] = M_UDEF;
    $train["authMode"] = M_N;
    $train["baliseName"] = "(00:00:00:00:00)"; // PT1 name of LRBG
    $train["prevBaliseName"] = "";
    $train["baliseID"] = "00:00:00:00:00"; // LRBG
    $train["distance"] = 0;
    $train["maxSpeed"] = 0; // Resulting allowed speed
    if (!isset($train["SRmaxSpeed"])) $train["SRmaxSpeed"] = SR_MAX_SPEED_DEFAULT;
    if (!isset($train["SHmaxSpeed"])) $train["SHmaxSpeed"] = SH_MAX_SPEED_DEFAULT;
    if (!isset($train["FSmaxSpeed"])) $train["FSmaxSpeed"] = FS_MAX_SPEED_DEFAULT;
    if (!isset($train["ATOmaxSpeed"])) $train["ATOmaxSpeed"] = ATO_MAX_SPEED_DEFAULT;
    $train["curPositionValid"] = false; // True if determined position is reliable - that is: reported valid by OBU and unambiguous
    $train["curPositionUnambiguous"] = true;
    $train["curOccupation"] = array();
    $train["speed"] = 0;
    $train["comTimeStamp"] = 0;
    $train["posTimeStamp"] = 0;
    $train["posRestored"] = true; // To prevent position restore until a real position report has been received.
    $train["posRestoredLogged"] = false;
    $train["restoreCount"] = 0;
    $train["driveDir"] = 1; // Nominel driving direction (UP/DOWN) determined by OBU
    $train["prevDriveDir"] = 1;
    $train["pwr"] = 0;
    $train["front"] = D_UP;
    $train["rtoMode"] = RTO_UDEF;
    $train["MAreceived"] = 0;
    $train["MAbalise"] = "00:00:00:00:00";
    $train["MAbaliseName"] = "(00:00:00:00:00)";
    $train["MAdist"] = 0;
    $train["MAdir"] = MD_NODIR; //  MD or D FIXME
    $train["MAdir"] = D_UDEF;
    $train["assignedRoute"] = "";
    $train["trn"] = ""; // Train running number
    $train["trnStatus"] = TRN_UNASSIGNED;
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
        $sim["trainIndex"] = $index;
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
    processSimScript($index);
  }
  $totalTrain = count($trainData);
  $trainData = array_merge($trainData); // reindex trainData to be continuous array starting from 0
  print "Count of trains: $totalTrain\n"; // Print or log? FIXME
}

function processSimScript($index) {
  global $simTrain, $DIRECTORY, $PT2;
  $sim = &$simTrain[$index];
  $sim["scriptIndex"] = 0;
  $sim["script"][0]["lineNo"] = "<no script>";
  $sim["script"][0]["baliseName"] = "<void balise>";
  $sim["script"][0]["baliseID"] = "00:00:00:00:00";
  $sim["script"][0]["dist"] = 0;
  $sim["pwr"] = P_UDEF;
  $sim["driveDir"] = D_UDEF;
  if(is_readable("$DIRECTORY/{$sim["simFile"]}")) {
    $fh = fopen("$DIRECTORY/{$sim["simFile"]}", "r");
    $lineNo = 0;
    $scriptIndex = 0;
    // FIXME syntax and semantics check
    while ($line = fgets($fh)) {
      $lineNo +=1;
      list($line) = explode("//", trim($line)); // Remove any comments
      if ($line) {
        list($p1, $p2) = explode(" ", $line);
        switch ($p1) {
          case "pwr":
          case "PWR":
            $sim["pwr"] = $p2;
          break;
          case "driveDir": // Nominel driving direction UP or DOWN
            $sim["driveDir"] = $p2;
          break;
          default: // Train movement
            $sim["script"][$scriptIndex]["baliseName"] = $p1;
            $sim["script"][$scriptIndex]["dist"] = $p2;
            if (isset($PT2[$sim["script"][$scriptIndex]["baliseName"]]) and $PT2[$sim["script"][$scriptIndex]["baliseName"]]["element"] == "BL") {
              $sim["script"][$scriptIndex]["baliseID"] = $PT2[$sim["script"][$scriptIndex]["baliseName"]]["ID"];
            } else {
              msgLog("Warning: Unknown balise name >{$sim["script"][$scriptIndex]["baliseName"]}< in simData, file {
              ".$simTrain[$index]["simFile"].".");
            }
            $sim["script"][$scriptIndex]["lineNo"] = $lineNo;
            $scriptIndex +=1;
          break;
        }
      }
    }
  }
}

?>
