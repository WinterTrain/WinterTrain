#!/usr/bin/php
<?php
// WinterTrain, RBC2
// Configuration files
include("RBC2_tech_config.php");    // Technical configuration data like file names, interfaces, addresses etc.
include("RBC2_func_config.php");    // Functional configuration data related to RBC operation, trains etc.
include("RBC2_enummeration.php");   // Definition of enummeration constants
include("RBC2_txt.php");            // Text and language support

// Feature handlers
include("RBC2_utility.php");        // Process related functions, logging etc. 
include("RBC2_interfaces.php");     // Handlers for I2C and stream interfaces for connection to Abus, radio, HMI, MCe, TMS
include("RBC2_EC.php");             // Handlers for Element Controllers 
include("RBC2_MCe.php");            // Handlers for MCe 
include("RBC2_HHT.php");            // Handlers for HHT
include("RBC2_HMI.php");            // Handlers for HMI
include("RBC2_TMS.php");            // Handlers for TMS

include("RBC2_RBC.php");            // Core fuctionalty 

// Site specific application data handlers
include("RBC2_PT2.php");            // Process PT2 data and create related objects
include("RBC2_TrackModel.php");     // Core object model
include("RBC2_TrainData.php");      // Process TrainData

// Where to initialize variables?? Here or in each module?? FIXME

//--------------------------------------- System variables
$debug = 0x00; $background = false; $run = true; $reloadRBC = false; $pollEC = false;
$startTime = $secondTimeout = time();
$trainData = array();
$simTrain = array();

$tmsStatus = TMS_NO_TMS;
$triggerHMIupdate = false;
$triggerMCeUpdate = false;
$HMItimeout = 0;
$MCetimeout = 0;
$pumpTimeout = 0;
$trainComTimeout = 0;
$emgRelEP = array(); // List of EP running emergency route release timers

$hhtFoundCount = 0;
$hhtFoundSum = 0;
$hhtBaliseStatus = "";
$hhtBaliseID = "--:--:--:--:--";
$hhtBaliseName = "";;

$baliseCountTotal = 0;
$baliseCountUnassigned = 0;

// -------------------------------------- Operational varaibles
$emergencyStop = false;
$arsEnabled = true;
$allowSR = false; $allowSH = false; $allowFS = false; $allowATO = false; // Overall allowance of modes
$tmsStatus = TMS_NO_TMS;

// Test
$test = false;
$recCount = 0;

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
  $triggerHMIupdate = true;
  processPT2();
  processTrainData();
  initEC();
  initRBC();
  foreach ($clients as $client) {
    switch ($clientsData[(int)$client]["type"]) {
      case "HMI":
        HMIstartup($client);
      break;
      case "MCe":
        MCeStartup($client);
      break;
    }
  }
  do {
    $now = time();
    if ($now != $secondTimeout) { // Every second
      $secondTimeout = $now;
      $pollEC = true; // Trigger EC poll once per second
      checkECtimeout();
      checkEmgRelTimers();
    }
    if ($now - $trainComTimeout >= TRAIN_COM_TIMEOUT) {
      $trainComTimeout = $now;
      checkTrainTimeout();
    }
    if ($now - $pumpTimeout >= PUMP_TIMEOUT) {
      $pumpTimeout = $now;
      pumpSignal();
    }
    if ($triggerHMIupdate or $now >= $HMItimeout) { 
      $HMItimeout = $now + HMI_UPDATE;  
      updateIndicationHMI();
      updateTrainDataHMI();
    }
    if ($triggerMCeUpdate or $now >= $MCetimeout) { 
      $MCetimeout = $now + MCe_UPDATE;  
      updateIndicationMCe();
    }
    pollNextEC();
    interfaceServer();
  } while ($run and !$reloadRBC);
} while ($run);
ShutDown();
?>

