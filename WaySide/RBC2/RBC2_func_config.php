<?php
// WinterTrain, RBC2
// Functional configuration

// EC
define("EC_TIMEOUT",5);

// RBC
// Train operation
$SRallowed = 0; // Enummeration? FIXME
$SHallowed = 0;
$FSallowed = 0;
$ATOallowed = 0;
$posRestoreEnabled = true; // Allow position restore
$clearMultiOccupationEnabled = true; // Allow clerance of track occupied by more trains

define("POSITION_TIMEOUT", 14);
define("TRAIN_COM_TIMEOUT",5);
define("EMG_REL_TIMEOUT", 10);
define("PM_RETRY_TIMEOUT", 2);
define("PM_MAX_RETRY", 3);

define("SR_MAX_SPEED_DEFAULT", 70);
define("SH_MAX_SPEED_DEFAULT", 50);
define("FS_MAX_SPEED_DEFAULT", 60);
define("ATO_MAX_SPEED_DEFAULT", 50);


// Automatic operation
$automaticPointThrowEnabled = true; // Enable automatic point throwing during route setting
$automaticPointThrowRetryEnabled = true; // Enable automatic retry of point throwing during route setting

// HMI
define("HMI_UPDATE", 5);    // Interval in sec. between regular HMI update

// MCe
define("MCe_UPDATE", 10);    // Interval in sec. between regular MCe update

// TMS
define("TMS_TIMEOUT",6);

// ------------------------------------------------------------------ EC timing
define("PUMP_TIMEOUT",4);


?>
