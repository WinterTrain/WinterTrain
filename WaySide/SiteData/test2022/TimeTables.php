<?php

$timeTables = [

"21" => [
  "start" => "Spisebord",
  "destination" => "Reol",
  "description" => "Krydsning, Op",
  "group" => "",
  "businessStart" => "14:00",
  "businessEnd" => "23:59",
  "locationTable" => [
    ["time" => "00:00", "location" => "S1", "progress" => "A",
      "actionTable" =>  [["dest" => "S4", "condition" => "", "delay" => ""],
                         ["dest" => "S5", "condition" => "", "delay" => "0"]]],
                                                    
    ["time" => "", "location" => "S4", "progress" => "S",
      "actionTable" =>  [["dest" => "S7", "condition" => "W", "mTrn" => "32", "mSignal" => "S3", "delay" => "10"]]],
      
    ["time" => "", "location" => "S5", "progress" => "S",
      "actionTable" =>  [["dest" => "S7", "condition" => "W", "mTrn" => "32", "mSignal" => "S2", "delay" => "10"]]],
      
    ["time" => "", "location" => "S7","progress" => "A",
      "actionTable" =>  [["dest" => "BS2", "condition" => "", "delay" => ""],
                         ["dest" => "BS3", "condition" => "", "delay" => ""]]],
                                                    
    ["time" => "", "location" => "BS2","progress" => "S",
      "actionTable" => [["dest" => "", "condition" => "N", "nextTrn" => "32", "delay" => "10"]]],
      
    ["time" => "", "location" => "BS3","progress" => "S",
      "actionTable" => [["dest" => "", "condition" => "N", "nextTrn" => "32", "delay" => "20"]]],
  ],
  "remarks" => "",
],

"32" => [
  "start" => "Reol",
  "destination" => "Spisebord",
  "description" => "Krydsning, Ned",
  "businessStart" => "14:00",
  "businessEnd" => "23:59",
  "group" => "",
  "locationTable" => [
    ["time" => "", "location" => "S8", "progress" => "A",
      "actionTable" => [["dest" => "S6", "condition" => "", "delay" => ""]]],
    
    ["time" => "", "location" => "S9", "progress" => "A",
      "actionTable" => [["dest" => "S6", "condition" => "", "delay" => ""]]],
    
    ["time" => "", "location" => "S6", "progress" => "A",
      "actionTable" => [["dest" => "S2", "condition" => "", "delay" => ""],
                   ["dest" => "S3", "condition" => "", "delay" => ""]]],
    
    ["time" => "", "location" => "S2", "progress" => "S",
      "actionTable" => [["dest" => "BS1", "condition" => "W", "mTrn" => "21", "mSignal" => "S5", "delay" => "10"]]],
    
    ["time" => "", "location" => "S3", "progress" => "S",
      "actionTable" => [["dest" => "BS1", "condition" => "W", "mTrn" => "21", "mSignal" => "S4", "delay" => "10"]]],

    ["time" => "", "location" => "BS1", "progress" => "S",
      "actionTable" => [["dest" => "", "condition" => "N", "nextTrn" => "21", "delay" => "30"]]],
  ],
  "remarks" => "",
],

"11" => [
  "start" => "Spisebord",
  "destination" => "Reol",
  "description" => "Op",
  "group" => "",
  "businessStart" => "14:00",
  "businessEnd" => "23:59",
  "locationTable" => [
    ["time" => "", "location" => "S1", "progress" => "A",
      "actionTable" => [["dest" => "S4", "condition" => "", "delay" => ""],
                        ["dest" => "S5", "condition" => "", "delay" => ""]]],
    
    ["time" => "", "location" => "S4",  "progress" => "A",
      "actionTable" => [["dest" => "S7", "condition" => "", "delay" => "10"]]],
    
    ["time" => "", "location" => "S5",  "progress" => "A",
      "actionTable" => [["dest" => "S7", "condition" => "", "delay" => "10"]]],
    
    ["time" => "", "location" => "S7",  "progress" => "A",
      "actionTable" => [["dest" => "BS2", "condition" => "", "delay" => ""],
                        ["dest" => "BS3", "condition" => "", "delay" => ""]]],
    
    ["time" => "", "location" => "BS2",  "progress" => "S",
      "actionTable" => [["dest" => "", "condition" => "N", "nextTrn" => "12", "delay" => "20"]]],
    
    ["time" => "", "location" => "BS3",  "progress" => "S",
      "actionTable" => [["dest" => "", "condition" => "N", "nextTrn" => "12", "delay" => "20"]]],
  ],
  "remarks" => "",
],

"12" => [
  "start" => "Reol",
  "destination" => "Spisebord",
  "description" => "Ned",
  "businessStart" => "14:00",
  "businessEnd" => "23:59",
  "group" => "",
  "locationTable" => [
    ["time" => "", "location" => "S8", "progress" => "A",
      "actionTable" => [["dest" => "S6", "condition" => "", "delay" => ""]]],
    
    ["time" => "", "location" => "S9", "progress" => "A",
      "actionTable" => [["dest" => "S6", "condition" => "", "delay" => ""]]],
    
    ["time" => "", "location" => "S6", "progress" => "A",
      "actionTable" => [["dest" => "S2", "condition" => "", "delay" => ""],
                   ["dest" => "S3", "condition" => "", "delay" => ""]]],
    
    ["time" => "", "location" => "S3", "progress" => "A",
      "actionTable" => [["dest" => "BS1", "condition" => "", "delay" => "10"]]],
    
    ["time" => "", "location" => "S2", "progress" => "A",
      "actionTable" => [["dest" => "BS1", "condition" => "", "delay" => "10"]]],
    
    ["time" => "", "location" => "BS1", "progress" => "S",
      "actionTable" => [["dest" => "", "condition" => "N", "nextTrn" => "11", "delay" => "20"]]],
  ],
  "remarks" => "",
],

"101" => [
  "start" => "Gulvet, S4",
  "destination" => "Gulvet, S2",
  "description" => "Vending, reol BS2",
  "group" => "",
  "locationTable" => [
    ["time" => "", "location" => "S4", "progress" => "A",
      "actionTable" => [["dest" => "BS2", "condition" => "", "delay" => ""]]],

    ["time" => "", "location" => "S8", "progress" => "S",
      "actionTable" => [["dest" => "S2", "condition" => "", "delay" => "10"]]],

    ["time" => "", "location" => "S2", "progress" => "S", 
      "actionTable" => [["dest" => "", "condition" => "E", "delay" => ""]]],
  ],
  "remarks" => "",
],

"102" => [
  "start" => "Gulvet, S5",
  "destination" => "Gulvet, S3",
  "description" => "Vending, reol BS3",
  "group" => "",
  "locationTable" => [
    ["time" => "", "location" => "S5", "progress" => "A",
     "actionTable" => [["dest" => "BS3", "condition" => "", "delay" => ""]]],
    
    ["time" => "", "location" => "S9", "progress" => "S",
     "actionTable" => [["dest" => "S3", "condition" => "", "delay" => "10"]]],
    
    ["time" => "", "location" => "S3", "progress" => "S",
     "actionTable" => [["dest" => "", "condition" => "E", "delay" => ""]]],
  ],
  "remarks" => "",
],


/*
// ----------------------------- Template

*/

];

?>
