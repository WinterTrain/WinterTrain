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
$RF12GROUP = 101;
$RBC_RADIO_ID = 10;
// Via USB
$RADIO_DEVICE_FILE = "/dev/ttyUSB0";

// ---------------------------------------------------------- Abus Gateway
// Via I2C
$ABUS_I2C_FILE = "/dev/i2c-9";
$MaxAbusBuf = 20;             // Abus protocol max packet size
$AbusMasterI2Caddress = 0x33; // Address of AbusMaster (arduino) connected to RasPI via I2C
define("ABUS_WAIT",10000);    // Delay for waiting for slave to reply
define("N_I2CSET",2);         // Number of retry using I2C tool

// Via IP
$ABUS_GATEWAYaddress = "10.0.0.201";
$ABUS_GATEWAYport = 9200;


?>
