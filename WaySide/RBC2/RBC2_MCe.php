<?php
// WinterTrain, RBC2
// MCe handlers


function processCommandMCe($command, $from) { // ------------------------------------------ Process commands from MCe clients
global $run, $reloadRBC, $inChargeMCe, $clientsData, $EC, $trackModel, $test, $triggerMCeUpdate, $triggerHMIupdate, $simTrain;

  $triggerMCeUpdate = true;
  $param = explode(" ",$command);
  switch ($param[0]) {
    case "Rq": // Request operation
      if ($inChargeMCe) {
        MCeIndication($from, "displayResponse {Rejected ".$clientsData[(int)$inChargeMCe]["addr"]." is in charge (since ".
          $clientsData[(int)$inChargeMCe]["inChargeSince"].")}\n");
      } else {
        $inChargeMCe = $from;
        $clientsData[(int)$from]["inChargeSince"] = date("Ymd H:i:s");
        MCeIndication($from, "oprAllowed\n");
      }
    break;
    case "Rl": // Release operation
      $inChargeMCe = false;
      MCeIndication($from, "oprReleased\n");
    break;
    case "CMD1": // DumpTrackModel
      if ($from == $inChargeMCe) {
        dumpTrackModel();
      }
    break;
    case "CMD2":
      if ($from == $inChargeMCe) {
        dumpRoutes();
      }
    break;
    case "CMD3":
      if ($from == $inChargeMCe) {
        dumpTrainData();
      }
    break;
    case "CMD4":
      if ($from == $inChargeMCe) { 
      }
    break;
    case "exitRBC":
      if ($from == $inChargeMCe) {
        $run = false;
      }
    break;
    case "rlRBC":
      if ($from == $inChargeMCe) {
        $reloadRBC = true;
      }
    break;
    case "ECstatus":
      foreach($EC as $addr => $ec) {
        requestECstatus($addr);
      }
    break;
// ------------------------------------------------------------------------------------------------ Train SIM
    case "posRepDrv":
      $triggerHMIupdate = true;
      $sim = $simTrain[$param[1]]; // FIXME check existance ?
      // syntax: processPositionReport($trainID, $requestedMode, $MAreceived, $nomDir, $pwr, $balise, $distance,  $speed, $rtoMode);
      processPositionReport($sim["ID"], $param[2], 1, $sim["nomDir"], $sim["pwr"], $sim["script"][$sim["scriptIndex"]]["baliseID"], 
        $sim["script"][$sim["scriptIndex"]]["dist"], 1, RTO_UDEF);
      if ($simTrain[$param[1]]["scriptIndex"] < sizeof($simTrain[$param[1]]["script"]) - 1) {
        $simTrain[$param[1]]["scriptIndex"] +=1;
      } 
    break;
    case "posRepSt":
      $triggerHMIupdate = true;
      $sim = $simTrain[$param[1]]; // FIXME check existance ?
      // syntax: processPositionReport($trainID, $requestedMode, $MAreceived, $nomDir, $pwr, $balise, $distance,  $speed, $rtoMode);
      processPositionReport($sim["ID"], $param[2], 0, D_STOP, $sim["pwr"], $sim["script"][$sim["scriptIndex"]]["baliseID"], 
        $sim["script"][$sim["scriptIndex"]]["dist"], 0, RTO_UDEF);

    break;
    case "posRepUdef":
      $triggerHMIupdate = true;
      $sim = $simTrain[$param[1]]; // FIXME check existance ?
      // syntax: processPositionReport($trainID, $requestedMode, $MAreceived, $nomDir, $pwr, $balise, $distance,  $speed, $rtoMode);
      processPositionReport($sim["ID"], $param[2], 0, D_UDEF, $sim["pwr"], "00:00:00:00:00", 0,  0, RTO_UDEF);

    break;
    case "reloadSim":
      ProcessSimScript($param[1]);
    break;
    case "nextSim":
      if ($simTrain[$param[1]]["scriptIndex"] < sizeof($simTrain[$param[1]]["script"]) - 1) {
        $simTrain[$param[1]]["scriptIndex"] +=1;
      }      
    break;
    case "prevSim":
      if ($simTrain[$param[1]]["scriptIndex"] > 0) {
        $simTrain[$param[1]]["scriptIndex"] -=1;
      }      
    break;
    default:
      errLog("Unknown MCe command: $command");
  }
}

function UpdateIndicationMCe() { // Update indications for all MCe clients
global $EC, $startTime, $tmsStatus, $TMS_STATUS_TXT, $triggerMCeUpdate, $simTrain;

  MCeIndicationAll("set ::serverUptime {".trim(`/usr/bin/uptime`)."}");
  MCeIndicationAll("set ::RBCuptime {".prettyPrintTime(time() - $startTime)."}");
  MCeIndicationAll("set ::tmsStatus {{$TMS_STATUS_TXT[$tmsStatus]}}"); 
  foreach($EC as $addr => $ec) {
    MCeIndicationAll("set ::EConline($addr) ".($ec["EConline"] ? "Online" : "Offline"));
    MCeIndicationAll("set ::ECuptime($addr) ".prettyPrintTime($ec["uptime"]));
    MCeIndicationAll("set ::elementConf($addr) {$ec["elementConf"]}");
    MCeIndicationAll("set ::N_ELEMENT($addr) {$ec["N_ELEMENT"]}");
    MCeIndicationAll("set ::N_PDEVICE($addr) {$ec["N_PDEVICE"]}");
    MCeIndicationAll("set ::N_UDEVICE($addr) {$ec["N_UDEVICE"]}");
    MCeIndicationAll("set ::N_LDEVICE($addr) {$ec["N_LDEVICE"]}");
  }
  foreach ($simTrain as $index => $sim) {
    $script = $sim["script"][$sim["scriptIndex"]];
    MCeIndicationAll("set ::simNextPos($index) {{$script["lineNo"]}: {$script["baliseName"]} {$script["dist"]}}");
  }
  $triggerMCeUpdate = false;
}

function MCeStartup($client) { // Initialise specific MCe client with static data
global $EC, $simTrain, $triggerMCeUpdate;
  MCeIndication($client, "destroyDynFrame");
  foreach ($EC as  $addr => $ec) {
    MCeIndication($client, "ECframe $addr");
  }
// If client already is in charge, initialize train frame with various buttons enabled FIXME
  foreach ($simTrain as  $index => $train) {
    MCeIndication($client, "SimFrame $index {$train["name"]} {$train["ID"]}");
  }
  $triggerMCeUpdate = true; 
}

function MCeIndication($to, $msg) {// Send indication to specific MCe client
  fwrite($to,"$msg\n");
}

function MCeIndicationAll($msg) {// Send indication to all MCe client
global $clients, $clientsData;
  foreach ($clients as $client) {
    if ($clientsData[(int)$client]["type"] == "MCe") {
      MCeIndication($client, $msg);
    }
  }
}

function dumpTrainData() {
global $trainData;
  print "\nRBC2 Train Data Dump ".date("Ymd H:i:s")."\n";
  print "ID Route    MAbalise         Dist Occupation\n";
  foreach ($trainData as $index => $train) {
    printf("%2.2s %-8.8s %-16.16s %4.4s ", $train["ID"], $train["assignedRoute"], $train["MAbaliseName"], $train["MAdist"]);
    if (!$train["curPositionUnambiguous"]) print "Amb! ";
    foreach ($train["curOccupation"] as $elementName) {
      print "$elementName ";
    }
    print "\n";
  }
}

function dumpRoutes() {
global $trackModel;
  print "\nRBC2 Route Dump ".date("Ymd H:i:s")."\n";
  print "EP       Train Route\n";
  foreach ($trackModel as $name => $model) {
    switch($model->elementType) {
      case "SU":
      case "SD":
      case "BSB":
      case "BSE":
        if ($model->routeLockingType == RT_END_POINT) {
          printf ("%-8.8s %2.2s    ", $name, $model->assignedTrain);
          print ($model->routeLockingUp ? "U" : "D")." ".$model->dumpRoute()."\n";
        }
      break;
      default:
      break;
    }
  }
  print "\n";
}

function dumpTrackModel() {
global  $trackModel, $TVS_TXT_SH, $RLS_TXT_SH, $RLT_TXT_SH, $RDIR_TXT_SH, $PS_TXT_SH;
  print "\nRBC2 Track Model Dump ".date("Ymd H:i:s")."\n";
  print "Name     Type TVS  RLS  RLT  DIR P Train\n";
  foreach ($trackModel as $name => $model) {
    printf("%-8.8s %-4.4s %-4.4s %-4.4s %-4.4s %2s  %1.1s %2.2s\n", $name, $model->elementType, $TVS_TXT_SH[$model->vacancyState], 
      $RLS_TXT_SH[$model->routeLockingState], $RLT_TXT_SH[$model->routeLockingType], $RDIR_TXT_SH[$model->routeLockingUp],
        (($model->elementType == "PF" or $model->elementType == "PT") ? $PS_TXT_SH[$model->pointState] : ""),
        (($model->elementType == "SU" or $model->elementType == "SD" or $model->elementType == "BSB" or $model->elementType == "BSE") ?
          $model->assignedTrain : "" ));
//    printf("%-8.8s %-4.4s %-4.4s %-4.4s %-4.4s %-2.2s %-8.8s \n", $name, $model->elementType, $model->vacancyState, 
//      $model->routeLockingState, $model->routeLockingType, $model->routeLockingUp, $model->facingUp);
      //$model->neighbourUp->elementName, $model->neighbourDown->elementName);
  }
  print "\n";
}

function prettyPrintTime($sec) {
  $s = $sec % 60;
  $min = ($sec - $s) / 60;
  $m = $min % 60;
  $hour = ($min - $m) / 60;
  $h = $hour % 24;
  $d = ($hour - $h) / 24;
  return "$d:$h:$m:$s";
}

?>
