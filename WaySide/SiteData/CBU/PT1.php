<?php
// PT1 data for the WinterTrain at CBU

$PT1_VERSION = "CBU01P01"; 
$PT1 = [

// -------------------------------------------------------------------------------- Station at Back Wall
// ---------------------------------------------------- Track 1
"B1" => [ // Bufferstop; begin of direction up
  "element" => "BSB",
  "U" => ["name" => "BG01", "dist" => 77],
  "HMI" => [
    "x" => 0,
    "y" => 2,
    "l" => 1,
    ],
  ],

"BG01" => [ // Balises
  "element" => "BL",
  "ID" => "76:00:0C:75:E5",
  "U" => ["name" => "S1", "dist" => 16],
  "D" => ["name" => "B1", "dist" => 1],
  ],

"S1" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 4,
    ],
  "U" => ["name" => "BG03", "dist" => 1],
  "D" => ["name" => "BG01", "dist" => 1],
  "HMI" => [
    "x" => 1,
    "y" => 2,
    ],
  ],

"BG03" => [ // Balises
  "element" => "BL",
  "ID" => "75:00:14:D1:25",
  "U" => ["name" => "P1", "dist" => 17],
  "D" => ["name" => "S1", "dist" => 1],
  ],

// ---------------------------------------------------- Track 2
  
"B2" => [ // Bufferstop; begin of direction up
  "element" => "BSB",
  "U" => ["name" => "BG02", "dist" => 80],
  "HMI" => [
    "x" => 0,
    "y" => 4,
    "l" => 1,
    ],
  ],

"BG02" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:D9:B2",
  "U" => ["name" => "S2", "dist" => 16],
  "D" => ["name" => "B2", "dist" => 1],
  ],

"S2" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 4,
    ],
  "U" => ["name" => "BG04", "dist" => 11],
  "D" => ["name" => "BG02", "dist" => 1],
  "HMI" => [
    "x" => 1,
    "y" => 4,
    ],
  ],

"BG04" => [ // Balises
  "element" => "BL",
  "ID" => "76:00:0C:FA:7D",
  "U" => ["name" => "P1", "dist" => 14],
  "D" => ["name" => "S2", "dist" => 1],
  ],

"P1" => [ // Point, trailing
  "element" => "PT",
  "EC" => [
    "addr" => 152,
    "type" => 10,
    "device1" => 1,
    "device2" => 0,
    ],
  "clamp" => "L",
  "R" => ["name" => "BG03", "dist" => 40],
  "L" => ["name" => "BG04", "dist" => 40],
  "T" => ["name" => "BG05", "dist" => 1],
  "HMI" => [
    "or" => "tl",
    "x" => 3,
    "y" => 2,
    ],
  ],  

"BG05" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:70:3D:8D",
  "U" => ["name" => "BG06", "dist" => 10],
  "D" => ["name" => "P1", "dist" => 10],
  ],

"BG06" => [ // Balises
  "element" => "BL",
  "ID" => "74:00:11:07:0B",
  "U" => ["name" => "S3", "dist" => 1],
  "D" => ["name" => "BG05", "dist" => 10],
  ],


"S3" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 4,
    ],
  "U" => ["name" => "BG07", "dist" => 1],
  "D" => ["name" => "BG06", "dist" => 1],
  "HMI" => [
    "x" => 5,
    "y" => 1,
    ],
  ],
  
// ------------------------------------------------------------------------------ Long side

"BG07" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:70:98:69",
  "U" => ["name" => "S4", "dist" => 228],
  "D" => ["name" => "S3", "dist" => 257],
  ],
  
"S4" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 0,
    ],
  "U" => ["name" => "S5", "dist" => 15],
  "D" => ["name" => "BG07", "dist" => 1],
  "HMI" => [
    "x" => 8,
    "y" => 2,
    ],
  ],  

"S5" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 0,
    ],
  "U" => ["name" => "BG08", "dist" => 1],
  "D" => ["name" => "S4", "dist" => 15],
  "HMI" => [
    "x" => 10,
    "y" => 1,
    ],
  ],  

"BG08" => [ // Balises
  "element" => "BL",
  "ID" => "74:00:15:50:C0",
  "U" => ["name" => "BG09", "dist" => 132],
  "D" => ["name" => "S5", "dist" => 1],
  ],
  
"BG09" => [ // Balises
  "element" => "BL",
  "ID" => "76:00:0C:E3:A8",
  "U" => ["name" => "S6", "dist" => 208],
  "D" => ["name" => "BG08", "dist" => 132],
  ],

// ---------------------------------------------- Main Station

"S6" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 0,
    ],
  "U" => ["name" => "BG10", "dist" => 59],
  "D" => ["name" => "BG09", "dist" => 1],
  "HMI" => [
    "x" => 13,
    "y" => 2,
    ],
  ], 

"BG10" => [ // Balises
  "element" => "BL",
  "ID" => "76:00:0D:03:82",
  "U" => ["name" => "BG11", "dist" => 10],
  "D" => ["name" => "S6", "dist" => 1],
  ],
  
"BG11" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:A6:71",
  "U" => ["name" => "P2", "dist" => 10],
  "D" => ["name" => "BG10", "dist" => 10],
  ],
  
"P2" => [ // Point, Facing
  "element" => "PF",
  "EC" => [
    "addr" => 152,
    "type" => 10,
    "device1" => 2,
    "device2" => 0,
    ],
  "clamp" => "",
  "R" => ["name" => "BG13", "dist" => 40],
  "L" => ["name" => "BG12", "dist" => 40],
  "T" => ["name" => "BG11", "dist" => 1],
  "HMI" => [
    "or" => "fr",
    "x" => 15,
    "y" => 2,
    ],
  ],
// ---------------------------------------------------------- Inner Track 

"BG12" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:E4:D1",
  "U" => ["name" => "BG14", "dist" => 10],
  "D" => ["name" => "P2", "dist" => 2],
  ],
 
"BG14" => [ // Balises
  "element" => "BL",
  "ID" => "76:00:0C:EC:E8",
  "U" => ["name" => "S7", "dist" => 1],
  "D" => ["name" => "BG12", "dist" => 11],
  ],
 
 "S7" => [ // Signal
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 0,
    ],
  "U" => ["name" => "S9", "dist" => 1],
  "D" => ["name" => "BG14", "dist" => 8],
  "HMI" => [
    "x" => 18,
    "y" => 1,
    ],
  ],

 "S9" => [ // Signal
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 0,
    ],
  "U" => ["name" => "BG16", "dist" => 0],
  "D" => ["name" => "S7", "dist" => 0],
  "HMI" => [
    "x" => 21,
    "y" => 2,
    ],
  ],
  
"BG16" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:C0:72",
  "U" => ["name" => "BG17", "dist" => 0],
  "D" => ["name" => "S9", "dist" => 0],
  ],
  
"BG17" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:93:AA",
  "U" => ["name" => "P3", "dist" => 0],
  "D" => ["name" => "BG16", "dist" => 0],
  ],
  

"P3" => [ // Point, trailing
  "element" => "PT",
  "EC" => [
    "addr" => 152,
    "type" => 10,
    "device1" => 1,
    "device2" => 0,
    ],
  "clamp" => "",
  "R" => ["name" => "BG17", "dist" => 0],
  "L" => ["name" => "P4", "dist" => 38],
  "T" => ["name" => "BG21", "dist" => 0],
  "HMI" => [
    "or" => "tl",
    "x" => 25,
    "y" => 2,
    ],
  ],
  
 
"BG21" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:6E:C8:15",
  "U" => ["name" => "BG22", "dist" => 0],
  "D" => ["name" => "P3", "dist" => 0],
  ],
  
"BG22" => [ // Balises
  "element" => "BL",
  "ID" => "74:00:11:09:64",
  "U" => ["name" => "S11", "dist" => 1],
  "D" => ["name" => "BG21", "dist" => 9],
  ],

 "S11" => [ // Signal
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 0,
    ],
  "U" => ["name" => "BG25", "dist" => 0],
  "D" => ["name" => "BG22", "dist" => 0],
  "HMI" => [
    "x" => 26,
    "y" => 1,
    ],
  ],
 
// --------------------------------------------------------------- Outer Track

"BG13" => [ // Balises
  "element" => "BL",
  "ID" => "74:00:15:0B:FB",
  "U" => ["name" => "BG15", "dist" => 9],
  "D" => ["name" => "P2", "dist" => 1],
  ],
 
"BG15" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:E3:9B",
  "U" => ["name" => "S8", "dist" => 1],
  "D" => ["name" => "BG13", "dist" => 10],
  ],
 
 "S8" => [ // Signal
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 0,
    ],
  "U" => ["name" => "BG18", "dist" => 0],
  "D" => ["name" => "BG15", "dist" => 1],
  "HMI" => [
    "x" => 18,
    "y" => 5,
    ],
  ],

"BG18" => [ // Balises
  "element" => "BL",
  "ID" => "76:00:0C:D2:BB",
  "U" => ["name" => "S10", "dist" => 0],
  "D" => ["name" => "S8", "dist" => 0],
  ],
 
 "S10" => [ // Signal
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 0,
    ],
  "U" => ["name" => "BG19", "dist" => 0],
  "D" => ["name" => "BG18", "dist" => 0],
  "HMI" => [
    "x" => 21,
    "y" => 6,
    ],
  ],

"BG19" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:9B:F4",
  "U" => ["name" => "P4", "dist" => 1],
  "D" => ["name" => "S10", "dist" => 9],
  ],

  
"P4" => [ // Point, Facing
  "element" => "PF",
  "EC" => [
    "addr" => 152,
    "type" => 10,
    "device1" => 3,
    "device2" => 0,
    ],
  "clamp" => "L",
  "R" => ["name" => "B3", "dist" => 0],
  "L" => ["name" => "P3", "dist" => 37],
  "T" => ["name" => "BG19", "dist" => 0],
  "HMI" => [
    "or" => "fl",
    "x" => 23,
    "y" => 4,
    ],
  ],



 "" => [ // Signal
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 0,
    ],
  "U" => ["name" => "BG18", "dist" => 0],
  "D" => ["name" => "P4", "dist" => 0],
  "HMI" => [
    "x" => 25,
    "y" => 5,
    ],
  ],

// ----------------------------------------------------------------- Inside Bar

"BG25" => [ // Balises
  "element" => "BL",
  "ID" => "74:00:10:F3:3E",
  "U" => ["name" => "S13", "dist" => 0],
  "D" => ["name" => "S11", "dist" => 0],
  ],
  
"S13" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "LS1",
  "EC" => [
    "addr" => 150,
    "type" => 41,
    "device1" => 5,
    ],
  "U" => ["name" => "BG27", "dist" => 0],
  "D" => ["name" => "BG25", "dist" => 1],
  "HMI" => [
    "x" => 29,
    "y" => 2,
    ],
  ],  

"BG27" => [ // Balises
  "element" => "BL",
  "ID" => "76:00:0C:FC:CB",
  "U" => ["name" => "S14", "dist" => 1],
  "D" => ["name" => "S13", "dist" => 9],
  ],


"S14" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "LS1",
  "EC" => [
    "addr" => 150,
    "type" => 41,
    "device1" => 4,
    ],
  "U" => ["name" => "B4", "dist" => 1],
  "D" => ["name" => "BG27", "dist" => 0],
  "HMI" => [
    "x" => 31,
    "y" => 1,
    ],
  ],  

"B4" => [ // Bufferstop; end of direction up
  "element" => "BSE",
  "D" => ["name" => "S14", "dist" => 71],
  "HMI" => [
    "x" => 34,
    "y" => 2,
    "l" => 1,
    ],
  ],

// ----------------------------------------------------------------- Outside Bar

"" => [ // Balises
  "element" => "BL",
  "ID" => "",
  "U" => ["name" => "S15", "dist" => 0],
  "D" => ["name" => "S12", "dist" => 0],
  ],
  
"" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 0,
    ],
  "U" => ["name" => "S16", "dist" => 0],
  "D" => ["name" => "BG18", "dist" => 1],
  "HMI" => [
    "x" => 29,
    "y" => 6,
    ],
  ],  

"" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "device1" => 0,
    ],
  "U" => ["name" => "BG19", "dist" => 1],
  "D" => ["name" => "S15", "dist" => 0],
  "HMI" => [
    "x" => 31,
    "y" => 5,
    ],
  ],  

"" => [ // Balises
  "element" => "BL",
  "ID" => "",
  "U" => ["name" => "B4", "dist" => 0],
  "D" => ["name" => "S16", "dist" => 0],
  ],
  
"B3" => [ // Bufferstop; end of direction up
  "element" => "BSE",
  "D" => ["name" => "P4", "dist" => 71],
  "HMI" => [
    "x" => 25,
    "y" => 6,
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
    "tr1" => [
      "balises" => [
              ],
      "x" => 7,
      "y" => 2,
      "l" => 1,
    ],
    "tr2" => [
      "balises" => [
              ],
      "x" => 12,
      "y" => 2,
      "l" => 1,
    ],
    "tr3" => [
      "balises" => [
              ],
      "x" => 17,
      "y" => 2,
      "l" => 1,
    ],
    "tr4" => [
      "balises" => [
              ],
      "x" => 20,
      "y" => 2,
      "l" => 1,
    ],
    "tr5" => [
      "balises" => [
              ],
      "x" => 20,
      "y" => 6,
      "l" => 1,
    ],
    "tr9" => [
      "balises" => [
              ],
      "x" => 23,
      "y" => 2,
      "l" => 2,
    ],
    "" => [
      "balises" => [
              ],
      "x" => 27,
      "y" => 6,
      "l" => 2,
    ],
    "tr11" => [
      "balises" => [
              ],
      "x" => 28,
      "y" => 2,
      "l" => 1,
    ],
    "tr12" => [
      "balises" => [
              ],
      "x" => 33,
      "y" => 2,
      "l" => 1,
    ],
    "" => [
      "balises" => [
              ],
      "x" => 33,
      "y" => 6,
      "l" => 1,
    ],

  ],
  "label" => [
/*    [
      "x" => 17,
      "y" => 1,
      "text" => "Christianshavn"
    ],
    [
      "x" => 2,
      "y" => 1,
      "text" => "Holmen"
    ],
*/
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

