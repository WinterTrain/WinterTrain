<?php

$timeTables = [
"4512" => [
  "start" => "Station Down",
  "destination" => "Station Up",
  "description" => "Running up, lower track",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "", "start" => "B", "dest" => "D", "cond" => ""],
    ["time" => "", "start" => "D", "dest" => "H", "cond" => ""],
    ["time" => "", "start" => "H", "dest" => "Q", "cond" => ""],
    ["time" => "", "start" => "Q", "cond" => "E"],
  ],
  "remarks" => "",
],
  
"4510" => [
  "start" => "Station Down",
  "destination" => "Station Up",
  "description" => "Running up, upper track",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "", "start" => "B", "dest" => "D", "cond" => ""],
    ["time" => "", "start" => "D", "dest" => "G", "cond" => ""],
    ["time" => "", "start" => "G", "dest" => "Q", "cond" => "W", "delay" => 10],
    ["time" => "", "start" => "Q", "cond" => "E"],
  ],
  "remarks" => "",
],
  
"3510" => [
  "start" => "Station Down",
  "destination" => "Station Up",
  "description" => "Running up, upper track, time",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "**:23:0*", "start" => "B", "dest" => "D", "cond" => "T"],
    ["time" => "", "start" => "D", "dest" => "G", "cond" => ""],
    ["time" => "**:23:3*", "start" => "G", "dest" => "Q", "cond" => "T"],
    ["time" => "", "start" => "Q", "cond" => "E"],
  ],
  "remarks" => "",
],

"4612" => [
  "start" => "Station Down",
  "destination" => "Station Mid",
  "description" => "Running up, lower track",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "", "start" => "B", "dest" => "D", "cond" => ""],
    ["time" => "", "start" => "D", "dest" => "H", "cond" => ""],
    ["time" => "", "start" => "H", "cond" => "E"],
  ],
  "remarks" => "",
],

"4511" => [
  "start" => "Station Up",
  "destination" => "Station Down",
  "description" => "Running down, upper track",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "", "start" => "J", "dest" => "E", "cond" => ""],
    ["time" => "", "start" => "E", "dest" => "C", "cond" => "C"],
    ["time" => "", "start" => "C", "dest" => "A", "cond" => ""],
    ["time" => "", "start" => "A", "cond" => "E"],
  ],
  "remarks" => "",
],

"4513" => [
  "start" => "Station Up",
  "destination" => "Station Down",
  "description" => "Running down, lower track",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "", "start" => "J", "dest" => "F", "cond" => ""],
    ["time" => "", "start" => "F", "dest" => "C", "cond" => ""],
    ["time" => "", "start" => "C", "dest" => "A", "cond" => ""],
    ["time" => "", "start" => "A", "cond" => "E"],
  ],
  "remarks" => "",
],

"4515" => [
  "start" => "Station Mid",
  "destination" => "Station Down",
  "description" => "Running down, left",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "", "start" => "F", "dest" => "C", "cond" => ""],
    ["time" => "", "start" => "C", "dest" => "A", "cond" => ""],
    ["time" => "", "start" => "A", "cond" => "E"],
  ],
  "remarks" => "",
],

];

?>
