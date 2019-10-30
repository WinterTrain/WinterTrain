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

";

foreach ($tt["routeTable"] as $routeIndex => $route) {
print "
<p>{$routeIndex} {$route["start"]} {$route["dest"]}
";
}



print "
<p><a href='index.php'>Overview</a>
</body>
</html>

\n";
?>
