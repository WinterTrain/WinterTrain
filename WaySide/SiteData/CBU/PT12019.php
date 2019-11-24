<?php
// PT1 data for the WinterTrain at CBU

$PT1_VERSION = "CBU2019_01P02";
$PT1 = [

// ----------------------------------------------------------------------------------------------------------------------------- Station A
//----------------------------------------------------------------- Inner track
"BS1" => [ // Bufferstop; begin of direction up            BS1
  "element" => "BSB",
  "U" => ["name" => "BG15", "dist" => 65],
  "HMI" => [
    "offset" => "A",
    "x" => 0,
    "y" => 0,
    "l" => 1,
    ],
  ],
"BG15" => [ // Balises                                     BG15
  "element" => "BL",
  "ID" => "FF:FF:FF:FF:FF",
  "U" => ["name" => "S2", "dist" => 20],
  "D" => ["name" => "BS1", "dist" => 50],
  ],
"S2" => [ // Signal                                        S2
  "element" => "SU",
  "type" => "MS2",
  "EC" => [
    "addr" => 203,
    "type" => 41,
    "majorDevice" => 6,
    ],
  "U" => ["name" => "BG16", "dist" => 10],
  "D" => ["name" => "BG15", "dist" => 1],
  "HMI" => [
    "offset" => "A",
    "x" => 1,
    "y" => 0,
    ],
  ],
"BG16" => [ // Balises                                      BG16
  "element" => "BL",
  "ID" => "1E:00:EC:F9:E1",
  "U" => ["name" => "BG17", "dist" => 10],
  "D" => ["name" => "S2", "dist" => 1],
  ],
"BG17" => [ // Balises                                      BG17
  "element" => "BL",
  "ID" => "1F:00:61:E5:87",
  "U" => ["name" => "P1", "dist" => 1],
  "D" => ["name" => "BG16", "dist" => 10],
  ],
//------------------------------------------------------------------------- Outer track
"BS3" => [ // Bufferstop; begin of direction up            BS3
  "element" => "BSB",
  "U" => ["name" => "BG18", "dist" => 65],
  "HMI" => [
    "offset" => "A",
    "x" => 0,
    "y" => 2,
    "l" => 1,
    ],
  ],
"BG18" => [ // Balises                                      BG18
  "element" => "BL",
  "ID" => "FF:FF:FF:FF:FF",
  "U" => ["name" => "S4", "dist" => 20],
  "D" => ["name" => "BS3", "dist" => 50],
  ],
"S4" => [ // Signal                                         S4
  "element" => "SU",
  "type" => "MS2",
  "EC" => [
    "addr" => 203,
    "type" => 41,
    "majorDevice" => 7,
    ],
  "U" => ["name" => "BG19", "dist" => 10],
  "D" => ["name" => "BG18", "dist" => 1],
  "HMI" => [
    "offset" => "A",
    "x" => 1,
    "y" => 2,
    ],
  ],
"BG19" => [ // Balises                                      BG19
  "element" => "BL",
  "ID" => "1F:00:84:7C:A5",
  "U" => ["name" => "BG20", "dist" => 5],
  "D" => ["name" => "S4", "dist" => 1],
  ],
"BG20" => [ // Balises                                      BG20
  "element" => "BL",
  "ID" => "1E:00:90:0C:A5",
  "U" => ["name" => "P1", "dist" => 1],
  "D" => ["name" => "BG19", "dist" => 5],
  ],
//------------------------------------------------------------------------------- Point
"P1" => [ // Point, trailing                                 P1
  "element" => "PT",
  "EC" => [
    "addr" => 202,
    "type" => 10,
    "majorDevice" => 3,
    "minorDevice" => 0,
    ],
  "supervisionState" => "P",
  "R" => ["name" => "BG17", "dist" => 30],
  "L" => ["name" => "BG20", "dist" => 30],
  "T" => ["name" => "S1", "dist" => 5],
  "HMI" => [
    "offset" => "A",
    "or" => "tr",
    "x" => 3,
    "y" => 0,
    ],
  ],
//-------------------------------------------------------------------------------
"S1" => [ // Signal                                          S1
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "BG21", "dist" => 1],
  "D" => ["name" => "P1", "dist" => 5],
  "HMI" => [
    "offset" => "A",
    "x" => 5,
    "y" => 1,
    "l" => 1
    ],
  ],
"BG21" => [ // Balises                                      BG21
  "element" => "BL",
  "ID" => "1E:00:98:B1:C9",
  "U" => ["name" => "BG22", "dist" => 90],
  "D" => ["name" => "S1", "dist" => 10],
  ],
"BG22" => [ // Balises                                      BG22
  "element" => "BL",
  "ID" => "76:00:0C:E3:A8",
  "U" => ["name" => "BG40", "dist" => 60],
  "D" => ["name" => "BG21", "dist" => 90],
  ],
"BG40" => [ // Balises                                      BG40
  "element" => "BL",
  "ID" => "76:00:0C:FA:7D",
  "U" => ["name" => "S6", "dist" => 250],
  "D" => ["name" => "BG22", "dist" => 60],
  ],
"S6" => [ // Signal                                        S6
  "element" => "SU",
  "type" => "MS2",
  "EC" => [
    "addr" => 203,
    "type" => 41,
    "majorDevice" => 8,
    ],
  "U" => ["name" => "BG41", "dist" => 10],
  "D" => ["name" => "BG40", "dist" => 1],
  "HMI" => [
    "offset" => "A",
    "x" => 8,
    "y" => 2,
    "l" => 1
    ],
  ], 
"BG41" => [ // Balises                                    BG41
  "element" => "BL",
  "ID" => "73:00:56:9F:A1",
  "U" => ["name" => "S3", "dist" => 1],
  "D" => ["name" => "S6", "dist" => 1],
  ],
"S3" => [ // Signal                                        S3
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "BG23", "dist" => 1],
  "D" => ["name" => "BG41", "dist" => 10],
  "HMI" => [
    "offset" => "A",
    "x" => 9,
    "y" => 1,
    "l" => 1
    ],
  ], 
"BG23" => [ // Balises                                      BG23
  "element" => "BL",
  "ID" => "1E:00:8E:57:F6",
  "U" => ["name" => "PH2", "dist" => 10],
  "D" => ["name" => "S3", "dist" => 270],
  ],
"PH2" => [ //  Enforced Point Holding Up
  "element" => "PHTU",
  "U" => ["name" => "BG24", "dist" => 20],
  "D" => ["name" => "BG23", "dist" => 1],
  "holdPoint" => "P2",
  ],  
"BG24" => [ // Balises                                      BG24
  "element" => "BL",
  "ID" => "1E:00:57:35:F9",
  "U" => ["name" => "S8", "dist" => 10],
  "D" => ["name" => "PH2", "dist" => 50],
  ],
// ---------------------------------------------------------------------------------------------------------------------- Station B

"S8" => [ // Signal                                         S8
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "P2", "dist" => 10],
  "D" => ["name" => "BG24", "dist" => 1],
  "HMI" => [
    "offset" => "B",
    "x" => 0,
    "y" => 0,
    "l" => 1
    ],
  ],
//------------------------------------------------------------------------ Point
"P2" => [ // Point, Facing                                  P2
  "element" => "PF",
  "EC" => [
    "addr" => 201,
    "type" => 10,
    "majorDevice" => 1,
    "minorDevice" => 0,
    ],
  "supervisionState" => "P",
  "R" => ["name" => "BG30", "dist" => 30],
  "L" => ["name" => "BG25", "dist" => 30],
  "T" => ["name" => "S8", "dist" => 1],
  "HMI" => [
    "offset" => "B",
    "or" => "fr",
    "x" => 1,
    "y" => 0,
    ],
  ],
//------------------------------------------------------------------------ Inner track
"BG25" => [ // Balises                                      BG25
  "element" => "BL",
  "ID" => "1F:00:69:A9:3C",
  "U" => ["name" => "BG26", "dist" => 5],
  "D" => ["name" => "P2", "dist" => 1],
  ],
"BG26" => [ // Balises                                      BG26
  "element" => "BL",
  "ID" => "1E:00:8E:DF:1F",
  "U" => ["name" => "S5", "dist" => 1],
  "D" => ["name" => "BG25", "dist" => 5],
  ],
"S5" => [ // Signal                                         S5
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "BG27", "dist" => 1],
  "D" => ["name" => "BG26", "dist" => 10],
  "HMI" => [
    "offset" => "B",
    "x" => 3,
    "y" => -1,
    ],
  ],
"BG27" => [ // Balises                                      BG27
  "element" => "BL",
  "ID" => "FF:FF:FF:FF:FF",
  "U" => ["name" => "S10", "dist" => 140],
  "D" => ["name" => "S5", "dist" => 140],
  ],
"S10" => [ // Signal                                        S10
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "BG28", "dist" => 10],
  "D" => ["name" => "BG27", "dist" => 1],
  "HMI" => [
    "offset" => "B",
    "x" => 6,
    "y" => 0,
    ],
  ],
"BG28" => [ // Balises                                      BG28
  "element" => "BL",
  "ID" => "1F:00:62:74:36",
  "U" => ["name" => "BG29", "dist" => 5],
  "D" => ["name" => "S10", "dist" => 1],
  ],
"BG29" => [ // Balises                                      BG29
  "element" => "BL",
  "ID" => "1E:00:B0:50:33",
  "U" => ["name" => "P3", "dist" => 1],
  "D" => ["name" => "BG28", "dist" => 5],
  ],  
//---------------------------------------------------------------------------- Outer track
"BG30" => [ // Balises                                      BG30
  "element" => "BL",
  "ID" => "",
  "U" => ["name" => "BG31", "dist" => 5],
  "D" => ["name" => "P2", "dist" => 1],
  ],
"BG31" => [ // Balises                                      BG31
  "element" => "BL",
  "ID" => "",
  "U" => ["name" => "S7", "dist" => 1],
  "D" => ["name" => "BG30", "dist" => 5],
  ],
"S7" => [ // Signal                                         S7
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
    "offset" => "B",
    "x" => 3,
    "y" => 1,
    ],
  ],
"BG32" => [ // Balises                                      BG32
  "element" => "BL",
  "ID" => "",
  "U" => ["name" => "S12", "dist" => 70],
  "D" => ["name" => "S7", "dist" => 70],
  ],
"S12" => [ // Signal                                        S12
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
    "offset" => "B",
    "x" => 6,
    "y" => 2,
    ],
  ],
"BG33" => [ // Balises                                      BG33
  "element" => "BL",
  "ID" => "",
  "U" => ["name" => "BG34", "dist" => 5],
  "D" => ["name" => "S12", "dist" => 1],
  ],
"BG34" => [ // Balises                                      BG34
  "element" => "BL",
  "ID" => "",
  "U" => ["name" => "P3", "dist" => 1],
  "D" => ["name" => "BG33", "dist" => 5],
  ],
//----------------------------------------------------------------------------- Point
"P3" => [ // Point, trailing                                P3
  "element" => "PT",
  "EC" => [
    "addr" => 201,
    "type" => 10,
    "majorDevice" => 2,
    "minorDevice" => 0,
    ],
  "supervisionState" => "P",
  "R" => ["name" => "BG29", "dist" => 30],
  "L" => ["name" => "BG34", "dist" => 30],
  "T" => ["name" => "S9", "dist" => 10],
  "HMI" => [
    "offset" => "B",
    "or" => "tl",
    "x" => 8,
    "y" => 0,
    ],
  ],
//-------------------------------------------------------------------------
"S9" => [ // Signal                                          S9
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "BG35", "dist" => 1],
  "D" => ["name" => "P3", "dist" => 10],
  "HMI" => [
    "offset" => "B",
    "x" => 9,
    "y" => -1,
    "l" => 1
    ],
  ],
"BG35" => [ // Balises                                      BG35
  "element" => "BL",
  "ID" => "1E:00:8E:70:11",
  "U" => ["name" => "PH3", "dist" => 50],
  "D" => ["name" => "S9", "dist" => 10],
  ],
"PH3" => [ //  Enforced Point Holding Up
  "element" => "PHTU",
  "U" => ["name" => "BG36", "dist" => 1],
  "D" => ["name" => "BG35", "dist" => 50],
  "holdPoint" => "P3",
  ],
"BG36" => [ // Balises                                      BG36
  "element" => "BL",
  "ID" => "1F:00:69:C9:3C",
  "U" => ["name" => "BG42", "dist" => 100],
  "D" => ["name" => "PH3", "dist" => 1],
  ],
"BG42" => [ // Balises                                      BG42
  "element" => "BL",
  "ID" => "73:00:56:D9:B2",
  "U" => ["name" => "BG44", "dist" => 40],
  "D" => ["name" => "BG36","dist" => 160],
  ],
"BG44" => [ // Balises                                      BG44
  "element" => "BL",
  "ID" => "73:00:56:D6:F2",
  "U" => ["name" => "S16", "dist" => 255],
  "D" => ["name" => "BG42","dist" => 40],
  ],
"S16" => [ // Signal                                        S16
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "S11", "dist" => 10],
  "D" => ["name" => "BG44", "dist" => 1],
  "HMI" => [
    "offset" => "B",
    "x" => 12,
    "y" => 0,
    "l" => 1
    ],
  ], 
"S11" => [ // Signal                                         S11
  "element" => "SD",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "BG37", "dist" => 1],
  "D" => ["name" => "S16", "dist" => 10],
  "HMI" => [
    "offset" => "B",
    "x" => 13,
    "y" => -1,
    "l" => 1
    ],
  ], 
"BG37" => [ // Balises                                      BG37
  "element" => "BL",
  "ID" => "75:00:14:FB:94",
  "U" => ["name" => "BG38", "dist" => 270],
  "D" => ["name" => "S11", "dist" => 60],
  ],
"BG38" => [ // Balises                                      BG38
  "element" => "BL",
  "ID" => "1F:00:69:F3:BB",
  "U" => ["name" => "BG43", "dist" => 50],
  "D" => ["name" => "BG37", "dist" => 270],
  ],
"BG43" => [ // Balises                                      BG43
  "element" => "BL",
  "ID" => "1F:00:4A:F2:D2",
  "U" => ["name" => "S20", "dist" => 10],
  "D" => ["name" => "BG38", "dist" => 50],
  ],
// ---------------------------------------------------------------------------------------------------------------------- Station C 
  
"BS5" => [ // Bufferstop; begin of direction up         BS05
  "element" => "BSB",
  "U" => ["name" => "BG39", "dist" => 1],
  "HMI" => [
    "offset" => "C",
    "x" => 0,
    "y" => 0,
    "l" => 1,
    ],
  ],
"BG39" => [ // Balises                                      BG39
  "element" => "BL",
  "ID" => "",
  "U" => ["name" => "S14", "dist" => 1],
  "D" => ["name" => "BS5", "dist" => 1],
  ],
"S14" => [ // Signal                                        S14
  "element" => "SU",
  "type" => "MS2",
  "EC" => [
    "addr" => 202,
    "type" => 41,
    "majorDevice" => 7,
    ],
  "U" => ["name" => "S13", "dist" => 10],
  "D" => ["name" => "BG39", "dist" => 1],
  "HMI" => [
    "offset" => "C",
    "x" => 1,
    "y" => 0,
    ],
  ],  
"S13" => [ // Signal                                        S13
  "element" => "SD",
  "type" => "MS2",
  "EC" => [
    "addr" => 202,
    "type" => 41,
    "majorDevice" => 8,
    ],
  "U" => ["name" => "BG13", "dist" => 1],
  "D" => ["name" => "S14", "dist" => 10],
  "HMI" => [
    "offset" => "C",
    "x" => 3,
    "y" => -1,
    ],
  ],  
"BG13" => [ // Balises                                     BG13
  "element" => "BL",
  "ID" => "1F:00:4A:FE:5F",
  "U" => ["name" => "BG14", "dist" => 185],
  "D" => ["name" => "S13", "dist" => 100],
  ],
"BG14" => [ // Balises                                      BG14
  "element" => "BL",
  "ID" => "1F:00:62:A3:D5",
  "U" => ["name" => "S18", "dist" => 100],
  "D" => ["name" => "BG13", "dist" => 185],
  ],
// ---------------------------------------------------------------------------------------------------------------------- Station D  
// ----------------------------------------------------------------- From station C
  "S18" => [ // Signal                                     S18
  "element" => "SU",
  "type" => "MS2",
  "EC" => [
    "addr" => 203,
    "type" => 41,
    "majorDevice" => 2,
    ],
  "U" => ["name" => "BG11", "dist" => 5],
  "D" => ["name" => "BG14", "dist" => 1],
  "HMI" => [
    "offset" => "D",
    "x" => 1,
    "y" => 0,
    ],
  ],
"BG11" => [ // Balises                                  BG11
  "element" => "BL",
  "ID" => "74:00:15:50:C0",
  "U" => ["name" => "BG12", "dist" => 10],
  "D" => ["name" => "S18", "dist" => 5],
  ],
"BG12" => [ // Balises                                   BG12
  "element" => "BL",
  "ID" => "74:00:15:55:26",
  "U" => ["name" => "P5", "dist" => 1],
  "D" => ["name" => "BG11", "dist" => 10],
  ],  
//--------------------------------------------------------------- From Station B
"S20" => [ // Signal                                     S20
  "element" => "SU",
  "type" => "MS2",
  "EC" => [
    "addr" => 203,
    "type" => 41,
    "majorDevice" => 1,
    ],
  "U" => ["name" => "P4", "dist" => 5],
  "D" => ["name" => "BG43", "dist" => 1],
  "HMI" => [
    "offset" => "D",
    "x" => 0,
    "y" => 4,
    "l" => 1
    ],
  ],
//----------------------------------------------------------------- Point
"P4" => [ // Point, Facing                               P4
  "element" => "PF",
  "EC" => [
    "addr" => 202,
    "type" => 10,
    "majorDevice" => 1,
    "minorDevice" => 0,
    ],
  "supervisionState" => "P",
  "R" => ["name" => "BG08", "dist" => 30],
  "L" => ["name" => "BG07", "dist" => 30],
  "T" => ["name" => "S20", "dist" => 10],
  "HMI" => [
    "offset" => "D",
    "or" => "fl",
    "x" => 1,
    "y" => 2,
    ],
  ],  
//------------------------------------------------------------------
"BG07" => [ // Balises                                   BG07
  "element" => "BL",
  "ID" => "74:00:15:65:29",
  "U" => ["name" => "P5", "dist" => 1],
  "D" => ["name" => "P4", "dist" => 1],
  ],
//-------------------------------------------------------------------- Point
"P5" => [ // Point, trailing                            P5
  "element" => "PT",
  "EC" => [
    "addr" => 202,
    "type" => 10,
    "majorDevice" => 2,
    "minorDevice" => 0,
    ],
  "supervisionState" => "P",
  "R" => ["name" => "BG12", "dist" => 30],
  "L" => ["name" => "BG07", "dist" => 30],
  "T" => ["name" => "S15", "dist" => 5],
  "HMI" => [
    "offset" => "D",
    "or" => "tl",
    "x" => 3,
    "y" => 0,
    ],
  ],
//------------------------------------------------------------------- Inner track
"S15" => [ // Signal                                     S15
  "element" => "SD",
  "type" => "MS3",
  "EC" => [
    "addr" => 202,
    "type" => 45,
    "majorDevice" => 4,
    ],
  "U" => ["name" => "BG10", "dist" => 1],
  "D" => ["name" => "P5", "dist" => 5],
  "HMI" => [
    "offset" => "D",
    "x" => 4,
    "y" => -1,
    "l" => 1
    ],
  ],
"BG10" => [ // Balises                                   BG10
  "element" => "BL",
  "ID" => "1F:00:50:6E:08",
  "U" => ["name" => "S22", "dist" => 90],
  "D" => ["name" => "S15", "dist" => 10],
  ],
"S22" => [ // Signal                                       S22
  "element" => "SU",
  "type" => "MS2",
  "EC" => [
    "addr" => 202,
    "type" => 41,
    "majorDevice" => 3,
    ],
  "U" => ["name" => "BG03", "dist" => 1],
  "D" => ["name" => "BG10", "dist" => 1],
  "HMI" => [
    "offset" => "D",
    "x" => 6,
    "y" => 0,
    ],
  ],
"BG03" => [ // Balises                                    BG03
  "element" => "BL",
  "ID" => "76:00:0C:FC:CB",
  "U" => ["name" => "BG06", "dist" => 1],
  "D" => ["name" => "S22", "dist" => 20],
  ],
"BG06" => [ // Balises                                    BG06
  "element" => "BL",
  "ID" => "73:00:6E:C8:15",
  "U" => ["name" => "P6", "dist" => 1],
  "D" => ["name" => "BG03", "dist" => 20],
  ],
//------------------------------------------------------------------ Outer track
"BG08" => [ // Balises                                   BG08
  "element" => "BL",
  "ID" => "73:00:56:C0:72",
  "U" => ["name" => "BG09", "dist" => 10],
  "D" => ["name" => "P4", "dist" => 1],
  ],
"BG09" => [ // Balises                                  BG09
  "element" => "BL",
  "ID" => "76:00:0D:1A:2E",
  "U" => ["name" => "S17", "dist" => 1],
  "D" => ["name" => "BG08", "dist" => 10],
  ],
"S17" => [ // Signal                                      S17
  "element" => "SD",
  "type" => "MS3",
  "EC" => [
    "addr" => 203,
    "type" => 45,
    "majorDevice" => 3,
    ],
  "U" => ["name" => "VBG02", "dist" => 1],
  "D" => ["name" => "BG09", "dist" => 10],
  "HMI" => [
    "offset" => "D",
    "x" => 3,
    "y" => 3,
    ],
  ],
"VBG02" => [ // Balises
  "element" => "BL",
  "ID" => "FF:FF:FF:FF:FF",
  "U" => ["name" => "S24", "dist" => 60],
  "D" => ["name" => "S17", "dist" => 60],
  ],
"S24" => [ // Signal                                       S24
  "element" => "SU",
  "type" => "MS2",
  "EC" => [
    "addr" => 202,
    "type" => 41,
    "majorDevice" => 2,
    ],
  "U" => ["name" => "BG04", "dist" => 10],
  "D" => ["name" => "VBG02", "dist" => 1],
  "HMI" => [
    "offset" => "D",
    "x" => 7,
    "y" => 4,
    ],
  ],
"BG04" => [ // Balises                                    BG04
  "element" => "BL",
  "ID" => "74:00:10:F3:3E",
  "U" => ["name" => "BG05", "dist" => 1],
  "D" => ["name" => "S24", "dist" => 20],
  ],
"BG05" => [ // Balises                                    BG05
  "element" => "BL",
  "ID" => "76:00:0C:A4:29",
  "U" => ["name" => "P6", "dist" => 1],
  "D" => ["name" => "BG04", "dist" => 20],
  ],
//------------------------------------------------------------------ Point
"P6" => [ // Point, trailing                              P6
  "element" => "PT",
  "EC" => [
    "addr" => 202,
    "type" => 10,
    "majorDevice" => 4,
    "minorDevice" => 0,
    ],
  "supervisionState" => "P",
  "R" => ["name" => "BG06", "dist" => 30],
  "L" => ["name" => "BG05", "dist" => 30],
  "T" => ["name" => "S19", "dist" => 1],
  "HMI" => [
    "offset" => "D",
    "or" => "tr",
    "x" => 9,
    "y" => 2,
    ],
  ],
//------------------------------------------------------------------ Depot track
"S19" => [ // Signal                                       S19
  "element" => "SD",
  "type" => "MS2",
  "EC" => [
    "addr" => 202,
    "type" => 41,
    "majorDevice" => 1,
    ],
  "U" => ["name" => "BG01", "dist" => 1],
  "D" => ["name" => "P6", "dist" => 1],
  "HMI" => [
    "offset" => "D",
    "x" => 11,
    "y" => 3,
    "l" => 1
    ],
  ],
"BG01" => [ // Balises                                     BG01
  "element" => "BL",
  "ID" => "1E:00:90:0C:8B",
  "U" => ["name" => "BG02", "dist" => 25],
  "D" => ["name" => "S19", "dist" => 10],
  ],
"BG02" => [ // Balises                                     BG02
  "element" => "BL",
  "ID" => "73:00:70:98:69",
  "U" => ["name" => "BS2", "dist" => 100],
  "D" => ["name" => "BG01", "dist" => 25],
  ],
"BS2" => [ // Bufferstop; end of direction up              BS2
  "element" => "BSE",
  "D" => ["name" => "BG02", "dist" => 60],
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
  
"" => [ //  Enforced Point Holding Up
  "element" => "PHTU",
  "U" => ["name" => "", "dist" => 0],
  "D" => ["name" => "", "dist" => 0],
  "holdPoint" => "",
  ],

"" => [ // Enforced Point Holding Down
  "element" => "PHTD",
  "U" => ["name" => "", "dist" => 0],
  "D" => ["name" => "", "dist" => 0],
  "holdPoint" => "",
  ],

"" => [ // Signal                
  "element" => "SU",
  "type" => "MB",
  "EC" => [
    "addr" => 0,
    "type" => 0,
    "majorDevice" => 0,
    ],
  "U" => ["name" => "", "dist" => 0],
  "D" => ["name" => "", "dist" => 1],
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
  "C" => ["x" => 19, "y" => 2],
  "D" => ["x" => 28, "y" => 2],
];

$HMI = [ 
  "baliseTrack" => [

    "tr1" => [
      "balises" => [ "BG21", "BG22"
              ],
      "offset" => "A",
      "or" => "s",
      "x" => 6,
      "y" => 2,
      "l" => 1,
    ],
    "tr2" => [
      "balises" => [ "BG40"
              ],
      "offset" => "A",
      "or" => "s",
      "x" => 7,
      "y" => 2,
      "l" => 1,
    ],
    "tr3" => [
      "balises" => [ "BG23"
              ],
      "offset" => "A",
      "or" => "s",
      "x" => 10,
      "y" => 2,
      "l" => 1,
    ],
   "tr4" => [
      "balises" => [ "BG24"
              ],
      "offset" => "A",
      "or" => "s",
      "x" => 11,
      "y" => 2,
      "l" => 1,
    ],
    "tr5" => [
      "balises" => [ "BG27"
              ],
      "offset" => "B",
      "or" => "s",
      "x" => 5,
      "y" => 0,
      "l" => 1,
    ],
    "tr6" => [
      "balises" => [ "BG32"
              ],
      "offset" => "B",
      "or" => "s",
      "x" => 5,
      "y" => 2,
      "l" => 1,
    ],
    "tr7" => [
      "balises" => [ "BG35", "BG36"
              ],
      "offset" => "B",
      "or" => "s",
      "x" => 10,
      "y" => 0,
      "l" => 1,
    ],
    "tr8" => [
      "balises" => [ "BG42", "BG44"
              ],
      "offset" => "B",
      "or" => "s",
      "x" => 11,
      "y" => 0,
      "l" => 1,
    ],
    "tr9" => [
      "balises" => [ "BG37", "BG38"
              ],
      "offset" => "B",
      "or" => "s",
      "x" => 14,
      "y" => 0,
      "l" => 1,
    ],
    "tr10" => [
      "balises" => [ "BG43"
              ],
      "offset" => "B",
      "or" => "s",
      "x" => 15,
      "y" => 0,
      "l" => 1,
    ],
    "tr11" => [
      "balises" => [ "BG10"
              ],
      "offset" => "D",
      "or" => "s",
      "x" => 5,
      "y" => 0,
      "l" => 1,
    ],
    "tr12" => [
      "balises" => [ "VBG02",
              ],
      "offset" => "D",
      "or" => "s",
      "x" => 5,
      "y" => 4,
      "l" => 2,
    ],
    "tr13" => [
      "balises" => [ "BG01", "BG02"
              ],
      "offset" => "D",
      "or" => "s",
      "x" => 12,
      "y" => 4,
      "l" => 1,
    ],
    "tr14" => [
      "balises" => [ "BG13", "BG14"
              ],
      "offset" => "C",
      "or" => "s",
      "x" => 5,
      "y" => 0,
      "l" => 5,
    ],
    "tr15" => [
      "balises" => [ "BG03", "BG06"
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
      "text" => "Christianshavn"
    ],
    [
      "x" => 17,
      "y" => 11,
      "text" => "Station B"
    ],
    [
      "x" => 23,
      "y" => 1,
      "text" => "Station C"
    ],
    [
      "x" => 34,
      "y" => 9,
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

