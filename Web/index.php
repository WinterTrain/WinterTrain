<?php

$TT_FILE = "/home/jabe/Git/WinterTrain/WaySide/SiteData/model/TimeTables.php";

include($TT_FILE);
ksort($timeTables);
print "<html>

<body>
<h1>WinterTrain</h1>
Welcome to the time table page\n";

foreach ($timeTables as $trn => $tt) {
  print "<H2>$trn</H2>\n";
}


print "
</body>
</html>

\n";
?>
