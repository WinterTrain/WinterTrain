<?php
// WinterTrain, OBUng
// DMI handlers

$dmiState = DMI_DISCONNECTED;
$modeSel = $dirSel = $driveSel = $batteryCgarge = $chargeStatus = $rssi = 0;
$indRedOpr = false; $indRed = false; $indYellow = false; $indGreen = false; $meter = 0;
$triggerOBUupdate = true;

function pollDMI() {
  global $inChargeDMI, $indRedOpr, $indRed, $indYellow, $indGreen, $meter;
  if ($inChargeDMI) {
    fwrite($inChargeDMI, "P1230101\n");
    fwrite($inChargeDMI, "S\n"); // FIXME each time??
  }
}

function processCommandDMI($data, $client) {
  global $DMI_ID, $dmiState, $modeSel, $dirSel, $driveSel, $batteryCharge, $chargeStatus, $rssi, $triggerMMIupdate,
    $indRedOpr, $indRed, $indYellow, $indGreen, $meter, $triggerOBUupdate;
//  print("DMI: >$data<\n");
  if ($data != "") {
    $triggerMMIupdate = true; // FIXME always?
    switch ($data[0]) {
      case "L":
        if ($data == "L,$DMI_ID") {
          fwrite($client, "A\n"); // Accept DMI login
          msgLog("DMI \"".substr($data, 2)."\" accepted\n");
          $dmiState = DMI_CONNECTED;
          $triggerOBUupdate = true;
        } else {
          fwrite($client, "R\n"); // Reject DMI login
          msgLog("DMI \"".substr($data, 2)."\" rejected");
          print "Login command rejected >$data< >$DMI_ID<\n";
        }
      break;
      case "S":
        $param = explode(",", $data);
        $batteryCharge = $param[1];
        $chargeStatus = $param[2];
        $rssi = $param[3];
      break;
      case "P":
        $param = explode(",", $data);
        if ($modeSel != $param[1] or $dirSel != $param[2] or $driveSel != $param[3]) {
          $modeSel = $param[1];
          $dirSel = $param[2];
          $driveSel = $param[3];
          $triggerOBUupdate = true;
        }
      break;
      case "O":
      break;
      default:
        print "unknown DMI command >$data<";  
    }
  }
}

?>
