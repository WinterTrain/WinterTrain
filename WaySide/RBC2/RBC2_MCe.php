<?php
// WinterTrain, RBC2
// MCe


function processCommandMCe($command, $from) { // Process commands fro MCe clients
global $run, $reloadRBC, $inChargeMCe, $clientsData;
  $param = explode(" ",$command);
  switch ($param[0]) {
    case "Rq": // request operation
      if ($inChargeMCe) {
        MCeIndication($from, "displayResponse {Rejected ".$clientsData[(int)$inChargeMCe]["addr"]." is in charge (since ".
          $clientsData[(int)$inChargeMCe]["inChargeSince"].")}\n");
      } else {
        $inChargeMCe = $from;
        $clientsData[(int)$from]["inChargeSince"] = date("Ymd H:i:s");
        MCeIndication($from, "oprAllowed\n");
      }
    break;
    case "Rl": // release operation
      $inChargeMCe = false;
      MCeIndication($from, "oprReleased\n");
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
    default:
      errLog("Unknown MCe command: {$param[0]}");
  }
}

function generateIndicationsMCe() { // Generate indications for all MCe clients
global $clients, $clientsData, $startTime, $tmsStatus, $TMS_STATUS_TXT;

  foreach ($clients as $client) {
    if ($clientsData[(int)$client]["type"] == "MCe") {
      MCeIndication($client, "set ::serverUptime {".trim(`/usr/bin/uptime`)."}");
      MCeIndication($client, "set ::RBCuptime {".(time() - $startTime)."}");
      MCeIndication($client, "set ::tmsStatus {{$TMS_STATUS_TXT[$tmsStatus]}}"); 
    }
  }
}


function MCeStartup($client) { // Initialise specific MCe client
global $EC;
  MCeIndication($client,"destroyECframe");
  foreach ($EC as  $addr => $ec) {
    MCeIndication($client,"ECframe $addr");
  }
}

function MCeIndication($to, $msg) {// Send specific indication to specific MCe client
  fwrite($to,"$msg\n");
}

?>
