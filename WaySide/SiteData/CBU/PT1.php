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
    "majorDevice" => 4,
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
    "majorDevice" => 4,
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
    "majorDevice" => 3,
    "minorDevice" => 0,
    ],
  "clamp" => "",
  "R" => ["name" => "BG03", "dist" => 50],
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
    "majorDevice" => 4,
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
  "U" => ["name" => "S4", "dist" => 21],
  "D" => ["name" => "S3", "dist" => 258],
  ],
  
"S4" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
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
    "majorDevice" => 0,
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
  "U" => ["name" => "S24", "dist" => 162],
  "D" => ["name" => "S5", "dist" => 210],
  ],
  
"S24" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "S25", "dist" => 15],
  "D" => ["name" => "BG08", "dist" => 1],
  "HMI" => [
    "x" => 13,
    "y" => 2,
    ],
  ],  

"S25" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "BG09", "dist" => 1],
  "D" => ["name" => "S24", "dist" => 15],
  "HMI" => [
    "x" => 15,
    "y" => 1,
    ],
  ],  

"BG09" => [ // Balises
  "element" => "BL",
  "ID" => "76:00:0C:E3:A8",
  "U" => ["name" => "S6", "dist" => 208],
  "D" => ["name" => "S25", "dist" => 71],
  ],

// ---------------------------------------------- Main Station

"S6" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "BG10", "dist" => 59],
  "D" => ["name" => "BG09", "dist" => 1],
  "HMI" => [
    "x" => 18,
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
    "majorDevice" => 2,
    "minorDevice" => 0,
    ],
  "clamp" => "",
  "R" => ["name" => "BG13", "dist" => 40],
  "L" => ["name" => "BG12", "dist" => 40],
  "T" => ["name" => "BG11", "dist" => 1],
  "HMI" => [
    "or" => "fr",
    "x" => 20,
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
    "majorDevice" => 0,
    ],
  "U" => ["name" => "VBG01", "dist" => 1],
  "D" => ["name" => "BG14", "dist" => 9],
  "HMI" => [
    "x" => 23,
    "y" => 1,
    ],
  ],

"VBG01" => [ // Virtual Balises (Does not exist on the track, it acts as a track segment)
  "element" => "BL",
  "ID" => "99:99:99:99:01",
  "U" => ["name" => "S9", "dist" => 72],
  "D" => ["name" => "S7", "dist" => 72],
  ], //TODO: Confirm the VBG concept with Jan

 "S9" => [ // Signal
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "BG16", "dist" => 1],
  "D" => ["name" => "VBG01", "dist" => 1],
  "HMI" => [
    "x" => 26,
    "y" => 2,
    ],
  ],
  
"BG16" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:C0:72",
  "U" => ["name" => "BG17", "dist" => 10],
  "D" => ["name" => "S9", "dist" => 4],
  ],
  
"BG17" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:93:AA",
  "U" => ["name" => "P3", "dist" => 12],
  "D" => ["name" => "BG16", "dist" => 9],
  ],
  

"P3" => [ // Point, trailing
  "element" => "PT",
  "EC" => [
    "addr" => 152,
    "type" => 10,
    "majorDevice" => 1,
    "minorDevice" => 0,
    ],
  "clamp" => "",
  "R" => ["name" => "BG17", "dist" => 46],
  "L" => ["name" => "BG20", "dist" => 46],
  "T" => ["name" => "BG21", "dist" => 1],
  "HMI" => [
    "or" => "tl",
    "x" => 30,
    "y" => 2,
    ],
  ],
  
 
"BG21" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:6E:C8:15",
  "U" => ["name" => "BG22", "dist" => 10],
  "D" => ["name" => "P3", "dist" => 10],
  ],
  
"BG22" => [ // Balises
  "element" => "BL",
  "ID" => "74:00:11:09:64",
  "U" => ["name" => "S11", "dist" => 21],
  "D" => ["name" => "BG21", "dist" => 10],
  ],

 "S11" => [ // Signal
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "BG25", "dist" => 1],
  "D" => ["name" => "BG22", "dist" => 1],
  "HMI" => [
    "x" => 31,
    "y" => 1,
    ],
  ],
 
// --------------------------------------------------------------- Outer Track

"BG13" => [ // Balises
  "element" => "BL",
  "ID" => "74:00:15:0B:FB",
  "U" => ["name" => "BG15", "dist" => 9],
  "D" => ["name" => "P2", "dist" => 2],
  ],
 
"BG15" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:E3:9B",
  "U" => ["name" => "S8", "dist" => 1],
  "D" => ["name" => "BG13", "dist" => 9],
  ],
 
 "S8" => [ // Signal
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "BG18", "dist" => 1],
  "D" => ["name" => "BG15", "dist" => 1],
  "HMI" => [
    "x" => 23,
    "y" => 5,
    ],
  ],

"BG18" => [ // Balises
  "element" => "BL",
  "ID" => "76:00:0C:D2:BB",
  "U" => ["name" => "S10", "dist" => 21],
  "D" => ["name" => "S8", "dist" => 94],
  ],
 
 "S10" => [ // Signal
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "BG19", "dist" => 1],
  "D" => ["name" => "BG18", "dist" => 1],
  "HMI" => [
    "x" => 26,
    "y" => 6,
    ],
  ],

"BG19" => [ // Balises
  "element" => "BL",
  "ID" => "74:00:10:E0:91",
  "U" => ["name" => "P4", "dist" => 3],
  "D" => ["name" => "S10", "dist" => 1],
  ],


"P4" => [ // Point, Facing
  "element" => "PF",
  "EC" => [
    "addr" => 152,
    "type" => 10,
    "majorDevice" => 4,
    "minorDevice" => 0,
    ],
  "clamp" => "",
  "R" => ["name" => "BG30", "dist" => 51],
  "L" => ["name" => "BG20", "dist" => 51],
  "T" => ["name" => "BG19", "dist" => 1],
  "HMI" => [
    "or" => "fl",
    "x" => 28,
    "y" => 4,
    ],
  ],

"BG20" => [ // Balises
  "element" => "BL",
  "ID" => "75:00:14:FB:94",
  "U" => ["name" => "P3", "dist" => 6],
  "D" => ["name" => "P4", "dist" => 2],
  ],


// ----------------------------------------------------------------- Inside Bar

"BG25" => [ // Balises
  "element" => "BL",
  "ID" => "74:00:10:F3:3E",
  "U" => ["name" => "S13", "dist" => 254],
  "D" => ["name" => "S11", "dist" => 198],
  ],
  
"S13" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "LS1",
  "EC" => [
    "addr" => 150,
    "type" => 41,
    "majorDevice" => 5,
    ],
  "U" => ["name" => "BG27", "dist" => 1],
  "D" => ["name" => "BG25", "dist" => 1],
  "HMI" => [
    "x" => 34,
    "y" => 2,
    ],
  ],

"BG27" => [ // Balises
  "element" => "BL",
  "ID" => "76:00:0C:FC:CB",
  "U" => ["name" => "S14", "dist" => 32],
  "D" => ["name" => "S13", "dist" => 1],
  ],


"S14" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "LS1",
  "EC" => [
    "addr" => 150,
    "type" => 41,
    "majorDevice" => 4,
    ],
  "U" => ["name" => "BG28", "dist" => 1],
  "D" => ["name" => "BG27", "dist" => 1],
  "HMI" => [
    "x" => 36,
    "y" => 1,
    ],
  ],

"BG28" => [ // Balises
  "element" => "BL",
  "ID" => "76:00:0D:12:CB",
  "U" => ["name" => "B4", "dist" => 47],
  "D" => ["name" => "S14", "dist" => 47],
  ],


"B4" => [ // Bufferstop; end of direction up
  "element" => "BSE",
  "D" => ["name" => "BG28", "dist" => 95],
  "HMI" => [
    "x" => 39,
    "y" => 2,
    "l" => 1,
    ],
  ],

// ----------------------------------------------------------------- Outside Bar

"BG30" => [ // Balises
  "element" => "BL",
  "ID" => "74:00:10:CA:A1",
  "U" => ["name" => "BG31", "dist" => 10],
  "D" => ["name" => "P4", "dist" => 3],
  ],
  
"BG31" => [ // Balises
  "element" => "BL",
  "ID" => "74:00:10:E1:92",
  "U" => ["name" => "S15", "dist" => 5],
  "D" => ["name" => "BG30", "dist" => 10],
  ],
  
"S15" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "BG32", "dist" => 1],
  "D" => ["name" => "BG31", "dist" => 10],
  "HMI" => [
    "x" => 31,
    "y" => 5,
    ],
  ],  

"BG32" => [ // Balises
  "element" => "BL",
  "ID" => "74:00:11:04:76",
  "U" => ["name" => "S16", "dist" => 241],
  "D" => ["name" => "S15", "dist" => 154],
  ],
  
"S16" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "BG33", "dist" => 10],
  "D" => ["name" => "BG32", "dist" => 1],
  "HMI" => [
    "x" => 34,
    "y" => 6,
    ],
  ],  

"BG33" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:DC:08",
  "U" => ["name" => "S17", "dist" => 5],
  "D" => ["name" => "S16", "dist" => 5],
  ],

"S17" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "BG34", "dist" => 1],
  "D" => ["name" => "BG33", "dist" => 10],
  "HMI" => [
    "x" => 36,
    "y" => 5,
    ],
  ],  

"BG34" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:9B:F4",
  "U" => ["name" => "B3", "dist" => 70],
  "D" => ["name" => "S17", "dist" => 131],
  ],


"B3" => [ // Bufferstop; end of direction up
  "element" => "BSE",
  "D" => ["name" => "BG34", "dist" => 75],
  "HMI" => [
    "x" => 39,
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
    "majorDevice" => 0,
    "minorDevice" => 0,
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
    "majorDevice" => 0,
    "minorDevice" => 0,
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
    "majorDevice" => 0,
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
    "majorDevice" => 1,
    "minorDevice" => 0,
    ],
  "ECbarrier" => [
    "addr" => 152,
    "type" => 32,
    "majorDevice" => 4,
    "minorDevice" => 0,
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
      "balises" => [ "BG07"
              ],
      "or" => "s",
      "x" => 7,
      "y" => 2,
      "l" => 1,
    ],
    "tr2" => [
      "balises" => ["BG08",
              ],
      "or" => "s",
      "x" => 12,
      "y" => 2,
      "l" => 1,
    ],
    "tr22" => [
      "balises" => ["BG09"
              ],
      "or" => "s",
      "x" => 17,
      "y" => 2,
      "l" => 1,
    ],
    "tr3" => [
      "balises" => ["BG12",
                    "BG14"
              ],
      "or" => "s",
      "x" => 22,
      "y" => 2,
      "l" => 1,
    ],
    "tr4" => [
      "balises" => ["VBG01"
              ],
      "or" => "s",
      "x" => 25,
      "y" => 2,
      "l" => 1,
    ],
    "tr5" => [
      "balises" => ["BG18"
              ],
      "or" => "s",
      "x" => 25,
      "y" => 6,
      "l" => 1,
    ],
    "tr6" => [
      "balises" => ["BG13", "BG15"
              ],
      "or" => "d",
      "x" => 22,
      "y" => 4,
      "l" => 1,
    ],
    "tr9" => [
      "balises" => ["BG16",
                    "BG17"
                   ],
      "or" => "s",
      "x" => 28,
      "y" => 2,
      "l" => 2,
    ],
    "tr10" => [
      "balises" => ["BG32",
              ],
      "or" => "s",
      "x" => 33,
      "y" => 6,
      "l" => 1,
    ],
    "tr11" => [
      "balises" => ["BG25"
              ],
      "or" => "s",
      "x" => 33,
      "y" => 2,
      "l" => 1,
    ],
    "tr12" => [
      "balises" => ["BG27",
              ],
      "or" => "s",
      "x" => 38,
      "y" => 2,
      "l" => 1,
    ],
    "tr13" => [
      "balises" => ["BG34",
              ],
      "or" => "s",
      "x" => 38,
      "y" => 6,
      "l" => 1,
    ],
    "tr14" => [
      "balises" => ["BG30", "BG31",
              ],
      "or" => "s",
      "x" => 30,
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

