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
  <th>Condition</th>
  <th>Meeting TRN</th>
";

$nextTrn = "";

foreach ($tt["routeTable"] as $routeIndex => $route) {
print "
<tr>
  <td>".(isset($route["station"]) ? $route["station"] : ""). "</td>
  <td>".(isset($route["time"]) ? $route["time"] : ""). "</td>
  <td>".(isset($route["delay"]) ? $route["delay"] : ""). "</td>
  <td>{$route["start"]}".(isset($route["dest"]) ? " => {$route["dest"]}" : "")."</td>
  <td>".(isset($route["condition"]) ? $route["condition"] : ""). "</td>
  <td>".(isset($route["mTrn"]) ? $route["mTrn"]." @ " : "").(isset($route["mSignal"]) ? $route["mSignal"] : "")."</td>
";
  if (isset($route["condition"]) and $route["condition"] == "N") {
    print "<td> NextTrn: <a href='showTimeTable.php?trn={$route["nextTrn"]}'>{$route["nextTrn"]}</a>";
  } else {
    print "<td>";
  }

print "</tr>
";
}

print "</table>
<hr>
";

print "<p>Conditions:
<dl>
  <dt>R</dt>
    <dd>Set route unconditional. condition \"R\" is default condition.</dd>
  <dt>E</dt>
    <dd>Signal is destination.</dd>
  <dt>N</dt>
    <dd>Signal is destination, Assign new TRN to train.</dd>
  <dt>W</dt>
    <dd>Wait for meeting train</dd>
  <dt>M</dt>
    <dd> Route from Signal to be set manually.</dd>
</ul>

";

print "
</body>
</html>

\n";
?>
