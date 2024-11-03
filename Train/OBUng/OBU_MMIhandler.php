<?php
// WinterTrain, OBUng
// MMI handlers

$MMIdisplayBuffer = array();
$HMIbuttons = [
  0 => [["lable" => "Cabin", "cmd" => "cabin"], ["lable" => "DrvCabA", "cmd" => "cabA"], ["lable" => "DrvCabB", "cmd" => "cabB"],
       ],
  1 => [["lable" => "FrontLight A", "cmd" => "FLA"], ["lable" => "FrontLightHigh A", "cmd" => "FLHA"],
       ["lable" => "RearLight A", "cmd" => "RLA"], 
       ["lable" => "FrontLight B", "cmd" => "FLB"], ["lable" => "FrontLightHigh B", "cmd" => "FLHB"],
       ["lable" => "RearLight B", "cmd" => "RLB"],
       ],
  2 => [["lable" => "Emergency STOP", "cmd" => "STOP"], ["lable" => "Reload TrainData", "cmd" => "RL"],
       ],
];
$buttonRow = 6;

function MMIdisplay() {
  global $MMIdisplayBuffer, $OBUstart, $dmiState, $connectedHWbackend, $modeSel, $dirSel, $driveSel, $batteryCharge, $chargeStatus,
    $rssi, $DMI_TXT_MODE, $DMI_TXT_DIR, $DMI_TXT_DRIVE, $emergencyStop, $directionForward, $motorControl,
    $activeOBUprofile, $activeOBUprofileID, $activeLocoProfile, $activeLocoProfileID, $activeRunningProfile, $activeRunningProfileID;
  $MMIdisplayBuffer = [
    ["Time:", date("Y-m-d H:i:s"), "", "", "OBU Uptime:", prettyPrintTime(time() - $OBUstart), "", ""],
    ["Train:", "OBU: {$activeOBUprofile["profileName"]} ($activeOBUprofileID)",
        "Loco: {$activeLocoProfile["profileName"]} ($activeLocoProfileID)",
        "RunProfile: ","{$activeRunningProfile["profileName"]} ($activeRunningProfileID)"],
    ["DMI: ", ($dmiState == 1 ? "Connected" : "-"), "modeSel: {$DMI_TXT_MODE[$modeSel]}", "dirSel: {$DMI_TXT_DIR[$dirSel]}",
        "driveSel: {$DMI_TXT_DRIVE[$driveSel]}", "BatteryCharge: $batteryCharge", "chargeStatus: $chargeStatus", "RSSI: $rssi"],
    ["HW backend: ", $connectedHWbackend ? "Connected" : "-", "", ""],
    ["Loco: ", "maxPWM {$activeLocoProfile["maxMotorControl"]}", "Motor: $motorControl", "Dir: ".($directionForward == FORWARD ? "Forward" : "Reverse"), ],
    [$emergencyStop ? "Emergency STOP" : ""],
  ];
}

function MMIupdate($client) {
  global $MMIdisplayBuffer;
  foreach ($MMIdisplayBuffer as $r => $row) {
    foreach ($row as $c => $column) {
      fwrite($client, "set ::displayBuffer($r)($c) {{$column}}\n");
    }
  }
}

function MMIupdateAll() {
  global $clients, $clientsData, $triggerMMIupdate;
  MMIdisplay();
  foreach ($clients as $client) {
    if ($clientsData[(int)$client]["type"] == "MMI") {
      MMIupdate($client);
    }
  }
  $triggerMMIupdate = false;
}

function MMIstartup($client) {
  global $MMIdisplayBuffer, $HMIbuttons, $buttonRow;
  print("MMI startup\n");
  MMIdisplay();
  /*
  $nRow = sizeof($MMIdisplayBuffer); // FIXME ???
  $nColumn = 0;
  foreach ($MMIdisplayBuffer as $row) {
    $c = sizeof($row);
    if ($c > $nColumn) $nColumn = $c; // FIXME ???
  }
  */
  fwrite($client, "destroy .f.buf.buffer\n");
  fwrite($client, "grid [ttk::frame .f.buf.buffer -padding \"3 3 12 12\" -relief solid -borderwidth 2] -column 0 -row 0 -sticky nwes
\n");
  foreach ($MMIdisplayBuffer as $r => $row) {
    foreach ($row as $c => $column) {
      fwrite($client, "grid [ttk::label .f.buf.buffer.r{$r}c{$c} -textvariable displayBuffer($r)($c) ] -row $r -column $c -padx 5 -pady 5 -sticky nw\n");      
    }
  }
  foreach ($HMIbuttons as $rowIndex => $row) {
    foreach ($row as $columnIndex => $button) {
      fwrite($client, "grid [ttk::button .f.buf.buffer.buttonR{$rowIndex}C$columnIndex -text {{$button["lable"]}} -command {sendCommand {$button["cmd"]}} ] -column $columnIndex -row ".($buttonRow + $rowIndex)." -sticky w\n");
    }
  }  
  MMIupdate($client);
}

function processCommandMMI($command, $client) {
  global $triggerOBUupdate, $triggerMMIupdate, $triggerHWbackendPoll, $lightOrderA, $emergencyStop;
//  print("MMI: >$command<\n");
  $triggerMMIupdate = $triggerHWbackendPoll = true;
  $param = explode(" ",$command);
  switch ($param[0]) {
    case "cabin":
      $lightOrderA["cabin"] = $lightOrderA["cabin"] == OFF ? CABIN_ON : OFF; 
    break;
    case "cabA":
      $lightOrderA["cab"] = $lightOrderA["cab"] == OFF ? CAB_ON : OFF; 
    break;
    case "RLA":
      $lightOrderA["rear"] = $lightOrderA["rear"] == OFF ? REAR_ON : OFF; 
    break;
    case "FLA":
      $lightOrderA["front"] = $lightOrderA["front"] == OFF ? FRONT_NORMAL : OFF; 
    break;
    case "FLHA":
      if ($lightOrderA["front"] != OFF) {
        $lightOrderA["front"] = $lightOrderA["front"] == FRONT_BRIGHT ? FRONT_NORMAL : FRONT_BRIGHT;       
      }
    break;
    case "RL": // Reload TrainData
      applyDynamicData();
      $triggerMMIupdate = true;
    break;
    case "STOP":
      $emergencyStop = !$emergencyStop;
    break;
    case "Rq":
      print "Request operation....\n";
    break;
    case "Rl": // Reload profiles  -------------------------------------- FIXME use apply dynamic comfiguratopn?
      if (verifyProfile()) {
        readDynamicData();
        $triggerOBUupdate = true;
      } else {
      // Sety MMI reply to error
      }
    break;
    default:
      msgLog("Ups, unimplemented MMI command {$param[0]}");
  }
}

?>
