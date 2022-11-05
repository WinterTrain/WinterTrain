<?php
// WinterTrain, RBC2
// MCe handlers

function processCommandMCe($command, $from) { // ------------------------------------------ Process commands from MCe clients
  global $run, $reloadRBC, $inChargeMCe, $clientsData, $EC, $trackModel, $test, $triggerMCeUpdate, $triggerHMIupdate, $simTrain,
    $automaticPointThrowEnabled, $DIRECTORY, $PT2_FILE, $BL_FILE, $PT2, $baliseCountUnassigned, $balisesID;
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
      if ($from == $inChargeMCe) { // Toggle automatic point throw
        $automaticPointThrowEnabled = !$automaticPointThrowEnabled;
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
    case "exitTMS":
      if ($from == $inChargeMCe) {
        notifyTMS("exitTMS");
      }
    break;
    case "ECstatus":
      foreach($EC as $addr => $ec) {
        requestECstatus($addr);
      }
    break;
    case "aBN": // assign balise name
      if ($from == $inChargeMCe) {
        $baliseID = $param[1];
        $baliseName = (isset($param[2]) ? $param[2] : "<udef>");
        if ($baliseID != "--:--:--:--:--") { // default in MCe after startup
          if (isset($PT2[$baliseName]) and $PT2[$baliseName]["element"] == "BL" ) {
            if (isset($balisesID[$PT2[$baliseName]["ID"]])) {
              unset($balisesID[$PT2[$baliseName]["ID"]]);
            }
            if (isset($balisesID[$baliseID])) {
              $PT2[$balisesID[$baliseID]]["ID"] = "FF:FF:FF:FF:FF";
              $PT2[$balisesID[$baliseID]]["dynName"] = true;
            }
            $PT2[$baliseName]["ID"] = $baliseID;
            $balisesID[$baliseID] = $baliseName;
            $PT2[$baliseName]["dynName"] = true;
            $hhtBaliseStatus = "OK";
          } else {
            $hhtBaliseStatus = "Unknown balise";
          }
          $baliseCountUnassigned = 0;
          foreach ($PT1 as $name => $element) {
            if ($element["element"] == "BL" and $element["ID"] == "FF:FF:FF:FF:FF") $baliseCountUnassigned++;
          }
        } // else ignore
      }
    break;    case "dBL": // dump balise list
      if ($blFh = fopen("$DIRECTORY/$BL_FILE","w")) {
        fwrite($blFh, "<?php
// Balise list generated by RBC
\$BL_PT2_FILE = \"".(realpath("$DIRECTORY/$PT2_FILE"))."\";
\$BL_GENERATION_TIME = \"".(date("Y-m-d H:i:s"))."\";

// -------------------------------------------------- Full balise List
\$baliseList = [
");
      foreach ($PT2 as $name => $element) {
        if ($element["element"] == "BL") {
          fwrite($blFh,"\"$name\" => [\"ID\" => \"{$element["ID"]}\",
           \"dynName\" => ".($element["dynName"] ? "true" : "false")."],\n");
        }
      }
      fwrite($blFh,"];
?>");
      fclose($blFh);
      } else {
        errLog("Warning: Cannot open Balise List file: $DIRECTORY/$BL_FILE");
      }
    break;
// ------------------------------------------------------------------------------------------------ Train SIM
    case "posRepDrv":
      $triggerHMIupdate = true;
      $sim = $simTrain[$param[1]]; // FIXME check existance ?
      // syntax: processPositionReport($trainID, $requestedMode, $MAreceived, $driveDir, $pwr, $frontUp, $balise, $distance,  $speed, $rtoMode);
      processPositionReport($sim["ID"], $param[2], 1, $sim["driveDir"], $sim["pwr"], $sim["script"][$sim["scriptIndex"]]["baliseID"], 
        $sim["script"][$sim["scriptIndex"]]["dist"], 1, RTO_DMI);
      if ($simTrain[$param[1]]["scriptIndex"] < sizeof($simTrain[$param[1]]["script"]) - 1) {
        $simTrain[$param[1]]["scriptIndex"] +=1;
      } 
    break;
    case "posRepSt":
      $triggerHMIupdate = true;
      $sim = $simTrain[$param[1]]; // FIXME check existance ?
      // syntax: processPositionReport($trainID, $requestedMode, $MAreceived, $driveDir, $pwr,  $frontUp,$balise, $distance,  $speed, $rtoMode);
      processPositionReport($sim["ID"], $param[2], 0, D_STOP, $sim["pwr"], 1, $sim["script"][$sim["scriptIndex"]]["baliseID"], 
        $sim["script"][$sim["scriptIndex"]]["dist"], 0, RTO_DMI);

    break;
    case "posRepUdef":
      $triggerHMIupdate = true;
      $sim = $simTrain[$param[1]]; // FIXME check existance ?
      // syntax: processPositionReport($trainID, $requestedMode, $MAreceived, $driveDir, $pwr,  $frontUp,$balise, $distance,  $speed, $rtoMode);
      processPositionReport($sim["ID"], $param[2], 0, D_STOP, $sim["pwr"], 1, "01:00:00:00:01", 0,  0, RTO_DMI);

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
  global $EC, $startTime, $tmsStatus, $TMS_STATUS_TXT, $TD_TXT_MADIR, $triggerMCeUpdate, $simTrain, $trainData, $hhtBaliseID, $hhtBaliseName,
    $hhtBaliseStatus, $baliseCountTotal, $baliseCountUnassigned;
  MCeIndicationAll("set ::serverUptime {".trim(`/usr/bin/uptime`)."}");
  MCeIndicationAll("set ::RBCuptime {".prettyPrintTime(time() - $startTime)."}");
  MCeIndicationAll("set ::tmsStatus {{$TMS_STATUS_TXT[$tmsStatus]}}"); 
  MCeIndicationAll("set ::baliseID {{$hhtBaliseID}}\n");
  MCeIndicationAll("set ::baliseName {{$hhtBaliseName}}\n");
  MCeIndicationAll("set ::baliseStatus {{$hhtBaliseStatus}}\n");
  MCeIndicationAll("set ::baliseCount {{$baliseCountTotal}/{$baliseCountUnassigned}}\n");
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
    $train = $trainData[$sim["trainIndex"]];
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
  global  $trackModel, $TVS_TXT_SH, $RLS_TXT_SH, $RLT_TXT_SH, $RDIR_TXT_SH, $PS_TXT_SH, $SIGNALLING_TXT_SH;
  print "\nRBC2 Track Model Dump ".date("Ymd H:i:s")."\n";
  print "Name     Type TVS  RLS  RLT  DIR P  Train Sig Occup\n";
  foreach ($trackModel as $name => $model) {
    $lines[] = sprintf("%-8.8s %-4.4s %-4.4s %-4.4s %-4.4s %2s  %2.2s %5.5s %2.2s %s\n", $name, $model->elementType, $TVS_TXT_SH[$model->vacancyState],
      $RLS_TXT_SH[$model->routeLockingState], $RLT_TXT_SH[$model->routeLockingType], $RDIR_TXT_SH[$model->routeLockingUp],
        (($model->elementType == "PF" or $model->elementType == "PT") ?
          ($PS_TXT_SH[$model->pointState].($model->throwLockedByRoute ? "!" : " ")) : ""),
        (($model->elementType == "SU" or $model->elementType == "SD" or $model->elementType == "BSB" or $model->elementType == "BSE") ?
          $model->assignedTrain : "" ),
        (($model->elementType == "SU" or $model->elementType == "SD") ?
          $SIGNALLING_TXT_SH[$model->signallingState] : ""), $model->occupationTrainID);

  }
  sort($lines, SORT_NATURAL);
  foreach ($lines as $line) {
    print "$line";
  }
  print "\n";
  dumpBaliseStat();
}

function dumpBaliseStat() {
  global $baliseStat;
  print "BaliseStat
Name    20  22  24
";
  
  foreach ($baliseStat as $name => $countTrain) {
    printf("%-5s  ", $name);
    foreach ($countTrain as $count) {
      printf("%3d ", $count);
    }
    print("\n");
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
