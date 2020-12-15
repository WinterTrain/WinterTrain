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
    ["time" => "", "start" => "S15", "dest" => "S11", "condition" => "", "delay" => ""],
//    ["time" => "", "start" => "S17", "dest" => "S7", "condition" => "", "delay" => ""],
//    ["time" => "", "start" => "S17", "dest" => "S11", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S17", "dest" => "S11", "condition" => "", "delay" => ""],
//    ["time" => "", "start" => "S13", "dest" => "S7", "condition" => "", "delay" => ""],
//    ["time" => "", "start" => "S13", "dest" => "S11", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S11", "dest" => "S7", "condition" => "", "delay" => ""],
//    ["time" => "", "start" => "S7", "dest" => "BS1", "condition" => "", "delay" => "45"],
//    ["time" => "", "start" => "S7", "dest" => "S1", "condition" => "", "delay" => "45"],
    ["time" => "", "start" => "S7", "dest" => "S3", "condition" => "", "delay" => "45"],
    ["time" => "", "start" => "S3", "dest" => "S1", "condition" => "", "delay" => ""],
//    ["time" => "", "start" => "S3", "dest" => "BS1", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S1", "dest" => "BS1", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "BS1", "dest" => "", "condition" => "N", "nextTrn" => "12", "delay" => 60],
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
    ["time" => "", "start" => "S4", "dest" => "S6", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S2", "dest" => "S6", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S6", "dest" => "S8", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S8", "dest" => "S12", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S12", "dest" => "S18", "condition" => "", "delay" => 55],
    ["time" => "", "start" => "S18", "dest" => "BS4", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "BS4", "dest" => "", "condition" => "N", "nextTrn" => "11", "delay" => 40],
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
    ["time" => "", "start" => "S1", "dest" => "S101", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S101", "dest" => "", "condition" => "N", "nextTrn" => "22", "delay" => 80],
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
