#!/usr/bin/php
<?php
// WinterTrain, RBC2

include("RBC2_tech_config.php");    // Technical configuration data like file names, interfaces, addresses etc.
include("RBC2_func_config.php");    // Functional configuration data related to RBC operation, trains etc.
include("RBC2_enummeration.php");   // Definition of enummeration constants
include("RBC2_txt.php");            // Text and language support


include("RBC2_utility.php");        // Process related functions, logging etc. 
include("RBC2_interfaces.php");     // Handlers for I2C and IP interfaces for connection to Abus, radio, HMI, MCe, TMS
include("RBC2_EC.php");             // Handlers for Element Controllers 
include("RBC2_MCe.php");            // Handlers for MCe 
include("RBC2_PT2.php");            // Process PT2 data
include("RBC2_TrainData.php");      // Process TrainData

//--------------------------------------- System variables
$debug = 0x00; $background = false; $run = true; $reloadRBC = false;
$startTime = $secondTimeout = time();
$tmsStatus = TMS_NO_TMS;

// --------------------------------------------------------------------------------------- PHP process initialization
cmdLineParam();
prepareMainProgram();
forkToBackground();
initMainProgram();

// --------------------------------------------------------------------------------------- System initialization
initInterfaces();

do {
  if ($reloadRBC) msgLog("Reloading RBC");
  $reloadRBC = false;
  processPT2();
  processTrainData();
  initEC();
  do {
    $now = time();
    if ($now != $secondTimeout) { // Every second
      $secondTimeout = $now;
      generateIndicationsMCe();
    }
    interfaceServer();
  } while ($run and !$reloadRBC);
} while ($run);
ShutDown();
?>

