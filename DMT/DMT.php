#!/usr/bin/php
<?php
// WinterTrain, PT2 generator


// -------------------------------------- File names and defaults
$SCREEN_LAYOUT_FILE = "screenLayout.sch";
$SIGNALLING_LAYOUT_FILE = "signallingLayout.sch";
$PT2_FILE = "PT2.php";
$DIRECTORY = ".";

$GNETLIST_CMD = "/usr/bin/gnetlist";

$PT1_PROJECT_NAME = "(n/a)";
$PT1_DATE = "(n/a)";
$PT1_AUTHOR ="(n/a)";

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
  case "C":
    compilePT2();
  break;
}

function readScreenLayout() {
global $PT1, $HMI, $scFh, $xOffset, $yOffset, $UNIT_SIZE, $FRAME_X_WIDTH, $FRAME_Y_HIGHT, $SU_SIZE, $SD1X_SIZE, $SDY_SIZE, $P_OR, $TR_OR,
  $DIRECTORY, $SCREEN_LAYOUT_FILE, $symbolDebug;

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
          Print "Warning: Unknown symbol \"{$param[6]}\"\n";
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
                Print "Warning: Unknown balise $name assigned to element $refdes at ($x, $y)\n";
              }
            }
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
global $PT1, $elementCount, $projectName, $projectDate, $projectAuthor;

  $frame = false;
  foreach($PT1 as $name => $element) {
    switch($element["element"]) {
    case "BL":
      if ($element["ID"] == "FF:FF:FF:FF:FF" or $element["ID"] == "00:00:00:00:00" or $element["ID"] == "") {
        print "Warning: Empty or default Balise ID assigned to element $name\n";
      }
      if (preg_match("/^[0-9a-fA-F]{2}[:][0-9a-fA-F]{2}[:][0-9a-fA-F]{2}[:][0-9a-fA-F]{2}[:][0-9a-fA-F]{2}$/", $element["ID"]) == 0){
        print "Error: Invalid balise ID format: \"{$element["ID"]}\", element $name\n";
      }
      if (isset($baliseID[$element["ID"]])) {
        print "Error: Dublicated balise ID {$element["ID"]} found in elements $name and {$baliseID[$element["ID"]]}\n";
      }
      $baliseID[$element["ID"]] = $name;
    break;
    case "SU":
    case "SD":
      if ($element["type"] != "MB" and ($element["EC"]["addr"] == 0 or ! is_int($element["EC"]["addr"]))) {
        print "Warning: No EC address assigned to real signal $name. Use element type \"MB\" (marker board)\n";
      }
    break;
    case "PF":
    case "PT":
      if (($element["supervisionState"] == "P" or $element["supervision State"] == "F") and 
        ($element["EC"]["addr"] == 0 or ! is_int($element["EC"]["addr"]))) {
        print "Warning: No EC address assigned to  point $name having supervision state {$element["supervisionState"]} \n";
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
global $debug, $SCREEN_LAYOUT_FILE, $SIGNALLING_LAYOUT_FILE, $PT2_FILE, $DIRECTORY, $element1, $element2, $argv, $PT1, $command, $symbolDebug;
  if (in_array("-h",$argv) or count($argv) == 1) {
    print "Usage: [option] COMMAND [PARAM]
Generate PT2 data for the WinterTrain
COMMAND
C                 Compile and verify PT2 data from input file \"signallingLayout.sch\" and \"screenLayout.sch\" into output file \"PT2.php\"
                  All files located in working directory. 
                  
-D <dir>          use <dir> as working directory for all files. Must be given before -sc -si and -p2 in order to take effect.
-sc <file>        read screenLayout from <file>
-si <file>        read signalling layout from <file>
-p2 <file>        write PT2 data to <file>
-d                enable debug info
-s                enable symbol debug info
";
    exit();
  }
  next($argv);
  while (list(,$opt) = each($argv)) {
    switch ($opt) {
      case "d":
        $command = "d";
        list(,$element1) = each($argv);
        list(,$element2) = each($argv);
        if (!$element1 or !$element2) {
          print "Error: command d requires two element names.\n";
        exit(1);
        }
      break;
      case "C":
        $command = "C";
      break;
      case "e":
        $command = "e";
      break;
      case "-sc":
        list(,$p) = each($argv);
        if ($p) {
          $SCREEN_LAYOUT_FILE = $p;
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
        list(,$p) = each($argv);
        if ($p) {
          $SIGNALLING_LAYOUT_FILE = $p;
          if (!is_readable("$DIRECTORY/$SIGNALLING_LAYOUT_FILE")) {
            print "Error: option -si: Cannot read $DIRECTORY/$SIGNALLING_LAYOUT_FILE \n";
            exit(1);
          }
        } else {
          print "Error: option -si: File name is missing \n";
          exit(1);
        }
        break;
      case "-p2":
        list(,$p) = each($argv);
        if ($p) {
          $P2_FILE = $p;
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
        list(,$p) = each($argv);
        if ($p) {
          $DIRECTORY = $p;
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

?>

