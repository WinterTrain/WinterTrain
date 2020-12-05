<?php
// ------------------------------------------------- Sources
$PT2_SIGNALLING_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/CBU_2020/signallingLayout.sch";
$PT2_SIGNALLING_LAYOUT_FILE_DATE = "2020-12-04 15:24:35";
$PT2_SCREEN_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/CBU_2020/screenLayout.sch";
$PT2_SCREEN_LAYOUT_FILE_DATE = "2020-12-04 12:09:28";
$PT2_GENERATION_TIME = "2020-12-04 15:24:39";
$PT1_PROJECT_NAME = "WinterHut _2020";
$PT1_DATE = "2020-12-04";
$PT1_AUTHOR = "JB";
$HMI_PROJECT_NAME = "WinterHut_2020";

// -------------------------------------------------- PT1
$PT1 = array (
  'BG202' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG204',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BS3',
      'dist' => 1,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG204' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S104',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG202',
      'dist' => 1,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'S101' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG02',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S104',
      'dist' => 10,
    ),
    'type' => 'MB',
    'EC' => 
    array (
      'addr' => 0,
      'type' => 0,
      'majorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 3,
      'y' => 4,
      'l' => 1,
    ),
  ),
  'S104' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'S101',
      'dist' => 6,
    ),
    'D' => 
    array (
      'name' => 'BG204',
      'dist' => 1,
    ),
    'type' => 'MB',
    'EC' => 
    array (
      'addr' => 0,
      'type' => 0,
      'majorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 2,
      'y' => 5,
      'l' => 1,
    ),
  ),
  'BG121' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG21',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'S12',
      'dist' => 7,
    ),
    'ID' => '75:00:14:FB:94',
  ),
  'BG129' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S17',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'BG29',
      'dist' => 7,
    ),
    'ID' => '1E:00:AC:98:25',
  ),
  'BG128' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S15',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'BG28',
      'dist' => 7,
    ),
    'ID' => '1F:00:50:6E:08',
  ),
  'BG117' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG17',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'S10',
      'dist' => 16,
    ),
    'ID' => '1F:00:69:F3:BB',
  ),
  'BG104' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG04',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'S4',
      'dist' => 7,
    ),
    'ID' => '1F:00:62:A3:D5',
  ),
  'BG03' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P1',
      'dist' => 13,
    ),
    'D' => 
    array (
      'name' => 'BG103',
      'dist' => 6,
    ),
    'ID' => '1F:00:69:C9:3C',
  ),
  'BG115' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S7',
      'dist' => 15,
    ),
    'D' => 
    array (
      'name' => 'BG15',
      'dist' => 5,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG122' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG22',
      'dist' => 5,
    ),
    'D' => 
    array (
      'name' => 'S14',
      'dist' => 5,
    ),
    'ID' => '1E:00:EC:F9:E1',
  ),
  'BG102' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S4',
      'dist' => 107,
    ),
    'D' => 
    array (
      'name' => 'BG02',
      'dist' => 85,
    ),
    'ID' => '76:00:0D:19:5B',
  ),
  'BG33' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BS4',
      'dist' => 40,
    ),
    'D' => 
    array (
      'name' => 'BG31',
      'dist' => 25,
    ),
    'ID' => '1E:00:90:0C:A5',
  ),
  'BG32' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BS2',
      'dist' => 45,
    ),
    'D' => 
    array (
      'name' => 'BG30',
      'dist' => 25,
    ),
    'ID' => '1F:00:62:25:B2',
  ),
  'BG126' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S18',
      'dist' => 119,
    ),
    'D' => 
    array (
      'name' => 'BG26',
      'dist' => 100,
    ),
    'ID' => '74:00:11:07:0B',
  ),
  'BS4' => 
  array (
    'element' => 'BSE',
    'D' => 
    array (
      'name' => 'BG33',
      'dist' => 40,
    ),
    'HMI' => 
    array (
      'x' => 42,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'BG31' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG33',
      'dist' => 25,
    ),
    'D' => 
    array (
      'name' => 'S17',
      'dist' => 30,
    ),
    'ID' => '73:00:56:D9:B2',
  ),
  'BG30' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG32',
      'dist' => 25,
    ),
    'D' => 
    array (
      'name' => 'S15',
      'dist' => 30,
    ),
    'ID' => '76:00:0C:FC:CB',
  ),
  'BG28' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG128',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'P6',
      'dist' => 1,
    ),
    'ID' => '1E:00:B0:50:33',
  ),
  'BG29' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG129',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'P6',
      'dist' => 1,
    ),
    'ID' => '1E:00:90:0C:8B',
  ),
  'BG27' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P6',
      'dist' => 3,
    ),
    'D' => 
    array (
      'name' => 'S18',
      'dist' => 25,
    ),
    'ID' => '1E:00:EA:E8:E9',
  ),
  'BG26' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG126',
      'dist' => 105,
    ),
    'D' => 
    array (
      'name' => 'S13',
      'dist' => 171,
    ),
    'ID' => '1F:00:62:74:36',
  ),
  'BG25' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S16',
      'dist' => 164,
    ),
    'D' => 
    array (
      'name' => 'BG24',
      'dist' => 105,
    ),
    'ID' => '1F:00:4D:FF:CC',
  ),
  'BG24' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG25',
      'dist' => 100,
    ),
    'D' => 
    array (
      'name' => 'S11',
      'dist' => 185,
    ),
    'ID' => '73:00:56:D6:F2',
  ),
  'BG23' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S11',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P5',
      'dist' => 5,
    ),
    'ID' => '76:00:0C:E3:A8',
  ),
  'BG22' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P5',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG122',
      'dist' => 9,
    ),
    'ID' => '74:00:15:65:29',
  ),
  'BG21' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P5',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG121',
      'dist' => 7,
    ),
    'ID' => '1F:00:50:3C:80',
  ),
  'BG20' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S14',
      'dist' => 111,
    ),
    'D' => 
    array (
      'name' => 'S9',
      'dist' => 59,
    ),
    'ID' => '76:00:0C:FA:7D',
  ),
  'BG19' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S12',
      'dist' => 81,
    ),
    'D' => 
    array (
      'name' => 'S7',
      'dist' => 89,
    ),
    'ID' => '76:00:0C:A4:29',
  ),
  'BG15' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG115',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'P3',
      'dist' => 1,
    ),
    'ID' => '1E:00:98:B1:C9',
  ),
  'BG18' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S9',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P4',
      'dist' => 1,
    ),
    'ID' => '1E:00:57:35:F9',
  ),
  'S9' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG20',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG18',
      'dist' => 10,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 204,
      'type' => 45,
      'majorDevice' => 4,
    ),
    'HMI' => 
    array (
      'x' => 23,
      'y' => 6,
      'l' => 1,
    ),
  ),
  'S7' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG19',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG115',
      'dist' => 10,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 204,
      'type' => 45,
      'majorDevice' => 1,
    ),
    'HMI' => 
    array (
      'x' => 23,
      'y' => 2,
      'l' => 1,
    ),
  ),
  'BG17' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P4',
      'dist' => 2,
    ),
    'D' => 
    array (
      'name' => 'BG117',
      'dist' => 7,
    ),
    'ID' => '76:00:0C:B7:36',
  ),
  'BG14' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P3',
      'dist' => 3,
    ),
    'D' => 
    array (
      'name' => 'S8',
      'dist' => 1,
    ),
    'ID' => '1F:00:4A:F2:D2',
  ),
  'BG13' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S10',
      'dist' => 110,
    ),
    'D' => 
    array (
      'name' => 'BG12',
      'dist' => 63,
    ),
    'ID' => '73:00:56:9F:A1',
  ),
  'S10' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG117',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BG13',
      'dist' => 1,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 204,
      'type' => 45,
      'majorDevice' => 10,
    ),
    'HMI' => 
    array (
      'x' => 18,
      'y' => 5,
      'l' => 2,
    ),
  ),
  'S8' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG14',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BG11',
      'dist' => 1,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 204,
      'type' => 45,
      'majorDevice' => 7,
    ),
    'HMI' => 
    array (
      'x' => 18,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'BG16' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P4',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P3',
      'dist' => 1,
    ),
    'ID' => '1E:00:8E:57:F6',
  ),
  'BG11' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S8',
      'dist' => 111,
    ),
    'D' => 
    array (
      'name' => 'BG10',
      'dist' => 64,
    ),
    'ID' => '1E:00:8E:B8:97',
  ),
  'BG12' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG13',
      'dist' => 62,
    ),
    'D' => 
    array (
      'name' => 'S5',
      'dist' => 140,
    ),
    'ID' => '73:00:70:98:69',
  ),
  'BG10' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG11',
      'dist' => 65,
    ),
    'D' => 
    array (
      'name' => 'S3',
      'dist' => 139,
    ),
    'ID' => '73:00:56:C0:72',
  ),
  'S5' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG12',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG09',
      'dist' => 10,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 43,
      'majorDevice' => 7,
    ),
    'HMI' => 
    array (
      'x' => 14,
      'y' => 4,
      'l' => 2,
    ),
  ),
  'S3' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG10',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG08',
      'dist' => 10,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 204,
      'type' => 45,
      'majorDevice' => 13,
    ),
    'HMI' => 
    array (
      'x' => 14,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'BG09' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S5',
      'dist' => 17,
    ),
    'D' => 
    array (
      'name' => 'P2',
      'dist' => 2,
    ),
    'ID' => '1F:00:61:E5:87',
  ),
  'BG08' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S3',
      'dist' => 16,
    ),
    'D' => 
    array (
      'name' => 'P2',
      'dist' => 1,
    ),
    'ID' => '1F:00:4D:6A:29',
  ),
  'BG07' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P2',
      'dist' => 3,
    ),
    'D' => 
    array (
      'name' => 'S6',
      'dist' => 1,
    ),
    'ID' => '1E:00:8E:DF:1F',
  ),
  'S6' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG07',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BG06',
      'dist' => 1,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 45,
      'majorDevice' => 4,
    ),
    'HMI' => 
    array (
      'x' => 11,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'BG06' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S6',
      'dist' => 126,
    ),
    'D' => 
    array (
      'name' => 'S1',
      'dist' => 155,
    ),
    'ID' => '1F:00:4A:FE:5F',
  ),
  'BG05' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S1',
      'dist' => 11,
    ),
    'D' => 
    array (
      'name' => 'P1',
      'dist' => 4,
    ),
    'ID' => '1F:00:69:A9:3C',
  ),
  'BG02' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG102',
      'dist' => 85,
    ),
    'D' => 
    array (
      'name' => 'S101',
      'dist' => 5,
    ),
    'ID' => '1E:00:AC:75:9B',
  ),
  'BG04' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P1',
      'dist' => 12,
    ),
    'D' => 
    array (
      'name' => 'BG104',
      'dist' => 7,
    ),
    'ID' => '74:00:15:50:C0',
  ),
  'BG103' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG03',
      'dist' => 6,
    ),
    'D' => 
    array (
      'name' => 'S2',
      'dist' => 6,
    ),
    'ID' => '1F:00:4D:6D:73',
  ),
  'S1' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG06',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG05',
      'dist' => 10,
    ),
    'type' => 'MS2',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 41,
      'majorDevice' => 3,
    ),
    'HMI' => 
    array (
      'x' => 9,
      'y' => 2,
      'l' => 1,
    ),
  ),
  'S4' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG104',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'BG102',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 41,
      'majorDevice' => 2,
    ),
    'HMI' => 
    array (
      'x' => 5,
      'y' => 5,
      'l' => 2,
    ),
  ),
  'S2' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG103',
      'dist' => 6,
    ),
    'D' => 
    array (
      'name' => 'BG01',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 41,
      'majorDevice' => 1,
    ),
    'HMI' => 
    array (
      'x' => 5,
      'y' => 3,
      'l' => 2,
    ),
  ),
  'P6' => 
  array (
    'element' => 'PF',
    'T' => 
    array (
      'name' => 'BG27',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG29',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG28',
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
      'x' => 37,
      'y' => 1,
      'l' => 2,
    ),
  ),
  'S17' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG31',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG129',
      'dist' => 7,
    ),
    'type' => 'MS2',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 41,
      'majorDevice' => 3,
    ),
    'HMI' => 
    array (
      'x' => 39,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'S15' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG30',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG128',
      'dist' => 7,
    ),
    'type' => 'MS2',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 41,
      'majorDevice' => 1,
    ),
    'HMI' => 
    array (
      'x' => 39,
      'y' => 0,
      'l' => 2,
    ),
  ),
  'S13' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG26',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S16',
      'dist' => 11,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 44,
      'majorDevice' => 10,
    ),
    'HMI' => 
    array (
      'x' => 33,
      'y' => 2,
      'l' => 1,
    ),
  ),
  'S18' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG27',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BG126',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 41,
      'majorDevice' => 2,
    ),
    'HMI' => 
    array (
      'x' => 36,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'S16' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'S13',
      'dist' => 11,
    ),
    'D' => 
    array (
      'name' => 'BG25',
      'dist' => 1,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 44,
      'majorDevice' => 13,
    ),
    'HMI' => 
    array (
      'x' => 32,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'S14' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG122',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BG20',
      'dist' => 1,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 44,
      'majorDevice' => 7,
    ),
    'HMI' => 
    array (
      'x' => 25,
      'y' => 7,
      'l' => 1,
    ),
  ),
  'BS3' => 
  array (
    'element' => 'BSB',
    'U' => 
    array (
      'name' => 'BG202',
      'dist' => 20,
    ),
    'HMI' => 
    array (
      'x' => 0,
      'y' => 5,
      'l' => 1,
    ),
  ),
  'P1' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'BG05',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG03',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG04',
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
      'or' => 'tl',
      'x' => 7,
      'y' => 3,
      'l' => 2,
    ),
  ),
  'S12' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG121',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'BG19',
      'dist' => 1,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 44,
      'majorDevice' => 1,
    ),
    'HMI' => 
    array (
      'x' => 25,
      'y' => 3,
      'l' => 2,
    ),
  ),
  'S11' => 
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
      'dist' => 5,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 44,
      'majorDevice' => 4,
    ),
    'HMI' => 
    array (
      'x' => 29,
      'y' => 2,
      'l' => 1,
    ),
  ),
  'P4' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'BG18',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG16',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG17',
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
      'x' => 21,
      'y' => 5,
      'l' => 2,
    ),
  ),
  'P5' => 
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
      'dist' => 23,
    ),
    'L' => 
    array (
      'name' => 'BG22',
      'dist' => 23,
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
      'x' => 27,
      'y' => 3,
      'l' => 2,
    ),
  ),
  'P3' => 
  array (
    'element' => 'PF',
    'T' => 
    array (
      'name' => 'BG14',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG16',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG15',
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
      'x' => 19,
      'y' => 3,
      'l' => 2,
    ),
  ),
  'P2' => 
  array (
    'element' => 'PF',
    'T' => 
    array (
      'name' => 'BG07',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG09',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG08',
      'dist' => 30,
    ),
    'supervisionState' => 'P',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 10,
      'majorDevice' => 1,
      'minorDevice' => 0,
    ),
    'HMI' => 
    array (
      'or' => 'fr',
      'x' => 12,
      'y' => 3,
      'l' => 2,
    ),
  ),
  'BS2' => 
  array (
    'element' => 'BSE',
    'D' => 
    array (
      'name' => 'BG32',
      'dist' => 50,
    ),
    'HMI' => 
    array (
      'x' => 42,
      'y' => 1,
      'l' => 1,
    ),
  ),
  'BS1' => 
  array (
    'element' => 'BSB',
    'U' => 
    array (
      'name' => 'BG01',
      'dist' => 50,
    ),
    'HMI' => 
    array (
      'x' => 3,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'BG01' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S2',
      'dist' => 97,
    ),
    'D' => 
    array (
      'name' => 'BS1',
      'dist' => 5,
    ),
    'ID' => '1F:00:78:EF:FD',
  ),
);

// -------------------------------------------------- HMI
$HMI = array (
  'label' => 
  array (
    0 => 
    array (
      'x' => 36,
      'y' => 1,
      'text' => 'Langtbortistan',
    ),
    1 => 
    array (
      'x' => 24.75,
      'y' => 1.25,
      'text' => 'Christiania',
    ),
    2 => 
    array (
      'x' => 5,
      'y' => 1,
      'text' => 'Christianshavn',
    ),
    3 => 
    array (
      'x' => 16,
      'y' => 1.25,
      'text' => 'Kystbanen',
    ),
    4 => 
    array (
      'x' => 5,
      'y' => 2,
      'text' => '------------VÆG-------------',
    ),
    5 => 
    array (
      'x' => 40,
      'y' => 5,
      'text' => '------------VÆG-------------',
    ),
    6 => 
    array (
      'x' => 30,
      'y' => 1.25,
      'text' => 'Alperne',
    ),
    7 => 
    array (
      'x' => 1,
      'y' => 7,
      'text' => 'Stalden',
    ),
  ),
  'arsIndicator' => 
  array (
    'x' => 11,
    'y' => 7,
  ),
  'eStopIndicator' => 
  array (
    'x' => 14,
    'y' => 7,
  ),
  'projectName' => 'WinterHut_2020',
  'baliseTrack' => 
  array (
    'tr10' => 
    array (
      'balises' => 
      array (
        0 => 'BG22',
      ),
      'or' => 'u',
      'x' => 26,
      'y' => 5,
      'l' => 1,
    ),
    'tr5c' => 
    array (
      'balises' => 
      array (
        0 => 'BG17',
      ),
      'or' => 'd',
      'x' => 20,
      'y' => 5,
      'l' => 1,
    ),
    'tr1' => 
    array (
      'balises' => 
      array (
        0 => 'BG01',
      ),
      'or' => 's',
      'x' => 4,
      'y' => 3,
      'l' => 1,
    ),
    'tr2' => 
    array (
      'balises' => 
      array (
        0 => 'BG02',
        1 => 'BG102',
      ),
      'or' => 's',
      'x' => 4,
      'y' => 5,
      'l' => 1,
    ),
    'tr3' => 
    array (
      'balises' => 
      array (
        0 => 'BG06',
      ),
      'or' => 's',
      'x' => 10,
      'y' => 3,
      'l' => 1,
    ),
    'tr5b' => 
    array (
      'balises' => 
      array (
        0 => 'BG13',
      ),
      'or' => 's',
      'x' => 17,
      'y' => 5,
      'l' => 1,
    ),
    'tr6' => 
    array (
      'balises' => 
      array (
        0 => 'BG15',
      ),
      'or' => 's',
      'x' => 21,
      'y' => 3,
      'l' => 2,
    ),
    'tr8' => 
    array (
      'balises' => 
      array (
        0 => 'BG19',
      ),
      'or' => 's',
      'x' => 24,
      'y' => 3,
      'l' => 1,
    ),
    'tr9' => 
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
    'tr11a' => 
    array (
      'balises' => 
      array (
        0 => 'BG24',
      ),
      'or' => 's',
      'x' => 30,
      'y' => 3,
      'l' => 1,
    ),
    'tr12a' => 
    array (
      'balises' => 
      array (
        0 => 'BG26',
      ),
      'or' => 's',
      'x' => 34,
      'y' => 3,
      'l' => 1,
    ),
    'tr13' => 
    array (
      'balises' => 
      array (
        0 => 'BG30',
        1 => 'BG32',
      ),
      'or' => 's',
      'x' => 41,
      'y' => 1,
      'l' => 1,
    ),
    'tr14' => 
    array (
      'balises' => 
      array (
        0 => 'BG31',
        1 => 'BG33',
      ),
      'or' => 's',
      'x' => 41,
      'y' => 3,
      'l' => 1,
    ),
    'tr4a' => 
    array (
      'balises' => 
      array (
        0 => 'BG10',
      ),
      'or' => 's',
      'x' => 16,
      'y' => 3,
      'l' => 1,
    ),
    'tr4b' => 
    array (
      'balises' => 
      array (
        0 => 'BG11',
      ),
      'or' => 's',
      'x' => 17,
      'y' => 3,
      'l' => 1,
    ),
    'tr5a' => 
    array (
      'balises' => 
      array (
        0 => 'BG12',
      ),
      'or' => 's',
      'x' => 16,
      'y' => 5,
      'l' => 1,
    ),
    'tr12b' => 
    array (
      'balises' => 
      array (
        0 => 'BG126',
      ),
      'or' => 's',
      'x' => 35,
      'y' => 3,
      'l' => 1,
    ),
    'tr11b' => 
    array (
      'balises' => 
      array (
        0 => 'BG25',
      ),
      'or' => 's',
      'x' => 31,
      'y' => 3,
      'l' => 1,
    ),
    'tr102' => 
    array (
      'balises' => 
      array (
        0 => 'BG202',
        1 => 'BG204',
      ),
      'or' => 's',
      'x' => 1,
      'y' => 5,
      'l' => 1,
    ),
  ),
);
?>