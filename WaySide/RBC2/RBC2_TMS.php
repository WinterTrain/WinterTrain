<?php
// WinterTrain, RBC2
// TMS handlers

function processCommandTMS($command) { // Process Commands from TMS engine
global $now, $tmsHB, $tmsStatus, $trainData, $trackModel, $arsEnabled, $triggerHMIupdate;
  $triggerHMIupdate = true; // Necessary?? FIXME
  $param = explode("|",$command);
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
    case "setRoute": // trainIndex, start, destination
//print "RBC: TMS request route {$param[2]} -> {$param[3]} for trainIndex {$param[1]} \n";
      if ($arsEnabled and $trackModel[$param[2]]->arsState == ARS_ENABLED) {
        if ($trackModel[$param[3]]->routeLockingState == R_IDLE) {
          $EP = $trackModel[$param[2]]->cmdSetRouteTo($param[3]);
          switch ($EP) {
            case "__R":
              notifyTMS("routeStatus {$param[1]} ".RSR_REJECTED); // Route impossible       
            break;
            case "__B":
            case "__O":
              notifyTMS("routeStatus {$param[1]} ".RSR_BLOCKED); // Route temporary blocked or occupied        
            break;
            case "__I":
              notifyTMS("routeStatus {$param[1]} ".RSR_INHIBITED); // Route temporary          
            break;
            default:
              if ($EP == $param[3]) {
                notifyTMS("routeStatus {$param[1]} ".RSR_ROUTE_SET);
              } else {
                notifyTMS("routeStatus {$param[1]} ".RSR_ROUTE_SET_EXTENDED);
              }
          }
        } else {
          notifyTMS("routeStatus {$param[1]} ".RSR_BLOCKED); // EP alreadu locked
        }
      } else {
        notifyTMS("routeStatus {$param[1]} ".RSR_ARS_DISABLED);
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
//print "TMSloc: $trainIndex $startPoint $runningState $dir\n";
  notifyTMS("trainLoc $trainIndex $startPoint $runningState $dir");
}

function TMSStartup() {
global $inChargeTMS, $trainData, $RBC_VERSION;
  notifyTMS("Hello this is RBC version $RBC_VERSION");
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
