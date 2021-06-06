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
      print "\n";
        foreach ($trackModel as $name => $model) {
          if ($model->elementType == "PF" or $model->elementType == "PT") {
          print "Point $name {$model->pointState}\n";
          }
        }
      }
    break;
    case "CMD3":
      if ($from == $inChargeMCe) {
      }
    break;
    case "CMD4":
      if ($from == $inChargeMCe) {
      $test = !$test;
      $trackModel["P1"]->vacancyState = $test ? V_CLEAR : V_OCCUPIED;
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
    case "posRepDrv":
      $triggerHMIupdate = true;
      $sim = $simTrain[$param[1]]; // FIXME check existance ?
      // processPositionReport($trainID, $requestedMode, $MAreceived, $nomDir, $pwr, $balise, $distance,  $speed, $rtoMode);
      processPositionReport($sim["ID"], $param[2], 1, D_UDEF, P_UDEF, $sim["script"][$sim["scriptIndex"]]["baliseID"], 
        $sim["script"][$sim["scriptIndex"]]["dist"], 1, RTO_UDEF);
      if ($simTrain[$param[1]]["scriptIndex"] < sizeof($simTrain[$param[1]]["script"]) - 1) {
        $simTrain[$param[1]]["scriptIndex"] +=1;
      } 
    break;
    case "posRepSt":
      $triggerHMIupdate = true;
      $sim = $simTrain[$param[1]]; // FIXME check existance ?
      // processPositionReport($trainID, $requestedMode, $MAreceived, $nomDir, $pwr, $balise, $distance,  $speed, $rtoMode);
      processPositionReport($sim["ID"], $param[2], 0, D_UDEF, P_UDEF, $sim["script"][$sim["scriptIndex"]]["baliseID"], 
        $sim["script"][$sim["scriptIndex"]]["dist"], 0, RTO_UDEF);

    break;
    case "posRepUdef":
      $triggerHMIupdate = true;
      $sim = $simTrain[$param[1]]; // FIXME check existance ?
      // processPositionReport($trainID, $requestedMode, $MAreceived, $nomDir, $pwr, $balise, $distance,  $speed, $rtoMode);
      processPositionReport($sim["ID"], $param[2], 0, D_UDEF, P_UDEF, "00:00:00:00:00", 0,  0, RTO_UDEF);

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

function dumpTrackModel() {
global  $trackModel, $TVS_TXT_SH, $RLS_TXT_SH, $RLT_TXT_SH, $RDIR_TXT_SH;
  print "\nRBC2 Track Model Dump ".date("Ymd H:i:s")."\n";
  print "Name     Type TVS  RLS  RLT  DIR\n";
  foreach ($trackModel as $name => $model) {
    printf("%-8.8s %-4.4s %-4.4s %-4.4s %-4.4s %2s\n", $name, $model->elementType, $TVS_TXT_SH[$model->vacancyState], 
      $RLS_TXT_SH[$model->routeLockingState], $RLT_TXT_SH[$model->routeLockingType], $RDIR_TXT_SH[$model->routeLockingUp]);
//    printf("%-8.8s %-4.4s %-4.4s %-4.4s %-4.4s %-2.2s %-8.8s \n", $name, $model->elementType, $model->vacancyState, 
//      $model->routeLockingState, $model->routeLockingType, $model->routeLockingUp, $model->facingUp);
      //$model->neighbourUp->elementName, $model->neighbourDown->elementName);
  }
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
