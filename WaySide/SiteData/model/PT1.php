<?php
// PT1 model data for testing

$PT1_VERSION = "01P01"; 
$PT1 = [

"A" => [ // Bufferstop; begin of direction up
  "element" => "BSB",
  "U" => ["name" => "BG01", "dist" => 20],
  "HMI" => [
    "x" => 0,
    "y" => 2,
    "l" => 1,
    ],
  ],

"BG01" => [ // Balises
  "element" => "BL",
  "ID" => "75:00:14:D2:51",
  "U" => ["name" => "BG02", "dist" => 20],
  "D" => ["name" => "A", "dist" => 25],
  ],

"BG02" => [ // Balises
  "element" => "BL",
  "ID" => "00:00:00:00:00",
  "U" => ["name" => "T1", "dist" => 1],
  "D" => ["name" => "BG01", "dist" => 16],
  ],

"T1" => [ // Trigger, direcction up, for enforced point holding
  "element" => "PHTU",
  "U" => ["name" => "B", "dist" => 1],
  "D" => ["name" => "BG02", "dist" => 1],
  "holdPoint" => "P1", // Affected point
  ],

"B" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 41,
    "majorDevice" => 6,
    ],
  "U" => ["name" => "BG03", "dist" => 10],
  "D" => ["name" => "T1", "dist" => 0],
  "HMI" => [
    "x" => 3,
    "y" => 2,
    ],
  ],

"BG03" => [ // Balises
  "element" => "BL",
  "ID" => "1E:00:90:89:ED",
  "U" => ["name" => "C", "dist" => 1],
  "D" => ["name" => "B", "dist" => 1],
  ],

"C" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 41,
    "majorDevice" => 7,
    ],
  "U" => ["name" => "BG04", "dist" => 0],
  "D" => ["name" => "BG03", "dist" => 10],
  "HMI" => [
    "x" => 5,
    "y" => 1,
    ],
  ],  

"BG04" => [ // Balises
  "element" => "BL",
  "ID" => "00:00:00:00:00",
  "U" => ["name" => "BG05", "dist" => 15],
  "D" => ["name" => "C", "dist" => 1],
  ],
  
"BG05" => [ // Balises
  "element" => "BL",
  "ID" => "00:00:00:00:00",
  "U" => ["name" => "D", "dist" => 1],
  "D" => ["name" => "BG04", "dist" => 1],
  ],
  
"D" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 3,
    ],
  "U" => ["name" => "P1", "dist" => 10],
  "D" => ["name" => "BG05", "dist" => 0],
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
  "R" => ["name" => "F", "dist" => 10],
  "L" => ["name" => "BG12", "dist" => 10],
  "T" => ["name" => "D", "dist" => 0],
  "HMI" => [
    "or" => "fr",
    "x" => 11,
    "y" => 2,
    ],
  ],
  
"BG12" => [ // Balises
  "element" => "BL",
  "ID" => "1F:00:68:D5:C8",
  "U" => ["name" => "E", "dist" => 1],
  "D" => ["name" => "P1", "dist" => 1],
  ],
  
"E" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 41,
    "majorDevice" => 7,
    ],
  "U" => ["name" => "BG06", "dist" => 0],
  "D" => ["name" => "BG12", "dist" => 10],
  "HMI" => [
    "x" => 13,
    "y" => 1,
    ],
  ],  

"BG06" => [ // Balises
  "element" => "BL",
  "ID" => "00:00:00:00:00",
  "U" => ["name" => "G", "dist" => 19],
  "D" => ["name" => "E", "dist" => 19],
  ],

"F" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 41,
    "majorDevice" => 7,
    ],
  "U" => ["name" => "BG07", "dist" => 0],
  "D" => ["name" => "P1", "dist" => 10],
  "HMI" => [
    "x" => 13,
    "y" => 3,
    ],
  ],
  
"BG07" => [ // Balises
  "element" => "BL",
  "ID" => "00:00:00:00:00",
  "U" => ["name" => "H", "dist" => 1],
  "D" => ["name" => "F", "dist" => 1],
  ],

 
"G" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 3,
    ],
  "U" => ["name" => "BG08", "dist" => 10],
  "D" => ["name" => "BG06", "dist" => 0],
  "HMI" => [
    "x" => 17,
    "y" => 2,
    ],
  ],

"BG08" => [ // Balises
  "element" => "BL",
  "ID" => "1F:00:1D:4C:3E",
  "U" => ["name" => "P2", "dist" => 1],
  "D" => ["name" => "G", "dist" => 1],
  ],
  
"H" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 3,
    ],
  "U" => ["name" => "BG09", "dist" => 10],
  "D" => ["name" => "BG07", "dist" => 0],
  "HMI" => [
    "x" => 17,
    "y" => 4,
    ],
  ],
 
"BG09" => [ // Balises
  "element" => "BL",
  "ID" => "00:00:00:00:00",
  "U" => ["name" => "P2", "dist" => 10],
  "D" => ["name" => "H", "dist" => 10],
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
  "R" => ["name" => "BG08", "dist" => 19],
  "L" => ["name" => "BG09", "dist" => 10],
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
    "addr" => 0,
    "type" => 41,
    "majorDevice" => 7,
    ],
  "U" => ["name" => "BG10", "dist" => 0],
  "D" => ["name" => "P2", "dist" => 30],
  "HMI" => [
    "x" => 21,
    "y" => 1,
    ],
  ],  

  "BG10" => [ // Balises
  "element" => "BL",
  "ID" => "76:00:0C:B7:36",
  "U" => ["name" => "Q", "dist" => 35],
  "D" => ["name" => "J", "dist" => 50],
  ],
 
  
  "Q" => [ // Bufferstop; end of direction up
  "element" => "BSE",
  "D" => ["name" => "BG10", "dist" => 0],
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
      "balises" => [ "BG01"
              ],
      "or" => "s",
      "x" => 1,
      "y" => 2,
      "l" => 1,
    ],
    "tr2" => [
      "balises" => ["BG02",
              ],
      "or" => "s",
      "x" => 2,
      "y" => 2,
      "l" => 1,
    ],
    "tr3" => [
      "balises" => [ "BG04"
              ],
      "or" => "s",
      "x" => 7,
      "y" => 2,
      "l" => 1,
    ],
    "tr4" => [
      "balises" => ["BG05",
              ],
      "or" => "s",
      "x" => 8,
      "y" => 2,
      "l" => 1,
    ],
    "tr5" => [
      "balises" => ["BG06",
              ],
      "or" => "s",
      "x" => 15,
      "y" => 2,
      "l" => 2,
    ],
    "tr6" => [
      "balises" => ["BG07",
              ],
      "or" => "s",
      "x" => 15,
      "y" => 4,
      "l" => 2,
    ],
    "tr7" => [
      "balises" => ["BG10",
              ],
      "or" => "s",
      "x" => 23,
      "y" => 2,
      "l" => 2,
    ],  ],
  "label" => [
    ["text" => "DownTown",
    "x" => 2,
    "y" => 1
    ],
    ["text" => "UpTown",
    "x" => 25,
    "y" => 1
    ]
  ],
  "eStopIndicator" => [
    "x" => 1,
    "y" => 4
  ],
  "arsIndicator" => [
    "x" => 1,
    "y" => 5
  ],
  "color" => [
    "aColor" => "orange",
    "fColor" => "blue",
    "oColor" => "lightgreen",
    "cColor" => "red",
  ],
];

?>

