<?php
// WinterTrain, RBC2
// Technical configuration

// Interface configuration

$radioInterface = "USB";  // Radio interface type:
                          //    USB:  JeeLink connected via USB
                          //    ABUS: JeeNode connected via Abus
                          //    NONE: no radio interface
$AbusInterface = "I2C";   // Abus interface type: 
                          //    I2C:    Abus gateway connected via I2C, using PHP I2C extention  
                          //    I2C_T:  Abus gateway connected via I2C, using I2C command line tool
                          //    IP:     Abus gateway connected via ethernet
                          //    NONE:   No interface to Abus

// Directory name and file names
$DIRECTORY =        ".";                  
$ERRLOG_FILE =      "Log/RBCIL_ErrLog.txt";
$MSGLOG_FILE =      "Log/RBCIL.log";
$PT2_FILE =         "PT2.php";
$BL_FILE =          "baliseDump.php";
$TRAIN_DATA_FILE =  "TrainData.php";

// IP addresses and port numbers
$HMIaddress = "0.0.0.0";
$HMIport = 9900;
$MCeAddress = "0.0.0.0";
$MCePort = 9901;
$TMSaddress = "0.0.0.0";
$TMSport = 9903;

// ---------------------------------------------------------- Radio network
define("RF12GROUP", 101);
define("RBC_RADIO_ID", 10);
define("RF12_ID_MASK", 0x1f);

// Via USB
$RADIO_DEVICE_FILE = "/dev/ttyUSB0";

// ---------------------------------------------------------- Abus Gateway
// Via I2C
$ABUS_I2C_FILE = "/dev/i2c-1";
define("MAX_ABUS_BUF", 20);             // Abus protocol max packet size
define("ABUS_MASTER_I2C_ADDR", 0x33);  // Address of AbusMaster (arduino) connected to RasPI via I2C
define("ABUS_WAIT",15000);              // Delay for waiting for slave to reply
define("N_I2CSET",2);                   // Number of retry using I2C tool
define("N_I2C_WRITE",2);                // Number of retry using PHP I2C extention

// Via IP
define("ABUS_GATEWAYaddress", "10.0.0.201"); // AbusGateway
define("ABUS_GATEWAYport", 9200);
define("LOCAL_GATEWAYaddress", "0.0.0.0");    // Local listenerfor replies from gateway
define("LOCAL_GATEWAYport", 9202);

?>
