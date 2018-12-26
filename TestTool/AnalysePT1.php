#!/usr/bin/php
<?php
// WinterTrain, PT1 analyser

$DATA_FILE = "../WaySide/SiteData/CBU/CBU.php";

//--------------------------------------- System variable
$debug = FALSE;
print "WinterTrain, PT1 analyser\n";
cmdLineParam();
if ($debug) {
  error_reporting(E_ALL);
} else {
  error_reporting(0);
}

require($DATA_FILE);

switch ($command) {
  case 'd':
    if (!array_key_exists($element1,$PT1)) {
      print "Error: Element $element1 is not defined in PT1 data\n";
      exit(1);
    }
    if (!array_key_exists($element2,$PT1)) {
      print "Error: Element $element2 is not defined in PT1 data\n";
      exit(1);
    }
    if ($PT1[$element1]["element"] != "BSE") {
      print "Up:\n";
      distance($PT1[$element1]["U"]["dist"], $PT1[$element1]["U"]["name"],$element2,"U", $element1)."\n";
    }
    if ($PT1[$element1]["element"] != "BSB") {
      print "\nDown:\n";
      distance($PT1[$element1]["D"]["dist"], $PT1[$element1]["D"]["name"],$element2,"D", $element1)."\n";
    }
  break;
}

function distance($sum, $element1, $element2, $direction, $previousElement) {
global $PT1;

//print "A: $sum, $element1, $element2, $direction\n";
  if ($element1 == $element2) {
  print "Sum: ".($sum + $PT1[$element1][($direction == "U" ? "D" : "U")]["dist"])."\n";
    return;
  } else {
    switch ($PT1[$element1]["element"]) {
      case "BSB":
      case "BSE":
        return;
      break;
      case "PF":
        if ($direction == "U") {
          print "$element1 right ";
          distance($PT1[$element1]["T"]["dist"] + $PT1[$element1]["R"]["dist"]
            + $sum, $PT1[$element1]["R"]["name"], $element2, $direction, $element1);
          print "$element1 left ";
          distance($PT1[$element1]["T"]["dist"] + $PT1[$element1]["L"]["dist"] 
            + $sum, $PT1[$element1]["L"]["name"], $element2, $direction, $element1);
            return;
        } else {
          distance($PT1[$element1]["T"]["dist"] 
            + $PT1[$element1][($previousElement == $PT1[$element1]["R"]["name"] ? "R": "L")]["dist"] + $sum, 
              $PT1[$element1]["T"]["name"], $element2, $direction, $element1);
          return ;
        }
      break;
      case "PT":
        if ($direction == "D") {
          print "$element1 right ";
          distance($PT1[$element1]["T"]["dist"] + $PT1[$element1]["R"]["dist"]
            + $sum, $PT1[$element1]["R"]["name"], $element2, $direction, $element1);
          print "$element1 left ";
          distance($PT1[$element1]["T"]["dist"] + $PT1[$element1]["L"]["dist"] 
            + $sum, $PT1[$element1]["L"]["name"], $element2, $direction, $element1);
          return; 
        } else {
          distance($PT1[$element1]["T"]["dist"] 
            + $PT1[$element1][($previousElement == $PT1[$element1]["R"]["name"] ? "R": "L")]["dist"] + $sum, 
              $PT1[$element1]["T"]["name"], $element2, $direction, $element1);
          return;
        }
      break;
      case "BL":
      case "SU":
      case "SD":
      case "LX":
      case "PHTU":
      case "PHTD":
        distance($PT1[$element1]["D"]["dist"] + $PT1[$element1]["U"]["dist"] + $sum, 
          $PT1[$element1][$direction]["name"],$element2, $direction, $element1);
        return;
      break;
      default:
        print "Ups 1: {$PT1[$element1]["element"]}\n";
        exit(1);
      break;
    }
  }
}

function CmdLineParam() {
global $debug, $DATA_FILE, $element1, $element2, $argv, $PT1, $command;
  if (in_array("-h",$argv) or count($argv) == 1) {
    print "Usage: [option] COMMAND [PARAM]
Performs analysis of PT1 data for the WinterTrain
COMMAND
d <element1> <element2>
                  measures distance betwen centre of <element1> and <element2>

-f <file>         reads PT1 data from <file>
-d                enable debug info
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
      case "-f":
        list(,$p) = each($argv);
        if ($p) {
          $DATA_FILE = $p;
          if (!is_readable($DATA_FILE)) {
            print "Error: option -f: Cannot read $DATA_FILE \n";
            exit(1); // If a data file is specified at the cmd line, it has to exist
          }
        } else {
          print "Error: option -f: File name is missing \n";
          exit(1);
        }
        break;
      case "-d":
      case "--debug";
        $debug = TRUE;
        print "Debugging mode\n";
        break;
      default :
        print "Unknown option: $opt\n";
      exit(1);
    }
  }
}


?>

