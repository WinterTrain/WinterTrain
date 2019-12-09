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
    ["time" => "", "start" => "S13", "dest" => "S33", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S33", "dest" => "BS5", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "BS5", "condition" => "E", "delay" => 30,  "nextTrn" => "1001", "station" => "Station C" ],
  ],
  "remarks" => "",
],
"1001" => [
  "start" => "Station C",
  "destination" => "Christianshavn",
  "description" => "Relativ køreplan",
  "group" => "",
  "routeTable" => [
    ["time" => "", "start" => "S32", "dest" => "S14", "condition" => "", "delay" => 30, "station" => "Station C"],
    ["time" => "", "start" => "S14", "dest" => "S18", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S18", "dest" => "S22", "condition" => "", "delay" => ""],
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
    ["time" => "**:*5:00", "start" => "S2", "dest" => "S6", "condition" => "", "delay" => "", "station" => "Christianshavn"],
    ["time" => "**:*5:00", "start" => "S4", "dest" => "S6", "condition" => "", "delay" => "", "station" => "Christianshavn"],
    ["time" => "", "start" => "S6", "dest" => "S8", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S8", "dest" => "S10", "condition" => "", "delay" => ""],
    ["time" => "**:*7:00", "start" => "S10", "dest" => "S16", "condition" => "", "delay" => "", "station" => "Station B"],
    ["time" => "", "start" => "S16", "dest" => "S20", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S20", "dest" => "S22", "condition" => "", "delay" => ""],
    ["time" => "**:*9:00", "start" => "S15", "dest" => "S13", "condition" => "", "delay" => "", "station" => "Station D"],
    ["time" => "", "start" => "S13", "dest" => "S33", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S33", "dest" => "BS5", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "BS5", "condition" => "E", "delay" => 30,  "nextTrn" => "2001", "station" => "Station C" ],
  ],
  "remarks" => "",
],
"2001" => [
  "start" => "Station C",
  "destination" => "Christianshavn",
  "description" => "",
  "group" => "",
  "routeTable" => [
    ["time" => "**:*1:00", "start" => "S32", "dest" => "S14", "condition" => "", "delay" => "", "station" => "Station C"],
    ["time" => "", "start" => "S14", "dest" => "S18", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S18", "dest" => "S22", "condition" => "", "delay" => ""],
    ["time" => "**:*3:00", "start" => "S15", "dest" => "S11", "condition" => "", "delay" => "", "station" => "Station D"],
    ["time" => "", "start" => "S11", "dest" => "S9", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S9", "dest" => "S5", "condition" => "", "delay" => ""],
    ["time" => "**:*6:00", "start" => "S5", "dest" => "S3", "condition" => "", "delay" => "", "station" => "Station B"],
    ["time" => "", "start" => "S3", "dest" => "BS3", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "BS3", "condition" => "E", "nextTrn" => "2002", "delay" => 30, "station" => "Christianshavn"],
  ],
  "remarks" => "",
],


"3001" => [
  "start" => "Depot",
  "destination" => "Station C",
  "description" => "",
  "group" => "",
  "routeTable" => [
    ["time" => "", "start" => "S19", "dest" => "S15", "condition" => "", "delay" => 30, "station" => "Depot"],
    ["time" => "", "start" => "S15", "dest" => "S13", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S13", "dest" => "S33", "condition" => "", "delay" => ""],
/*    ["time" => "", "start" => "S33", "dest" => "BS5", "condition" => "", "delay" => ""],*/
    ["time" => "", "start" => "S33", "condition" => "E", "delay" => 10,  "nextTrn" => "3002", "station" => "Station C" ],
  ],
  "remarks" => "",
],
"3002" => [
  "start" => "Station C",
  "destination" => "Depot",
  "description" => "",
  "group" => "",
  "routeTable" => [
    ["time" => "", "start" => "S32", "dest" => "S14", "condition" => "", "delay" => 30, "station" => "Station C"],
    ["time" => "", "start" => "S14", "dest" => "S18", "condition" => "", "delay" => 30],
    ["time" => "", "start" => "S18", "dest" => "S22", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S22", "dest" => "BS2", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "BS2", "condition" => "E", "nextTrn" => "3001", "delay" => 10, "station" => "Depot"],
  ],
  "remarks" => "",
],

// ------------------------------------------- Test
"4012" => [
  "start" => "Christianshavn",
  "destination" => "Station D, depot",
  "description" => "Mødetest tog 1",
  "group" => "",
  "routeTable" => [
    ["time" => "", "start" => "S2", "dest" => "S6", "condition" => "", "delay" => 30, "station" => "Christianshavn"],
    ["time" => "", "start" => "S4", "dest" => "S6", "condition" => "", "delay" => 30, "station" => "Christianshavn"],
    ["time" => "", "start" => "S6", "dest" => "S8", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S8", "dest" => "S12", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S8", "dest" => "S10", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S10", "dest" => "S16", "condition" => "W", "mTrain" => "4021", "mSignal" => "S7", "delay" => 40, "station" => "Station B"],
    ["time" => "", "start" => "S12", "dest" => "S16", "condition" => "W", "mTrain" => "4021", "mSignal" => "S5", "delay" => 40, "station" => "Station B"],
    ["time" => "", "start" => "S16", "dest" => "S20", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S20", "dest" => "S24", "condition" => "", "delay" => "", "station" => "Station D"],
    ["time" => "", "start" => "S24", "dest" => "BS2", "condition" => "", "delay" => 20],
    ["time" => "", "start" => "BS2", "condition" => "E", "delay" => 30,  "nextTrn" => "4011", "station" => "Depot" ],
  ],
  "remarks" => "",
],
"4011" => [
  "start" => "Station C",
  "destination" => "Christianshavn",
  "description" => "Mødetest tog 1",
  "group" => "",
  "routeTable" => [
    ["time" => "", "start" => "S19", "dest" => "S15", "condition" => "", "delay" => 30, "station" => "Depot"],
    ["time" => "", "start" => "S15", "dest" => "S11", "condition" => "", "delay" => "", "station" => "Station D"],
    ["time" => "", "start" => "S11", "dest" => "S9", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S9", "dest" => "S5", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S9", "dest" => "S7", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S5", "dest" => "S3", "condition" => "W", "mTrain" => "4022", "mSignal" => "S12", "delay" => 30, "station" => "Station B"],
    ["time" => "", "start" => "S7", "dest" => "S3", "condition" => "W", "mTrain" => "4022", "mSignal" => "S10", "delay" => 30, "station" => "Station B"],
    ["time" => "", "start" => "S3", "dest" => "BS3", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "BS3", "condition" => "E", "nextTrn" => "4012", "delay" => 30, "station" => "Christianshavn"],
  ],
  "remarks" => "",
],
"4022" => [
  "start" => "Christianshavn",
  "destination" => "Station D, depot",
  "description" => "Mødetest tog 2",
  "group" => "",
  "routeTable" => [
    ["time" => "", "start" => "S2", "dest" => "S6", "condition" => "", "delay" => 30, "station" => "Christianshavn"],
    ["time" => "", "start" => "S4", "dest" => "S6", "condition" => "", "delay" => 30, "station" => "Christianshavn"],
    ["time" => "", "start" => "S6", "dest" => "S8", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S8", "dest" => "S12", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S8", "dest" => "S10", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S12", "dest" => "S16", "condition" => "W", "mTrain" => "4011", "mSignal" => "S5", "delay" => 40, "station" => "Station B"],
    ["time" => "", "start" => "S10", "dest" => "S16", "condition" => "W", "mTrain" => "4011", "mSignal" => "S7", "delay" => 40, "station" => "Station B"],
    ["time" => "", "start" => "S16", "dest" => "S20", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S20", "dest" => "S24", "condition" => "", "delay" => "", "station" => "Station D"],
    ["time" => "", "start" => "S24", "dest" => "BS2", "condition" => "", "delay" => 20],
    ["time" => "", "start" => "BS2", "condition" => "E", "delay" => 30,  "nextTrn" => "4021", "station" => "Depot" ],
  ],
  "remarks" => "",
],
"4021" => [
  "start" => "Station C",
  "destination" => "Christianshavn",
  "description" => "Mødetest tog 2",
  "group" => "",
  "routeTable" => [
    ["time" => "", "start" => "S19", "dest" => "S15", "condition" => "", "delay" => 30, "station" => "Depot"],
    ["time" => "", "start" => "S15", "dest" => "S11", "condition" => "", "delay" => "", "station" => "Station D"],
    ["time" => "", "start" => "S11", "dest" => "S9", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S9", "dest" => "S5", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S9", "dest" => "S7", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "S5", "dest" => "S3", "condition" => "W", "mTrain" => "4012", "mSignal" => "S12", "delay" => 30, "station" => "Station B"],
    ["time" => "", "start" => "S7", "dest" => "S3", "condition" => "W", "mTrain" => "4012", "mSignal" => "S10", "delay" => 30, "station" => "Station B"],
    ["time" => "", "start" => "S3", "dest" => "BS3", "condition" => "", "delay" => ""],
    ["time" => "", "start" => "BS3", "condition" => "E", "nextTrn" => "4022", "delay" => 30, "station" => "Christianshavn"],
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
