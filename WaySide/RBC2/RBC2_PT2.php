<?php
// WinterTrain, RBC2
// PT2 handlers

function  ProcessPT2() {
global $DIRECTORY, $PT2_FILE, $PT2;

  require("$DIRECTORY/$PT2_FILE");
  if (isset($PT1)) { // Rename PT1 to PT2
    $PT2 = $PT1;
    unset($PT1);
  }

}

?>
