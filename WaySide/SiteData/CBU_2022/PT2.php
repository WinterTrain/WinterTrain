<?php
// ------------------------------------------------- Sources
$PT2_SIGNALLING_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/CBU_2022/signallingLayout.sch";
$PT2_SIGNALLING_LAYOUT_FILE_DATE = "2022-12-30 13:00:42";
$PT2_SCREEN_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/CBU_2022/screenLayout.sch";
$PT2_SCREEN_LAYOUT_FILE_DATE = "2022-11-22 20:06:48";
$PT2_GENERATION_TIME = "2022-12-30 13:00:53";
$PT1_PROJECT_NAME = "CBU_2022";
$PT1_DATE = "2022-12-01";
$PT1_AUTHOR = "JB";
$HMI_PROJECT_NAME = "CBU_2022";

// -------------------------------------------------- PT1
$PT1 = array (
  'VBG1' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P1',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S5',
      'dist' => 1,
    ),
    'ID' => '00:00:00:00:01',
  ),
  'VBG3' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S12',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S11',
      'dist' => 1,
    ),
    'ID' => '00:00:00:00:02',
  ),
  'VBG2' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S10',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S9',
      'dist' => 1,
    ),
    'ID' => '00:00:00:00:03',
  ),
  'S17' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG27',
      'dist' => 71,
    ),
    'D' => 
    array (
      'name' => 'BG25',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EPdist' => 40,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 201,
      'type' => 41,
      'majorDevice' => 6,
      'riAddr' => 0,
      'riType' => 50,
      'riMajorDevice' => 5,
    ),
    'HMI' => 
    array (
      'x' => 33,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'BG27' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P5',
      'dist' => 75,
    ),
    'D' => 
    array (
      'name' => 'S17',
      'dist' => 1,
    ),
    'ID' => '1F:00:69:A9:3C',
  ),
  'BG22' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG26',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'P3',
      'dist' => 30,
    ),
    'ID' => '1F:00:4D:4B:FE',
  ),
  'BG31' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P6',
      'dist' => 30,
    ),
    'D' => 
    array (
      'name' => 'BG30',
      'dist' => 7,
    ),
    'ID' => '1F:00:62:74:36',
  ),
  'BG30' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG31',
      'dist' => 31,
    ),
    'D' => 
    array (
      'name' => 'S19',
      'dist' => 20,
    ),
    'ID' => '1F:00:4A:FE:5F',
  ),
  'BG29' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S19',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG28',
      'dist' => 50,
    ),
    'ID' => '1F:00:61:EF:11',
  ),
  'BG28' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG29',
      'dist' => 50,
    ),
    'D' => 
    array (
      'name' => 'S16',
      'dist' => 10,
    ),
    'ID' => '1F:00:69:B5:36',
  ),
  'BG26' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S16',
      'dist' => 11,
    ),
    'D' => 
    array (
      'name' => 'BG22',
      'dist' => 31,
    ),
    'ID' => '1F:00:62:75:10',
  ),
  'BG25' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S17',
      'dist' => 13,
    ),
    'D' => 
    array (
      'name' => 'BG24',
      'dist' => 47,
    ),
    'ID' => '1F:00:4A:F2:D2',
  ),
  'BG24' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG25',
      'dist' => 47,
    ),
    'D' => 
    array (
      'name' => 'S14',
      'dist' => 25,
    ),
    'ID' => '1E:00:90:0C:A5',
  ),
  'BG23' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S14',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P4',
      'dist' => 76,
    ),
    'ID' => '1F:00:66:0E:0C',
  ),
  'S19' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG30',
      'dist' => 58,
    ),
    'D' => 
    array (
      'name' => 'BG29',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 3,
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
      'x' => 33,
      'y' => 7,
      'l' => 2,
    ),
  ),
  'S14' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG24',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG23',
      'dist' => 63,
    ),
    'type' => 'MS3',
    'EPdist' => 40,
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
      'x' => 30,
      'y' => 2,
      'l' => 1,
    ),
  ),
  'S16' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG28',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG26',
      'dist' => 58,
    ),
    'type' => 'MS3',
    'EPdist' => 30,
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
      'x' => 29,
      'y' => 6,
      'l' => 2,
    ),
  ),
  'S21' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG34',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BG33',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 3,
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
      'x' => 40,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'S23' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG37',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BG36',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 3,
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
      'x' => 40,
      'y' => 7,
      'l' => 1,
    ),
  ),
  'S10' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG15',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'VBG2',
      'dist' => 6,
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
      'x' => 22,
      'y' => 2,
      'l' => 1,
    ),
  ),
  'S12' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG19',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'VBG3',
      'dist' => 6,
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
      'x' => 22,
      'y' => 6,
      'l' => 1,
    ),
  ),
  'S11' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'VBG3',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'BG18',
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
      'x' => 21,
      'y' => 7,
      'l' => 1,
    ),
  ),
  'S9' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'VBG2',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'BG14',
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
      'majorDevice' => 4,
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
  'S7' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'P2',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BG12',
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
      'x' => 13,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'S4' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG10',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P1',
      'dist' => 14,
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
      'x' => 11,
      'y' => 2,
      'l' => 1,
    ),
  ),
  'BG108' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG9',
      'dist' => 21,
    ),
    'D' => 
    array (
      'name' => 'BG8',
      'dist' => 20,
    ),
    'ID' => '1F:00:62:A3:D5',
  ),
  'BG45' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S6',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P2',
      'dist' => 19,
    ),
    'ID' => '1F:00:4D:FF:CC',
  ),
  'BG46' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S8',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P2',
      'dist' => 20,
    ),
    'ID' => '1F:00:61:E5:87',
  ),
  'BG44' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BS4',
      'dist' => 55,
    ),
    'D' => 
    array (
      'name' => 'BG43',
      'dist' => 10,
    ),
    'ID' => '73:00:56:9F:A1',
  ),
  'BG43' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG44',
      'dist' => 5,
    ),
    'D' => 
    array (
      'name' => 'BG41',
      'dist' => 37,
    ),
    'ID' => '74:00:15:50:C0',
  ),
  'BG42' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BS3',
      'dist' => 100,
    ),
    'D' => 
    array (
      'name' => 'BG40',
      'dist' => 8,
    ),
    'ID' => '1F:00:4B:83:72',
  ),
  'BG40' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG42',
      'dist' => 8,
    ),
    'D' => 
    array (
      'name' => 'S24',
      'dist' => 21,
    ),
    'ID' => '1E:00:8E:70:11',
  ),
  'BG41' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG43',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S26',
      'dist' => 20,
    ),
    'ID' => '76:00:0C:E3:A8',
  ),
  'BG38' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S24',
      'dist' => 35,
    ),
    'D' => 
    array (
      'name' => 'BG35',
      'dist' => 100,
    ),
    'ID' => '1E:00:90:73:4B',
  ),
  'BG39' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S26',
      'dist' => 100,
    ),
    'D' => 
    array (
      'name' => 'BG37',
      'dist' => 100,
    ),
    'ID' => '74:00:11:07:0B',
  ),
  'BG37' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG39',
      'dist' => 100,
    ),
    'D' => 
    array (
      'name' => 'S23',
      'dist' => 29,
    ),
    'ID' => '76:00:0D:19:5B',
  ),
  'BG36' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S23',
      'dist' => 162,
    ),
    'D' => 
    array (
      'name' => 'S20',
      'dist' => 19,
    ),
    'ID' => '1F:00:68:D5:C8',
  ),
  'BG35' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG38',
      'dist' => 100,
    ),
    'D' => 
    array (
      'name' => 'BG34',
      'dist' => 100,
    ),
    'ID' => '1E:00:57:DC:9F',
  ),
  'BG34' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG35',
      'dist' => 100,
    ),
    'D' => 
    array (
      'name' => 'S21',
      'dist' => 30,
    ),
    'ID' => '1F:00:6A:63:A2',
  ),
  'BG33' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S21',
      'dist' => 161,
    ),
    'D' => 
    array (
      'name' => 'S18',
      'dist' => 19,
    ),
    'ID' => '1E:00:56:C3:9C',
  ),
  'BG32' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S18',
      'dist' => 19,
    ),
    'D' => 
    array (
      'name' => 'P5',
      'dist' => 10,
    ),
    'ID' => '1E:00:57:ED:F2',
  ),
  'BG21' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P4',
      'dist' => 28,
    ),
    'D' => 
    array (
      'name' => 'S13',
      'dist' => 1,
    ),
    'ID' => '1F:00:4B:02:3D',
  ),
  'BG20' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S15',
      'dist' => 20,
    ),
    'D' => 
    array (
      'name' => 'BG19',
      'dist' => 102,
    ),
    'ID' => '1F:00:50:6E:08',
  ),
  'BG19' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG20',
      'dist' => 102,
    ),
    'D' => 
    array (
      'name' => 'S12',
      'dist' => 119,
    ),
    'ID' => '1F:00:4D:6D:73',
  ),
  'BG18' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S11',
      'dist' => 64,
    ),
    'D' => 
    array (
      'name' => 'BG16',
      'dist' => 100,
    ),
    'ID' => '1E:00:EA:E8:E9',
  ),
  'BG16' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG18',
      'dist' => 100,
    ),
    'D' => 
    array (
      'name' => 'S8',
      'dist' => 19,
    ),
    'ID' => '1E:00:98:B1:C9',
  ),
  'BG17' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S13',
      'dist' => 20,
    ),
    'D' => 
    array (
      'name' => 'BG15',
      'dist' => 102,
    ),
    'ID' => '1E:00:57:07:51',
  ),
  'BG15' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG17',
      'dist' => 102,
    ),
    'D' => 
    array (
      'name' => 'S10',
      'dist' => 119,
    ),
    'ID' => '1E:00:57:4C:C8',
  ),
  'BG14' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S9',
      'dist' => 64,
    ),
    'D' => 
    array (
      'name' => 'BG13',
      'dist' => 100,
    ),
    'ID' => '1E:00:AC:CA:72',
  ),
  'BG13' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG14',
      'dist' => 100,
    ),
    'D' => 
    array (
      'name' => 'S6',
      'dist' => 18,
    ),
    'ID' => '1F:00:4D:6A:29',
  ),
  'BG1' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG2',
      'dist' => 5,
    ),
    'D' => 
    array (
      'name' => 'BS1',
      'dist' => 200,
    ),
    'ID' => '1E:00:EC:F9:E1',
  ),
  'BG2' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S1',
      'dist' => 9,
    ),
    'D' => 
    array (
      'name' => 'BG1',
      'dist' => 10,
    ),
    'ID' => '1E:00:8E:57:F6',
  ),
  'BG3' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG4',
      'dist' => 70,
    ),
    'D' => 
    array (
      'name' => 'S1',
      'dist' => 90,
    ),
    'ID' => '1E:00:56:DE:2A',
  ),
  'BG4' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG5',
      'dist' => 50,
    ),
    'D' => 
    array (
      'name' => 'BG3',
      'dist' => 45,
    ),
    'ID' => '1E:00:8E:DF:1F',
  ),
  'BG5' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S2',
      'dist' => 75,
    ),
    'D' => 
    array (
      'name' => 'BG4',
      'dist' => 70,
    ),
    'ID' => '1E:00:57:35:F9',
  ),
  'BG8' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG108',
      'dist' => 20,
    ),
    'D' => 
    array (
      'name' => 'S2',
      'dist' => 25,
    ),
    'ID' => '1F:00:62:25:B2',
  ),
  'BG9' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S5',
      'dist' => 20,
    ),
    'D' => 
    array (
      'name' => 'BG108',
      'dist' => 20,
    ),
    'ID' => '1E:00:57:5B:28',
  ),
  'BG6' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG7',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BS2',
      'dist' => 90,
    ),
    'ID' => '1F:00:69:F3:BB',
  ),
  'BG7' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S3',
      'dist' => 11,
    ),
    'D' => 
    array (
      'name' => 'BG6',
      'dist' => 8,
    ),
    'ID' => '1F:00:50:3C:80',
  ),
  'BG10' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG11',
      'dist' => 70,
    ),
    'D' => 
    array (
      'name' => 'S4',
      'dist' => 5,
    ),
    'ID' => '1E:00:AC:75:9B',
  ),
  'BG11' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG12',
      'dist' => 40,
    ),
    'D' => 
    array (
      'name' => 'BG10',
      'dist' => 47,
    ),
    'ID' => '1E:00:B0:50:33',
  ),
  'BG12' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S7',
      'dist' => 9,
    ),
    'D' => 
    array (
      'name' => 'BG11',
      'dist' => 70,
    ),
    'ID' => '1E:00:AC:98:25',
  ),
  'S5' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'VBG1',
      'dist' => 5,
    ),
    'D' => 
    array (
      'name' => 'BG9',
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
      'majorDevice' => 6,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 7,
      'y' => 3,
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
      'name' => 'BG7',
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
      'majorDevice' => 5,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 7,
      'y' => 1,
      'l' => 2,
    ),
  ),
  'S1' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG3',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BG2',
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
      'x' => 2,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'S2' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG8',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG5',
      'dist' => 5,
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
      'x' => 5,
      'y' => 2,
      'l' => 1,
    ),
  ),
  'BS1' => 
  array (
    'element' => 'BSB',
    'U' => 
    array (
      'name' => 'BG1',
      'dist' => 1,
    ),
    'routeInfo' => 0,
    'HMI' => 
    array (
      'x' => 0,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'P1' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'S4',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'S3',
      'dist' => 53,
    ),
    'L' => 
    array (
      'name' => 'VBG1',
      'dist' => 44,
    ),
    'supervisionState' => 'P',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 10,
      'majorDevice' => 3,
      'minorDevice' => 0,
    ),
    'HMI' => 
    array (
      'or' => 'tr',
      'x' => 9,
      'y' => 1,
      'l' => 2,
    ),
  ),
  'S8' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG16',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG46',
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
      'x' => 17,
      'y' => 6,
      'l' => 2,
    ),
  ),
  'S6' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG13',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG45',
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
      'x' => 17,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'P2' => 
  array (
    'element' => 'PF',
    'T' => 
    array (
      'name' => 'S7',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG46',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG45',
      'dist' => 30,
    ),
    'supervisionState' => 'P',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 10,
      'majorDevice' => 4,
      'minorDevice' => 0,
    ),
    'HMI' => 
    array (
      'or' => 'fr',
      'x' => 14,
      'y' => 3,
      'l' => 2,
    ),
  ),
  'BS3' => 
  array (
    'element' => 'BSE',
    'D' => 
    array (
      'name' => 'BG42',
      'dist' => 1,
    ),
    'routeInfo' => 1,
    'HMI' => 
    array (
      'x' => 46,
      'y' => 1,
      'l' => 1,
    ),
  ),
  'S24' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG40',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG38',
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
      'x' => 44,
      'y' => 0,
      'l' => 1,
    ),
  ),
  'S26' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG41',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG39',
      'dist' => 80,
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
      'x' => 43,
      'y' => 6,
      'l' => 1,
    ),
  ),
  'BS4' => 
  array (
    'element' => 'BSE',
    'D' => 
    array (
      'name' => 'BG44',
      'dist' => 1,
    ),
    'routeInfo' => 3,
    'HMI' => 
    array (
      'x' => 46,
      'y' => 7,
      'l' => 1,
    ),
  ),
  'S20' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG36',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P6',
      'dist' => 9,
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
      'x' => 38,
      'y' => 6,
      'l' => 1,
    ),
  ),
  'S18' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG33',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG32',
      'dist' => 8,
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
      'y' => 2,
      'l' => 2,
    ),
  ),
  'S15' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'P3',
      'dist' => 14,
    ),
    'D' => 
    array (
      'name' => 'BG20',
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
      'riType' => 50,
      'riMajorDevice' => 3,
    ),
    'HMI' => 
    array (
      'x' => 25,
      'y' => 7,
      'l' => 1,
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
      'name' => 'BG31',
      'dist' => 30,
    ),
    'supervisionState' => 'P',
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
      'x' => 36,
      'y' => 5,
      'l' => 2,
    ),
  ),
  'P4' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'BG23',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG21',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'P3',
      'dist' => 30,
    ),
    'supervisionState' => 'P',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 10,
      'majorDevice' => 2,
      'minorDevice' => 0,
    ),
    'HMI' => 
    array (
      'or' => 'tl',
      'x' => 28,
      'y' => 3,
      'l' => 2,
    ),
  ),
  'P5' => 
  array (
    'element' => 'PF',
    'T' => 
    array (
      'name' => 'BG27',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'P6',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG32',
      'dist' => 30,
    ),
    'supervisionState' => 'P',
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
      'x' => 34,
      'y' => 3,
      'l' => 2,
    ),
  ),
  'P3' => 
  array (
    'element' => 'PF',
    'T' => 
    array (
      'name' => 'S15',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG22',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'P4',
      'dist' => 30,
    ),
    'supervisionState' => 'P',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 10,
      'majorDevice' => 1,
      'minorDevice' => 0,
    ),
    'HMI' => 
    array (
      'or' => 'fl',
      'x' => 26,
      'y' => 5,
      'l' => 2,
    ),
  ),
  'BS2' => 
  array (
    'element' => 'BSB',
    'U' => 
    array (
      'name' => 'BG6',
      'dist' => 1,
    ),
    'routeInfo' => 0,
    'HMI' => 
    array (
      'x' => 5,
      'y' => 1,
      'l' => 1,
    ),
  ),
  'S13' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG21',
      'dist' => 11,
    ),
    'D' => 
    array (
      'name' => 'BG17',
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
      'majorDevice' => 1,
      'riAddr' => 0,
      'riType' => 50,
      'riMajorDevice' => 1,
    ),
    'HMI' => 
    array (
      'x' => 25,
      'y' => 3,
      'l' => 2,
    ),
  ),
);

// -------------------------------------------------- HMI
$HMI = array (
  'label' => 
  array (
    0 => 
    array (
      'x' => 20,
      'y' => 5,
      'text' => 'Kystbanen',
    ),
    1 => 
    array (
      'x' => 32,
      'y' => 5,
      'text' => 'Alperne',
    ),
    2 => 
    array (
      'x' => 46,
      'y' => 3,
      'text' => 'Langtbortistan',
    ),
    3 => 
    array (
      'x' => 46,
      'y' => 6,
      'text' => 'Højbanen',
    ),
    4 => 
    array (
      'x' => 7,
      'y' => 5.125,
      'text' => 'Christianshavn',
    ),
    5 => 
    array (
      'x' => 2,
      'y' => 5.125,
      'text' => 'Holmen',
    ),
    6 => 
    array (
      'x' => 39,
      'y' => 5,
      'text' => 'Beboerhuset',
    ),
  ),
  'baliseTrack' => 
  array (
    'tr1' => 
    array (
      'balises' => 
      array (
        0 => 'BG1',
        1 => 'BG2',
      ),
      'or' => 's',
      'x' => 1,
      'y' => 3,
      'l' => 1,
    ),
    'tr8' => 
    array (
      'balises' => 
      array (
        0 => 'BG46',
      ),
      'or' => 'd',
      'x' => 16,
      'y' => 5,
      'l' => 1,
    ),
    'tr26' => 
    array (
      'balises' => 
      array (
        0 => 'BG34',
      ),
      'or' => 'u',
      'x' => 41,
      'y' => 1,
      'l' => 1,
    ),
    'tr4' => 
    array (
      'balises' => 
      array (
        0 => 'BG6',
        1 => 'BG7',
      ),
      'or' => 's',
      'x' => 6,
      'y' => 1,
      'l' => 1,
    ),
    'tr5' => 
    array (
      'balises' => 
      array (
        0 => 'BG8',
        1 => 'BG9',
      ),
      'or' => 's',
      'x' => 6,
      'y' => 3,
      'l' => 1,
    ),
    'tr2' => 
    array (
      'balises' => 
      array (
        0 => 'BG3',
      ),
      'or' => 's',
      'x' => 3,
      'y' => 3,
      'l' => 1,
    ),
    'tr3' => 
    array (
      'balises' => 
      array (
        0 => 'BG4',
        1 => 'BG5',
      ),
      'or' => 's',
      'x' => 4,
      'y' => 3,
      'l' => 1,
    ),
    'tr7' => 
    array (
      'balises' => 
      array (
        0 => 'BG45',
      ),
      'or' => 's',
      'x' => 16,
      'y' => 3,
      'l' => 1,
    ),
    'tr9' => 
    array (
      'balises' => 
      array (
        0 => 'BG13',
      ),
      'or' => 's',
      'x' => 19,
      'y' => 3,
      'l' => 1,
    ),
    'tr11' => 
    array (
      'balises' => 
      array (
        0 => 'BG16',
      ),
      'or' => 's',
      'x' => 19,
      'y' => 7,
      'l' => 1,
    ),
    'tr19' => 
    array (
      'balises' => 
      array (
        0 => 'BG22',
      ),
      'or' => 's',
      'x' => 28,
      'y' => 7,
      'l' => 1,
    ),
    'tr15' => 
    array (
      'balises' => 
      array (
        0 => 'BG21',
      ),
      'or' => 's',
      'x' => 27,
      'y' => 3,
      'l' => 1,
    ),
    'tr21' => 
    array (
      'balises' => 
      array (
        0 => 'BG28',
      ),
      'or' => 's',
      'x' => 31,
      'y' => 7,
      'l' => 1,
    ),
    'tr18' => 
    array (
      'balises' => 
      array (
        0 => 'BG24',
      ),
      'or' => 's',
      'x' => 31,
      'y' => 3,
      'l' => 1,
    ),
    'tr10' => 
    array (
      'balises' => 
      array (
        0 => 'BG14',
      ),
      'or' => 's',
      'x' => 20,
      'y' => 3,
      'l' => 1,
    ),
    'tr13' => 
    array (
      'balises' => 
      array (
        0 => 'BG18',
      ),
      'or' => 's',
      'x' => 20,
      'y' => 7,
      'l' => 1,
    ),
    'tr12' => 
    array (
      'balises' => 
      array (
        0 => 'BG15',
      ),
      'or' => 's',
      'x' => 23,
      'y' => 3,
      'l' => 1,
    ),
    'tr14' => 
    array (
      'balises' => 
      array (
        0 => 'BG17',
      ),
      'or' => 's',
      'x' => 24,
      'y' => 3,
      'l' => 1,
    ),
    'tr16' => 
    array (
      'balises' => 
      array (
        0 => 'BG19',
      ),
      'or' => 's',
      'x' => 23,
      'y' => 7,
      'l' => 1,
    ),
    'tr17' => 
    array (
      'balises' => 
      array (
        0 => 'BG20',
      ),
      'or' => 's',
      'x' => 24,
      'y' => 7,
      'l' => 1,
    ),
    'tr24' => 
    array (
      'balises' => 
      array (
        0 => 'BG31',
      ),
      'or' => 's',
      'x' => 35,
      'y' => 7,
      'l' => 1,
    ),
    'tr22' => 
    array (
      'balises' => 
      array (
        0 => 'BG32',
      ),
      'or' => 's',
      'x' => 36,
      'y' => 3,
      'l' => 1,
    ),
    'tr29' => 
    array (
      'balises' => 
      array (
        0 => 'BG40',
        1 => 'BG42',
      ),
      'or' => 's',
      'x' => 45,
      'y' => 1,
      'l' => 1,
    ),
    'tr33' => 
    array (
      'balises' => 
      array (
        0 => 'BG41',
        1 => 'BG43',
      ),
      'or' => 's',
      'x' => 44,
      'y' => 7,
      'l' => 1,
    ),
    'tr30' => 
    array (
      'balises' => 
      array (
        0 => 'BG36',
      ),
      'or' => 's',
      'x' => 39,
      'y' => 7,
      'l' => 1,
    ),
    'tr31' => 
    array (
      'balises' => 
      array (
        0 => 'BG37',
      ),
      'or' => 's',
      'x' => 41,
      'y' => 7,
      'l' => 1,
    ),
    'tr25' => 
    array (
      'balises' => 
      array (
        0 => 'BG33',
      ),
      'or' => 's',
      'x' => 39,
      'y' => 3,
      'l' => 1,
    ),
    'tr28' => 
    array (
      'balises' => 
      array (
        0 => 'BG38',
      ),
      'or' => 's',
      'x' => 43,
      'y' => 1,
      'l' => 1,
    ),
    'tr23' => 
    array (
      'balises' => 
      array (
        0 => 'BG29',
      ),
      'or' => 's',
      'x' => 32,
      'y' => 7,
      'l' => 1,
    ),
    'tr20' => 
    array (
      'balises' => 
      array (
        0 => 'BG25',
      ),
      'or' => 's',
      'x' => 32,
      'y' => 3,
      'l' => 1,
    ),
    'tr27' => 
    array (
      'balises' => 
      array (
        0 => 'BG35',
      ),
      'or' => 's',
      'x' => 42,
      'y' => 1,
      'l' => 1,
    ),
    'tr6' => 
    array (
      'balises' => 
      array (
        0 => 'BG10',
        1 => 'BG11',
        2 => 'BG12',
      ),
      'or' => 's',
      'x' => 12,
      'y' => 3,
      'l' => 1,
    ),
    'tr32' => 
    array (
      'balises' => 
      array (
        0 => 'BG39',
      ),
      'or' => 's',
      'x' => 42,
      'y' => 7,
      'l' => 1,
    ),
    'tr34' => 
    array (
      'balises' => 
      array (
        0 => 'BG43',
        1 => 'BG44',
      ),
      'or' => 's',
      'x' => 45,
      'y' => 7,
      'l' => 1,
    ),
  ),
  'projectName' => 'CBU_2022',
  'arsIndicator' => 
  array (
    'x' => 7.125,
    'y' => 7.125,
  ),
  'eStopIndicator' => 
  array (
    'x' => 1.125,
    'y' => 7.125,
  ),
);
?>