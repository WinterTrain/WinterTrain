<?php

require("webConfig.php");

include($TT_FILE);

ksort($timeTables);
print "<html>

<body>
<h1>WinterTrain 2019</h1>
Welcome to the WinterTrain at Christianshavns B&aringdudlejning og Café.

<p><a href='http://w57.dk/doku.php?id=it:wintertrainv4:start'>Project Wiki</a>

<p>Time Tables:

<ul>
";


foreach ($timeTables as $trn => $tt) {
  if ($trn != "") {
    print "<li><a href='/showTimeTable.php?trn=$trn'>$trn</a> {$tt["start"]} - {$tt["destination"]}  ";
    print "<ul><li>{$tt["description"]}\n";
    print "</ul>\n";
  }
}

print "</ul>
";

print "
</body>
</html>

\n";
?>
