#!/usr/bin/php
<?php
// WinterTrain, OBUng
// Configuration files
include("OBU_tech_config.php");    // Technical configuration data like file names, interfaces, addresses etc.
//include("OBU_enummeration.php");   // Definition of enummeration constants
//include("OBU_txt.php");            // Text and language support

// Feature handlers
include("OBU_utility.php");        // Process related functions, logging etc. 
include("OBU_interfaces.php");     // Handlers for I2C and stream interfaces for connection to DMI, MMI, RBC and train HW 
include("OBU_DMIhandler.php");     // Handlers for DMI interface 
include("OBU_MMIhandler.php");     // Handlers for MMI interface 

// Train specific application data handlers
include("OBU_TrainData.php");      // Process OBU TrainData

// Where to initialize variables?? Here or in each module?? FIXME

//--------------------------------------- System variables
$debug = 0x00; $background = false; $run = true; $noHWbackend = false;
$startTime = $secondTimeout = time();
$MMItimer = 0;
$trainData = array();

// -------------------------------------- Operational varaibles
$emergencyStop = false;


// --------------------------------------------------------------------------------------- PHP process initialization
cmdLineParam();
prepareMainProgram();
forkToBackground();
initMainProgram();
// --------------------------------------------------------------------------------------- System initialization
initInterfaces();

$prevHrTime = hrtime(true);
do {
  $now = time();
  $hrTime = hrtime(true);
  $dT = $hrtime - $prevHrTime;
  $prevHrTime = $hrTime;
  if ($now > $MMItimer) { // Each second
    $MMItimer = $now;
    pollHWbackend();
    pollDMI();
    MMIupdateAll();
  }
  motorControl($dT);
  interfaceServer();
} while ($run);
ShutDown();
?>

