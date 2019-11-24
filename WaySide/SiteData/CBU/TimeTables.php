<?php

$timeTables = [

"1002" => [
  "start" => "Christianshavn",
  "destination" => "Station C",
  "description" => "Relativ køreplan",
  "group" => "",
  "routeTable" => [
    ["time" => "", "start" => "S2", "dest" => "S6", "condition" => "", "delay" => 30, "station" => "Christianshavn"],
    ["time" => "", "start" => "S4", "dest" => "S6", "condition" => "", "delay" => 30, "station" => "Christianshavn"],
    ["time" => "", "start" => "S6", "dest" => "S8", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S8", "dest" => "S10", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S10", "dest" => "S16", "condition" => "", "delay" => 40, "station" => "Station B"],
    ["time" => "", "start" => "S16", "dest" => "S20", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S20", "dest" => "S22", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S15", "dest" => "S13", "condition" => "", "delay" => 30, "station" => "Station D"],
    ["time" => "", "start" => "S13", "condition" => "E", "delay" => 30,  "nextTrn" => "1001", "station" => "Station C" ],
  ],
  "remarks" => "",
],
"1001" => [
  "start" => "Station C",
  "destination" => "Christianshavn",
  "description" => "Relativ køreplan",
  "group" => "",
  "routeTable" => [
    ["time" => "", "start" => "S18", "dest" => "S22", "condition" => "", "delay" => 30, "station" => "Station C"],
    ["time" => "", "start" => "S15", "dest" => "S11", "condition" => "", "delay" => 30, "station" => "Station D"],
    ["time" => "", "start" => "S11", "dest" => "S9", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S9", "dest" => "S5", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S5", "dest" => "S3", "condition" => "", "delay" => 30, "station" => "Station B"],
    ["time" => "", "start" => "S3", "dest" => "BS3", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "BS3", "condition" => "E", "nextTrn" => "1002", "delay" => 30, "station" => "Christianshavn"],
  ],
  "remarks" => "",
],


"2002" => [
  "start" => "Christianshavn",
  "destination" => "Station C",
  "description" => "",
  "group" => "",
  "routeTable" => [
    ["time" => "**:*5", "start" => "S2", "dest" => "S6", "condition" => "", "delay" => "", "station" => "Christianshavn"],
    ["time" => "**:*5", "start" => "S4", "dest" => "S6", "condition" => "", "delay" => "", "station" => "Christianshavn"],
    ["time" => "", "start" => "S6", "dest" => "S8", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S8", "dest" => "S10", "condition" => "", "delay" => ""],
    ["time" => "**:*7", "start" => "S10", "dest" => "S16", "condition" => "", "delay" => "", "station" => "Station B"],
    ["time" => "", "start" => "S16", "dest" => "S20", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S20", "dest" => "S22", "condition" => "", "delay" => ""],
    ["time" => "**:*9", "start" => "S15", "dest" => "S13", "condition" => "", "delay" => "", "station" => "Station D"],
    ["time" => "", "start" => "S13", "condition" => "E", "delay" => 30,  "nextTrn" => "1001", "station" => "Station C" ],
  ],
  "remarks" => "",
],
"2001" => [
  "start" => "Station C",
  "destination" => "Christianshavn",
  "description" => "",
  "group" => "",
  "routeTable" => [
    ["time" => "**:*1", "start" => "S18", "dest" => "S22", "condition" => "", "delay" => "", "station" => "Station C"],
    ["time" => "**:*3", "start" => "S15", "dest" => "S11", "condition" => "", "delay" => "", "station" => "Station D"],
    ["time" => "", "start" => "S11", "dest" => "S9", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S9", "dest" => "S5", "condition" => "", "delay" => ""],
    ["time" => "**:*6", "start" => "S5", "dest" => "S3", "condition" => "", "delay" => "", "station" => "Station B"],
    ["time" => "", "start" => "S3", "dest" => "BS3", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "BS3", "condition" => "E", "nextTrn" => "1002", "delay" => 30, "station" => "Christianshavn"],
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
