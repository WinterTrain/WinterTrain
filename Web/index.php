<?php

require("webConfig.php");

include($TT_FILE);
ksort($timeTables);
print "<html>

<body>
<h1>WinterTrain 2019</h1>
Welcome to the WinterTrain at Christianshavns Bådudlejning og Café.

<p><a href='http://w57.dk/doku.php?id=it:wintertrainv4:start'>Project Wiki</a>

<p>Time Tables:

<ul>
";


foreach ($timeTables as $trn => $tt) {
  print "<li><a href='/showTimeTable.php'>$trn</a> {$tt["start"]} - {$tt["destination"]}  ";
}

print "</ul>
";

print "
</body>
</html>

\n";
?>
