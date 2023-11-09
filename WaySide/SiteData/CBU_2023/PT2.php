<?php
// ------------------------------------------------- Sources
$PT2_SIGNALLING_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/CBU_2023/signallingLayout.sch";
$PT2_SIGNALLING_LAYOUT_FILE_DATE = "2023-11-09 10:44:08";
$PT2_SCREEN_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/CBU_2023/screenLayout.sch";
$PT2_SCREEN_LAYOUT_FILE_DATE = "2023-11-09 11:22:31";
$PT2_GENERATION_TIME = "2023-11-09 11:22:37";
$PT1_PROJECT_NAME = "CBU_2023";
$PT1_DATE = "2023-11-9";
$PT1_AUTHOR = "JB";
$HMI_PROJECT_NAME = "CBU_2023";

// -------------------------------------------------- PT1
$PT1 = array (
  'S1' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'S2',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BS1',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 202,
      'type' => 41,
      'majorDevice' => 7,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 3,
      'y' => 4,
      'l' => 1,
    ),
  ),
  'BS1' => 
  array (
    'element' => 'BSB',
    'U' => 
    array (
      'name' => 'S1',
      'dist' => 0,
    ),
    'routeInfo' => 0,
    'HMI' => 
    array (
      'x' => 1,
      'y' => 4,
      'l' => 1,
    ),
  ),
  'S2' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'S4',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S1',
      'dist' => 10,
    ),
    'type' => 'MS2',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 202,
      'type' => 41,
      'majorDevice' => 5,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 4,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'S4' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'P1',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'S2',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 202,
      'type' => 41,
      'majorDevice' => 4,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 6,
      'y' => 4,
      'l' => 2,
    ),
  ),
  'S3' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'P1',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BS2',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 202,
      'type' => 41,
      'majorDevice' => 3,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 6,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'P1' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'S5',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'S3',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'S4',
      'dist' => 30,
    ),
    'supervisionState' => 'S',
    'EC' => 
    array (
      'addr' => 0,
      'type' => 0,
      'majorDevice' => 0,
      'minorDevice' => 0,
    ),
    'HMI' => 
    array (
      'or' => 'tr',
      'x' => 8,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'S5' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'S6',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P1',
      'dist' => 10,
    ),
    'type' => 'MS2',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 202,
      'type' => 41,
      'majorDevice' => 6,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 10,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'P2' => 
  array (
    'element' => 'PF',
    'T' => 
    array (
      'name' => 'S6',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'S8',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'S7',
      'dist' => 30,
    ),
    'supervisionState' => 'S',
    'EC' => 
    array (
      'addr' => 0,
      'type' => 0,
      'majorDevice' => 0,
      'minorDevice' => 0,
    ),
    'HMI' => 
    array (
      'or' => 'fr',
      'x' => 13,
      'y' => 4,
      'l' => 2,
    ),
  ),
  'S6' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'P2',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'S5',
      'dist' => 1,
    ),
    'type' => 'MS3',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 202,
      'type' => 44,
      'majorDevice' => 10,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 12,
      'y' => 4,
      'l' => 1,
    ),
  ),
  'S8' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'S11',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P2',
      'dist' => 10,
    ),
    'type' => 'MS2',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 204,
      'type' => 41,
      'majorDevice' => 14,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 16,
      'y' => 7,
      'l' => 2,
    ),
  ),
  'S7' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'S9',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P2',
      'dist' => 10,
    ),
    'type' => 'MS2',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 204,
      'type' => 41,
      'majorDevice' => 13,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 16,
      'y' => 3,
      'l' => 2,
    ),
  ),
  'S12' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'S14',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S11',
      'dist' => 10,
    ),
    'type' => 'MS3',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 204,
      'type' => 45,
      'majorDevice' => 4,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 21,
      'y' => 7,
      'l' => 1,
    ),
  ),
  'S10' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'S13',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S9',
      'dist' => 10,
    ),
    'type' => 'MS3',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 204,
      'type' => 45,
      'majorDevice' => 1,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 21,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'S9' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'S10',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'S7',
      'dist' => 1,
    ),
    'type' => 'MS3',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 204,
      'type' => 45,
      'majorDevice' => 7,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 20,
      'y' => 4,
      'l' => 1,
    ),
  ),
  'S11' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'S12',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'S8',
      'dist' => 1,
    ),
    'type' => 'MS3',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 204,
      'type' => 45,
      'majorDevice' => 10,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 20,
      'y' => 8,
      'l' => 1,
    ),
  ),
  'S13' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'P4',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'S10',
      'dist' => 1,
    ),
    'type' => 'MS3',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 201,
      'type' => 44,
      'majorDevice' => 4,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 24,
      'y' => 4,
      'l' => 2,
    ),
  ),
  'S14' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'P3',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'S12',
      'dist' => 1,
    ),
    'type' => 'MS3',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 201,
      'type' => 44,
      'majorDevice' => 7,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 24,
      'y' => 8,
      'l' => 1,
    ),
  ),
  'S16' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'S18',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P3',
      'dist' => 10,
    ),
    'type' => 'MS3',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 201,
      'type' => 44,
      'majorDevice' => 10,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 28,
      'y' => 7,
      'l' => 2,
    ),
  ),
  'S15' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'S17',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P4',
      'dist' => 10,
    ),
    'type' => 'MS3',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 201,
      'type' => 44,
      'majorDevice' => 1,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 29,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'S18' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'P6',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'S16',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 201,
      'type' => 41,
      'majorDevice' => 8,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 32,
      'y' => 8,
      'l' => 2,
    ),
  ),
  'S17' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'P5',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'S15',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 201,
      'type' => 41,
      'majorDevice' => 7,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 32,
      'y' => 4,
      'l' => 1,
    ),
  ),
  'BS4' => 
  array (
    'element' => 'BSE',
    'D' => 
    array (
      'name' => 'S24',
      'dist' => 0,
    ),
    'routeInfo' => 0,
    'HMI' => 
    array (
      'x' => 44,
      'y' => 8,
      'l' => 1,
    ),
  ),
  'S24' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BS4',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S22',
      'dist' => 10,
    ),
    'type' => 'MS2',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 204,
      'type' => 41,
      'majorDevice' => 15,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 42,
      'y' => 7,
      'l' => 1,
    ),
  ),
  'S23' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BS3',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S21',
      'dist' => 10,
    ),
    'type' => 'MS2',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 201,
      'type' => 41,
      'majorDevice' => 4,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 42,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'S22' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'S24',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'S20',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 201,
      'type' => 41,
      'majorDevice' => 6,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 39,
      'y' => 8,
      'l' => 1,
    ),
  ),
  'S20' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'S22',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P6',
      'dist' => 10,
    ),
    'type' => 'MS3',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 201,
      'type' => 45,
      'majorDevice' => 1,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 37,
      'y' => 7,
      'l' => 1,
    ),
  ),
  'S21' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'S23',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'S19',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 201,
      'type' => 41,
      'majorDevice' => 5,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 39,
      'y' => 4,
      'l' => 1,
    ),
  ),
  'S19' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'S21',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P5',
      'dist' => 10,
    ),
    'type' => 'MS3',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 201,
      'type' => 44,
      'majorDevice' => 13,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 36,
      'y' => 3,
      'l' => 2,
    ),
  ),
  'P6' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'S20',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'P5',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'S18',
      'dist' => 30,
    ),
    'supervisionState' => 'S',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 10,
      'majorDevice' => 4,
      'minorDevice' => 0,
    ),
    'HMI' => 
    array (
      'or' => 'tr',
      'x' => 35,
      'y' => 6,
      'l' => 2,
    ),
  ),
  'P4' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'S15',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'S13',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'P3',
      'dist' => 30,
    ),
    'supervisionState' => 'S',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 10,
      'majorDevice' => 1,
      'minorDevice' => 0,
    ),
    'HMI' => 
    array (
      'or' => 'tl',
      'x' => 27,
      'y' => 4,
      'l' => 2,
    ),
  ),
  'P5' => 
  array (
    'element' => 'PF',
    'T' => 
    array (
      'name' => 'S17',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'P6',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'S19',
      'dist' => 30,
    ),
    'supervisionState' => 'S',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 10,
      'majorDevice' => 3,
      'minorDevice' => 0,
    ),
    'HMI' => 
    array (
      'or' => 'fr',
      'x' => 33,
      'y' => 4,
      'l' => 2,
    ),
  ),
  'P3' => 
  array (
    'element' => 'PF',
    'T' => 
    array (
      'name' => 'S14',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'S16',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'P4',
      'dist' => 30,
    ),
    'supervisionState' => 'S',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 10,
      'majorDevice' => 2,
      'minorDevice' => 0,
    ),
    'HMI' => 
    array (
      'or' => 'fl',
      'x' => 25,
      'y' => 6,
      'l' => 2,
    ),
  ),
  'BS3' => 
  array (
    'element' => 'BSE',
    'D' => 
    array (
      'name' => 'S23',
      'dist' => 0,
    ),
    'routeInfo' => 0,
    'HMI' => 
    array (
      'x' => 44,
      'y' => 4,
      'l' => 1,
    ),
  ),
  'BS2' => 
  array (
    'element' => 'BSB',
    'U' => 
    array (
      'name' => 'S3',
      'dist' => 0,
    ),
    'routeInfo' => 0,
    'HMI' => 
    array (
      'x' => 4,
      'y' => 2,
      'l' => 1,
    ),
  ),
);

// -------------------------------------------------- HMI
$HMI = array (
  'label' => 
  array (
  ),
  'baliseTrack' => 
  array (
  ),
  'projectName' => 'CBU_2023',
);
?>