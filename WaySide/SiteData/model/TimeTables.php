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
  
"2510" => [
  "start" => "Station Down",
  "destination" => "Station Up",
  "description" => "Running up, upper track",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "**:*8:00", "start" => "B", "dest" => "D", "action" => ""],
    ["start" => "D", "dest" => "G", "action" => ""],
    ["time" => "", "start" => "G", "dest" => "Q", "action" => "", "delay" => ""],
    ["time" => "", "start" => "Q", "action" => "N", "nextTrn" => "2511", "delay" => 10],
  ],
  "remarks" => "",
],
  
"4510" => [
  "start" => "DownTown",
  "destination" => "UpTown",
  "description" => "Running up, upper track",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "**:*5:00", "start" => "B", "dest" => "D", "action" => "", "station" => "DownTown"],
    ["start" => "D", "dest" => "G", "action" => ""],
    ["time" => "", "start" => "G", "dest" => "Q", "action" => "", "delay" => "25", "station" => "Middle"],
    ["time" => "", "start" => "Q", "action" => "N", "delay" => 20, "nextTrn" => "4511", "station" => "UpTown"],
  ],
  "remarks" => "",
],
  
"3510" => [
  "start" => "Station Down",
  "destination" => "Station Up",
  "description" => "Running up, upper track, time",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "**:**:00", "start" => "B", "dest" => "D", "action" => ""],
    ["time" => "", "start" => "D", "dest" => "G", "action" => ""],
    ["time" => "**:**:30", "start" => "G", "dest" => "Q", "action" => ""],
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

"2511" => [
  "start" => "Station Up",
  "destination" => "Station Down",
  "description" => "Running down, upper track",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "**:*2:30", "start" => "J", "dest" => "E", "action" => ""],
    ["time" => "", "start" => "E", "dest" => "C", "action" => "", "delay" => ""],
    ["time" => "", "start" => "C", "dest" => "A", "action" => ""],
    ["time" => "", "start" => "A", "action" => "N", "nextTrn" => "2510", "delay" => 10],
  ],
  "remarks" => "",
],

"3511" => [
  "start" => "Station Up",
  "destination" => "Station Down",
  "description" => "Running down, upper track",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "", "start" => "J", "dest" => "E", "action" => ""],
    ["time" => "", "start" => "E", "dest" => "C", "action" => "", "delay" => "30"],
    ["time" => "", "start" => "C", "dest" => "A", "action" => ""],
    ["time" => "", "start" => "A", "action" => "E", "nextTrn" => "", "delay" => 20],
  ],
  "remarks" => "",
],
"4511" => [
  "start" => "UpTown",
  "destination" => "DownTown",
  "description" => "Running down, upper track",
  "group" => "Monday",
  "routeTable" => [
    ["time" => "**:*2:30", "start" => "J", "dest" => "E", "action" => "", "station" => "UpTown"],
    ["time" => "", "start" => "E", "dest" => "C", "action" => "", "delay" => "15", "station" => "Middle"], 
    ["time" => "", "start" => "C", "dest" => "A", "action" => ""],
    ["time" => "", "start" => "A", "action" => "N", "nextTrn" => "4510", "delay" => 20, "station" => "DownTown"],
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
