<?php
// PT1 data for the WinterTrain at CBU

$PT1_VERSION = "CBU01P01"; 
$PT1 = [

// -------------------------------------------------------------------------------- Station at Back Wall
// ---------------------------------------------------- Track 1 (wall)
"B1" => [ // Bufferstop; begin of direction up
  "element" => "BSB",
  "U" => ["name" => "BG01", "dist" => 104],
  "HMI" => [
    "x" => 0,
    "y" => 2,
    "l" => 1,
    ],
  ],

"BG01" => [ // Balises
  "element" => "BL",
  "ID" => "76:00:0C:FA:7D",
  "U" => ["name" => "S1", "dist" => 50],
  "D" => ["name" => "B1", "dist" => 1],
  ],

"S1" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 1,
    ],
  "U" => ["name" => "BG03", "dist" => 15],
  "D" => ["name" => "BG01", "dist" => 1],
  "HMI" => [
    "x" => 1,
    "y" => 2,
    ],
  ],

"BG03" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:6E:C8:15",
  "U" => ["name" => "P1", "dist" => 1],
  "D" => ["name" => "S1", "dist" => 1],
  ],

// ---------------------------------------------------- Track 2
  
"B2" => [ // Bufferstop; begin of direction up
  "element" => "BSB",
  "U" => ["name" => "BG02", "dist" => 104],
  "HMI" => [
    "x" => 0,
    "y" => 4,
    "l" => 1,
    ],
  ],

"BG02" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:70:3D:8D",
  "U" => ["name" => "S2", "dist" => 50],
  "D" => ["name" => "B2", "dist" => 1],
  ],

"S2" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MS2",
  "EC" => [
    "addr" => 202,
    "type" => 41,
    "majorDevice" => 8,
    ],
  "U" => ["name" => "BG04", "dist" => 15],
  "D" => ["name" => "BG02", "dist" => 1],
  "HMI" => [
    "x" => 1,
    "y" => 4,
    ],
  ],

"BG04" => [ // Balises
  "element" => "BL",
  "ID" => "75:00:14:FB:94",
  "U" => ["name" => "P1", "dist" => 1],
  "D" => ["name" => "S2", "dist" => 1],
  ],

"P1" => [ // Point, trailing
  "element" => "PT",
  "EC" => [
    "addr" => 202,
    "type" => 10,
    "majorDevice" => 1,
    "minorDevice" => 0,
    ],
  "supervisionState" => "P",
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
  "ID" => "76:00:0C:E3:A8",
  "U" => ["name" => "S3", "dist" => 1],
  "D" => ["name" => "P1", "dist" => 40],
  ],


"S3" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MS2",
  "EC" => [
    "addr" => 202,
    "type" => 41,
    "majorDevice" => 7,
    ],
  "U" => ["name" => "BG07", "dist" => 1],
  "D" => ["name" => "BG05", "dist" => 1],
  "HMI" => [
    "x" => 5,
    "y" => 1,
    ],
  ],

// ------------------------------------------------------------------------------ Long side

"BG07" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:A3:40",
  "U" => ["name" => "S4", "dist" => 120],
  "D" => ["name" => "S3", "dist" => 200],
  ],
  
"S4" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MS3",
  "EC" => [
    "addr" => 202,
    "type" => 45,
    "majorDevice" => 1,
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
  "type" => "MS3",
  "EC" => [
    "addr" => 202,
    "type" => 45,
    "majorDevice" => 4,
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
  "ID" => "73:00:56:9F:A1",
  "U" => ["name" => "S24", "dist" => 180],
  "D" => ["name" => "S5", "dist" => 170],
  ],

"S24" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MS3",
  "EC" => [
    "addr" => 201,
    "type" => 44,
    "majorDevice" => 7,
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
  "type" => "MS3",
  "EC" => [
    "addr" => 201,
    "type" => 44,
    "majorDevice" => 10,
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
  "ID" => "76:00:0D:05:97",
  "U" => ["name" => "P2T", "dist" => 100],
  "D" => ["name" => "S25", "dist" => 160],
  ],

// ---------------------------------------------- Main Station

"P2T" => [ // Balises
  "element" => "PHTU",
  "U" => ["name" => "S6", "dist" => 20],
  "D" => ["name" => "BG09", "dist" => 1],
  "holdPoint" => "P2",
  ],

"S6" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MS3",
  "EC" => [
    "addr" => 201,
    "type" => 44,
    "majorDevice" => 14,
    ],
  "U" => ["name" => "BG10", "dist" => 20],
  "D" => ["name" => "P2T", "dist" => 1],
  "HMI" => [
    "x" => 18,
    "y" => 2,
    ],
  ], 

"BG10" => [ // Balises
  "element" => "BL",
  "ID" => "74:00:11:09:64",
  "U" => ["name" => "P2", "dist" => 10],
  "D" => ["name" => "S6", "dist" => 10],
  ],
  
"P2" => [ // Point, Facing
  "element" => "PF",
  "EC" => [
    "addr" => 201,
    "type" => 10,
    "majorDevice" => 2,
    "minorDevice" => 0,
    ],
  "supervisionState" => "P",
  "R" => ["name" => "BG13", "dist" => 40],
  "L" => ["name" => "BG12", "dist" => 40],
  "T" => ["name" => "BG10", "dist" => 1],
  "HMI" => [
    "or" => "fr",
    "x" => 20,
    "y" => 2,
    ],
  ],
// ---------------------------------------------------------- Inner Track 

"BG12" => [ // Balises
  "element" => "BL",
  "ID" => "76:00:0C:A4:29",
  "U" => ["name" => "S7", "dist" => 15],
  "D" => ["name" => "P2", "dist" => 2],
  ],
 
 "S7" => [ // Signal
  "element" => "SD",
  "type" => "MS2",
  "EC" => [
    "addr" => 201,
    "type" => 41,
    "majorDevice" => 3,
    ],
  "U" => ["name" => "VBG01", "dist" => 1],
  "D" => ["name" => "BG12", "dist" => 1],
  "HMI" => [
    "x" => 23,
    "y" => 1,
    ],
  ],

"VBG01" => [ // Virtual Balises (Does not exist on the track, it acts as a track segment)
  "element" => "BL",
  "ID" => "99:99:99:99:01",
  "U" => ["name" => "S9", "dist" => 135],
  "D" => ["name" => "S7", "dist" => 135],
  ],

 "S9" => [ // Signal
  "element" => "SU",
  "type" => "MS2",
  "EC" => [
    "addr" => 201,
    "type" => 43,
    "majorDevice" => 1,
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
  "ID" => "73:00:56:F0:55",
  "U" => ["name" => "P3", "dist" => 10],
  "D" => ["name" => "S9", "dist" => 10],
  ],
  

"P3" => [ // Point, trailing
  "element" => "PT",
  "EC" => [
    "addr" => 201,
    "type" => 10,
    "majorDevice" => 1,
    "minorDevice" => 0,
    ],
  "supervisionState" => "P",
  "R" => ["name" => "BG16", "dist" => 46],
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
  "ID" => "76:00:0D:0B:F7",
  "U" => ["name" => "S11", "dist" => 10],
  "D" => ["name" => "P3", "dist" => 5],
  ],


 "S11" => [ // Signal
  "element" => "SD",
  "type" => "MS3",
  "EC" => [
    "addr" => 201,
    "type" => 44,
    "majorDevice" => 4,
    ],
  "U" => ["name" => "P3T", "dist" => 1],
  "D" => ["name" => "BG21", "dist" => 1],
  "HMI" => [
    "x" => 31,
    "y" => 1,
    ],
  ],
 
// --------------------------------------------------------------- Outer Track

"BG13" => [ // Balises
  "element" => "BL",
  "ID" => "76:00:0D:1A:2E",
  "U" => ["name" => "S8", "dist" => 9],
  "D" => ["name" => "P2", "dist" => 2],
  ],

 "S8" => [ // Signal
  "element" => "SD",
  "type" => "MS2",
  "EC" => [
    "addr" => 201,
    "type" => 41,
    "majorDevice" => 4,
    ],
  "U" => ["name" => "BG18", "dist" => 1],
  "D" => ["name" => "BG13", "dist" => 1],
  "HMI" => [
    "x" => 23,
    "y" => 5,
    ],
  ],

"BG18" => [ // Balises
  "element" => "BL",
  "ID" => "99:99:99:99:99",
  "U" => ["name" => "S10", "dist" => 90],
  "D" => ["name" => "S8", "dist" => 90],
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
  "ID" => "74:00:15:55:26",
  "U" => ["name" => "P4", "dist" => 3],
  "D" => ["name" => "S10", "dist" => 1],
  ],


"P4" => [ // Point, Facing
  "element" => "PF",
  "EC" => [
    "addr" => 201,
    "type" => 10,
    "majorDevice" => 3,
    "minorDevice" => 0,
    ],
  "supervisionState" => "P",
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
  "ID" => "74:00:15:50:C0",
  "U" => ["name" => "P3", "dist" => 6],
  "D" => ["name" => "P4", "dist" => 2],
  ],


// ----------------------------------------------------------------- Inside Bar

"P3T" => [ // Point Hold Trigger
  "element" => "PHTD",
  "U" => ["name" => "BG24", "dist" => 1],
  "D" => ["name" => "S11", "dist" => 1],
  "holdPoint" => "P3",
  ],


"BG24" => [ // Balises
  "element" => "BL",
  "ID" => "76:00:0C:FC:CB",
  "U" => ["name" => "BG25", "dist" => 125],
  "D" => ["name" => "P3T", "dist" => 125],
  ],
  


"BG25" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:70:98:69",
  "U" => ["name" => "S13", "dist" => 211],
  "D" => ["name" => "BG24", "dist" => 125],
  ],

"S13" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MS2",
  "EC" => [
    "addr" => 203,
    "type" => 41,
    "majorDevice" => 3,
    ],
  "U" => ["name" => "BG28", "dist" => 1],
  "D" => ["name" => "BG25", "dist" => 1],
  "HMI" => [
    "x" => 34,
    "y" => 2,
    ],
  ],


"BG28" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:D9:B2",
  "U" => ["name" => "S14", "dist" => 2],
  "D" => ["name" => "S13", "dist" => 2],
  ],

"S14" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MS2",
  "EC" => [
    "addr" => 203,
    "type" => 41,
    "majorDevice" => 4,
    ],
  "U" => ["name" => "BG29", "dist" => 1],
  "D" => ["name" => "BG28", "dist" => 1],
  "HMI" => [
    "x" => 36,
    "y" => 1,
    ],
  ],


"BG29" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:D6:F2",
  "U" => ["name" => "B4", "dist" => 25],
  "D" => ["name" => "S14", "dist" => 70],
  ],

"B4" => [ // Bufferstop; end of direction up
  "element" => "BSE",
  "D" => ["name" => "BG29", "dist" => 70],
  "HMI" => [
    "x" => 39,
    "y" => 2,
    "l" => 1,
    ],
  ],

// ----------------------------------------------------------------- Outside Bar

"BG30" => [ // Balises
  "element" => "BL",
  "ID" => "74:00:15:65:29",
  "U" => ["name" => "S15", "dist" => 10],
  "D" => ["name" => "P4", "dist" => 3],
  ],
  
  
"S15" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MS3",
  "EC" => [
    "addr" => 201,
    "type" => 44,
    "majorDevice" => 1,
    ],
  "U" => ["name" => "BG32", "dist" => 1],
  "D" => ["name" => "BG30", "dist" => 10],
  "HMI" => [
    "x" => 31,
    "y" => 5,
    ],
  ],  

"BG32" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:C0:72",
  "U" => ["name" => "S16", "dist" => 225],
  "D" => ["name" => "S15", "dist" => 200],
  ],
  
"S16" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MS2",
  "EC" => [
    "addr" => 203,
    "type" => 41,
    "majorDevice" => 1,
    ],
  "U" => ["name" => "BG33", "dist" => 1],
  "D" => ["name" => "BG32", "dist" => 1],
  "HMI" => [
    "x" => 34,
    "y" => 6,
    ],
  ],  

"BG33" => [ // Balises
  "element" => "BL",
  "ID" => "74:00:10:F3:3E",
  "U" => ["name" => "S17", "dist" => 5],
  "D" => ["name" => "S16", "dist" => 5],
  ],

"S17" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MS2",
  "EC" => [
    "addr" => 203,
    "type" => 41,
    "majorDevice" => 2,
    ],
  "U" => ["name" => "BG34", "dist" => 1],
  "D" => ["name" => "BG33", "dist" => 1],
  "HMI" => [
    "x" => 36,
    "y" => 5,
    ],
  ],  

"BG34" => [ // Balises
  "element" => "BL",
  "ID" => "73:00:56:93:AA",
  "U" => ["name" => "B3", "dist" => 70],
  "D" => ["name" => "S17", "dist" => 175],
  ],


"B3" => [ // Bufferstop; end of direction up
  "element" => "BSE",
  "D" => ["name" => "BG34", "dist" => 30],
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
  "supervisionState" => "",
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
  "supervisionState" => "",
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
    "addr" => 0,
    "type" => 31,
    "majorDevice" => 1,
    "minorDevice" => 0,
    ],
  "ECbarrier" => [
    "addr" => 0,
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
      "balises" => ["BG13"
              ],
      "or" => "d",
      "x" => 22,
      "y" => 4,
      "l" => 1,
    ],
    "tr9" => [
      "balises" => ["BG16",
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
      "balises" => ["BG24", "BG25"
              ],
      "or" => "s",
      "x" => 33,
      "y" => 2,
      "l" => 1,
    ],
    "tr12" => [
      "balises" => ["BG29",
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
      "balises" => ["BG30",
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
//    "aColor" => "orange",
//    "fColor" => "blue",
//    "oColor" => "lightgreen",
//    "cColor" => "red",
  ],
];

?>

