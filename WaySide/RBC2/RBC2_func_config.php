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

// Automatic operation
$automaticPointThrowEnabled = true; // Enable automatic point throwing during route setting

// HMI
define("HMI_UPDATE", 5);    // Interval in sec. between regular HMI update

// MCe
define("MCe_UPDATE", 10);    // Interval in sec. between regular MCe update


?>
