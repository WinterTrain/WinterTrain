<?php
// WinterTrain, OBUng
// Technical configuration
$OBU_HOSTNAME = "TrackPanel";

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
$RBCaddress = "192.168.1.230";  // FIXME List of addresses??
$RBCport = 9904; // FIXME

// 
define("SERVER_WAIT", 100000); // Max waiting time for IP interface
define("TIMER_DMI_POLL", 5);
define("TIMER_MMI_POLL", 5); 
define("TIMER_HWB_POLL", 3);



// ---------------------------------------------------------- Train HW backend interface
$TTY_FILE = "/dev/ttyS0";
$I2C_FILE = "/dev/i2c-1";

?>
