#!/usr/bin/php
<?php
// WinterTrain, Track Panle Master

//--------------------------------------- Default Configuration
$VERSION = "01P01";  // What does this mean with git?? FIXME

$RBC_SERVER_ADDR = "10.0.0.206";
$RBC_SERVER_PORT = 9900;

// ----------------------------------------- File names
$DIRECTORY = ".";
$PM_CONF_FILE = "TrackPanelConfiguration.php"; // Configuration giles for PanelModules
$MSGLOG = "Log/TPM.log";
$ERRLOG = "Log/TPM_ErrLog.txt";

// ----------------------------------------- GPIO configuration
$GPIO_HOSTNAME = "SuperPhoneII"; // Only conigure GPIO if running on this host
$GPIO_EXPORT = "/sys/class/gpio/export";
$GPIO_DIRECTION = "/sys/class/gpio/gpio4/direction";
$GPIO_VALUE = "/sys/class/gpio/gpio4/value";
$GPIO_RUN_PIN = 4;

// ----------------------------------------- I2C configuration
$I2C_FILE = "/dev/i2c-1";
define("N_I2C_WRITE","3");
// EVERY Panel Module orders and registers
define("PM_WRITE_OUTPUT","20");
define("PM_READ_INPUT","21");
define("PM_RESET","10");
define("PM_CONFIGURE_INPUT","11");
define("PM_GET_STATUS","8");
// MCP23017 orders and registers
define("MCP_GPIOA","0x12");

// ---------------------------------------- Ennumeration
$outputUseCodes = array("lR","lG","tR","tG","aY","bB","rR","rG", "oG"); // Possible output useCodes
$inputUseCodes = array("S","N","B","R","F","E","G","A", "O"); // Possible input useCodes
define("TR_CLEAR", 0);
define("TR_CLEAR_LOCKED", 1);
define("TR_OCCUPIED", 2);

// ---------------------------------------- Timing
define("RECONNECT_TIMEOUT",3);
define("SERVER_TIMEOUT", 500000); // [ms]
define("POLL_TIMEOUT", 0.31); // Poll PanelModule inputs [s]
define("UPDATE_TIMEOUT", 1); // Update PanelModule outputs [s]

//--------------------------------------- System variable
$debug = 0x00; $background = FALSE; $run = true; $useI2C = true;
$startTime = time();
$pollTimer = 0; $updateTimer = 0;

//----------------------------------------- Panel Master variable
$runIndicator = false;
$runInd = false;
$buttonsReleased = false;
$reqOpr = false;
$operationAllowed = false;
$occupationTrainID = array();

//---------------------------------------------------------------------------------------------------------- System 
cmdLineParam();
prepareMainProgram();
forkToBackground();
initMainProgram();
configurePanelModules();

do {
  if (initServer()) {
    initPM();
    do {
      $now = microtime(true);
      if ($now > $pollTimer) {
        $pollTimer = $now + POLL_TIMEOUT;
        readPanelInput();
      }
      if ($now > $updateTimer) {
        $updateTimer = $now + UPDATE_TIMEOUT;
        writePanelOutput();
      }
      toggleRunIndicator();
    } while (server() and $run);
    clearPanel();
  } else {
    sleep(RECONNECT_TIMEOUT);
  }
  toggleRunIndicator();
} while ($run);
msgLog("Exitting...");

// ---------------------------------------------------------------------------------------------------------- PM

function processPMconfiguration() {
  global $PM, $PM_CONF_FILE, $DIRECTORY, $elementIndicator, $outputUseCodes, $inputUseCodes, $elementCT, $ctIndication;
  require("$DIRECTORY/$PM_CONF_FILE");
  if (array_key_exists(0, $PM)) { unset($PM[0]); } // Delete any remaining template entries
  // FIXME add consistency check against PT2; here or in DMT
  foreach ($PM as $I2Caddr => &$pmData) {
    switch ($pmData["type"]) {
      case "EVERY":
        $pmData["outputOrder"] = 0;
        $pmData["inputPins"] = array();
        $pmData["inputButtons"] = array();
        foreach ($pmData["pins"] as $pinNo => $use) {
          if ($pinNo >= 0 and $pinNo < 18 or $pinNo == 20 or $pinNo == 21) {
            if ($use != "") {
              if (strpos($use, ".") !== false) { 
                list($element, $useCode) = explode(".", $use);
                if ($element != "") {
                  if (in_array($useCode, $inputUseCodes)) { // Push button
                    // FIXME check that only "S" can be used for elements
                    $pmData["inputPins"][] = $pinNo;
                    $pmData["inputButtons"][] = $use;
                  } elseif (in_array($useCode, $outputUseCodes)) { // Indication
                    if (isset($elementIndicator[$element][$useCode])) {
                      print "Warning: double assignemt of element indicator, element $element, use code $useCode: I2C ".
                        "{$elementIndicator[$element][$useCode]["I2C"]} pin no {$elementIndicator[$element][$useCode]["pinNo"]} ".
                        "and I2C $I2Caddr pin no $pinNo\n";
                    }
                    $elementIndicator[$element][$useCode]["I2C"] = $I2Caddr;
                    $elementIndicator[$element][$useCode]["pinNo"] = $pinNo;
                  } else {
                    print "Warning: Unknown pin use code in \"$use\" I2C addr $I2Caddr, pin no $pinNo\n";          
                  }
                } else {
                  print "Warning: Missing element name (or \"COM\" for common) in  \"$use\" I2C addr $I2Caddr, pin no $pinNo\n";
                }
              } else {
                print "Warning: Incorrect pin use code format \"$use\" I2C addr $I2Caddr, pin no $pinNo\n";
              }
            }
          } else {
            print "Error: PinNo $pinNo not applicable for PanelModule type EVERY\n";
          }
        }
      break;
      case "MCP23017":
        $pmData["outputOrder"] = 0;
        $pmData["inputButtons"] = array();
        foreach ($pmData["pins"] as $pinNo => $use) {
          if ($pinNo >= 0 and $pinNo < 16) {
            if ($use != "") {
              if (strpos($use, ".") !== false) { 
                list($element, $useCode) = explode(".", $use);
                if ($element != "") {
                  if (in_array($useCode, $outputUseCodes)) { // Indication
                    if (isset($elementIndicator[$element][$useCode])) {
                      print "Warning: double assignemt of element indicator, element $element, use code $useCode: I2C ".
                        "{$elementIndicator[$element][$useCode]["I2C"]} pin no {$elementIndicator[$element][$useCode]["pinNo"]} ".
                        "and I2C $I2Caddr pin no $pinNo\n";
                    }
                    $elementIndicator[$element][$useCode]["I2C"] = $I2Caddr;
                    $elementIndicator[$element][$useCode]["pinNo"] = $pinNo;
                  } elseif (in_array($useCode, $inputUseCodes)) {
                    print "Error: Configuring I2C addr $I2Caddr, pin no. $pinNo as input is not implemented for PanelModule type MCP23017\n";
                  } else {
                    print "Warning: Unknown pin use code in \"$use\" I2C addr $I2Caddr, pin no. $pinNo\n";          
                  }
                } else {
                  print "Warning: Missing element name (or \"COM\" for common) in  \"$use\" I2C addr $I2Caddr, pin no $pinNo\n";
                }
              } else {
                print "Warning: Incorrect pin use code format \"$use\" I2C addr $I2Caddr, pin no $pinNo\n";
              }
            }
          } else {
            print "Error: PinNo $pinNo not applicable for PanelModule type MCP23017\n";
          }
        }
      break;
      default:
        print "Error: Unsupported PanelModule type {$pmData["type"]}\n";
    }
  }
  if (array_key_exists("", $combinedTrackIndication)) { unset($combinedTrackIndication[""]); } // Delete any remaining template entries
  foreach ($combinedTrackIndication as $ctName => &$combinedTracks) {
    foreach ($combinedTracks as $index => $elementName) {
      $elementCT[$elementName] = $ctName;
      $ctIndication[$ctName][$elementName] = TR_CLEAR;
    }
  }
//print_r($PM);
//print_r($elementCT);
//print_r($ctIndication);
}

function initPM() {
  global $PM, $reqOpr;
  configurePanelModules();
  clearPanel();
  if ($reqOpr) sendCommandRBC("Rq");
}

function clearPanel() {
  global $PM;
  foreach ($PM as $I2Caddr => &$moduleData) {
    $moduleData["outputOrder"] = 0;
    switch ($moduleData["type"]) {
      case "EVERY":
        writePanelModule($I2Caddr, PM_WRITE_OUTPUT, array(0, 0, 0));
      break;
      case "MCP23017":
        writePanelModule($I2Caddr, MCP_GPIOA, array(0, 0));
      break;
    }
  }
}

function configurePanelModules() {
  global $PM;
  foreach ($PM as $I2Caddr => $moduleData) {
    switch ($moduleData["type"]) {
      case "EVERY":
        writePanelModule($I2Caddr, PM_RESET, array());
        foreach($moduleData["inputPins"] as $pin) {
          writePanelModule($I2Caddr, PM_CONFIGURE_INPUT, array($pin));
        }
      break;
      case "MCP23017": // Input from MCP23017 is not implemented

      break;
    }
  }
}

function handleButtonPress($buttonsPressed) {
  global $buttonsReleased, $operationAllowed, $occupationTrainID;
  $count = count($buttonsPressed);
  if ($count == 0) {
    $buttonsReleased = true;
  } elseif ($buttonsReleased) {
    if ($count == 2) {
      list ($e1, $c1) = explode(".", $buttonsPressed[0]);
      list ($e2, $c2) = explode(".", $buttonsPressed[1]);
      $buttonsReleased = false;;
//      print "Buttons: $e1.$c1 $e2.$c2 ";
      if ($c1 == "S" and $c2 == "S") { // Two select buttons => Set route
        print "Set route $e1 $e2\n";
        sendCommandRBC("tr $e2 $e1");
      } elseif (($c1 == "S" or $c2 == "S")) { // One select button => "common command"
        if ($c1 == "S") {
          $common = $c2;
          $element = $e1;
        } else {
          $common = $c1;
          $element = $e2;
        }
        switch ($common) {
          case "N":
            sendCommandRBC("pt $element");
          break;
          case "R":
            sendCommandRBC("rr $element");
          break;
          case "F":
            sendCommandRBC("err $element");
          break;
          case "A": // Toggle specific ARS
            sendCommandRBC("ars $element");            
          break;
          case "B": // Block signal / point / LX
            // FIXME different command for signals and points....
          break;
          case "I": // Show train ID
            if (isset($elementCT[$element])) {
              print "  Train ID for CT {$elementCT[$element]}\n";
              foreach ($combinedTrackIndication[$elementCT[$element]] as $ctName => $state) {
                print "    Train ID for $ctName: ".(isset($occupationTrainID[$ctNamet]) ? $occupationTrainID[$ctName] : "<unknovn>")."\n";
              }
            } else {
             print "Train ID for $element: ".(isset($occupationTrainID[$element]) ? $occupationTrainID[$element] : "<unknovn>")."\n";
            }
          break;
          case "T": // Show TRN
          break;
          case "O": // Ignore not used combinations
          break;
          default:
            print "Ups, unimplemented common push button $common\n";
        }
      } else { // Two common buttons => special command
        if (($c1 == "N" and $c2 == "O") or ($c1 == "O" and $c2 == "N") ) { // Request / release operation
          if ($operationAllowed) {
            sendCommandRBC("Rl");
          } else {
            sendCommandRBC("Rq");
          }
        } elseif (($c1 == "N" and $c2 == "A") or ($c1 == "A" and $c2 == "N") ) { // Toggle generic ARS
          sendCommandRBC("arsAll");
        }
      }
    }
  }
}

function processNotificationRBC($data) {
  global $operationAllowed, $elementCT, $ctIndication, $occupationTrainID;
  $tokens = explode(" ", $data);
  switch ($tokens[0]) {
    case "pointState": // name vacancyState routeLockingState routeLockingType lockingState blockingState pointState {occupationTrainID ""}
      $element = $tokens[1];
      $occupationTrainID[$element] = "";
      switch ($tokens[7]) {
        case 2: // Right
          switch ($tokens[2]) { // Track state
            case 1: // Occupied
              setIndication($element, "lR", 0);
              setIndication($element, "lG", 0);
              setIndication($element, "rR", 1);
              setIndication($element, "rG", 0);
              $occupationTrainID[$element] = isset($tokens[7]) ? $tokens[7] : "";
            break;
            case 2: // Clear
              if ($tokens[3] != 1 and $tokens[4] == 7) { // Locked
                setIndication($element, "lR", 0);
                setIndication($element, "lG", 0);
                setIndication($element, "rR", 0);
                setIndication($element, "rG", 1);
              } else {
                setIndication($element, "lR", 0);
                setIndication($element, "lG", 0);
                setIndication($element, "rR", 1);
                setIndication($element, "rG", 1);
              }
            break;
            default:
              setIndication($element, "lR", 0);
              setIndication($element, "lG", 0);
              setIndication($element, "rR", 1);
              setIndication($element, "rG", 1);
          }
        break;
        case 3: // Left
          switch ($tokens[2]) { // Track state
            case 1: // Occupied
              setIndication($element, "lR", 1);
              setIndication($element, "lG", 0);
              setIndication($element, "rR", 0);
              setIndication($element, "rG", 0);
              $occupationTrainID[$element] = isset($tokens[7]) ? $tokens[7] : "";
            break;
            case 2: // Clear
              if ($tokens[3] != 1 and $tokens[4] == 8) { // Locked
                setIndication($element, "lR", 0);
                setIndication($element, "lG", 1);
                setIndication($element, "rR", 0);
                setIndication($element, "rG", 0);
              } else {
                setIndication($element, "lR", 1);
                setIndication($element, "lG", 1);
                setIndication($element, "rR", 0);
                setIndication($element, "rG", 0);
              }
            break;
            default:
              setIndication($element, "lR", 1);
              setIndication($element, "lG", 1);
              setIndication($element, "rR", 0);
              setIndication($element, "rG", 0);
          }
        break;
        default: 
          setIndication($element, "rR", 1);
          setIndication($element, "rG", 1);
          setIndication($element, "lR", 1);
          setIndication($element, "lG", 1);
      }
    break;
    case "signalState":
      // name vacancyState routeLockingState routeLockingType lockingState blockingState signalState arsState {occupationTrainID ""}
      $element = $tokens[1];
      $occupationTrainID[$element] = "";
      switch ($tokens[7]) { // Signal state
        case 2:
        case 3:
        case 6: // SIG_NOT_LOCKED, SIG_STOP, SIG_CLOSED
          if ($tokens[3] == 1 or $tokens[4] == 4) { // Not locked in route or locked as reverse via
            setIndication($element, "lG", 0);
            setIndication($element, "lR", 0);            
          } else {
            setIndication($element, "lG", 0);
            setIndication($element, "lR", 1);
          }
        break;
        case 4: // Proceed
        case 5: // Proceed proceed
          setIndication($element, "lG", 1);
          setIndication($element, "lR", 0);
        break;
        default:
          setIndication($element, "lG", 0);
          setIndication($element, "lR", 0);
      }
      switch ($tokens[2]) { // Track state
        case 1: // Occupied
          if (isset($elementCT[$element])) {
            $ctIndication[$elementCT[$element]][$element] = TR_OCCUPIED;
            setIndication($elementCT[$element], "tR", 1);        
            setIndication($elementCT[$element], "tG", 0);
          } else {
            setIndication($element, "tR", 1);        
            setIndication($element, "tG", 0);
          }
          $occupationTrainID[$element] = isset($tokens[9]) ? $tokens[9] : "";
        break;
        case 2: // Clear
          if ($tokens[3] != 1 and ($tokens[4] == 2 or $tokens[4] == 4 or $tokens[4] == 5)) { // Locked
            if (isset($elementCT[$element])) {
              $ctName = $elementCT[$element];
              $ctIndication[$ctName][$element] = TR_CLEAR_LOCKED;
              $isOccupied = false;
              foreach ($ctIndication[$ctName] as $indication) {
                if ($isOccupied = $indication == TR_OCCUPIED) break;
              }
              if (!$isOccupied) {
                setIndication($elementCT[$element], "tR", 0);        
                setIndication($elementCT[$element], "tG", 1);
              }
            } else {
              setIndication($element, "tR", 0);  
              setIndication($element, "tG", 1);
            }
          } else {
            if (isset($elementCT[$element])) {
              $ctName = $elementCT[$element];
              $ctIndication[$ctName][$element] = TR_CLEAR;
              $isNotClear = false;
              foreach ($ctIndication[$ctName] as $indication) {
                if ($isNotClear = $indication != TR_CLEAR) break;
              }
              if (!$isNotClear) {
                setIndication($elementCT[$element], "tR", 0);        
                setIndication($elementCT[$element], "tG", 0);
              }
            } else {
              setIndication($element, "tR", 0);        
              setIndication($element, "tG", 0);          
            }
          }
        break;
        default: // FIXME used? indicate error?
          setIndication($element, "tR", 0);        
          setIndication($element, "tG", 0);
      }
      switch ($tokens[8]) { // ARS state
        case 0: // ARS disabled
          setIndication($element, "aY", 1);
        break;
        case 1: // ARS enabled
          setIndication($element, "aY", 0);
        break;
      }
    break;    
    case "trState": // name routeState trackState {occupationTrainID ""}
      $element = $tokens[1];
      $occupationTrainID[$element] = "";
      switch ($tokens[3]) { // Track state
        case 1: // Occupied
          if (isset($elementCT[$element])) {
            $ctIndication[$elementCT[$element]][$element] = TR_OCCUPIED;
            setIndication($elementCT[$element], "tR", 1);        
            setIndication($elementCT[$element], "tG", 0);
          } else {
            setIndication($element, "tR", 1);        
            setIndication($element, "tG", 0);
          }
          $occupationTrainID[$element] = isset($tokens[4]) ? $tokens[4] : "";
        break;
        case 2: // Clear
          if ($tokens[2] != 1) { // Locked
            if (isset($elementCT[$element])) {
              $ctName = $elementCT[$element];
              $ctIndication[$ctName][$element] = TR_CLEAR_LOCKED;
              $isOccupied = false;
              foreach ($ctIndication[$ctName] as $indication) {
                if ($isOccupied = $indication == TR_OCCUPIED) break;
              }
              if (!$isOccupied) {
                setIndication($elementCT[$element], "tR", 0);        
                setIndication($elementCT[$element], "tG", 1);
              }
            } else {
              setIndication($element, "tR", 0);  
              setIndication($element, "tG", 1);
            }
          } else {
            if (isset($elementCT[$element])) {
              $ctName = $elementCT[$element];
              $ctIndication[$ctName][$element] = TR_CLEAR;
              $isNotClear = false;
              foreach ($ctIndication[$ctName] as $indication) {
                if ($isNotClear = $indication != TR_CLEAR) break;
              }
              if (!$isNotClear) {
                setIndication($elementCT[$element], "tR", 0);        
                setIndication($elementCT[$element], "tG", 0);
              }
            } else {
              setIndication($element, "tR", 0);        
              setIndication($element, "tG", 0);          
            }
          }
        break;
        default: // FIXME used?
          setIndication($element, "tR", 0);        
          setIndication($element, "tG", 0);
      }    
    break;    
    case "bufferStopState": // name vacancyState routeLockingState routeLockingType lockingState blockingState {occupationTrainID ""}
    // FIXME due to bug in RBC code, data contains two space, which in explode adds one extra index
      $element = $tokens[1];
      if ($tokens[4] != 1 and $tokens[5] == 3) {
        setIndication($element, "rR", 1);        
      } else {
        setIndication($element, "rR", 0);              
      }
    break;    
    case "displayResponse":
      print "RBC response: $data\n";
    break;   
    case "oprAllowed":
      $operationAllowed = true;
      setIndication("COM", "oG", 1);              
      print "Operation allowed\n";
    break; 
    case "oprReleased":
      $operationAllowed = false;
      setIndication("COM", "oG", 0);  
      print "operation not allowed\n";
    break;
    case "eStopInd": // state
      switch ($tokens[1]) {
        case 0: // eStop inactive
          setIndication($element, "eR", 0);
        break;
        case 1: // eStop active
          setIndication($element, "eR", 1);
        break;
      }
    break;    
    case "arsAllInd": // state
      switch ($tokens[1]) { 
        case 0: // ARS enabled
          setIndication($element, "gY", 0);
        break;
        case 1: // ARS disabled
          setIndication($element, "gY", 1);
        break;
      }
    break;    
    case "SRmode":
    break;
    case "SHmode":
    break;    
    case "FSmode":
    break;    
    case "ATOmode":
    break;    
    case "srGeneral":
    break;    
    case "shGeneral":
    break;    
    case "fsGeneral":
    break;    
    case "atoGeneral":
    break;    
    case "destroyTrainFrame": // RBC notifications not relevant for trackPanel
    case "set":
    case "dGrid":
    case "resetLabel":
    case "label":
    case "eStopIndicator":
    case "arsIndicator":
    case ".f.canvas":
    case "trainFrame":
    case "trainDataS":
    case "trainDataD":
    case "signal":
    case "bufferStop":
    case "point":
    case "track":
    case "":
    case "":
    case "":
    case "":
    case "":
    case "":
    break;
    default:
      print "Warning: RBC status >$data< not implemented\n";
  }
}

function setIndication($element, $useCode, $value) {
  global $elementIndicator, $PM;
  if (isset($elementIndicator[$element][$useCode])) {
    $pinNo = $elementIndicator[$element][$useCode]["pinNo"];
    $o = &$PM[$elementIndicator[$element][$useCode]["I2C"]]["outputOrder"];
    if ($value == 1) {
      $o = $o | (1 << $pinNo);
    } else {
      $o = $o & ~(1 << $pinNo);      
    }
  }
}

function sendCommandRBC($command) {
global $RBCfh;
  if ($RBCfh) fwrite($RBCfh,"$command\n");
}

function writePanelOutput() {
  global $PM;
  foreach ($PM as $I2Caddr => $moduleData) {
    switch ($moduleData["type"]) {
      case "EVERY":
        $o = $moduleData["outputOrder"];
        $packet = array();
        for ($b = 0; $b < 3; $b++) {
          $packet[$b] = $o & 0xFF;
          $o = $o >> 8;
        }
        writePanelModule($I2Caddr, PM_WRITE_OUTPUT, $packet);
      break;
      case "MCP23017":
        $o = $moduleData["outputOrder"];
        $packet = array();
        for ($b = 0; $b < 2; $b++) {
          $packet[$b] = $o & 0xFF;
          $o = $o >> 8;
        }
        writePanelModule($I2Caddr, MCP_GPIOA, $packet);
      break;
    }
  }
}

function readPanelInput() {
  global $PM;
  $buttonsPressed = array();
  foreach ($PM as $I2Caddr => $moduleData) {
    if ($moduleData["inputButtons"] != []) {
      $input = readPanelModule($I2Caddr, PM_READ_INPUT)[0];
      foreach ($moduleData["inputButtons"] as $index => $button) {
        if (!($input & (1 << $index))) $buttonsPressed[] = $button;
      }
    }
  }
  foreach ($buttonsPressed as $button) print "$button ";
  print "\n";
  handleButtonPress($buttonsPressed);
}

//----------------------------------------------------------------------------------------- (server)

function initServer() {
global $RBCfh, $RBC_SERVER_ADDR, $RBC_SERVER_PORT, $version;
  $RBCfh = @stream_socket_client("tcp://$RBC_SERVER_ADDR:$RBC_SERVER_PORT", $errno,$errstr);
  if ($RBCfh) {
    stream_set_blocking($RBCfh,false);
    msgLog("Connected to RBC");
    sendCommandRBC("Hello this is Panel Master $version");
    return true;
  } else {
    fwrite(STDERR,"Cannot create client socket for RBC: $errstr ($errno)\n");
    return false;
  }
}

function server() {
global $RBCfh, $gpioFh;
  $except = NULL;
  $write = NULL;
  $read[] = $RBCfh;
//          fwrite($gpioFh, "0");

  if (stream_select($read, $write, $except, 0, SERVER_TIMEOUT)) {
//          fwrite($gpioFh, "1");
    foreach ($read as $r) {
      if ($r == $RBCfh) {
        if ($data = fgets($r)) {
          processNotificationRBC(trim($data));
        } else { //RBC gone
          msgLog("RBC gone");
          return false;
        }
      }
    }
  }
//          fwrite($gpioFh, "1");
  return true;
}

//----------------------------------------------------------------------------------------- Utility

function readPanelModule($I2Caddr, $register) {
  global $I2CFh, $useI2C;
  if ($useI2C) {
    i2c_select($I2CFh, $I2Caddr);
    i2c_write($I2CFh, $register);
    $t = i2c_read($I2CFh, 1); // Notice i2c_read() returns an array FIXME
    return $t;
  }
}

function writePanelModule($I2Caddr, $order, $packet) {
  global $I2CFh, $useI2C;
  if ($useI2C) {
    i2c_select($I2CFh, $I2Caddr);
    $n = 0;
    while (!i2c_write($I2CFh, $order, $packet) and $n < N_I2C_WRITE) {
      debugPrint("I2C write retry");
      $n +=1;
    }
  }
}

function toSigned($b1, $b2) {
  $dec = $b2 * 256 + $b1;
  $_dec = 65536 - $dec;
  return $dec > $_dec ? -$_dec : $dec;
}

function CmdLineParam() {
global $debug, $background, $TMS_CONFIG, $VERSION, $argv, $RBC_SERVER_ADDR, $TT_FILE, $PT2_FILE, $DIRECTORY, $TRAIN_DATA_FILE, $useI2C,
  $reqOpr;
  if (in_array("-h",$argv)) {
    fwrite(STDERR,"Track Panel Master, version $VERSION
Usage:
-b, --background      Start as daemon
-IP <IP-address>      IP-address of RBC to contact
-l                    Connect to local host
-o                    Request operation
-ni                   Don't access I2C
-d                    Enable debug info, level all
");
    exit();
  }
  next($argv);
  while (list(,$opt) = each($argv)) {
    switch ($opt) {
    case "-IP":
      list(,$p) = each($argv);
      if ($p) {
        $RBC_SERVER_ADDR = $p;
      } else {
        fwrite(STDERR,"Error: option -IP: IP-address is missing \n");
        exit(1);
      }
    break;
    case "-l":
        $RBC_SERVER_ADDR = "127.0.1.0";
    break;
    case "-b":
    case "--background" :
      $background = TRUE;
    break;
    case "-o":
      $reqOpr = true;
    break;
    case "-ni":
      $useI2C = false;
    break;
    case "-d":
      $debug = 0x07;
      fwrite(STDERR,"Debug, all\n");
    break;
    default :
      fwrite(STDERR,"Unknown option: $opt\n");
      exit(1);
    }
  }
}

function prepareMainProgram() {
global $logFh, $errFh, $debug, $TMS_CONFIG, $MSGLOG, $ERRLOG, $DIRECTORY;
  if ($debug) {
    error_reporting(E_ALL);
  } else {
    error_reporting(0);
  }
  if (!($errFh = fopen("$DIRECTORY/$ERRLOG","a"))) {
    fwrite(STDERR,"Warning: Cannot open Error log file: $DIRECTORY/$ERRLOG\n");
    $errFh = fopen("/dev/null","w");
  }
  if (!($logFh = fopen("$DIRECTORY/$MSGLOG","a"))) {
    fwrite(STDERR,"Warning: Cannot open Log file: $DIRECTORY/$MSGLOG\n");
    $logFh = fopen("/dev/null","w");
  }
  processPMconfiguration();
}


function initMainProgram() {
  global $logFh, $errFh, $debug, $background, $ERRLOG, $MSGLOG, $PT2_FILE, $PT2, $trainData, $DIRECTORY, $TRAIN_DATA_FILE,
    $GPIO_HOSTNAME, $GPIO_EXPORT, $GPIO_DIRECTION, $GPIO_VALUE, $GPIO_RUN_PIN, $gpioFh, $runIndicator, $I2C_FILE, $I2CFh, $useI2C;

  if ($runIndicator = (php_uname("n") == $GPIO_HOSTNAME)) { // Configure GPIO for run indicator
    $fp = fopen($GPIO_EXPORT, 'w');
    fwrite($fp, "$GPIO_RUN_PIN");
    fclose($fp);
    $fp = fopen($GPIO_DIRECTION, 'w');
    fwrite($fp, 'out');
    fclose($fp);
    $gpioFh = fopen($GPIO_VALUE, 'w');
  }
  if (!($errFh = fopen("$DIRECTORY/$ERRLOG","a"))) {
    fwrite(STDERR,"Warning: Cannot open Error log file: $DIRECTORY/$ERRLOG\n");
    $errFh = fopen("/dev/null","w");
  }
  if (!($logFh = fopen("$DIRECTORY/$MSGLOG","a"))) {
    fwrite(STDERR,"Warning: Cannot open Log file: $DIRECTORY/$MSGLOG\n");
    $logFh = fopen("/dev/null","w");
  }
  if ($useI2C) {
    $I2CFh = i2c_open($I2C_FILE);
    if (!$I2CFh) fatalError("Cannot open I2C interface:: $I2C_FILE"); 
  }
  if ($background) {
    msgLog("Starting as daemon");
  } else {
    msgLog("Starting in forground");
  }
}

function toggleRunIndicator() {
  global $runIndicator, $runInd, $gpioFh;
  if ($runIndicator) {
    $runInd = !$runInd;
    fwrite($gpioFh, $runInd ? "1" : "0");
  }
}

function forkToBackground() {
global $background, $errFh, $logFh;

  fclose($errFh);
  fclose($logFh);
  if ($background) {
  	$pid = pcntl_fork();
    if ($pid == -1) {
      die("Could not fork");
    } else if ($pid) {
      fwrite(STDERR,"Starting as daemon...\n");
      exit();
    }
  } else {
    fwrite(STDERR,"Starting in forground\n");
  }
}

function debugPrint($txt) {
global $debug;
  if ($debug & 0x01) {
    print "$txt\n";
  }
}

function msgLog($txt) {
global $logFh;
  debugPrint (date("Ymd H:i:s")." $txt");
  fwrite($logFh,date("Ymd H:i:s")." $txt\n");
}

function errLog($txt) {
global $errFh;
  debugPrint (date("Ymd H:i:s")." $txt");
  fwrite($errFh,date("Ymd H:i:s")." $txt\n");
}

function fatalError($txt) {
global $logFh, $errFh;
  debugPrint (date("Ymd H:i:s")." Fatal Error: $txt");
  fwrite($logFh,date("Ymd H:i:s")." Fatal Error: $txt - Exiting...\n");
  fwrite($errFh,date("Ymd H:i:s")." Fatal Error: $txt - Exiting...\n");
  exit(1);
}

?>
