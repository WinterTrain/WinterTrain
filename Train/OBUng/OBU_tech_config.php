<?php
// WinterTrain, OBUng
// Technical configuration
$OBU_HOSTNAME = "";

// Interface configuration

// Directory name and file names
$DIRECTORY =        ".";                  
$ERRLOG_FILE =      "Log/OBU_ErrLog.txt";
$MSGLOG_FILE =      "Log/OBU.log";
$DUMP_FILE =        "Log/Dump.log";
$TRAIN_DATA_FILE =  "TrainProfiles.php";

// IP addresses and port numbers
$DMIaddress = "0.0.0.0";
$DMIport = 9910;
$MMIaddress = "0.0.0.0";
$MMIport = 9911;
$RBCaddress = "10.0.0.230";
$RBCport = 9904; // FIXME

// 
define("SERVER_WAIT", "100000"); // Max waiting time for IP interface




// ---------------------------------------------------------- Train HW backend interface
$I2C_FILE = "/dev/i2c-1";

?>
