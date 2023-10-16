<?php

print "<!DOCTYPE html>
<html>
<body>
";

require("webConfig.php");

if (is_file($PT2_FILE)) {
  include($PT2_FILE); 
} else {
  $PT1 = array();
  print "
<p><i>PT2 Data: $PT2_FILE is not available</i>";
}

if (is_file($TT_FILE)) {
  include($TT_FILE); 
  $ttEditable = false;
  if (is_writable($TT_FILE)) {
    if (isset($_GET["new"])) { // ------------------------------------------------------------------------------------------- Create new
      $tt["protection"] = "";
      $tt["start"] = "";
      $tt["destination"] = "";
      $tt["description"] = "";
      for ($i = 0; $i < 10; $i++) {
        $tt["locationTable"][] = ["station" => "", "location" => "",
            "actionTable" => [0 => ["dest" => "", "delay" => "", "action" => " ", "xTrn" => "", "xSig" => ""]]];;
      }
      editTimeTable(0, "", $tt);
    } else {
      $trn = $_GET["trn"];
      if ($trn == 0 or isset($timeTables[$trn])) { // Existing or new trn
        if ($trn == 0 or $timeTables[$trn]["protection"] != "R") {
          $ttEditable = true;
          if (isset($_POST["delete"])) { // -------------------------------------------------------------------------- Delete
            print "
<p style='color:red;'>Delete Time Table for Train Running Number \"{$_GET["trn"]}\"?
<form action='showTimeTable2.php?trn={$_GET["trn"]}' method = 'post'>
  <input  style='color:red;' type='submit' value='Delete' name='doDelete'>
  <input type='submit' value='Cancel' name='cancel'>
</form>
            ";
          } else if (isset($_POST["doDelete"])) { // ---------------------------------------------------------------------- Do Delete
            unset($timeTables[$_GET["trn"]]);
            saveTts();
            print "
<p>Deleted!
<p><a href='index.php'>Overview</a>";
          } else if (isset($_POST["edit"])) { // ---------------------------------------------------------------------- Edit
            editTimeTable($trn, $trn, $timeTables[$trn]);
          } else if (isset($_POST["verifyEdit"])) { // ---------------------------------------------------------------- Verify / CleanUp
            $tt =  $_POST["tt"];
            $tt["locationTable"] = cleanUp($tt["locationTable"]);
            $errTxt = verify($tt["locationTable"]);
            editTimeTable($trn, $_POST["editTrn"], $tt, $errTxt);
          } else if (isset($_POST["addActions"])) { // ---------------------------------------------------------------- Add Actions
            $tt =  $_POST["tt"];
            foreach ($tt["locationTable"] as &$location) {
              $location["actionTable"][] = ["dest" => "", "delay" => "", "action" => " ", "xTrn" => "", "xSig" => ""];
            }
            editTimeTable($trn, $_POST["editTrn"], $tt);
          } else if (isset($_POST["addLocations"])) { // -------------------------------------------------------------- Add Locations
            $tt =  $_POST["tt"];
            $tt["locationTable"] = array();
            if (isset($_POST["tt"]["locationTable"])) { // locationTable might be missing
              foreach ($_POST["tt"]["locationTable"] as $location) {
                $tt["locationTable"][] = $location;
                $tt["locationTable"][] = ["station" => "", "location" => "",
                  "actionTable" => [0 => ["dest" => "", "delay" => "", "action" => " ", "xTrn" => "", "xSig" => ""]]];
              }
            } else {
              for ($i = 0; $i < 5; $i++) {
              $tt["locationTable"][] = ["station" => "", "location" => "",
                "actionTable" => [0 => ["dest" => "", "delay" => "", "action" => " ", "xTrn" => "", "xSig" => ""]]];
              }
            }
            editTimeTable($trn, $_POST["editTrn"], $tt);
          } else if (isset($_POST["verify"])) { // -------------------------------------------------------------------- Verify existing
            $tt = $timeTables[$_GET["trn"]];
            $errTxt = verify($tt["locationTable"]);
            printTimeTable($trn, $tt, $errTxt);
          } else if (isset($_POST["save"]) and $trn == 0) { // -------------------------------------------------------- Save new
            $tt =  $_POST["tt"];
            $tt["locationTable"] = cleanUp($tt["locationTable"]);
            if (is_numeric($_POST["editTrn"]) and $_POST["editTrn"] > 0 and !isset($timeTables[$_POST["editTrn"]])) {
              $timeTables[$_POST["editTrn"]] = $tt;
              saveTts();
              $trn = $_POST["editTrn"];
              printTimeTable($trn, $tt);
            } else {
              print "
<p style='color:red;'>Train Running Number \"{$_POST["editTrn"]}\" is invalid or already in use!";
              editTimeTable($trn, $_POST["editTrn"], $tt);
            }
          } else if (isset($_POST["saveNew"])) { // ------------------------------------------------------------------- Save as New
            $tt =  $_POST["tt"];
            $tt["locationTable"] = cleanUp($tt["locationTable"]);
            if (is_numeric($_POST["editTrn"]) and $_POST["editTrn"] > 0) {
              if (!isset($timeTables[$_POST["editTrn"]])) {
                $timeTables[$_POST["editTrn"]] = $tt;
                saveTts();
                $trn = $_POST["editTrn"];
                printTimeTable($trn, $tt);
              } else {
                print "
<p style='color:red;'>Train Running Number \"{$_POST["editTrn"]}\" is invalid or already in use!";
              editTimeTable($trn, $_POST["editTrn"], $tt);
              }      
            } else {
              print "
<p style='color:red;'>Train Running Number \"{$_POST["editTrn"]}\" is invalid!";
              editTimeTable($trn, $_POST["editTrn"], $tt);
            }
          } else if (isset($_POST["save"]) and $trn > 0) { // --------------------------------------------------------- Save existing
            $tt =  $_POST["tt"];
            $tt["locationTable"] = cleanUp($tt["locationTable"]);
            if (is_numeric($_POST["editTrn"]) and $_POST["editTrn"] > 0) {
              if ($_POST["editTrn"] == $trn) { // Save with same trn
                $timeTables[$_POST["editTrn"]] = $tt;
                saveTts();
                printTimeTable($trn, $tt);            
              } else if (!isset($timeTables[$_POST["editTrn"]])) { // Save with new trn
                unset($timeTables[$trn]);
                $timeTables[$_POST["editTrn"]] = $tt;
                saveTts();
                $trn = $_POST["editTrn"];
                printTimeTable($trn, $tt);
              } else {
                print "
<p style='color:red;'>Train Running Number \"{$_POST["editTrn"]}\" is invalid or already in use!";
              editTimeTable($trn, $_POST["editTrn"], $tt);
              }      
            } else {
              print "
<p style='color:red;'>Train Running Number \"{$_POST["editTrn"]}\" is invalid!";
              editTimeTable($trn, $_POST["editTrn"], $tt);
            }
          } else {
            printTimeTable($trn, $timeTables[$trn]);
          }
        } else { // timeTable protected        
          printTimeTable($trn, $timeTables[$trn]);
        }
      } else { // unknown trn
        print "
<p>Unknown trn in URL";    
      }
    } 
  } else { // file is read only
    $trn = $_GET["trn"];
    if (isset($timeTables[$trn])) { // Existing trn
      printTimeTable($trn, $timeTables[$trn]);
    } else {
      print "
<p>Unknown trn in URL";
    }
  }
} else {
  print "
<p><i>Time Table Book: $TT_FILE is not available</i>";
}


print "
<hr>
<p style='font-size:70%;'>Time Table Book: $TT_FILE
<br>PT2 data: $PT2_FILE</p>
</body>
</html>
";

// ===================================================================================

function saveTts() {
  global $timeTables, $TT_FILE;
  $ttFh = fopen("$TT_FILE", "w");
  fwrite($ttFh, "<?php
// ------------------------------------------------- 

\$PT2_GENERATION_TIME = \"".(date("Y-m-d H:i:s"))."\";

// -------------------------------------------------- TimeTables
\$timeTables = ".(var_export($timeTables,true)).";

?>");
  fclose($ttFh);
}


function verify ($locationTable) {
global $PT1, $timeTables;
  $res = array();
  $ok = true;
  foreach ($locationTable as $locationIndex => $location) {
    $res[$locationIndex]["err"] = "";
    if (!isset($PT1[$location["location"]])) {
      $ok = false;
      $res[$locationIndex]["err"] .= "Unknown location \"{$location["location"]}\" ";
    }
    foreach ($location["actionTable"] as $actionIndex => $action) {
      $res[$locationIndex][$actionIndex] = "";
      if ($action["delay"] != "" and $action["delay"] < 0) {
        $ok = false;
        $res[$locationIndex][$actionIndex] .= "If set, delay must be nimeric and non-negative";      
      }
      switch ($action["action"]) {
        case " ":
        case "R":
          if (!isset($PT1[$action["dest"]])) {
            $ok = false;
            $res[$locationIndex][$actionIndex] .= "Unknown destination \"{$action["dest"]}\" ";
          }
          if ($PT1[$location["location"]]["element"] == "BSB" or $PT1[$location["location"]]["element"] == "BSE") {
            $ok = false;
            $res[$locationIndex][$actionIndex] .=
              "Location \"{$location["location"]}\" of type Buffer Stop cannot be used as start signal of route ";
          }
          if ($action["xTrn"]!= "") {
            $ok = false;
            $res[$locationIndex][$actionIndex] .= "Wait Trn not needed for Action \"R\" ";
          }
          if ($action["xSig"]!= "") {
            $ok = false;
            $res[$locationIndex][$actionIndex] .= "Wait signal not needed for Ation \"R\" ";
          }
        break;
        case "W":
          if (!isset($PT1[$action["dest"]]) and $PT1[$action["dest"]] != "*") {
            $ok = false;
            $res[$locationIndex][$actionIndex] .= "Unknown destination \"{$action["dest"]}\" ";
          }
          if (!isset($PT1[$action["xSig"]])) {
            $ok = false;
            $res[$locationIndex][$actionIndex] .= "Unknown wait signal \"{$action["xSig"]}\" ";
          }
          if (!isset($timeTables[$action["xTrn"]])) {
            $ok = false;
            $res[$locationIndex][$actionIndex] .= "Unknown wait Trn \"{$action["xTrn"]}\" ";
          }
        break;
        case "N":
          if (!isset($timeTables[$action["xTrn"]])) {
            $ok = false;
            $res[$locationIndex][$actionIndex] .= "Unknown new Trn \"{$action["xTrn"]}\" ";
          }
          if ($action["dest"]!= "") {
            $ok = false;
            $res[$locationIndex][$actionIndex] .= "Destination not needed for Action \"N\" ";
          }
          if ($action["xSig"]!= "") {
            $ok = false;
            $res[$locationIndex][$actionIndex] .= "Wait signal not needed for Action \"N\" ";
          }        break;
        case "D":
          if ($action["dest"]!= "") {
            $ok = false;
            $res[$locationIndex][$actionIndex] .= "Destination not needed for Action \"D\" ";
          }
          if ($action["xTrn"]!= "") {
            $ok = false;
            $res[$locationIndex][$actionIndex] .= "Wait Trn not needed for Action \"D\" ";
          }
          if ($action["xSig"]!= "") {
            $ok = false;
            $res[$locationIndex][$actionIndex] .= "Wait signal not needed for Action \"D\" ";
          }
        break;
        case "E";
          if ($action["dest"]!= "") {
            $ok = false;
            $res[$locationIndex][$actionIndex] .= "Destination not needed ";
          }
          if ($action["xTrn"]!= "") {
            $ok = false;
            $res[$locationIndex][$actionIndex] .= "New Trn not needed for Action \"E\" ";
          }
          if ($action["xSig"]!= "") {
            $ok = false;
            $res[$locationIndex][$actionIndex] .= "Wait signal not needed for Action \"E\" ";
          }
        break;
      }
    }
  }
  if ($ok) $res["ok"] = "";
  return ($res);
}

function cleanUp($locationTable) {
  $res = array();
  foreach ($locationTable as $location) {
    $resLocation = $location;
    $resLocation["actionTable"] = array();
    foreach ($location["actionTable"] as $action) {
      if ($action["dest"] != "" or $action["delay"] != "" or $action["action"] != " " or $action["xTrn"] != ""
        or $action["xSig"] != "") 
        $resLocation["actionTable"][] = $action;
    }
    if ($resLocation["station"] != "" or $resLocation["location"] != "" or count($resLocation["actionTable"]) > 0) {
      if (count($resLocation["actionTable"]) == 0)
        $resLocation["actionTable"][] = ["dest" => "", "delay" => "", "action" => " ", "xTrn" => "", "xSig" => ""];
      $res[] = $resLocation;
    }
  }
  return $res;
}

function editTimeTable($trn, $editTrn, $tt, $errTxt = null) {
//print_r2($tt);
  print "
<form action='showTimeTable2.php?trn=$trn' method = 'post'>
  <input type='hidden' name='tt[protection]' value='{$tt["protection"]}'>
  <label for='start'>Train Runnung Number</label><br>
  <input type='text' id='start' name='editTrn' value='$editTrn'>

<table>
<tr>
  <td>
  <label for='start'>Departure station</label><br>
  <input type='text' id='start' name='tt[start]' value='{$tt["start"]}'><br>
  </td>
  <td>  
  <label for='destination'>Destination station</label><br>
  <input type='text' id='destination' name='tt[destination]' value='{$tt["destination"]}'>
  </td>
</tr>
<tr>
  <td>
  <label for='description'>Description</label><br>
  <input type='text' id='description' name='tt[description]' value='{$tt["description"]}'>
  </td><td></td>
</tr>
</table>
<hr>
<table>
<tr>
  <th>Station</th>
  <th>Time</th>
  <th>Appr</th>
  <th>Loc</th>
  <th>Dest</th>
  <th>Delay</th>
  <th>Act</th>
  <th>Trn</th>
  <th>Sig</th>
  <td style='color:green;'>".(isset($errTxt["ok"]) ? "OK" : "")."</td>
</tr>
";
  foreach ($tt["locationTable"] as $locationIndex => $location) {
    $lPrinted = false;
    foreach ($location["actionTable"] as $actionIndex => $action) {
      print "
<tr>";
      if (!$lPrinted) {
        print "
  <td> <input type='text' name='tt[locationTable][$locationIndex][station]' size='1' value='".
    (isset($location["station"]) ? $location["station"] : "").
  "'></td>
  <td>".(isset($location["time"]) ? $location["time"] : ""). "</td>
  <td><input type='checkbox' name='tt[locationTable][$locationIndex][approach]' ".(isset($location["approach"])  ? "checked" : "")."></td>
  <td> <input type='text' name='tt[locationTable][$locationIndex][location]' size='1' value='{$location["location"]}'></td>
";
      } else {
        print "
  <td></td> <td></td> <td></td> <td></td>";
      }
      print "
  <td> => <input type='text' size='1' value='{$action["dest"]}' name='tt[locationTable][$locationIndex][actionTable][$actionIndex][dest]'></td>
  <td><input type='number' size='1' value='{$action["delay"]}' name='tt[locationTable][$locationIndex][actionTable][$actionIndex][delay]'></td>
  <td><select name='tt[locationTable][$locationIndex][actionTable][$actionIndex][action]'>
  <option value=' ' ".($action["action"] == " " ? "selected" : "")."> </option>
  <option value='W' ".($action["action"] == "W" ? "selected" : "").">W</option>
  <option value='D' ".($action["action"] == "D" ? "selected" : "").">D</option>
  <option value='E' ".($action["action"] == "E" ? "selected" : "").">E</option>
  <option value='N' ".($action["action"] == "N" ? "selected" : "").">N</option>
  <option value='M' ".($action["action"] == "M" ? "selected" : "").">M</option>
</select> </td>
  <td><input type='text' size='1' value='{$action["xTrn"]}' name='tt[locationTable][$locationIndex][actionTable][$actionIndex][xTrn]'></td>
  <td><input type='text' size='1' value='{$action["xSig"]}' name='tt[locationTable][$locationIndex][actionTable][$actionIndex][xSig]'></td>";
      if ($errTxt != "") {
        print "
   <td style='color:red;'>".(!$lPrinted ? $errTxt[$locationIndex]["err"] : "")." {$errTxt[$locationIndex][$actionIndex]}</td>
</tr>";
      } else {
        print "
  <td></td>
</tr>";
      }
      $lPrinted = true;
    }
  }
  print "
</table>
<hr>";
  print "
  <input type='submit' value='Save' name='save'>
  <input type='submit' value='Save as New' name='saveNew'>
  <input type='submit' value='Verify/CleanUp' name='verifyEdit'>
  <input type='reset' value='Reset'>
  <input type='submit' value='Cancel' name='cancel'>
  <input type='submit' value='Add Actions' name='addActions'>
  <input type='submit' value='Add Locations' name='addLocations'>
</form>
";
}

function printTimeTable($trn, $tt, $errTxt = null) {
global $ttEditable;

  if ($trn >0) {
    print "
<h2>$trn</h2>

<h3>{$tt["start"]} - {$tt["destination"]}</h3>
<h4>{$tt["description"]}</h4>

<p><a href='index.php'>Overview</a>
<hr>
<table>
<tr>
  <th>Station</th>
  <th>Time</th>
  <th>Appr</th>
  <th>Loc</th>
  <th>Dest</th>
  <th>Delay</th>
  <th>Act</th>
  <th>Trn</th>
  <th>Sig</th>
  <th style='color:green;'>".(isset($errTxt["ok"]) ? "OK" : "")."</th>
</tr>
";

    foreach ($tt["locationTable"] as $locationIndex => $location) {
      $lPrinted = false;
      foreach ($location["actionTable"] as $actionIndex => $action) {
        print "
<tr>";
        if (!$lPrinted) {
          print "
  <td>".(isset($location["station"]) ? $location["station"] : ""). "</td>
  <td>".(isset($location["time"]) ? $location["time"] : ""). "</td>
  <td>".(isset($location["approach"]) ? "A" : "")."</td>
  <td>{$location["location"]}</td>";
        } else {
          print "
  <td></td> <td></td> <td></td> <td></td>";
        }
        switch ($action["action"]) {
          case "W" :
            print "
  <td> => {$action["dest"]}</td>
  <td>{$action["delay"]}</td>
  <td>W</td>
  <td><a href='showTimeTable2.php?trn={$action["xTrn"]}'>{$action["xTrn"]}</a></td>
  <td>{$action["xSig"]}</td>";
          break;
          case "N" :
            print "
  <td></td>
  <td>{$action["delay"]}</td>
  <td>N</td>
  <td><a href='showTimeTable2.php?trn={$action["xTrn"]}'>{$action["xTrn"]}</a></td>
  <td></td>";
          break;
          case "D" :
            print "
  <td></td>
  <td></td>
  <td>D</td>
  <td></td>
  <td></td>";
          break;
          case "R":
          default:
            print "
  <td> => {$action["dest"]}</td>
  <td> {$action["delay"]}</td>
  <td>{$action["action"]}</td>
  <td></td>
  <td></td>";
        }
        if ($errTxt != "") {
          print "
   <td style='color:red;'>".(!$lPrinted ? $errTxt[$locationIndex]["err"] : "")." {$errTxt[$locationIndex][$actionIndex]}</td>
</tr>";
        } else {
        print "
  <td></td>
</tr>";
        }
        $lPrinted = true;
      }
    }
    print "
</table>
<hr>";
    if ($ttEditable) {
      print "
<form action='showTimeTable2.php?trn=$trn' method = 'post'>
  <input type='submit' value='Edit' name='edit'>
  <input type='submit' value='Verify' name='verify'>
  <input type='submit' value='Delete' name='delete'>
</form>
";
    } else {
      print "
(Time table is read only)\n";
    }
    } else {
      print "
<p><a href='index.php'>Overview</a>
";
  }
}

function printHelp() {

print "<p>Approach flag:
<dl>
  <dt>Set</dt>
    <dd>Approaching location</dd>
</dl>

<p>Action flag::
<dl>
  <dt>R</dt>
    <dd>Set route unconditional. Action \"R\" is default action.</dd>
  <dt>D</dt>
    <dd>Change driving direction (unconditional).</dd>
  <dt>E</dt>
    <dd>Signal is destination.</dd>
  <dt>N</dt>
    <dd>Signal is destination, Assign new TRN to train.</dd>
  <dt>W</dt>
    <dd>Wait for meeting train</dd>
  <dt>M</dt>
    <dd> Route from Signal to be set manually.</dd>
</dl>

";
}

function print_r2($val){
        echo '<pre>';
        print_r($val);
        echo  '</pre>';
}

?>
