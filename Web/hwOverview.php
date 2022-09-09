<?php

print "<!DOCTYPE html>
<html>
<body>
<h1>WinterTrain 2022</h1>

<p><a href='http://w57.dk/doku.php?id=it:wintertrainv4:start'>Project Wiki,</a>
<a href='index.php'>Time Table Overview</a>
";

require("webConfig.php");

if (is_file($PT2_FILE)) {
  include($PT2_FILE); 
  ksort($PT1,SORT_NATURAL );
  print "
<hr>";
  PT2overview();
} else {
  print "
<p><i>PT2 Data: $PT2_FILE is not available</i>";

}

print "
<hr>
<p style='font-size:70%;'>PT2 data: $PT2_FILE</p>
</body>
</html>
";


function PT2overview() {
global $PT1; // FIXME PT1 vs PT2

$HWtypeText = array(0 => "n/a", 10 => "PM w/o pos.(1P)", 11 => "PM w. pos.(1P)", 21 => "Semaphor (1P)", 40 => "Signal 2/2 (2L)", 41 => "Signal 2/2 (1U)",
  42 => "Signal 2/3 (2L)", 43 => "Signal 2/3 (2U)", 44 => "Signal 3/3 (3L)", 45 => "Signal 3/3 (3U)");
$HWdevice = array(0 => "-", 10 => "P", 11 => "P", 21 => "P", 40 => "L", 41 => "U", 42 => "L", 43 => "U", 44 => "L", 45 => "U");
  
  $EC = array();
  
  print "
<h2>PT2 Elements:</h2>
<table>
<tr>
  <th>ID</th>
  <th>Element</th>
  <th>Type</th>
  <th>HW Type</th>
  <th>EC address</th>
  <th>Device</th>
  <th>Major</th>
</tr>
";
  foreach ($PT1 as $name => $element) {
    switch($element["element"]) {
      case "PF":
      case "PT":
      $EC[$element["EC"]["addr"]][$HWdevice[$element["EC"]["type"]]][] = ["major" => $element["EC"]["majorDevice"], "ID" => $name];
        print "
<tr>
  <td>$name</td>
  <td>{$element["element"]}</td>
  <td></td>
  <td>{$HWtypeText[$element["EC"]["type"]]}</td>
  <td>{$element["EC"]["addr"]}</td>
  <td>{$HWdevice[$element["EC"]["type"]]}</td>
  <td>{$element["EC"]["majorDevice"]}</td>
</tr>";
      break;
      case "SU":
      case "SD":
      $EC[$element["EC"]["addr"]][$HWdevice[$element["EC"]["type"]]][] = ["major" => $element["EC"]["majorDevice"], "ID" => $name];
        print "
<tr>
  <td>$name</td>
  <td>{$element["element"]}</td>
  <td>{$element["type"]}</td>
  <td>{$HWtypeText[$element["EC"]["type"]]}</td>
  <td>{$element["EC"]["addr"]}</td>
  <td>{$HWdevice[$element["EC"]["type"]]}</td>
  <td>{$element["EC"]["majorDevice"]}</td>
</tr>";
      break;
      default:     
    }
  }
  print "
</table>";

  print "
<h2>Element Controllers:</h2>
<table>
<tr>
  <th>EC addr</th>
  <th>DevType</th>
  <th>Major</th>
  <th>Element ID</th>
</tr>
";
  ksort($EC,SORT_NATURAL );
  foreach ($EC as $addr => $devices) {
    foreach ($devices as $deviceType => $assignments) {
      foreach ($assignments as $assignment) {
        print "
<tr>
  <td>$addr</td>";
        print "
  <td>$deviceType</td>
  <td>{$assignment["major"]}</td>
  <td>{$assignment["ID"]}</td>
</tr>";
        $addr = "";
        $deviceType = "";
      }
    }
  }
    print "
</table>";
}
?>
