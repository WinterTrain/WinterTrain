<?php
// PT1 data for Test Train at W57

$PT1_VERSION = "02P01"; 
$PT1 = [

"E" => [ // Bufferstop; begin of direction up
  "element" => "BSB",
  "U" => ["name" => "B", "dist" => 106],
  "HMI" => [
    "x" => 0,
    "y" => 2,
    "l" => 2,
    ],
  ],
"B" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 0,
    ],
  "U" => ["name" => "01", "dist" => 0],
  "D" => ["name" => "E", "dist" => 0],
  "HMI" => [
    "x" => 2,
    "y" => 2,
    ],
  ],
"01" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:D6:F2",
  "U" => ["name" => "N101", "dist" => 11],
  "D" => ["name" => "B", "dist" => 0],
  ],
  
"F" => [ // Bufferstop; begin of direction up
  "element" => "BSB",
  "U" => ["name" => "C", "dist" => 121],
  "HMI" => [
    "x" => 0,
    "y" => 4,
    "l" => 2,
    ],
  ],
"C" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 0,
    ],
  "U" => ["name" => "02", "dist" => 0],
  "D" => ["name" => "F", "dist" => 0],
  "HMI" => [
    "x" => 2,
    "y" => 4,
    ],
  ],  
"02" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:A3:40",
  "U" => ["name" => "N101", "dist" => 11],
  "D" => ["name" => "C", "dist" => 0],
  ],
"N101" => [ // Point, trailing
  "element" => "PT",
  "EC" => [
    "addr" => 152,
    "type" => 10,
    "device1" => 1,
    "device2" => 0,
    ],
  "clamp" => "",
  "R" => ["name" => "01", "dist" => 23],
  "L" => ["name" => "02", "dist" => 23],
  "T" => ["name" => "03", "dist" => 23],
  "HMI" => [
    "or" => "tl",
    "x" => 4,
    "y" => 2,
    ],
  ],  
"03" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:F0:55",
  "U" => ["name" => "A", "dist" => 0],
  "D" => ["name" => "N101", "dist" => 25],
  ],
"A" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 0,
    ],
  "U" => ["name" => "04", "dist" => 0],
  "D" => ["name" => "03", "dist" => 0],
  "HMI" => [
    "x" => 6,
    "y" => 1,
    ],
  ],  


 "04" => [ // Balises
  "element" => "BL",
  "ID" => "74:00:15:55:26",
  "U" => ["name" => "G", "dist" => 106],
  "D" => ["name" => "A", "dist" => 77],
  ],   
"G" => [ // Signal
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 0,
    ],
  "U" => ["name" => "05", "dist" => 0],
  "D" => ["name" => "04", "dist" => 0],
  "HMI" => [
    "x" => 12,
    "y" => 2,
    ],
  ],
"05" => [ // Balises
  "element" => "BL",
  "ID" => "76:00:0D:12:CB",
  "U" => ["name" => "H", "dist" => 0],
  "D" => ["name" => "G", "dist" => 0],
  ],  
"H" => [ // Signal
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 0,
    ],
  "U" => ["name" => "06", "dist" => 0],
  "D" => ["name" => "05", "dist" => 0],
  "HMI" => [
    "x" => 14,
    "y" => 1,
    ],
  ], 
"06" => [ // Balises
  "element" => "BL",
  "ID" => "1F:00:4D:4B:FE",
  "U" => ["name" => "D", "dist" => 0],
  "D" => ["name" => "H", "dist" => 65],
  ],  

"D" => [ // Bufferstop; end of direction up
  "element" => "BSE",
  "D" => ["name" => "06", "dist" => 71],
  "HMI" => [
    "x" => 18,
    "y" => 2,
    "l" => 1,
    ],
  ],
// -------------------------------------------------------------------------------------- Templates
"" => [ // Bufferstop; begin of direction up
  "element" => "BSB",
  "U" => ["name" => "", "dist" => 0],
  "HMI" => [
    "x" => 0,
    "y" => 0,
    "l" => 0,
    ],
  ],
"" => [ // Bufferstop; end of direction up
  "element" => "BSE",
  "D" => ["name" => "", "dist" => 0],
  "HMI" => [
    "x" => 0,
    "y" => 0,
    "l" => 0,
    ],
  ],
"" => [ // Balises
  "element" => "BL",
  "ID" => "",
  "U" => ["name" => "", "dist" => 0],
  "D" => ["name" => "", "dist" => 0],
  ],
"" => [ // Point, Facing
  "element" => "PF",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 0,
    "device2" => 0,
    ],
  "clamp" => "",
  "R" => ["name" => "", "dist" => 0],
  "L" => ["name" => "", "dist" => 0],
  "T" => ["name" => "", "dist" => 0],
  "HMI" => [
    "or" => "",
    "x" => 0,
    "y" => 0,
    ],
  ],
"" => [ // Point, trailing
  "element" => "PT",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 0,
    "device2" => 0,
    ],
  "clamp" => "",
  "R" => ["name" => "", "dist" => 0],
  "L" => ["name" => "", "dist" => 0],
  "T" => ["name" => "", "dist" => 0],
  "HMI" => [
    "or" => "",
    "x" => 0,
    "y" => 0,
    ],
  ],
"" => [ // Signal, facing and ...
  "element" => "SU",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 0,
    ],
  "U" => ["name" => "", "dist" => 0],
  "D" => ["name" => "", "dist" => 0],
  "HMI" => [
    "x" => 0,
    "y" => 0,
    ],
  ],
"" => [ // LX
  "element" => "LX",
  "ECsignal" => [
    "addr" => 152,
    "type" => 31,
    "device1" => 1,
    "device2" => 0,
    ],
  "ECbarrier" => [
    "addr" => 152,
    "type" => 32,
    "device1" => 4,
    "device2" => 0,
    ],
  "U" => ["name" => "BG028", "dist" => 20],
  "D" => ["name" => "BG027", "dist" => 20],
  "HMI" => [
    "x" => 25,
    "y" => 1,
    ],
  ],

];

$HMI = [ //------------------------------------------------------------------ HMI
  "baliseTrack" => [
    "tr3" => [
      "balises" => ["04"
              ],
      "x" => 8,
      "y" => 2,
      "l" => 4,
    ],
    "tr4" => [
      "balises" => ["06"
              ],
      "x" => 16,
      "y" => 2,
      "l" => 2,
    ],
  ],
  "label" => [
    [
      "x" => 30,
      "y" => 1,
      "text" => "Kgs. Nytorv"
    ],
    [
      "x" => 17,
      "y" => 1,
      "text" => "Christianshavn"
    ],
    [
      "x" => 2,
      "y" => 1,
      "text" => "Holmen"
    ],
  ],
  "scale" => "45",
  "color" => [
    "aColor" => "orange",
    "fColor" => "blue",
    "oColor" => "lightgreen",
    "cColor" => "red",
  ],
];

?>

