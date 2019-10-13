<?php

$timeTables = [
"4510" => [
  "start" => "Station Down",
  "destination" => "Station Up",
  "description" => "Running up",
  "group" => "Monday",
  "startSignals" => [
    ["time" => "", "start" => "B", "dest" => "D", "cond" => ""],
    ["time" => "", "start" => "D", "dest" => "H", "cond" => ""],
    ["time" => "", "start" => "H", "dest" => "Q", "cond" => ""],
    ["time" => "", "start" => "Q", "dest" => "", "cond" => "E"],
  ],
  "remarks" => "",
  ],

"4511" => [
  "start" => "Station Up",
  "destination" => "Station Down",
  "description" => "Running down",
  "group" => "Monday",
  "startSignals" => [
    ["time" => "", "start" => "J", "dest" => "E", "cond" => ""],
    ["time" => "", "start" => "E", "dest" => "C", "cond" => ""],
    ["time" => "", "start" => "C", "dest" => "A", "cond" => ""],
    ["time" => "", "start" => "A", "dest" => "", "cond" => "E"],
  ],
  "remarks" => "",
  ],


"1712" => [
  ],
];

?>
