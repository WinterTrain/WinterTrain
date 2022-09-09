<?php

print "<!DOCTYPE html>
<html>
<body>
<h1>WinterTrain 2022</h1>

<p><a href='http://w57.dk/doku.php?id=it:wintertrainv4:start'>Project Wiki,</a>
<a href='hwOverview.php'>PT2 HW overview</a>

";

require("webConfig.php");

if (is_file($TT_FILE) and is_file($PT2_FILE)) {
  include($TT_FILE); 
  include($PT2_FILE); 
  ksort($timeTables);
  ksort($PT1,SORT_NATURAL );
  TToverview(is_writable($TT_FILE));
} else {
  if (!is_file($TT_FILE)) print "
<p><i>Time Table Book: $TT_FILE is not available</i>";
  if (!is_file($PT2_FILE)) print "
<p><i>PT2 Data: $PT2_FILE is not available</i>";

}

print "
<hr>
<p style='font-size:70%;'>Time Table Book: $TT_FILE
<br>PT2 data: $PT2_FILE</p>
</body>
</html>
";

function TToverview($rw) {
global $timeTables;
  print "
<h2>Time Tables:</h2>".($rw ? "" : " <i>Note: The Time Table Book is read only</i>")."

<ul>
";

// Check file protection / time table editable at all FIXME
  foreach ($timeTables as $trn => $tt) {
    if ($trn != "") {
      print "<li><a href='/showTimeTable2.php?trn=$trn'>$trn</a> {$tt["start"]} - {$tt["destination"]}  ".($tt["protection"] == "R" ? "(Locked)" : "");
      print "<ul><li>{$tt["description"]}\n";
      print "</ul>\n";
    }
  }

  print "</ul>
<p><a href='/showTimeTable2.php?new'>Create new time table</a> 
";
}

?>
