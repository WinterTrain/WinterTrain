<?php
// WinterTrain, OBUng
// DMI handlers

function pollDMI() {
  global $inChargeDMI;
  if ($inChargeDMI) { 
    fwrite($inChargeDMI, "P\n");
  }
}

function DMIstartup($client) {
  print("DMI startup\n");
}

function processCommandDMI($data, $client) {
  print("DMI: >$data<\n");
}

?>
