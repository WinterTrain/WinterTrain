#!/usr/bin/php
<?php
// WinterTrain, PT2 generator


// -------------------------------------- File names and defaults
$SCREEN_LAYOUT_FILE = "screenLayout.sch";
$SIGNALLING_LAYOUT_FILE = "signallingLayout.sch";
$BALISE_DUMP_FILE = "baliseDump.php";
$PT2_FILE = "PT2.php";
$DIRECTORY = ".";

$GNETLIST_CMD = "/usr/bin/gnetlist";

$PT1_PROJECT_NAME = "(n/a)";
$PT1_DATE = "(n/a)";
$PT1_AUTHOR ="(n/a)";

$doReplaceDefaultID = true;

// -------------------------------------- SignallingLayout rewrite

// parameters are assumed common across all element types
$DEFAULT_PARAM = ["ID" => "FF:FF:FF:FF:FF", "EC_addr" => "0", "EC_type" => "0", "EC_major" => "0", "EC_minor" => "0", "supervisionState" => "S", "HoldPoint" => "?", "type" => "MB"];

//--------------------------------------- Text

// --------------------------------------- Symbol layout constants

$FRAME_X_WIDTH = 500;
$FRAME_Y_HIGHT = 6800;
$UNIT_SIZE = 800;
$SU_SIZE = 1200;
$SD1X_SIZE = 700;
$SDY_SIZE = 1600;


$P_OR = ["HMI_PT_r" => "tr", "HMI_PT_l" => "tl", "HMI_PF_r" => "fr", "HMI_PF_l" => "fl"];
$TR_OR = ["HMI_tr1" => "s", "HMI_tr2" => "s", "HMI_tr3" => "s", "HMI_tr_u" => "u", "HMI_tr_d" => "d"];


//--------------------------------------- System variable
$debug = FALSE;
$symbolDebug = FALSE;
$PT1 = [];
$PT1_VERSION = "";
$HMI = [];
$elementCount = ["BL" => 0, "SU" => 0, "SD" => 0, "PF" => 0, "PT" => 0, "PHTU" => 0, "PHTD" => 0, "BSB" => 0, "BSE" => 0, "FRAME" => 0];

// --------------------------------------- Main

print "WinterTrain, DMT\n";
cmdLineParam();
if ($debug) {
  error_reporting(E_ALL);
} else {
  error_reporting(0);
}
switch ($command) {
  case "":
    print "No command given, try -h\n";
  break;
  case "C":
    compilePT2();
  break;
  case "D":
  case "B":
    print "Resetting ".($command == "B" ? "parameter ID" : "all parameters")." to default in:
  $DIRECTORY/$SIGNALLING_LAYOUT_FILE
";
    modifySL();
  break;
  case "O":
  case "U":
    print "Updating ".($command == "O" ? "all" : "changed")." balises in:
  $DIRECTORY/$SIGNALLING_LAYOUT_FILE
reading new IDs from:
  $DIRECTORY/$BALISE_DUMP_FILE
";
    require($BALISE_DUMP_FILE);
    modifySL();
  break;
  case "S":
    generateShuntingBorder();
  break;
  default:
    print "Error: Command $command not implemented (yet)\n";
}

function generateShuntingBorder() {
global $PT1, $elementName, $distance, $maxBalise;
  readSignallingLayout();
  verifySignallingLayout();
  if (isset($PT1[$elementName])) {
    switch ($PT1[$elementName]["element"]) {
      case "BSB":
      case "SD":
        if ($distance >= 0) {
          distUp($PT1[$elementName]["U"]["dist"] - $distance, $PT1[$elementName]["U"]["name"], 0, $elementName);        
        } else {
          print "Error: Border distance must be positive or zero for element type BSB and SD\n";
        }
      break;
      case "BSE":
      case "SU":
        if ($distance <= 0) {
          distDown($PT1[$elementName]["D"]["dist"] + $distance, $PT1[$elementName]["D"]["name"], 0, $elementName);        
        } else {
          print "Error: Border distance must be negative or zero for element type BSE and SU\n";
        }
      break;
      default:
        print "Error: Border element $elementName must be of type Buffer Stop or facing Signal.";
      break;
    }
  } else {
    print "Error: Element \"$elementName\" not found in PT1\n";
  }
}

function distUp($sum, $elementName, $baliseCount, $caller) {
global $PT1, $maxBalise, $wheelFactor;
  $element = $PT1[$elementName];
  switch($element["element"]) {
    case "BSE":
      return;
    break;
    case "SU":
    case "SD":
    case "PHTU":
    case "PHTD":
      $sumCenter = $sum + $element["D"]["dist"];
      distUp($sumCenter + $element["U"]["dist"], $element["U"]["name"], $baliseCount, $elementName);
    break;
    case "BL":
      if ($baliseCount < $maxBalise) {
        $sumCenter = $sum + $element["D"]["dist"];
        print "{{0x".substr($element["ID"],0,2).", 0x".substr($element["ID"],3,2).", 0x".substr($element["ID"],6,2).", 0x".substr($element["ID"],9,2).", 0x".substr($element["ID"],12,2)."},  ".round(-$sumCenter / $wheelFactor)."},    /* $elementName / ".-$sumCenter." cm */\\\n";

        distUp($sumCenter + $element["U"]["dist"], $element["U"]["name"], $baliseCount + 1, $elementName);
      }
    break;
    case "PT":
      $sumCenter = $sum + ($caller == $element["R"]["name"] ? $element["R"]["dist"] :  $element["L"]["dist"]);
      distUp($sumCenter + $element["T"]["dist"], $element["T"]["name"], $baliseCount, $elementName);    
    break;
    case "PF":
      return;
    break;
    default:
    print "Ups...\n";
    break;    
  }
}

function distDown($sum, $elementName, $baliseCount, $caller) {
global $PT1, $maxBalise, $wheelFactor;
  $element = $PT1[$elementName];
  switch($element["element"]) {
    case "BSE":
      return;
    break;
    case "SU":
    case "SD":
    case "PHTU":
    case "PHTD":
      $sumCenter = $sum + $element["U"]["dist"];
      distDown($sumCenter + $element["D"]["dist"], $element["D"]["name"], $baliseCount, $elementName);
    break;
    case "BL":
      if ($baliseCount < $maxBalise) {
        $sumCenter = $sum + $element["U"]["dist"];
        print "{{0x".substr($element["ID"],0,2).", 0x".substr($element["ID"],3,2).", 0x".substr($element["ID"],6,2).", 0x".substr($element["ID"],9,2).", 0x".substr($element["ID"],12,2)."},  ".round($sumCenter / $wheelFactor)."},    /* $elementName / ".$sumCenter." cm */\\\n";

        distDown($sumCenter + $element["D"]["dist"], $element["D"]["name"], $baliseCount + 1, $elementName);
      }
    break;
    case "PF":
      $sumCenter = $sum + ($caller == $element["R"]["name"] ? $element["R"]["dist"] :  $element["L"]["dist"]);
      distDown($sumCenter + $element["T"]["dist"], $element["T"]["name"], $baliseCount, $elementName);    
    break;
    case "PT":
      return;
    break;
    default:
    print "Ups...\n";
    break;    
  }
}


function modifySL() {
global $DIRECTORY, $SIGNALLING_LAYOUT_FILE, $command, $DEFAULT_PARAM, $baliseList, $refdes, $BALISE_DUMP_FILE;


  $refdes = "";
  if (rename("$DIRECTORY/$SIGNALLING_LAYOUT_FILE", "$DIRECTORY/{$SIGNALLING_LAYOUT_FILE}_OLD")) {
    $slFh = fopen("$DIRECTORY/{$SIGNALLING_LAYOUT_FILE}_OLD", "r");
    if ($newSlFh = fopen("$DIRECTORY/$SIGNALLING_LAYOUT_FILE", "w")) {
      while ($line = fgets($slFh) ) {
        $line = trim($line);
//        print "Source: >$line<\n";
        if (strpos($line, "C ") === 0) { // start of element found
          $refdes = "";
        }
        if (strpos($line, "=") !== false) {
          list($param,$value) = explode("=", trim($line));
          switch ($param) {
            case "refdes":
              $refdes = $value;
//              debugPrint ("refdes: $value");
            break;
            default:
              switch ($command) {
                case "B": // Set balise ID to default
                if ($param == "ID") {
                  $line = "ID=FF:FF:FF:FF:FF";
                  debugPrint ("Changed element $refdes: $line");
                }
                break;
                case "D": // Set all element parameters to default
                  if (isset($DEFAULT_PARAM[$param])) {
                    $line = "$param={$DEFAULT_PARAM[$param]}";              
                    debugPrint ("Changed element $refdes: $line");
                  }
                break;
                case "O": // Overwrite balise ID from balise dump
                case "U": 
                  if ($param == "ID")  {
                    if ($refdes != "") {
                      if (isset($baliseList[$refdes]) and ($command == "O" or $baliseList[$refdes]["dynName"])) {
                        $line = "ID={$baliseList[$refdes]["ID"]}";
                        debugPrint ("Changed element $refdes: $line");
                      }
                    } else {
                      print "Error: Attribute \"ID\" is specified before attribute \"refdes\". Please correct BG symbol.\n";
                      exit(1);
                    }
                  }
                break;
              }
            break;
          }
        }
        fwrite($newSlFh, "$line\n");
      }
    } else {
      print "Error: Cannot create $DIRECTORY/$SIGNALLING_LAYOUT_FILE\n";
      exit(1);
    }
  } else {
    print "Error: Cannot rename $DIRECTORY/$SIGNALLING_LAYOUT_FILE to $DIRECTORY/{$SIGNALLING_LAYOUT_FILE}_OLD\n";
  exit(1);
  }
}


function readScreenLayout() {
global $PT1, $HMI, $scFh, $xOffset, $yOffset, $UNIT_SIZE, $FRAME_X_WIDTH, $FRAME_Y_HIGHT, $SU_SIZE, $SD1X_SIZE, $SDY_SIZE, $P_OR, $TR_OR,
  $DIRECTORY, $SCREEN_LAYOUT_FILE, $symbolDebug;

$HMI["label"] = array();

// Find position of frame
  $scFh = fopen("$DIRECTORY/$SCREEN_LAYOUT_FILE", "r");
  $frame = false;
  while ($line = fgets($scFh) ) {
    $param = explode(" ", trim($line));
    if ($param[0] == "C" and $param[6] == "HMI_Frame-0.sym") {
      $xOffset = $param[1] + $FRAME_X_WIDTH;
      $yOffset = $param[2] + $FRAME_Y_HIGHT;
      $frame = true;
      if ($symbolDebug) {
        print "Frame symbol x: {$param[1]} y: {$param[2]}\n";
      }
    }
  }
  if (!$frame) {
    print "Error: Mandatory HMI frame missing in screen layoyt: $DIRECTORY/$SCREEN_LAYOUT_FILE.\n";
    exit(1);
  }
  $scFh = fopen("$DIRECTORY/$SCREEN_LAYOUT_FILE", "r");
  while ($line = fgets($scFh) ) {
    $param = explode(" ", trim($line));
    switch ($param[0]) {
    case "C": // start of component
      $refdes = ""; $device = ""; $length = 1; $x = false; $y = false; $balises = ""; $text = "";
      switch ($param[6]) {
        case "HMI_Frame-0.sym":
        break;
        case "HMI_eSTOP-0.sym":
          if ($symbolDebug) {
            print "eStop symbol x: {$param[1]} y: {$param[2]}\n";
          }
        case "HMI_BSB-0.sym":
        case "HMI_BSE-0.sym":
        case "HMI_tr1-0.sym":
        case "HMI_ARS-0.sym":
        case "HMI_LABEL-0.sym":
          $x = ($param[1] - $xOffset) / $UNIT_SIZE;
          $y = - ($param[2] - $yOffset + $UNIT_SIZE) / $UNIT_SIZE;
          $length = 1;
        break;
        case "HMI_tr2-0.sym":
          $x = ($param[1] - $xOffset) / $UNIT_SIZE;
          $y = - ($param[2] - $yOffset + $UNIT_SIZE) / $UNIT_SIZE;
          $length = 2;
        break;
        case "HMI_tr3-0.sym":
          $x = ($param[1] - $xOffset) / $UNIT_SIZE;
          $y = - ($param[2] - $yOffset + $UNIT_SIZE) / $UNIT_SIZE;
          $length = 3;
        break;
        case "HMI_SU1-0.sym":
          $x = ($param[1] - $xOffset) / $UNIT_SIZE;
          $y = - ($param[2] - $yOffset + $SU_SIZE) / $UNIT_SIZE;
          $length = 1;
        break;
        case "HMI_SU2-0.sym":
          $x = ($param[1] - $xOffset) / $UNIT_SIZE;
          $y = - ($param[2] - $yOffset + $SU_SIZE) / $UNIT_SIZE;
          $length = 2;
        break;
        case "HMI_SD1-0.sym":
          $x = ($param[1] - $xOffset +$SD1X_SIZE) / $UNIT_SIZE;
          $y = - ($param[2] - $yOffset + $SDY_SIZE) / $UNIT_SIZE;
          $length = 1;
        break;
        case "HMI_SD2-0.sym":
          $x = ($param[1] - $xOffset) / $UNIT_SIZE;
          $y = - ($param[2] - $yOffset + $SDY_SIZE) / $UNIT_SIZE;
          $length = 2;
        break;
        case "HMI_PF_l-0.sym":
        case "HMI_PF_r-0.sym":
        case "HMI_PT_l-0.sym":
        case "HMI_PT_r-0.sym":
          $x = ($param[1] - $xOffset) / $UNIT_SIZE;
          $y = -($param[2] - $yOffset + 3 * $UNIT_SIZE) / $UNIT_SIZE;
          $length = 2;
        break;
        case "HMI_tr_u-0.sym":
        case "HMI_tr_d-0.sym":
          $x = ($param[1] - $xOffset) / $UNIT_SIZE;
          $y = -($param[2] - $yOffset + 3 * $UNIT_SIZE) / $UNIT_SIZE;
          $length = 1;
        break;
        default:
          print "Warning: Unknown symbol \"{$param[6]}\"\n";
        break;
      }
    break;
    case "}":
      switch ($device) {
        case "HMI_Frame": //
          $HMI["projectName"] = $projectName;
        break;
        case "":
        break;
        case "HMI_BSB": // Generate PT1 data
        case "HMI_BSE":
        case "HMI_SU1":
        case "HMI_SU2":
        case "HMI_SD1":
        case "HMI_SD2":
          if (isset($PT1[$refdes])) {
            $PT1[$refdes]["HMI"]["x"] = $x;
            $PT1[$refdes]["HMI"]["y"] = $y;
            $PT1[$refdes]["HMI"]["l"] = $length;
          } else {
            print "Warning: Screen element $refdes at ($x, $y) not known in PT1 data\n";
          }
        break;
        case "HMI_PF_r":
        case "HMI_PF_l":
        case "HMI_PT_r":
        case "HMI_PT_l":
          if (isset($PT1[$refdes])) {
            $PT1[$refdes]["HMI"]["or"] = $P_OR[$device];
            $PT1[$refdes]["HMI"]["x"] = $x;
            $PT1[$refdes]["HMI"]["y"] = $y;
            $PT1[$refdes]["HMI"]["l"] = $length;
          } else {
            print "Warning: Screen element $refdes at ($x, $y) not known in PT1 data\n";
          }
        break;
        case "HMI_tr1": // Generate HMI/balisetrack data
        case "HMI_tr2":
        case "HMI_tr3":
        case "HMI_tr_u":
        case "HMI_tr_d":
          if (!isset($HMI["baliseTrack"][$refdes])) {
            $HMI["baliseTrack"][$refdes]["balises"] = explode(",", $balises);
            $HMI["baliseTrack"][$refdes]["or"] = $TR_OR[$device];
            $HMI["baliseTrack"][$refdes]["x"] = $x;
            $HMI["baliseTrack"][$refdes]["y"] = $y;
            $HMI["baliseTrack"][$refdes]["l"] = $length;
            if ($balises == "") {
              print "Warning: No balises assigned to $refdes at ($x, $y)\n";
            } else {
              $balise = explode(",",$balises);
              foreach ($balise as $name) {
                if (!isset($PT1[$name])) {
                  print "Warning: Unknown balise $name assigned to element $refdes at ($x, $y)\n";
                }
              }
            }
          } else {
            print "Warning: Dublicated balisetrack element $refdes at ($x, $y) - ignored.\n";
          }
        break;
        case "HMI_LABEL": // Generate HMI/labels 
          $HMI["label"][] = ["x" => $x, "y" => $y, "text" => $text];
        break;
        case "HMI_eStop": // Generate HMI/eStop 
          $HMI["eStopIndicator"]= ["x" => $x, "y" => $y];
        break;
        case "HMI_ARS": // Generate HMI/ars 
          $HMI["arsIndicator"]= ["x" => $x, "y" => $y];
        break;
        default:
          print "Warning: Unknown device: $device at ($x, $y)\n";
        break;
      }
    break;
    default: 
      $param2 = explode("=",trim($line));
      switch ($param2[0]) {
      case "refdes":
        $refdes = $param2[1];
      break;
      case "device":
        $device = $param2[1];
      break;
      case "text":
        $text = $param2[1];
      break;
      case "balises":
        $balises = $param2[1];
      break;
      case "ProjectName":
        $projectName = $param2[1];
      break;
      }
    break;
    }
  }
}

function readSignallingLayout () {
global $DIRECTORY, $GNETLIST_CMD, $SIGNALLING_LAYOUT_FILE, $PT1;

  exec ("cd $DIRECTORY; $GNETLIST_CMD -g wt $SIGNALLING_LAYOUT_FILE");
  require("$DIRECTORY/output.net");
  unlink("$DIRECTORY/output.net");
}

function verifySignallingLayout() {
global $PT1, $elementCount, $projectName, $projectDate, $projectAuthor, $doReplaceDefaultID;

  $frame = false;
  $virtuelID = 1; 
  foreach($PT1 as $name => &$element) {
    switch($element["element"]) {
    case "BL":
      $element["ID"] = strtoupper($element["ID"]);
      if ($element["ID"] == "00:00:00:00:00" or $element["ID"] == "") {
        print "Warning: Empty or default Balise ID assigned to element $name\n";
      }
      if ($element["ID"] == "FF:FF:FF:FF:FF" and $doReplaceDefaultID) { // Default balise ID FF:FF:FF:FF:FF will be replaced by unique ID
                                                                        // Allows balises to be used by simulated trains and as virtual balises
        $element["ID"] = sprintf("00:00:00:%02X:%02X", intdiv($virtuelID, 256), $virtuelID % 256);
        $virtuelID +=1;
      }
      if (preg_match("/^[0-9a-fA-F]{2}[:][0-9a-fA-F]{2}[:][0-9a-fA-F]{2}[:][0-9a-fA-F]{2}[:][0-9a-fA-F]{2}$/", $element["ID"]) == 0){
        print "Error: Invalid balise ID format: \"{$element["ID"]}\", element $name\n";
      }
      if (isset($baliseID[$element["ID"]]) and $element["ID"] != "FF:FF:FF:FF:FF" and $element["ID"] != "00:00:00:00:00") {
        print "Error: Dublicated balise ID {$element["ID"]} found in elements $name and {$baliseID[$element["ID"]]}\n";
      }
      $baliseID[$element["ID"]] = $name;
    break;
    case "SU":
    case "SD":
      if ($element["type"] != "MB") {
        if (!is_int($element["EC"]["addr"]) or $element["EC"]["addr"] == 0) {
          print "Warning: No EC address assigned to real signal $name. Use element type \"MB\" (marker board)\n";
        }
        if (!is_int($element["EC"]["majorDevice"]) or $element["EC"]["majorDevice"] < 1 or $element["EC"]["majorDevice"] > 32 ) {
          print "Warning: No valid major device number assigned to physical signal $name\n";
        }
      }
    break;
    case "PF":
    case "PT":
      if ($element["supervisionState"] == "P" or $element["supervisionState"] == "F") {
        if ($element["EC"]["addr"] == 0 or ! is_int($element["EC"]["addr"])) {
          print "Warning: No EC address assigned to  point $name having supervision state {$element["supervisionState"]} \n";
        }
        if (!is_int($element["EC"]["majorDevice"]) or $element["EC"]["majorDevice"] < 1 or $element["EC"]["majorDevice"] > 32 ) {
          print "Warning: No valid major device number assigned to  point $name having supervision state {$element["supervisionState"]} \n";
        }
      }
    break;
    case "PHTU":
    case "PHTD":
      if (!isset($PT1[$element["holdPoint"]])) {
        print "Error: Unknown or empty point {$element["holdPoint"]} assigned to {$element["element"]} element $name\n";
      }
    break;
    case "BSB":
    case "BSE":
    break;
    case "FRAME":
      $projectName = $element["projectName"];
      $projectDate = $element["date"];
      $projectAuthor = $element["author"];
      unset($PT1["FRAME"]); // In order not to confuse RBCIL
      $frame = true;
    break;
    default:
      print ("Error: Element type {$element["element"]} (instance: $name) not implemented.\n");
    }
    $elementCount[$element["element"]] += 1;
  }
  if (!$frame) {
    print "Warning: Frame symbol missing in signalling layout. Project name ect. set to default\n";
  }
}

function compilePT2() {
global $PT1, $HMI, $SCREEN_LAYOUT_FILE, $SIGNALLING_LAYOUT_FILE, $PT2_FILE, $DIRECTORY, $xOffset, $yOffset, $FRAME_X_WIDTH, $FRAME_Y_HIGHT, $PT1_VERSION, $elementCount, $projectName, $projectDate, $projectAuthor;

print "Compiling\n  Signalling Layout: \"$DIRECTORY/$SIGNALLING_LAYOUT_FILE\" and
  Screen Layout: \"$DIRECTORY/$SCREEN_LAYOUT_FILE\" into
  Output file: \"$DIRECTORY/$PT2_FILE\"
";

  $pt2Fh = fopen("$DIRECTORY/$PT2_FILE", "w");

  readSignallingLayout();
  verifySignallingLayout();
  readScreenLayout();
  
  print "Element count: ";
  foreach ($elementCount as $e => $c) {
    print "$e: $c, ";
  }
  print "\n";
  
fwrite($pt2Fh, "<?php
// ------------------------------------------------- Sources
\$PT2_SIGNALLING_LAYOUT_FILE = \"".(realpath("$DIRECTORY/$SIGNALLING_LAYOUT_FILE"))."\";
\$PT2_SIGNALLING_LAYOUT_FILE_DATE = \"".(date("Y-m-d H:i:s", filemtime("$DIRECTORY/$SIGNALLING_LAYOUT_FILE")))."\";
\$PT2_SCREEN_LAYOUT_FILE = \"".(realpath("$DIRECTORY/$SCREEN_LAYOUT_FILE"))."\";
\$PT2_SCREEN_LAYOUT_FILE_DATE = \"".(date("Y-m-d H:i:s", filemtime("$DIRECTORY/$SCREEN_LAYOUT_FILE")))."\";
\$PT2_GENERATION_TIME = \"".(date("Y-m-d H:i:s"))."\";
\$PT1_PROJECT_NAME = \"$projectName\";
\$PT1_DATE = \"$projectDate\";
\$PT1_AUTHOR = \"$projectAuthor\";
\$HMI_PROJECT_NAME = \"{$HMI["projectName"]}\";

// -------------------------------------------------- PT1
\$PT1 = ".(var_export($PT1,true)).";

// -------------------------------------------------- HMI
\$HMI = ".(var_export($HMI,true)).";
?>");

}


function CmdLineParam() {
global $debug, $SCREEN_LAYOUT_FILE, $SIGNALLING_LAYOUT_FILE, $PT2_FILE, $DIRECTORY,$BALISE_DUMP_FILE, $element1, $element2, $argv, $PT1, $command, $symbolDebug, $doReplaceDefaultID, $elementName, $distance, $wheelFactor, $maxBalise;
  if (in_array("-h",$argv) or count($argv) == 1) {
    print "Usage: [option] COMMAND [PARAM]
Generate PT2 data for the WinterTrain. All files are located in working directory unless option -D is called..
COMMAND
C                 Compile and verify PT2 data from input file \"$SIGNALLING_LAYOUT_FILE\" and \"$SCREEN_LAYOUT_FILE\" into output file \"$PT2_FILE\"
                   
D                 Set all element parameters in \"$SIGNALLING_LAYOUT_FILE\" to Default values overwriting any engineered values. Element names are not affected. 

N                 Set all element Names in \"$SIGNALLING_LAYOUT_FILE\" to default values overwriting any engineered values. Element parameters are not affected.

U                 Update ID of all balises in \"$SIGNALLING_LAYOUT_FILE\" to ID values found in \"$BALISE_DUMP_FILE\" marked as changed.

O                 Overwrite ID of all balises in \"$SIGNALLING_LAYOUT_FILE\" to ID values found in \"$BALISE_DUMP_FILE\" ignoring any change marking.

B                 Overwrite ID of all Balises in \"$SIGNALLING_LAYOUT_FILE\" to default ID = \"FF:FF:FF:FF:FF\".

S <element> <distance> <wheel factor> <max balises>
                  Generate a list of distances from balises to a location <distance> away from <element>. The sign of <distance> indicates the direction
                  from <element>. <wheel factor> is the conversion from wheel turn to distance.
                  The element must be of type Buffer stop or facing signal. A maximum of <max balises> will be included in the list.
                  The list will be formatted as an array in C syntax ready to be used in OBU application data. 
                  
Commands D, N, U, O and B will rename the input file \"$SIGNALLING_LAYOUT_FILE\" to \"{$SIGNALLING_LAYOUT_FILE}_OLD\" and create a new \"$SIGNALLING_LAYOUT_FILE\"
                  
-D <dir>          use <dir> as directory for all files. Must be given before -bl -sc -si and -p2 in order to take effect.
-sc <file>        read screenLayout from <file>
-si <file>        read signalling layout from <file>
-bl <file>        read balise list (for command U and O) from <file>
-p2 <file>        write PT2 data to <file>
-nv               do not replace default balise ID FF:FF:FF:FF:FF with unique ID
-d                enable debug info
-s                enable symbol debug info
";
    exit();
  }
  while ($opt = next($argv)) {
    switch ($opt) {
      case "C":
      case "D":
      case "N":
      case "U":
      case "O":
      case "B":
        $command = $opt;
      break;
      case "S":
        $command = $opt;
        if (count($argv) >=4) {
          $elementName = next($argv);
          if (!is_numeric($distance  = next($argv))) {
            print "Error: <distance> must be an integer\n";
          }
          if (!is_numeric($wheelFactor  = next($argv))) {
            print "Error: <wheel factor> must be an integer\n";
          }
          if (!is_numeric($maxBalise =  next($argv))) {
            print "Error: <max balise> must be an integer\n";          
          }
        } else {
          print "Error: command S requires four parameters\n";
          exit(1);
        }        
      case "-nv":
        $doReplaceDefaultID = false;
      break;
      case "-sc":
        if (count($argv) >= 1) {
          $SCREEN_LAYOUT_FILE = next($argv);
          if (!is_readable("$DIRECTORY/$SCREEN_LAYOUT_FILE")) {
            print "Error: option -sc: Cannot read $DIRECTORY/$SCREEN_LAYOUT_FILE \n";
            exit(1);
          }
        } else {
          print "Error: option -sc: File name is missing \n";
          exit(1);
        }
        break;
      case "-si":
        if (count($argv) >= 1) {
          $SIGNALLING_LAYOUT_FILE = next($argv);
          if (!is_readable("$DIRECTORY/$SIGNALLING_LAYOUT_FILE")) {
            print "Error: option -si: Cannot read $DIRECTORY/$SIGNALLING_LAYOUT_FILE \n";
            exit(1);
          }
        } else {
          print "Error: option -si: File name is missing \n";
          exit(1);
        }
        break;
      case "bl":
        if (count($argv) >= 1) {
          $BALISE_DUMP_FILE = next($argv);
          if (!is_readable("$DIRECTORY/$BALISE_DUMP_FILE")) {
            print "Error: option -bl: Cannot read $DIRECTORY/$BALISE_DUMP_FILE \n";
            exit(1);
          }
        } else {
          print "Error: option -bl: File name is missing \n";
          exit(1);
        }
        break;
        case "-p2":
        if (count($argv) >= 1) {
          $P2_FILE = next($argv);
          if (!is_writeable("$DIRECTORY/$P2_FILE")) {
            print "Error: option -p2: Cannot write $DIRECTORY/$P2_FILE \n";
            exit(1);
          }
        } else {
          print "Error: option -p2: File name is missing \n";
          exit(1);
        }
        break;
      case "-D":
        if (count($argv) >= 1) {
          $DIRECTORY = next($argv);
          if (!is_dir($DIRECTORY)) {
            print "Error: option -D: Cannot access $DIRECTORY\n";
            exit(1);
          }
        } else {
          print "Error: option -D: Directory name is missing\n";
          exit(1);
        }
        break;
      case "-d":
      case "--debug";
        $debug = TRUE;
        print "Debugging mode\n";
        break;
      case "-s":
        $symbolDebug = TRUE;
        print "Symbol debugging mode\n";
        break;
      default :
        print "Unknown option: $opt\n";
      exit(1);
    }
  }
}

function debugPrint($txt) {
global $debug;
  if ($debug & 0x01) {
    print "$txt\n";
  }
}

?>

