<?php

$timeTables = [
"4512" => [
  "start" => "Station Down",
  "destination" => "Station Up",
  "description" => "Running up, lower track",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "", "start" => "B", "dest" => "D", "action" => ""],
    ["time" => "", "start" => "D", "dest" => "H", "action" => ""],
    ["time" => "", "start" => "H", "dest" => "Q", "action" => ""],
    ["time" => "", "start" => "Q", "action" => "E"],
  ],
  "remarks" => "",
],
  
"4510" => [
  "start" => "Station Down",
  "destination" => "Station Up",
  "description" => "Running up, upper track",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "", "start" => "B", "dest" => "D", "action" => ""],
    ["time" => "", "start" => "D", "dest" => "G", "action" => ""],
    ["time" => "", "start" => "G", "dest" => "Q", "action" => "", "delay" => 20],
    ["time" => "", "start" => "Q", "action" => "N", "delay" => 40, "nextTrn" => "4511"],
  ],
  "remarks" => "",
],
  
"3510" => [
  "start" => "Station Down",
  "destination" => "Station Up",
  "description" => "Running up, upper track, time",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "'':23:00", "start" => "B", "dest" => "D", "action" => ""],
    ["time" => "", "start" => "D", "dest" => "G", "action" => ""],
    ["time" => "**:23:30", "start" => "G", "dest" => "Q", "action" => ""],
    ["time" => "", "start" => "Q", "action" => "E"],
  ],
  "remarks" => "",
],

"4612" => [
  "start" => "Station Down",
  "destination" => "Station Mid",
  "description" => "Running up, lower track",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "", "start" => "B", "dest" => "D", "action" => ""],
    ["time" => "", "start" => "D", "dest" => "H", "action" => ""],
    ["time" => "", "start" => "H", "action" => "E"],
  ],
  "remarks" => "",
],

"4511" => [
  "start" => "Station Up",
  "destination" => "Station Down",
  "description" => "Running down, upper track",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "", "start" => "J", "dest" => "E", "action" => ""],
    ["time" => "", "start" => "E", "dest" => "C", "action" => "M"],
    ["time" => "", "start" => "C", "dest" => "A", "action" => ""],
    ["time" => "", "start" => "A", "action" => "E"],
  ],
  "remarks" => "",
],

"4513" => [
  "start" => "Station Up",
  "destination" => "Station Down",
  "description" => "Running down, lower track",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "", "start" => "J", "dest" => "F", "action" => ""],
    ["time" => "", "start" => "F", "dest" => "C", "action" => ""],
    ["time" => "", "start" => "C", "dest" => "A", "action" => ""],
    ["time" => "", "start" => "A", "action" => "E"],
  ],
  "remarks" => "",
],

"4515" => [
  "start" => "Station Mid",
  "destination" => "Station Down",
  "description" => "Running down, left",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "", "start" => "F", "dest" => "C", "action" => ""],
    ["time" => "", "start" => "C", "dest" => "A", "action" => ""],
    ["time" => "", "start" => "A", "action" => "E"],
  ],
  "remarks" => "",
],

];

?>
