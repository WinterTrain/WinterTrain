<?php
// PT1 data for the WinterTrain at CBU

$PT1_VERSION = "CBU2019_01P01"; 
$PT1 = [

// ----------------------------------------------------------------------------------------------------------------------------- Station A

"BS1" => [ // Bufferstop; begin of direction up
  "element" => "BSB",
  "U" => ["name" => "S2", "dist" => 0],
  "HMI" => [
    "offset" => "A",
    "x" => 0,
    "y" => 0,
    "l" => 1,
    ],
  ],

"S2" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "P1", "dist" => 0],
  "D" => ["name" => "BS1", "dist" => 0],
  "HMI" => [
    "offset" => "A",
    "x" => 1,
    "y" => 0,
    ],
  ],

"BS3" => [ // Bufferstop; begin of direction up
  "element" => "BSB",
  "U" => ["name" => "S4", "dist" => 0],
  "HMI" => [
    "offset" => "A",
    "x" => 0,
    "y" => 2,
    "l" => 1,
    ],
  ],

"S4" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "P1", "dist" => 0],
  "D" => ["name" => "BS3", "dist" => 0],
  "HMI" => [
    "offset" => "A",
    "x" => 1,
    "y" => 2,
    ],
  ],

"P1" => [ // Point, trailing
  "element" => "PT",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    "minorDevice" => 0,
    ],
  "supervisionState" => "S",
  "R" => ["name" => "S2", "dist" => 0],
  "L" => ["name" => "S4", "dist" => 0],
  "T" => ["name" => "S1", "dist" => 0],
  "HMI" => [
    "offset" => "A",
    "or" => "tr",
    "x" => 3,
    "y" => 0,
    ],
  ],

"S1" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "S24", "dist" => 0],
  "D" => ["name" => "P1", "dist" => 0],
  "HMI" => [
    "offset" => "A",
    "x" => 5,
    "y" => 1,
    "l" => 1
    ],
  ],

"S24" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "S21", "dist" => 0],
  "D" => ["name" => "S1", "dist" => 0],
  "HMI" => [
    "offset" => "A",
    "x" => 8,
    "y" => 2,
    "l" => 1
    ],
  ], 

"S21" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "S6", "dist" => 0],
  "D" => ["name" => "S24", "dist" => 0],
  "HMI" => [
    "offset" => "A",
    "x" => 9,
    "y" => 1,
    "l" => 1
    ],
  ], 
// ---------------------------------------------------------------------------------------------------------------------- Station B

"S6" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "P2", "dist" => 0],
  "D" => ["name" => "S21", "dist" => 0],
  "HMI" => [
    "offset" => "B",
    "x" => 0,
    "y" => 0,
    "l" => 1
    ],
  ],

"P2" => [ // Point, Facing
  "element" => "PF",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    "minorDevice" => 0,
    ],
  "supervisionState" => "S",
  "R" => ["name" => "S5", "dist" => 0],
  "L" => ["name" => "S3", "dist" => 0],
  "T" => ["name" => "S6", "dist" => 0],
  "HMI" => [
    "offset" => "B",
    "or" => "fr",
    "x" => 1,
    "y" => 0,
    ],
  ],

"S3" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "S8", "dist" => 0],
  "D" => ["name" => "P2", "dist" => 0],
  "HMI" => [
    "offset" => "B",
    "x" => 3,
    "y" => -1,
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
  "U" => ["name" => "S10", "dist" => 0],
  "D" => ["name" => "P2", "dist" => 0],
  "HMI" => [
    "offset" => "B",
    "x" => 3,
    "y" => 1,
    ],
  ],
  
"S8" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "P3", "dist" => 0],
  "D" => ["name" => "S3", "dist" => 0],
  "HMI" => [
    "offset" => "B",
    "x" => 6,
    "y" => 0,
    ],
  ],

"S10" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "P3", "dist" => 0],
  "D" => ["name" => "S5", "dist" => 0],
  "HMI" => [
    "offset" => "B",
    "x" => 6,
    "y" => 2,
    ],
  ],

"P3" => [ // Point, trailing
  "element" => "PT",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    "minorDevice" => 0,
    ],
  "supervisionState" => "S",
  "R" => ["name" => "S8", "dist" => 0],
  "L" => ["name" => "S10", "dist" => 0],
  "T" => ["name" => "S7", "dist" => 0],
  "HMI" => [
    "offset" => "B",
    "or" => "tl",
    "x" => 8,
    "y" => 0,
    ],
  ],

"S7" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "S34", "dist" => 0],
  "D" => ["name" => "P3", "dist" => 0],
  "HMI" => [
    "offset" => "B",
    "x" => 9,
    "y" => -1,
    "l" => 1
    ],
  ],

"S34" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "S31", "dist" => 0],
  "D" => ["name" => "S7", "dist" => 0],
  "HMI" => [
    "offset" => "B",
    "x" => 12,
    "y" => 0,
    "l" => 1
    ],
  ], 

"S31" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "S16", "dist" => 0],
  "D" => ["name" => "S34", "dist" => 0],
  "HMI" => [
    "offset" => "B",
    "x" => 13,
    "y" => -1,
    "l" => 1
    ],
  ], 
  
// ---------------------------------------------------------------------------------------------------------------------- Station C 
  
"BS5" => [ // Bufferstop; begin of direction up
  "element" => "BSB",
  "U" => ["name" => "S12", "dist" => 0],
  "HMI" => [
    "offset" => "C",
    "x" => 0,
    "y" => 0,
    "l" => 1,
    ],
  ],

"S12" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "S14", "dist" => 0],
  "D" => ["name" => "BS5", "dist" => 0],
  "HMI" => [
    "offset" => "C",
    "x" => 1,
    "y" => 0,
    ],
  ],  
// ---------------------------------------------------------------------------------------------------------------------- Station D  
  "S14" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "P5", "dist" => 0],
  "D" => ["name" => "S12", "dist" => 0],
  "HMI" => [
    "offset" => "D",
    "x" => 1,
    "y" => 0,
    ],
  ],
    
  "S16" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "P4", "dist" => 0],
  "D" => ["name" => "S31", "dist" => 0],
  "HMI" => [
    "offset" => "D",
    "x" => 0,
    "y" => 4,
    "l" => 1
    ],
  ],

"P4" => [ // Point, Facing
  "element" => "PF",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    "minorDevice" => 0,
    ],
  "supervisionState" => "S",
  "R" => ["name" => "S11", "dist" => 0],
  "L" => ["name" => "P5", "dist" => 0],
  "T" => ["name" => "S16", "dist" => 0],
  "HMI" => [
    "offset" => "D",
    "or" => "fl",
    "x" => 1,
    "y" => 2,
    ],
  ],
  
"P5" => [ // Point, trailing
  "element" => "PT",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    "minorDevice" => 0,
    ],
  "supervisionState" => "S",
  "R" => ["name" => "S14", "dist" => 0],
  "L" => ["name" => "P4", "dist" => 0],
  "T" => ["name" => "S9", "dist" => 0],
  "HMI" => [
    "offset" => "D",
    "or" => "tl",
    "x" => 3,
    "y" => 0,
    ],
  ],

"S9" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "S18", "dist" => 0],
  "D" => ["name" => "P5", "dist" => 0],
  "HMI" => [
    "offset" => "D",
    "x" => 4,
    "y" => -1,
    "l" => 1
    ],
  ],
  
"S11" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "S20", "dist" => 0],
  "D" => ["name" => "P4", "dist" => 0],
  "HMI" => [
    "offset" => "D",
    "x" => 3,
    "y" => 3,
    ],
  ],
  
"S18" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "VBG1", "dist" => 0],
  "D" => ["name" => "S9", "dist" => 0],
  "HMI" => [
    "offset" => "D",
    "x" => 6,
    "y" => 0,
    ],
  ],
"VBG1" => [ // Balises
  "element" => "BL",
  "ID" => "",
  "U" => ["name" => "P6", "dist" => 0],
  "D" => ["name" => "S18", "dist" => 0],
  ],
  
"S20" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "P6", "dist" => 0],
  "D" => ["name" => "S11", "dist" => 0],
  "HMI" => [
    "offset" => "D",
    "x" => 7,
    "y" => 4,
    ],
  ],

"P6" => [ // Point, trailing
  "element" => "PT",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    "minorDevice" => 0,
    ],
  "supervisionState" => "S",
  "R" => ["name" => "VBG1", "dist" => 0],
  "L" => ["name" => "S20", "dist" => 0],
  "T" => ["name" => "S13", "dist" => 0],
  "HMI" => [
    "offset" => "D",
    "or" => "tr",
    "x" => 9,
    "y" => 2,
    ],
  ],

"S13" => [ // Signal, facing and ...
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "BS2", "dist" => 0],
  "D" => ["name" => "P6", "dist" => 0],
  "HMI" => [
    "offset" => "D",
    "x" => 11,
    "y" => 3,
    "l" => 1
    ],
  ],

"BS2" => [ // Bufferstop; end of direction up
  "element" => "BSE",
  "D" => ["name" => "S13", "dist" => 0],
  "HMI" => [
    "offset" => "D",
    "x" => 13,
    "y" => 4,
    "l" => 1,
    ],
  ],


// -------------------------------------------------------------------------------------- Templates
"" => [ // Bufferstop; begin of direction up
  "element" => "BSB",
  "U" => ["name" => "", "dist" => 0],
  "HMI" => [
    "offset" => "",
    "x" => 0,
    "y" => 0,
    "l" => 0,
    ],
  ],
  
"" => [ // Bufferstop; end of direction up
  "element" => "BSE",
  "D" => ["name" => "", "dist" => 0],
  "HMI" => [
    "offset" => "",
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
    "offset" => "",
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
    "offset" => "",
    "or" => "",
    "x" => 0,
    "y" => 0,
    ],
  ],
  
"" => [ // Signal, facing and ...
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "", "dist" => 0],
  "D" => ["name" => "", "dist" => 0],
  "HMI" => [
    "offset" => "",
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
    "offset" => "",
    "x" => 25,
    "y" => 1,
    ],
  ],
];

//------------------------------------------------------------------ HMI

$HMIoffset = [
  "A" => ["x" => 0, "y" => 4],
  "B" => ["x" => 12, "y" => 6],
  "C" => ["x" => 21, "y" => 2],
  "D" => ["x" => 28, "y" => 2],
];

$HMI = [ 
  "baliseTrack" => [

    "tr1" => [
      "balises" => [ "VBG1"
              ],
      "offset" => "A",
      "or" => "s",
      "x" => 6,
      "y" => 2,
      "l" => 1,
    ],
    "tr21" => [
      "balises" => [ "VBG1"
              ],
      "offset" => "A",
      "or" => "s",
      "x" => 7,
      "y" => 2,
      "l" => 1,
    ],
    "tr22" => [
      "balises" => [ "VBG1"
              ],
      "offset" => "A",
      "or" => "s",
      "x" => 10,
      "y" => 2,
      "l" => 1,
    ],
   "tr23" => [
      "balises" => [ "VBG1"
              ],
      "offset" => "A",
      "or" => "s",
      "x" => 11,
      "y" => 2,
      "l" => 1,
    ],    "" => [ // tr2
      "balises" => [ "VBG1"
              ],
      "offset" => "A",
      "or" => "s",
      "x" => 11,
      "y" => 0,
      "l" => 1,
    ],
    "tr3" => [
      "balises" => [ "VBG1"
              ],
      "offset" => "B",
      "or" => "s",
      "x" => 5,
      "y" => 0,
      "l" => 1,
    ],
    "tr4" => [
      "balises" => [ "VBG1"
              ],
      "offset" => "B",
      "or" => "s",
      "x" => 5,
      "y" => 2,
      "l" => 1,
    ],
    "tr5" => [
      "balises" => [ "VBG1"
              ],
      "offset" => "B",
      "or" => "s",
      "x" => 10,
      "y" => 0,
      "l" => 1,
    ],
    "tr35" => [
      "balises" => [ "VBG1"
              ],
      "offset" => "B",
      "or" => "s",
      "x" => 11,
      "y" => 0,
      "l" => 1,
    ],
    "tr36" => [
      "balises" => [ "VBG1"
              ],
      "offset" => "B",
      "or" => "s",
      "x" => 14,
      "y" => 0,
      "l" => 1,
    ],
    "tr37" => [
      "balises" => [ "VBG1"
              ],
      "offset" => "B",
      "or" => "s",
      "x" => 15,
      "y" => 0,
      "l" => 1,
    ],
    "tr6" => [
      "balises" => [ "VBG1"
              ],
      "offset" => "D",
      "or" => "s",
      "x" => 5,
      "y" => 0,
      "l" => 1,
    ],
    "tr7" => [
      "balises" => [ "VBG1"
              ],
      "offset" => "D",
      "or" => "s",
      "x" => 5,
      "y" => 4,
      "l" => 2,
    ],
    "tr8" => [
      "balises" => [ "VBG1"
              ],
      "offset" => "D",
      "or" => "s",
      "x" => 12,
      "y" => 4,
      "l" => 1,
    ],
    "tr9" => [
      "balises" => [ "VBG1"
              ],
      "offset" => "C",
      "or" => "s",
      "x" => 3,
      "y" => 0,
      "l" => 6,
    ],
    "tr10" => [
      "balises" => [ "VBG1"
              ],
      "offset" => "D",
      "or" => "d",
      "x" => 8,
      "y" => 0,
      "l" => 1,
    ],


// Template
    "" => [
      "balises" => [ ""
              ],
      "offset" => "",
      "or" => "s",
      "x" => 0,
      "y" => 0,
      "l" => 0,
    ],

  ],
  
  
// Lables
  
  "label" => [
    [
      "x" => 2,
      "y" => 3,
      "text" => "Station A"
    ],
    [
      "x" => 17,
      "y" => 10,
      "text" => "Station B"
    ],
    [
      "x" => 23,
      "y" => 1,
      "text" => "Station C"
    ],
    [
      "x" => 34,
      "y" => 8,
      "text" => "Station D"
    ],

  ],
  "scale" => "45",
  "eStopIndicator" => [
    "x" => 1,
    "y" => 2
  ],
  "arsIndicator" => [
    "x" => 1,
    "y" => 3
  ],
  "color" => [
    "aColor" => "orange",
    "fColor" => "blue",
    "oColor" => "lightgreen",
    "cColor" => "red",
  ],
];

?>

