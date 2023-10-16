<?php
// WinterTrain, OBUng
// MMI handlers

$MMIdisplayBuffer = array();

function MMIdisplay() {
  global $MMIdisplayBuffer, $OBUstart, $inChargeDMI, $connectedHWbackend;
  $MMIdisplayBuffer = [
    ["Time:", date("Y-m-d H:i:s"), "", "xxx"],
    ["OBU Uptime:", prettyPrintTime(time() - $OBUstart), "yyy:", "234"],
    ["DMI: ", $inChargeDMI ? "Connected" : "-", "", ""],
    ["HW backend: ", $connectedHWbackend ? "Connected" : "-", "", ""],
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
  global $clients, $clientsData;
  MMIdisplay();
  foreach ($clients as $client) {
    if ($clientsData[(int)$client]["type"] == "MMI") {
      MMIupdate($client);
    }
  }
}

function MMIstartup($client) {
  global $MMIdisplayBuffer;
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
  MMIupdate($client);
}

function processCommandMMI($data, $client) {
  print("MMI: >$data<\n");
}

?>x
