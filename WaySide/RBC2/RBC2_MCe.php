<?php
// WinterTrain, RBC2
// MCe handlers

function processCommandMCe($command, $from) { // ------------------------------------------ Process commands from MCe clients
  global $run, $reloadRBC, $inChargeMCe, $clientsData, $EC, $trackModel, $test, $triggerMCeUpdate, $triggerHMIupdate, $simTrain,
    $automaticPointThrowEnabled, $DIRECTORY, $PT2_FILE, $BL_FILE, $PT2, $baliseCountUnassigned, $balisesID, $inChargeHMI, $dumpActive, $dumpFh,
    $MCeBaliseReader, $MCeBaliseID, $MCeBaliseName;
  $triggerMCeUpdate = true;
  $param = explode(" ",$command);
  switch ($param[0]) {
    case "Rq": // Request operation
      if ($inChargeMCe) { // FIXME re-arrange flow as for HMI
        MCeIndication($from, "displayResponse {Rejected ".$clientsData[(int)$inChargeMCe]["addr"]." is in charge (since ".
          $clientsData[(int)$inChargeMCe]["inChargeSince"].")}\n"); // FIXME add userName
      } else { // FIXME add user check
        $inChargeMCe = $from;
        $clientsData[(int)$from]["inChargeSince"] = date("Ymd H:i:s");
        $clientsData[(int)$from]["activeAt"] = date("Ymd H:i:s");
        $clientsData[(int)$from]["userName"] = isset($param[1]) ? $param[1] : "<unknown>";
        MCeIndication($from, "oprAllowed\n");
      }
    break;
    case "Rl": // Release operation
      if ($from == $inChargeMCe) {
        $clientsData[(int)$from]["activeAt"] = date("Ymd H:i:s");
        $inChargeMCe = false;
        MCeIndication($from, "oprReleased\n");
      }
    break;
    case "CMD1": // DumpTrackModel
      if ($from == $inChargeMCe) {
        $clientsData[(int)$from]["activeAt"] = date("Ymd H:i:s");
        dumpTrackModel();
      }
    break;
    case "CMD2":
      if ($from == $inChargeMCe) {
        $clientsData[(int)$from]["activeAt"] = date("Ymd H:i:s");
        dumpRoutes();
      }
    break;
    case "CMD3":
      if ($from == $inChargeMCe) {
        $clientsData[(int)$from]["activeAt"] = date("Ymd H:i:s");
        dumpTrainData();
      }
    break;
    case "CMD4":
      if ($from == $inChargeMCe) { // Toggle automatic point throw
        $clientsData[(int)$from]["activeAt"] = date("Ymd H:i:s");
        $automaticPointThrowEnabled = !$automaticPointThrowEnabled;
      }
    break;
    case "CMD5":
      if ($from == $inChargeMCe) {
        $clientsData[(int)$from]["activeAt"] = date("Ymd H:i:s");
        HMIindication($inChargeHMI, "oprReleased");
        HMIindication($inChargeHMI, "displayResponse {--- Operation released by maintenance operator ---}");        
        $inChargeHMI = false;
      }
    break;
    case "CMD6":
      if ($from == $inChargeMCe) {
        $clientsData[(int)$from]["activeAt"] = date("Ymd H:i:s");
      }
      print "gryf $gryf\n";
    break;
    case "CMD7":
      if ($from == $inChargeMCe) {
        $clientsData[(int)$from]["activeAt"] = date("Ymd H:i:s");
      }
    break;
    case "CMD8":
      if ($from == $inChargeMCe) {
        $clientsData[(int)$from]["activeAt"] = date("Ymd H:i:s");
        $dumpActive = !$dumpActive;
        fwrite($dumpFh, date("Ymd H:i:s").($dumpActive ? " Start dump" : " End dump")."\n"); 
      }
    break;
    case "exitRBC":
      if ($from == $inChargeMCe) {
        $clientsData[(int)$from]["activeAt"] = date("Ymd H:i:s");
        $run = false;
      }
    break;
    case "rlRBC":
      if ($from == $inChargeMCe) {
        $clientsData[(int)$from]["activeAt"] = date("Ymd H:i:s");
        $reloadRBC = true;
      }
    break;
    case "exitTMS":
      if ($from == $inChargeMCe) {
        $clientsData[(int)$from]["activeAt"] = date("Ymd H:i:s");
        notifyTMS("exitTMS");
      }
    break;
    case "ECstatus":
      if ($from == $inChargeMCe) {
        $clientsData[(int)$from]["activeAt"] = date("Ymd H:i:s");
        foreach($EC as $addr => $ec) {
          requestECstatus($addr);
        }
      }
    break;
    case "blR": // Select balise reader
      $MCeBaliseReader = $param[1];
      MCeIndicationAll("set ::MCeBaliseReader {{$MCeBaliseReader}}\n");
      $MCeBaliseID = "<udef>";
      $MCeBaliseName = "<udef>";
      print ">$command<\n";
    break;
    case "aBN": // assign balise name
      if ($from == $inChargeMCe) {
        $clientsData[(int)$from]["activeAt"] = date("Ymd H:i:s");
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
          foreach ($PT2 as $name => $element) {
            if ($element["element"] == "BL" and $element["ID"] == "FF:FF:FF:FF:FF") $baliseCountUnassigned++;
          }
        } // else ignore
      }
    break;
    case "dBL": // dump balise list
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
    $hhtBaliseStatus, $baliseCountTotal, $baliseCountUnassigned, $MCeBaliseID, $MCeBaliseReader, $MCeBaliseName;
  MCeIndicationAll("set ::serverUptime {".trim(`/usr/bin/uptime`)."}");
  MCeIndicationAll("set ::RBCuptime {".prettyPrintTime(time() - $startTime)."}");
  MCeIndicationAll("set ::tmsStatus {{$TMS_STATUS_TXT[$tmsStatus]}}"); 
//  MCeIndicationAll("set ::baliseID {{$hhtBaliseID}}\n");
//  MCeIndicationAll("set ::baliseName {{$hhtBaliseName}}\n");
  MCeIndicationAll("set ::baliseID {{$MCeBaliseID}}\n");
  MCeIndicationAll("set ::baliseName {{$MCeBaliseName}}\n");
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
  global $EC, $simTrain, $triggerMCeUpdate, $trainData;
  MCeIndication($client, "destroyDynFrame");
  foreach ($EC as  $addr => $ec) {
    MCeIndication($client, "ECframe $addr");
  }
// If client already is in charge, initialize train frame with various buttons enabled FIXME
  foreach ($simTrain as  $index => $train) {
    MCeIndication($client, "SimFrame $index {$train["name"]} {$train["ID"]}");
  }
  $trains = "HHT";
  foreach ($trainData as $index => $train) {
    $trains .= " ".$train["name"];
  }
  // Update Balise reader selector
  MCeIndication($client, "destroy .f.fStatus.balise.selector"); // Move to client program
  MCeIndication($client, "grid [ttk::combobox .f.fStatus.balise.selector -values \"$trains\" -textvariable baliseReader] -column 2 -row 0 -padx 5 -pady 5 -sticky we
");

  $triggerMCeUpdate = true; 
}

function MCeIndication($to, $msg) {// Send indication to specific MCe client
  @fwrite($to,"$msg\n"); // FIXME Consider a better solution
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
  debugPrint("\n".date("Ymd H:i:s")." RBC2 Train Data Dump");
  debugPrint("ID Route    MAbalise         Dist Occupation");
  foreach ($trainData as $index => $train) {
    $line = sprintf("%2.2s %-8.8s %-16.16s %4.4s ", $train["ID"], $train["assignedRoute"], $train["MAbaliseName"], $train["MAdist"]);
    if (!$train["curPositionUnambiguous"]) $line .= "Amb! ";
    foreach ($train["curOccupation"] as $elementName) {
      $line .= "$elementName ";
    }
    debugPrint($line);
  }
}

function dumpRoutes() {
  global $trackModel;
  debugPrint("\n".date("Ymd H:i:s")." RBC2 Route Dump");
  debugPrint("EP       Train Route");
  foreach ($trackModel as $name => $model) {
    switch($model->elementType) {
      case "SU":
      case "SD":
      case "BSB":
      case "BSE":
        if ($model->routeLockingType == RT_END_POINT) {
          debugPrint(sprintf("%-8.8s %2.2s    ", $name, $model->assignedTrain).($model->routeLockingUp ? "U" : "D")." ".$model->dumpRoute());
        }
      break;
      default:
      break;
    }
  }
  debugPrint("");
}

function dumpTrackModel() {
  global  $trackModel, $TVS_TXT_SH, $RLS_TXT_SH, $RLT_TXT_SH, $RDIR_TXT_SH, $PS_TXT_SH, $SIGNALLING_TXT_SH;
  debugPrint("\n".date("Ymd H:i:s")." RBC2 Track Model Dump");
  debugPrint("Name     Type TVS  RLS  RLT  DIR P  Train Sig Occup");
  foreach ($trackModel as $name => $model) {
    $lines[] = sprintf("%-8.8s %-4.4s %-4.4s %-4.4s %-4.4s %2s  %2.2s %5.5s %2.2s %s", $name, $model->elementType, $TVS_TXT_SH[$model->vacancyState],
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
    debugPrint("$line");
  }
  debugPrint("");
  dumpBaliseStat();
}

function dumpBaliseStat() {
  global $baliseStat;
  debugPrint("Balise Statistics
Name    20  22  24");
  
  foreach ($baliseStat as $name => $countTrain) {
    $line = sprintf("%-5s  ", $name);
    foreach ($countTrain as $count) {
      $line .= sprintf("%3d ", $count);
    }
    debugPrint($line);
  }
}

function prettyPrintTime($sec) {
  if (is_numeric($sec)) {
  $s = $sec % 60;
  $min = ($sec - $s) / 60;
  $m = $min % 60;
  $hour = ($min - $m) / 60;
  $h = $hour % 24;
  $d = ($hour - $h) / 24;
  return "$d-$h:$m:$s";
  } else {
    return "*-*:*:*";
  }
}

?>
