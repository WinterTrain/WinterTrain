<?php

require("webConfig.php");

include($TT_FILE);
$trn = $_GET["trn"];
$tt = $timeTables[$trn];
print "<html>
<body>

<h2>$trn</h2>

<h3>{$tt["start"]} - {$tt["destination"]}</h3>
<h4>{$tt["description"]}</h4>

<p><a href='index.php'>Overview</a>
<hr>
<table>
<tr>
  <th>Station</th>
  <th>Time</th>
  <th>Delay</th>
  <th>Route</th>
  <th>Action</th>
";

$nextTrn = "";

foreach ($tt["routeTable"] as $routeIndex => $route) {
print "
<tr>
  <td>".(isset($route["station"]) ? $route["station"] : ""). "</td>
  <td>".(isset($route["time"]) ? $route["time"] : ""). "</td>
  <td>".(isset($route["delay"]) ? $route["delay"] : ""). "</td>
  <td>{$route["start"]}".(isset($route["dest"]) ? " => {$route["dest"]}" : "")."</td>
  <td>".(isset($route["action"]) ? $route["action"] : ""). "</td>
</tr>
";
  if (isset($route["action"]) and $route["action"] == "N") {
    $nextTrn = $route["nextTrn"];
  }
}

print "</table>

<hr>
<p>Train Running Number to be assigned at destination station: ".($nextTrn == "" ? "(none)" : "<a href='showTimeTable.php?trn=$nextTrn'>$nextTrn</a>")."
";

print "<p>Actions:
<dl>
  <dt>R</dt>
    <dd>Set route unconditional. Action \"R\" is default action.</dd>
  <dt>E</dt>
    <dd>Signal is destination.</dd>
  <dt>N</dt>
    <dd>Signal is destination, Assign new TRN to train.</dd>
  <dt>M</dt>
    <dd> Route from Signal to be set manually.</dd>
</ul>

";

print "
</body>
</html>

\n";
?>
