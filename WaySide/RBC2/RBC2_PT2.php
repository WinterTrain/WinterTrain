<?php
// WinterTrain, RBC2
// PT2 handlers

// Print or log errors? ProcessPT2 might be called while running in background FIXME


function  ProcessPT2() { //  PT2 (site specific application data) management and analysis
global $DIRECTORY, $PT2_FILE, $PT2, $trackModel, $HMI, $balisesID, $baliseCountTotal, $totalElement,  $errorFound, $nInspection,
  $baliseCountUnassigned;

  require("$DIRECTORY/$PT2_FILE");
  if (isset($PT1)) { // Rename PT1 to PT2 - DMT to be updated to generate "PT2"
    $PT2 = $PT1;
    unset($PT1);
  }
  if (array_key_exists("", $PT2)) { unset($PT2[""]); } // Delete any remaining template entry
  $totalElement = count($PT2);
  $baliseCountUnassigned = 0;
  $baliseCountTotal = 0;
  $trackModel = array();
  
  foreach ($PT2 as $name => &$element) {  // -------------- Check each node and generate various objects, lists etc.
    $element["checked"] = false;
    switch ($element["element"]) {
      case "BL":
        $balisesID[$element["ID"]] = $name;
//        $element["dynName"] = false; // HHT FIXME
        if ($element["ID"] == "FF:FF:FF:FF:FF") $baliseCountUnassigned++; // Other unassigned IDs ?? e.g. 00:00:00:00:00 FIXME 
        $baliseCountTotal++;
        $trackModel[$name] = new BGelement($name);
      break;
      case "TG":
        $triggers[] = $name;
        $trackModel[$name] = new TGelement($name);
      break;
      case "PHTU":
      case "PHTD":
        if (!isset($PT2[$element["holdPoint"]])) {
          $errorFound = true;
          print "Error: Unknown point \"{$element["holdPoint"]}\" specified in PHTU/PHTD: $name\n";
        }
        $trackModel[$name] = new PHTelement($name);
      break;
      case "LX":
        $levelCrossings[] = $name;
        $trackModel[$name] = new LXelement($name);
      break;
      case "PF":
      case "PT":
        $points[] = $name;
        switch($element["supervisionState"]) {
          case "U":   // Unsupervised
          case "S":   // Suprvision state simulated, no real point machine
          case "P":   // Real point machine
          case "F":   // Real point machine with position feedback
          case "CR":  // Clamped right 
          case "CL":  // Clamped left
          break;    
          default:
            print "Element $name: Unknown supervision state: {$element["supervisionState"]}\n";
            $errorFound = true;
          break;
        }
        $trackModel[$name] = new Pelement($name);
      break;
      case "SU":
      case "SD":
        $trackModel[$name] = new Selement($name);
      break;
      case "BSB":
      case "BSE":
        $bufferstops[] = $name;
        $trackModel[$name] = new BSelement($name);
      break;
      default:
        print "Element $name: Unknown type of element.\n";
        $errorFound = true;
      break;
    }
  }
  unset($element); // Otherwise next foreach is not working, see PHP manual
  
  foreach ($PT2 as $name => $element) {  // ---------------------------------------------------------- Assign neighbours to elements
    switch ($element["element"]) {
      case "SU":
      case "SD":
      case "BL":
      case "TG":
      case "PHTU":
      case "PHTD":
      case "LX":
        $trackModel[$name]->neighbourUp = $trackModel[$element["U"]["name"]];
        $trackModel[$name]->neighbourDown = $trackModel[$element["D"]["name"]];
      break;
      case "PF":
      case "PT":
        $trackModel[$name]->neighbourTip = $trackModel[$element["T"]["name"]];
        $trackModel[$name]->neighbourRight = $trackModel[$element["R"]["name"]];
        $trackModel[$name]->neighbourLeft = $trackModel[$element["L"]["name"]];
      break;
      case "BSB":
        $trackModel[$name]->neighbourUp = $trackModel[$element["U"]["name"]];
      break;
      case "BSE":
        $trackModel[$name]->neighbourDown = $trackModel[$element["D"]["name"]];
      break;
      default:
      break;
    }
  }
  
  print "Count of elements: $totalElement\n";
  $start = ""; 
  foreach ($bufferstops as $name) { // ----------.---------------- Find a beginning Bufferstop as starting point for checking the graph
    if ($PT2[$name]["element"] == "BSB") {
      $start = $name;
      break;
    }
  }
  if ($start) { // ----------------------------------------------- Inspect all elements
    $PT2[$start]["checked"] = true;
    $nInspection = 0;
    inspect($PT2[$start]["U"],$start,true);
  } else {
    print "Error: At least one element of type 'BSB' (Bufferstop begin) is required in the track network.\n";
    $errorFound = true;
  }
  foreach ($PT2 as $name => $element) { // ----------------------- Check that all nodes have been checked and hence are connected
   if (!$element["checked"] and $name != "") {
      print "Warning: Element $name is not connected to main network. Element ignored.\n";
    }
  }
  if ($errorFound) {
    print "Error: Track network not OK. Source: $DIRECTORY/$PT2_FILE\n";
    fatalError("Track network not OK. Source: $DIRECTORY/$PT2_FILE");
  } else {
    msgLog("Found $totalElement elements in PT2 data file: $DIRECTORY/$PT2_FILE");
    if ($baliseCountUnassigned > 0) msgLog("Warning: Found $baliseCountUnassigned balises with default ID (FF:FF:FF:FF:FF)");
  }
  
// FIXME Check uniqueness of balises - done by DMT. Allow for virtual balises with same ID

// ------------------------------------- Check HMI data
  if (array_key_exists("", $HMI["baliseTrack"])) { unset($HMI["baliseTrack"][""]); } // Delete any remaining template entry
  foreach ($HMI["baliseTrack"] as $trackName => $baliseTrack ) {
//    $baliseTrack["trackState"] = T_CLEAR; FIXME
    foreach ($baliseTrack["balises"] as $baliseName ) {
      if (!isset($PT2[$baliseName])) {
        $errorFound = true;
        print "Error: Unknown balise \"$baliseName\" in HMI baliseTrack: $trackName\n";
      }
    }
  }
  if ($errorFound) {
    print "Error: HMI data not OK. Source: $DIRECTORY/$PT2_FILE\n";
    fatalError("HMI data not OK. Source: $DIRECTORY/$PT2_FILE");
  }  
}

function inspect($this, $prevName, $up) { // Check each edge in the graph for consistency
global $PT2, $nInspection, $totalElement, $errorFound;
  $nInspection +=1;
  $name = $this["name"];
  if ($nInspection < 3 * $totalElement) { //  Prevent endless inspection loop
    if (array_key_exists($this["name"], $PT2)) {
      $thisNode = $PT2[$name];
      if ($up) { // ----------------------- UP
        switch ($thisNode["element"]) {
          case "BL":
          case "TK":
          case "TG":
          case "LX":
          case "PHTU":
          case "PHTD":
            $neighbor = $thisNode["D"];
            $PT2[$name]["checked"] = true;
            inspect($thisNode["U"], $name, true);
          break;
          case "PF":
            $neighbor = $thisNode["T"];
            $PT2[$name]["checked"] = true;
            inspect($thisNode["R"], $name, true);
            inspect($thisNode["L"], $name, true);
          break;
          case "PT":
            if ($prevName == $thisNode["R"]["name"]) {
              $neighbor = $thisNode["R"];
              if (!$thisNode["checked"]) {
                $PT2[$name]["checked"] = true;
                inspect($thisNode["T"], $name, true);
                inspect($thisNode["L"], $name, false);
              }
            } elseif ($prevName == $thisNode["L"]["name"]) {
              $neighbor = $thisNode["L"];
              if (!$thisNode["checked"]) {
                $PT2[$name]["checked"] = true;
                inspect($thisNode["T"], $name, true);
                inspect($thisNode["R"], $name, false);
              }
            } else {
              $PT2[$name]["checked"] = true;
              inspect($thisNode["T"], $name, true);
              $neighbor = ["name" => "","dist" => 0];
              print "Error: ($prevName => $name) Inconsistant branch reference\n";
              $errorFound = true;
            }
          break;
          case "SU":
            $neighbor = $thisNode["D"];
            $PT2[$name]["checked"] = true;
            inspect($thisNode["U"], $name, true);
          break;
          case "SD":
            $neighbor = $thisNode["D"];
            $PT2[$name]["checked"] = true;
            inspect($thisNode["U"], $name, true);
          break;
          case "BSB":
            print "Error: ($prevName => $name) BSB cannot be used as end of track for direction up.\n";
            $neighbor = ["name" => "","dist" => 0];
            $errorFound = true;
          break;
          case "BSE":
            $PT2[$name]["checked"] = true;
            $neighbor = $thisNode["D"];
          break;
          default :
            print "Error: ($prevName => $name) Unknown element type: ".$thisNode["element"]."\n";
            $errorFound = true;
        }
      } else { //------------------------------------------- DOWN
        switch ($thisNode["element"]) {
          case "BL":
          case "TR":
          case "TG":
          case "LX":
          case "PHTU":
          case "PHTD":
            $neighbor = $thisNode["U"];
            $PT2[$name]["checked"] = true;
            inspect($thisNode["D"], $name, false);
          break;
          case "PT":
            $neighbor = $thisNode["T"];
            $PT2[$name]["checked"] = true;
            inspect($thisNode["R"], $name, false);
            inspect($thisNode["L"], $name, false);
          break;
          case "PF":
            if ($prevName == $thisNode["R"]["name"]) {
              $neighbor = $thisNode["R"];
              if (!$thisNode["checked"]) {
                $PT2[$name]["checked"] = true;
                inspect($thisNode["T"], $name, false);
                inspect($thisNode["L"], $name, true);
              }
            } elseif ($prevName == $thisNode["L"]["name"]) {
              $neighbor = $thisNode["L"];
              if (!$thisNode["checked"]) {
                $PT2[$name]["checked"] = true;
                inspect($thisNode["T"], $name, false);
                inspect($thisNode["R"], $name, true);
              }
            } else {
              inspect($thisNode["T"], $name, false);
              $neighbor = ["name" => "","dist" => 0];
              print "Error: ($prevName => $name) Inconsistant branch reference\n";
              $errorFound = true;
            }
          break;
          case "SU":
            $neighbor = $thisNode["U"];
            $PT2[$name]["checked"] = true;
            inspect($thisNode["D"], $name, false);
          break;
          case "SD":
            $neighbor = $thisNode["U"];
            $PT2[$name]["checked"] = true;
            inspect($thisNode["D"], $name, false);
          break;
          case "BSB":
            $PT2[$name]["checked"] = true;
            $neighbor = $thisNode["U"];
          break;
          case "BSE":
            print "Error: ($prevName => $name) BSE cannot be used as end of track for direction down.\n";
            $neighbor = ["name" => "","dist" => 0];
            $errorFound = true;
          break;
            default :
            print "Error: ($prevName => $name) Unknown element type: ".$thisNode["element"]."\n";
            $errorFound = true;
        }
      }
      if ($neighbor["name"] != $prevName) {
        print "Error: ($prevName => $name) Inconsistant reference\n";
        $errorFound = true;
      }
    } else {
      print "Error: ($prevName => $name) Unknown element $name\n";
      $errorFound = true;
    }
  } else {
    print "Error: Looping references detected.\n";
    $errorFound = true;
  }
}

?>
