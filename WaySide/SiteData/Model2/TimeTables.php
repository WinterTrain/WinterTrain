<?php

$timeTables = [

"11" => [
  "start" => "Langbortistan",
  "destination" => "Christianshavn",
  "description" => "InterCity",
  "group" => "",
  "businessStart" => "14:00",
  "businessEnd" => "23:59",
  "routeTable" => [
//    ["time" => "", "start" => "S15", "dest" => "S7", "condition" => "", "delay" => ""],
//    ["time" => "", "start" => "S15", "dest" => "S11", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S22", "dest" => "S18", "condition" => "", "delay" => ""],
//    ["time" => "", "start" => "S17", "dest" => "S7", "condition" => "", "delay" => ""],
//    ["time" => "", "start" => "S17", "dest" => "S11", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S18", "dest" => "S13", "condition" => "", "delay" => "45"],
//    ["time" => "", "start" => "S13", "dest" => "S7", "condition" => "", "delay" => ""],
//    ["time" => "", "start" => "S13", "dest" => "S11", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S13", "dest" => "S9", "condition" => "", "delay" => "40"],
//    ["time" => "", "start" => "S7", "dest" => "BS1", "condition" => "", "delay" => "45"],
//    ["time" => "", "start" => "S7", "dest" => "S1", "condition" => "", "delay" => "45"],
    ["time" => "", "start" => "S9", "dest" => "S6", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S6", "dest" => "S3", "condition" => "", "delay" => ""],
//    ["time" => "", "start" => "S3", "dest" => "", "condition" => "E", "delay" => ""],
//    ["time" => "", "start" => "S1", "dest" => "BS1", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S3", "dest" => "", "condition" => "N", "nextTrn" => "12", "delay" => "60"],
  ],
  "remarks" => "",
],

"12" => [
  "start" => "Christianshavn",
  "destination" => "Langbortistan",
  "description" => "InterCity",
  "businessStart" => "14:00",
  "businessEnd" => "23:59",
  "group" => "",
  "routeTable" => [
    ["time" => "", "start" => "S5", "dest" => "S11", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S7", "dest" => "S11", "condition" => "", "delay" => "20"],
    ["time" => "", "start" => "S11", "dest" => "S15", "condition" => "", "delay" => "20"],
    ["time" => "", "start" => "S15", "dest" => "S20", "condition" => "", "delay" => "20"],
    ["time" => "", "start" => "S20", "dest" => "BS3", "condition" => "", "delay" => "20"],
//    ["time" => "", "start" => "BS3", "dest" => "", "condition" => "E", "delay" => ""],
//    ["time" => "", "start" => "S12", "dest" => "S18", "condition" => "", "delay" => 55],
//    ["time" => "", "start" => "S18", "dest" => "BS4", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "BS3", "dest" => "", "condition" => "N", "nextTrn" => "11", "delay" => "30"],
  ],
  "remarks" => "",
],


"21" => [
  "start" => "Langbortistan",
  "destination" => "Christianshavn",
  "description" => "Gods",
  "group" => "",
  "businessStart" => "14:00",
  "businessEnd" => "23:59",
  "routeTable" => [
    ["time" => "", "start" => "S15", "dest" => "S11", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S17", "dest" => "S11", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S11", "dest" => "S9", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S9", "dest" => "S5", "condition" => "", "delay" => "45"],
    ["time" => "", "start" => "S5", "dest" => "S1", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S1", "dest" => "S10", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S10", "dest" => "", "condition" => "N", "nextTrn" => "22", "delay" => 80],
  ],
  "remarks" => "",
],

"22" => [
  "start" => "Christianshavn",
  "destination" => "Langbortistan",
  "description" => "Gods",
  "businessStart" => "14:00",
  "businessEnd" => "23:59",
  "group" => "",
  "routeTable" => [
    ["time" => "", "start" => "S4", "dest" => "S6", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S2", "dest" => "S6", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S6", "dest" => "S10", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S10", "dest" => "S14", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S14", "dest" => "S18", "condition" => "", "delay" => 55],
    ["time" => "", "start" => "S18", "dest" => "BS2", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "BS2", "dest" => "", "condition" => "N", "nextTrn" => "21", "delay" => 60],
  ],
  "remarks" => "",
],
/*
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
*/

];

?>
