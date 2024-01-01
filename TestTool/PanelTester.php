#!/usr/bin/php
<?php
// WinterTrain, Track Panle Tester

$I2C_FILE = "/dev/i2c-1";


// ARDUINO EVERY Panel Module orders and registers
define("PM_WRITE_OUTPUT","20");
define("PM_READ_INPUT","21");
define("PM_RESET","10");
define("PM_CONFIGURE_INPUT","11");
define("PM_GET_STATUS","8");
// MCP23017 orders and registers
define("MCP_GPIOA",0x12);
define("MCP_IODIRA",0x00);

$type = "";
$addr = "";
$pin = "";
$value = "";

cmdLineParam();




function CmdLineParam() {
  global $debug, $type, $addr, $pin, $value, $argv;

  $p = 0;
var_dump($argv);
  if (in_array("-h",$argv) or count($argv) == 1) {
    fwrite(STDERR,"Track Panel Tester
Usage: PanelTester [option] moduleType moduleAddress pin value

moduleType        Type of module: e (for EVERY) | m /for MCP23017)
moduleAddr        I2C address, deciaml
pin               Pin number, decimal
value             Pin order (0 | 1)

-d                Enable debug info, level all
");
    exit();
  }
  while ($opt = next($argv)) {
  print ">$opt<\n";
  /*
    if (is_numeric($opt)) {
      switch ($p) {
      case 0:
        $addr = $opt;
      break;
      case 1:
        $pin = $opt;
      break;
      case 2:
        $value = $opt;
      break;
      }
      $p++;
    } else {
      switch ($opt) {
      case "e":
        $type = "EVERY";
      break;
      case "m":
        $type = "MCP23017";
      break;
      case "-d":
        $debug = 0x07;
        fwrite(STDERR,"Debug, all\n");
      break;
      default :
        fwrite(STDERR,"Unknown option: $opt\n");
        exit(1);
      }
    } */
    
  }
  if ($type === "") {
    print "Error: Panel module type is missing.\n";
  }
  if ($addr === "") {
    print "Error: Panel module address is missing.\n";
  }
  if ($pin === "") {
    print "Error: Output pin number is missing.\n";
  }
  if ($value === "") {
    print "Error: Output value value is missing.\n";
  }
}


?>

