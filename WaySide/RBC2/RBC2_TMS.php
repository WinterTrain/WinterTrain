<?php
// WinterTrain, RBC2
// TMS handlers

function processCommandTMS($command) { // Process Commands from TMS engine
global $now, $tmsHB, $tmsStatus, $trainData, $trackModel, $arsEnabled;
  $param = explode(" ",$command);
  switch ($param[0]) {
    case "TMS_HB":
      $tmsHB = $now + TMS_TIMEOUT;
      $tmsStatus = $param[1];
    break;
    case "Hello":
    break;
    case "trnStatus":
      $trainData[$param[1]]["trnStatus"] = $param[2];
    break;
    case "setRoute": // trainIndex, dir, start, destination
print "TMS: trying route {$param[3]} -> {$param[4]}\n";
      if ($arsEnabled and $trackModel[$param[3]]->arsState == ARS_ENABLED) {
        if ($trackModel[$param[4]]->routeLockingState == R_IDLE) {
          if ($trackModel[$param[3]]->cmdSetRouteTo($param[4]) != "") {
            notifyTMS("routeStatus {$param[1]} {$param[2]} ".RS_ROUTE_SET);
          } else {
          // FIXME be more specific on rejection reason: temporary blocked, impossible, inhibited
            notifyTMS("routeStatus {$param[1]} {$param[2]} ".RS_REJECTED); // Route setting rejected          
          }
        } else {
          notifyTMS("routeStatus {$param[1]} {$param[2]} ".RS_BLOCKED); // EP alreadu locked
        }
      } else {
        notifyTMS("routeStatus {$param[1]} {$param[2]} ".RS_ARS_DISABLED);
      }
    break;
    case "setTRN":
      $trainData[$param[1]]["trn"] = $param[2];
    break;
    case "etd":
      $trainData[$param[1]]["etd"] = $param[2];
    break;
    default:
      print "Ups unimplemented TMS command ".$param[0]."\n";
    break;
  }
}

function trainLocationTMS($trainIndex, $startPoint, $runningState, $dir) {
print "TMSloc: $trainIndex $startPoint $runningState $dir\n";
  notifyTMS("trainLoc $trainIndex $startPoint $runningState $dir");
}

function TMSStartup() {
global $inChargeTMS, $trainData, $RBC_VERSION;
 fwrite($inChargeTMS,"Hello this is RBCIL version $RBC_VERSION\n");
  foreach ($trainData as $index => $train) {
    notifyTMS("setTRN {$index} {$train["trn"]}");
  }
}

function notifyTMS($data) {
global $inChargeTMS;
  if ($inChargeTMS) {
    fwrite($inChargeTMS, "$data\n");
  }
}

?>
