#!/usr/bin/php
<?php
// WinterTrain, OBUng
// Configuration files
include("OBU_tech_config.php");    // Technical configuration data: file names, interfaces, addresses etc.
include("OBU_func_config.php");    // Functional configuration data: OBU profile identifier etc.
include("OBU_enummeration.php");   // Definition of enummeration constants
include("OBU_txt.php");            // Text and language support

// Feature handlers
include("OBU_OBU.php");            // OBU main functions 
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
$MMItimer = 0; $dmiPoll = 0; $HWbackendPoll = 0;
$OBUprofile = $locoProfile = $runningProfile = array();

$triggerMMIupdate = $triggerHWbackendPoll = false;

// -------------------------------------- Operational varaibles
$emergencyStop = false;


// --------------------------------------------------------------------------------------- PHP process initialization
cmdLineParam();
prepareMainProgram();
forkToBackground();
initMainProgram();

// --------------------------------------------------------------------------------------- System initialization
applyStaticData();
initInterfaces();
applyDynamicData(); 
//configureHWbackend(); // in applyDynamicData

$prevHrTime = hrtime(true);
while ($run) {
  $now = time(); $hrTime = hrtime(true); $dT = $hrTime - $prevHrTime; $prevHrTime = $hrTime;
  if ($now > $HWbackendPoll or $triggerHWbackendPoll) {
    $HWbackendPoll = $now + TIMER_HWB_POLL;
    pollHWbackend();
  }
  if ($now > $dmiPoll) {
    $dmiPoll = $now + TIMER_DMI_POLL;
    pollDMI();
  }
  if ($now > $MMItimer or $triggerMMIupdate) {
    $MMItimer = $now + TIMER_MMI_POLL;
    MMIupdateAll();
  }
  if ($triggerOBUupdate) {
    OBUupdate();
    $triggerOBUupdate = false;
  }
  dynamicFeature($dT);
  interfaceServer();
};
ShutDown();
?>

