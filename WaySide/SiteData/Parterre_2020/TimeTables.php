<?php

$timeTables = [


"201" => [
  "start" => "Spor 1",
  "destination" => "Spor 3",
  "description" => "",
  "group" => "",
  "routeTable" => [
    ["time" => "", "start" => "S3", "dest" => "S11", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S4", "dest" => "BS2", "condition" => "", "delay" => "30"],
    ["time" => "", "start" => "S1", "dest" => "S9", "condition" => "", "delay" => "35"],
    ["time" => "", "start" => "S6", "dest" => "BS6", "condition" => "", "delay" => "30"],
    ["time" => "", "start" => "BS6", "dest" => "", "condition" => "N", "nextTrn" => "202", "delay" => "60"],
//    ["time" => "", "start" => "", "dest" => "", "condition" => "", "delay" => ""],
//    ["time" => "", "start" => "", "dest" => "", "condition" => "", "delay" => ""],
//    ["time" => "", "start" => "", "dest" => "", "condition" => "", "delay" => ""],
//    ["time" => "", "start" => "", "dest" => "", "condition" => "", "delay" => ""],
//    ["time" => "", "start" => "S11", "condition" => "N", "nextTrn" => "142", "delay" => "40"],
  ],
  "remarks" => "",
],

"202" => [
  "start" => "Spor 3",
  "destination" => "Spor 1",
  "description" => "",
  "group" => "",
  "routeTable" => [
    ["time" => "", "start" => "S5", "dest" => "S9", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S6", "dest" => "BS2", "condition" => "", "delay" => "40"],
    ["time" => "", "start" => "S1", "dest" => "S11", "condition" => "", "delay" => "40"],
    ["time" => "", "start" => "S4", "dest" => "BS4", "condition" => "", "delay" => "30"],
    ["time" => "", "start" => "BS4", "condition" => "N", "nextTrn" => "201", "delay" => "60"],
  ],
  "remarks" => "",
],



"114" => [
  "start" => "Spor 1",
  "destination" => "Spor 4",
  "description" => "",
  "group" => "",
  "routeTable" => [
    ["time" => "", "start" => "S3", "dest" => "S11", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S11", "dest" => "BS3", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "BS3", "condition" => "N", "nextTrn" => "142", "delay" => "40"],
  ],
  "remarks" => "",
],

"142" => [
  "start" => "Spor 4",
  "destination" => "Spor 2",
  "description" => "",
  "group" => "",
  "routeTable" => [
    ["time" => "", "start" => "S10", "dest" => "S4", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S4", "dest" => "BS2", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "BS2", "condition" => "N", "nextTrn" => "125", "delay" => "40"],
  ],
  "remarks" => "",
],

"125" => [
  "start" => "Spor 2",
  "destination" => "Spor 5",
  "description" => "",
  "group" => "",
  "routeTable" => [
    ["time" => "", "start" => "S1", "dest" => "S7", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S7", "dest" => "S9", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S9", "dest" => "BS1", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "BS1", "condition" => "N", "nextTrn" => "151", "delay" => "40"],
  ],
  "remarks" => "",
],

"151" => [
  "start" => "Spor 5",
  "destination" => "Spor 1",
  "description" => "",
  "group" => "",
  "routeTable" => [
    ["time" => "", "start" => "S8", "dest" => "S4", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S4", "dest" => "BS4", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "BS4", "condition" => "N", "nextTrn" => "114", "delay" => "40"],
  ],
  "remarks" => "",
],

// ----------------------------- Template
"" => [
  "start" => "",
  "destination" => "",
  "description" => "",
  "group" => "",
  "routeTable" => [
    ["time" => "", "start" => "", "dest" => "", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "", "dest" => "", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "", "dest" => "", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "", "dest" => "", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "", "dest" => "", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "", "dest" => "", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "", "condition" => "E", "nextTrn" => "", "delay" => ""],
  ],
  "remarks" => "",
],


];

?>
