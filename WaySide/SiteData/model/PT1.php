<?php
// PT1 model data for testing

$PT1_VERSION = "01P01"; 
$PT1 = [

"A" => [ // Bufferstop; begin of direction up
  "element" => "BSB",
  "U" => ["name" => "01", "dist" => 15],
  "HMI" => [
    "x" => 0,
    "y" => 2,
    "l" => 1,
    ],
  ],

"01" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:F0:55",
  "U" => ["name" => "02", "dist" => 35],
  "D" => ["name" => "A", "dist" => 20],
  ],

"02" => [ // Balises
  "element" => "BL",
  "ID" => "76:00:0C:E3:A8",
  "U" => ["name" => "T1", "dist" => 40],
  "D" => ["name" => "01", "dist" => 35],
  ],

"T1" => [ // Trigger, direcction up, for enforced point holding
  "element" => "PHTU",
  "U" => ["name" => "B", "dist" => 40],
  "D" => ["name" => "02", "dist" => 35],
  "holdPoint" => "P1", // Affected point
  ],

"B" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 203,
    "type" => 41,
    "majorDevice" => 6,
    ],
  "U" => ["name" => "03", "dist" => 10],
  "D" => ["name" => "T1", "dist" => 0],
  "HMI" => [
    "x" => 3,
    "y" => 2,
    ],
  ],

"03" => [ // Balises
  "element" => "BL",
  "ID" => "74:00:15:65:29",
  "U" => ["name" => "C", "dist" => 0],
  "D" => ["name" => "B", "dist" => 0],
  ],

"C" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 203,
    "type" => 41,
    "majorDevice" => 7,
    ],
  "U" => ["name" => "04", "dist" => 0],
  "D" => ["name" => "03", "dist" => 10],
  "HMI" => [
    "x" => 5,
    "y" => 1,
    ],
  ],  

 "04" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:D9:B2",
  "U" => ["name" => "05", "dist" => 25],
  "D" => ["name" => "C", "dist" => 65],
  ],
  
"05" => [ // Balises
  "element" => "BL",
  "ID" => "76:00:0C:FA:7D",
  "U" => ["name" => "D", "dist" => 20],
  "D" => ["name" => "04", "dist" => 25],
  ],
  
"D" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 203,
    "type" => 0,
    "majorDevice" => 3,
    ],
  "U" => ["name" => "P1", "dist" => 10],
  "D" => ["name" => "05", "dist" => 0],
  "HMI" => [
    "x" => 9,
    "y" => 2,
    ],
  ],


"P1" => [ // Point, Facing
  "element" => "PF",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    "minorDevice" => 0,
    ],
  "supervisionState" => "S",
  "release" => "Y",
  "R" => ["name" => "F", "dist" => 0],
  "L" => ["name" => "E", "dist" => 0],
  "T" => ["name" => "D", "dist" => 0],
  "HMI" => [
    "or" => "fr",
    "x" => 11,
    "y" => 2,
    ],
  ],
  

"E" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 203,
    "type" => 41,
    "majorDevice" => 7,
    ],
  "U" => ["name" => "06", "dist" => 0],
  "D" => ["name" => "P1", "dist" => 10],
  "HMI" => [
    "x" => 13,
    "y" => 1,
    ],
  ],  

"F" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 203,
    "type" => 41,
    "majorDevice" => 7,
    ],
  "U" => ["name" => "07", "dist" => 0],
  "D" => ["name" => "P1", "dist" => 10],
  "HMI" => [
    "x" => 13,
    "y" => 3,
    ],
  ],  

  
"06" => [ // Balises
  "element" => "BL",
  "ID" => "00:00:0C:FA:7D",
  "U" => ["name" => "G", "dist" => 20],
  "D" => ["name" => "E", "dist" => 25],
  ],
  
 "07" => [ // Balises
  "element" => "BL",
  "ID" => "00:00:0C:FA:7D",
  "U" => ["name" => "H", "dist" => 20],
  "D" => ["name" => "F", "dist" => 25],
  ],
 
"G" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 203,
    "type" => 0,
    "majorDevice" => 3,
    ],
  "U" => ["name" => "P2", "dist" => 10],
  "D" => ["name" => "06", "dist" => 0],
  "HMI" => [
    "x" => 17,
    "y" => 2,
    ],
  ],

"H" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 203,
    "type" => 0,
    "majorDevice" => 3,
    ],
  "U" => ["name" => "P2", "dist" => 10],
  "D" => ["name" => "07", "dist" => 0],
  "HMI" => [
    "x" => 17,
    "y" => 4,
    ],
  ],

 
 
"P2" => [ // Point, trailing
  "element" => "PT",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    "minorDevice" => 0,
    ],
  "supervisionState" => "S",
  "R" => ["name" => "G", "dist" => 0],
  "L" => ["name" => "H", "dist" => 0],
  "T" => ["name" => "J", "dist" => 0],
  "HMI" => [
    "or" => "tl",
    "x" => 19,
    "y" => 2,
    ],
  ],

  "J" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 203,
    "type" => 41,
    "majorDevice" => 7,
    ],
  "U" => ["name" => "08", "dist" => 0],
  "D" => ["name" => "P2", "dist" => 10],
  "HMI" => [
    "x" => 21,
    "y" => 1,
    ],
  ],  
  "08" => [ // Balises
  "element" => "BL",
  "ID" => "00:00:00:00:00",
  "U" => ["name" => "Q", "dist" => 20],
  "D" => ["name" => "J", "dist" => 25],
  ],
 
  
  "Q" => [ // Bufferstop; end of direction up
  "element" => "BSE",
  "D" => ["name" => "08", "dist" => 35],
  "HMI" => [
    "x" => 25,
    "y" => 2,
    "l" => 1,
    ],
  ]
];

$HMI = [ //------------------------------------------------------------------ HMI
  "baliseTrack" => [
    "tr1" => [
      "balises" => [ "01"
              ],
      "or" => "s",
      "x" => 1,
      "y" => 2,
      "l" => 1,
    ],
    "tr2" => [
      "balises" => ["02",
              ],
      "or" => "s",
      "x" => 2,
      "y" => 2,
      "l" => 1,
    ],
    "tr3" => [
      "balises" => [ "04"
              ],
      "or" => "s",
      "x" => 7,
      "y" => 2,
      "l" => 1,
    ],
    "tr4" => [
      "balises" => ["05",
              ],
      "or" => "s",
      "x" => 8,
      "y" => 2,
      "l" => 1,
    ],
    "tr5" => [
      "balises" => ["06",
              ],
      "or" => "s",
      "x" => 15,
      "y" => 2,
      "l" => 2,
    ],
    "tr6" => [
      "balises" => ["07",
              ],
      "or" => "s",
      "x" => 15,
      "y" => 4,
      "l" => 2,
    ],
    "tr7" => [
      "balises" => ["08",
              ],
      "or" => "s",
      "x" => 23,
      "y" => 2,
      "l" => 2,
    ],  ],
  "label" => [
  ],
  "color" => [
    "aColor" => "orange",
    "fColor" => "blue",
    "oColor" => "lightgreen",
    "cColor" => "red",
  ],
];

?>

