<?php
// ------------------------------------------------- Sources
$PT2_SIGNALLING_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/CBU_2021/signallingLayout.sch";
$PT2_SIGNALLING_LAYOUT_FILE_DATE = "2021-12-30 13:23:11";
$PT2_SCREEN_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/CBU_2021/screenLayout.sch";
$PT2_SCREEN_LAYOUT_FILE_DATE = "2021-11-16 13:27:11";
$PT2_GENERATION_TIME = "2021-12-30 13:23:41";
$PT1_PROJECT_NAME = "WinterHut _2021";
$PT1_DATE = "2021-11-13";
$PT1_AUTHOR = "JB";
$HMI_PROJECT_NAME = "WinterHut_2021";

// -------------------------------------------------- PT1
$PT1 = array (
  'PHT1' => 
  array (
    'element' => 'PHTD',
    'U' => 
    array (
      'name' => 'S16',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG36',
      'dist' => 23,
    ),
    'holdPoint' => 'P5',
  ),
  'BG16' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S9',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'P2',
      'dist' => 10,
    ),
    'ID' => '1E:00:8E:57:F6',
  ),
  'BG15' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S8',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'P2',
      'dist' => 10,
    ),
    'ID' => '1F:00:4A:FE:5F',
  ),
  'BG53' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BS4',
      'dist' => 49,
    ),
    'D' => 
    array (
      'name' => 'BG52',
      'dist' => 20,
    ),
    'ID' => '74:00:15:65:29',
  ),
  'BG52' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG53',
      'dist' => 20,
    ),
    'D' => 
    array (
      'name' => 'S23',
      'dist' => 30,
    ),
    'ID' => '76:00:0C:A4:29',
  ),
  'BG44b' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S22',
      'dist' => 43,
    ),
    'D' => 
    array (
      'name' => 'BG44a',
      'dist' => 82,
    ),
    'ID' => '1F:00:62:A3:D5',
  ),
  'BG44a' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG44b',
      'dist' => 82,
    ),
    'D' => 
    array (
      'name' => 'BG44',
      'dist' => 74,
    ),
    'ID' => '1E:00:90:0C:A5',
  ),
  'S21' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG49',
      'dist' => 86,
    ),
    'D' => 
    array (
      'name' => 'BG47',
      'dist' => 1,
    ),
    'type' => 'MB',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 41,
      'majorDevice' => 2,
    ),
    'HMI' => 
    array (
      'x' => 45,
      'y' => 10,
      'l' => 1,
    ),
  ),
  'S20' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG44',
      'dist' => 45,
    ),
    'D' => 
    array (
      'name' => 'BG43',
      'dist' => 1,
    ),
    'type' => 'MB',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 41,
      'majorDevice' => 2,
    ),
    'HMI' => 
    array (
      'x' => 44,
      'y' => 6,
      'l' => 1,
    ),
  ),
  'BG3a' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S3',
      'dist' => 21,
    ),
    'D' => 
    array (
      'name' => 'BG3',
      'dist' => 39,
    ),
    'ID' => '1E:00:EA:E8:E9',
  ),
  'BG46' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S19',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P6',
      'dist' => 1,
    ),
    'ID' => '00:00:00:00:01',
  ),
  'BG4' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG5',
      'dist' => 14,
    ),
    'D' => 
    array (
      'name' => 'BS2',
      'dist' => 55,
    ),
    'ID' => '00:00:00:00:02',
  ),
  'BG3' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG3a',
      'dist' => 39,
    ),
    'D' => 
    array (
      'name' => 'S2',
      'dist' => 50,
    ),
    'ID' => '76:00:0C:B7:36',
  ),
  'S3' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG6',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG3a',
      'dist' => 21,
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
      'y' => 3,
      'l' => 1,
    ),
  ),
  'BG45' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG47',
      'dist' => 50,
    ),
    'D' => 
    array (
      'name' => 'S19',
      'dist' => 32,
    ),
    'ID' => '1F:00:50:6E:08',
  ),
  'BG47' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S21',
      'dist' => 33,
    ),
    'D' => 
    array (
      'name' => 'BG45',
      'dist' => 51,
    ),
    'ID' => '73:00:56:D9:B2',
  ),
  'BG49' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG51',
      'dist' => 70,
    ),
    'D' => 
    array (
      'name' => 'S21',
      'dist' => 86,
    ),
    'ID' => '73:00:70:98:69',
  ),
  'BG51' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S23',
      'dist' => 146,
    ),
    'D' => 
    array (
      'name' => 'BG49',
      'dist' => 70,
    ),
    'ID' => '73:00:56:9F:A1',
  ),
  'S23' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG52',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG51',
      'dist' => 10,
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
      'x' => 48,
      'y' => 9,
      'l' => 1,
    ),
  ),
  'S2' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG3',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S1',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 41,
      'majorDevice' => 8,
    ),
    'HMI' => 
    array (
      'x' => 3,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'S1' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'S2',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG2',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 41,
      'majorDevice' => 7,
    ),
    'HMI' => 
    array (
      'x' => 2,
      'y' => 4,
      'l' => 1,
    ),
  ),
  'BG20' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S10',
      'dist' => 124,
    ),
    'D' => 
    array (
      'name' => 'BG18',
      'dist' => 52,
    ),
    'ID' => '74:00:15:50:C0',
  ),
  'BG23' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S11',
      'dist' => 124,
    ),
    'D' => 
    array (
      'name' => 'BG21',
      'dist' => 52,
    ),
    'ID' => '1F:00:61:E5:87',
  ),
  'BG29' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S14',
      'dist' => 56,
    ),
    'D' => 
    array (
      'name' => 'BG28',
      'dist' => 31,
    ),
    'ID' => '1E:00:AC:75:9B',
  ),
  'BG32' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S15',
      'dist' => 55,
    ),
    'D' => 
    array (
      'name' => 'BG30',
      'dist' => 32,
    ),
    'ID' => '1F:00:4D:6A:29',
  ),
  'BG39' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG40',
      'dist' => 38,
    ),
    'D' => 
    array (
      'name' => 'BG38',
      'dist' => 69,
    ),
    'ID' => '73:00:56:D6:F2',
  ),
  'BG38' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG39',
      'dist' => 69,
    ),
    'D' => 
    array (
      'name' => 'BG37',
      'dist' => 27,
    ),
    'ID' => '76:00:0D:19:5B',
  ),
  'P5' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'BG36',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG33',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG35',
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
      'or' => 'tr',
      'x' => 32,
      'y' => 4,
      'l' => 2,
    ),
  ),
  'P4' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'S12',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG24',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG25',
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
      'or' => 'tl',
      'x' => 25,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'P3' => 
  array (
    'element' => 'PF',
    'T' => 
    array (
      'name' => 'S11',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG26',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG25',
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
      'x' => 23,
      'y' => 4,
      'l' => 2,
    ),
  ),
  'BG43' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S20',
      'dist' => 34,
    ),
    'D' => 
    array (
      'name' => 'BG42',
      'dist' => 48,
    ),
    'ID' => '73:00:56:C0:72',
  ),
  'BG42' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG43',
      'dist' => 50,
    ),
    'D' => 
    array (
      'name' => 'S18',
      'dist' => 33,
    ),
    'ID' => '1E:00:56:DE:2A',
  ),
  'S19' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG45',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG46',
      'dist' => 22,
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
      'x' => 42,
      'y' => 9,
      'l' => 2,
    ),
  ),
  'S18' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG42',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P6',
      'dist' => 22,
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
      'x' => 41,
      'y' => 5,
      'l' => 2,
    ),
  ),
  'P6' => 
  array (
    'element' => 'PF',
    'T' => 
    array (
      'name' => 'S17',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG46',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'S18',
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
      'or' => 'fr',
      'x' => 39,
      'y' => 6,
      'l' => 2,
    ),
  ),
  'BG1' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG2',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BS1',
      'dist' => 1,
    ),
    'ID' => '00:00:00:00:03',
  ),
  'BG2' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S1',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG1',
      'dist' => 1,
    ),
    'ID' => '00:00:00:00:04',
  ),
  'BG31' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG33',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'S14',
      'dist' => 7,
    ),
    'ID' => '00:00:00:00:05',
  ),
  'BG26' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG27',
      'dist' => 27,
    ),
    'D' => 
    array (
      'name' => 'P3',
      'dist' => 2,
    ),
    'ID' => '1E:00:90:0C:8B',
  ),
  'BG10' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P1',
      'dist' => 11,
    ),
    'D' => 
    array (
      'name' => 'S5',
      'dist' => 13,
    ),
    'ID' => '1E:00:EC:F9:E1',
  ),
  'BG9' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P1',
      'dist' => 12,
    ),
    'D' => 
    array (
      'name' => 'S4',
      'dist' => 13,
    ),
    'ID' => '1F:00:78:EF:FD',
  ),
  'BG34' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG35',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'S15',
      'dist' => 7,
    ),
    'ID' => '00:00:00:00:06',
  ),
  'BG7' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S5',
      'dist' => 27,
    ),
    'D' => 
    array (
      'name' => 'BG6',
      'dist' => 26,
    ),
    'ID' => '76:00:0C:E3:A8',
  ),
  'BG50' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BS3',
      'dist' => 53,
    ),
    'D' => 
    array (
      'name' => 'BG48',
      'dist' => 35,
    ),
    'ID' => '1F:00:69:F3:BB',
  ),
  'BG40' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S17',
      'dist' => 75,
    ),
    'D' => 
    array (
      'name' => 'BG39',
      'dist' => 38,
    ),
    'ID' => '00:00:00:00:07',
  ),
  'BS4' => 
  array (
    'element' => 'BSE',
    'D' => 
    array (
      'name' => 'BG53',
      'dist' => 1,
    ),
    'HMI' => 
    array (
      'x' => 50,
      'y' => 10,
      'l' => 1,
    ),
  ),
  'BG48' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG50',
      'dist' => 34,
    ),
    'D' => 
    array (
      'name' => 'S22',
      'dist' => 16,
    ),
    'ID' => '1F:00:62:74:36',
  ),
  'BG44' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG44a',
      'dist' => 74,
    ),
    'D' => 
    array (
      'name' => 'S20',
      'dist' => 45,
    ),
    'ID' => '1F:00:4D:FF:CC',
  ),
  'BG37' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG38',
      'dist' => 27,
    ),
    'D' => 
    array (
      'name' => 'S16',
      'dist' => 56,
    ),
    'ID' => '00:00:00:00:08',
  ),
  'BG36' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'PHT1',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P5',
      'dist' => 4,
    ),
    'ID' => '1F:00:62:25:B2',
  ),
  'BG35' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P5',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG34',
      'dist' => 7,
    ),
    'ID' => '76:00:0C:FC:CB',
  ),
  'BG33' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P5',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG31',
      'dist' => 8,
    ),
    'ID' => '76:00:0D:1A:2E',
  ),
  'BG30' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG32',
      'dist' => 32,
    ),
    'D' => 
    array (
      'name' => 'S13',
      'dist' => 47,
    ),
    'ID' => '1E:00:57:35:F9',
  ),
  'BG28' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG29',
      'dist' => 31,
    ),
    'D' => 
    array (
      'name' => 'S12',
      'dist' => 46,
    ),
    'ID' => '1F:00:69:A9:3C',
  ),
  'BG24' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P4',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG22',
      'dist' => 19,
    ),
    'ID' => '1E:00:57:4C:C8',
  ),
  'S13' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG30',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG27',
      'dist' => 27,
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
      'x' => 26,
      'y' => 5,
      'l' => 2,
    ),
  ),
  'S12' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG28',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P4',
      'dist' => 33,
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
      'x' => 27,
      'y' => 1,
      'l' => 1,
    ),
  ),
  'BG27' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S13',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG26',
      'dist' => 1,
    ),
    'ID' => '00:00:00:00:09',
  ),
  'BG22' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG24',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S10',
      'dist' => 1,
    ),
    'ID' => '00:00:00:00:0A',
  ),
  'BG21' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG23',
      'dist' => 154,
    ),
    'D' => 
    array (
      'name' => 'BG19',
      'dist' => 24,
    ),
    'ID' => '1E:00:B0:50:33',
  ),
  'S11' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'P3',
      'dist' => 16,
    ),
    'D' => 
    array (
      'name' => 'BG23',
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
      'x' => 22,
      'y' => 6,
      'l' => 1,
    ),
  ),
  'S10' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG22',
      'dist' => 19,
    ),
    'D' => 
    array (
      'name' => 'BG20',
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
      'x' => 22,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'BG25' => 
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
    'ID' => '1F:00:4A:F2:D2',
  ),
  'BG18' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG20',
      'dist' => 154,
    ),
    'D' => 
    array (
      'name' => 'BG17',
      'dist' => 24,
    ),
    'ID' => '1E:00:8E:DF:1F',
  ),
  'BG19' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG21',
      'dist' => 166,
    ),
    'D' => 
    array (
      'name' => 'S9',
      'dist' => 11,
    ),
    'ID' => '1E:00:AC:98:25',
  ),
  'BG17' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG18',
      'dist' => 166,
    ),
    'D' => 
    array (
      'name' => 'S8',
      'dist' => 11,
    ),
    'ID' => '1F:00:50:3C:80',
  ),
  'S9' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG19',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG16',
      'dist' => 20,
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
      'x' => 17,
      'y' => 5,
      'l' => 2,
    ),
  ),
  'S8' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG17',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG15',
      'dist' => 20,
    ),
    'type' => 'MS2',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 41,
      'majorDevice' => 5,
    ),
    'HMI' => 
    array (
      'x' => 17,
      'y' => 1,
      'l' => 2,
    ),
  ),
  'S7' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'P2',
      'dist' => 7,
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
      'majorDevice' => 13,
    ),
    'HMI' => 
    array (
      'x' => 13,
      'y' => 2,
      'l' => 1,
    ),
  ),
  'BG13' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S7',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S6',
      'dist' => 114,
    ),
    'ID' => '1F:00:68:D5:C8',
  ),
  'BG12' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S6',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P1',
      'dist' => 8,
    ),
    'ID' => '74:00:11:07:0B',
  ),
  'BG6' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG7',
      'dist' => 26,
    ),
    'D' => 
    array (
      'name' => 'S3',
      'dist' => 20,
    ),
    'ID' => '00:00:00:00:0B',
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
      'name' => 'BG12',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 41,
      'majorDevice' => 6,
    ),
    'HMI' => 
    array (
      'x' => 11,
      'y' => 1,
      'l' => 1,
    ),
  ),
  'S5' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG10',
      'dist' => 12,
    ),
    'D' => 
    array (
      'name' => 'BG7',
      'dist' => 1,
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
      'x' => 7,
      'y' => 4,
      'l' => 2,
    ),
  ),
  'S4' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG9',
      'dist' => 12,
    ),
    'D' => 
    array (
      'name' => 'BG5',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 41,
      'majorDevice' => 4,
    ),
    'HMI' => 
    array (
      'x' => 7,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'S22' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG48',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG44b',
      'dist' => 40,
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
      'x' => 47,
      'y' => 5,
      'l' => 1,
    ),
  ),
  'S17' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'P6',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'BG40',
      'dist' => 1,
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
      'x' => 37,
      'y' => 6,
      'l' => 2,
    ),
  ),
  'S15' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG34',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'BG32',
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
      'x' => 29,
      'y' => 6,
      'l' => 2,
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
    'HMI' => 
    array (
      'x' => 0,
      'y' => 4,
      'l' => 1,
    ),
  ),
  'P1' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'BG12',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG9',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG10',
      'dist' => 30,
    ),
    'supervisionState' => 'P',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 10,
      'majorDevice' => 2,
      'minorDevice' => 0,
    ),
    'HMI' => 
    array (
      'or' => 'tl',
      'x' => 9,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'S14' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG31',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'BG29',
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
      'x' => 29,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'S16' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG37',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'PHT1',
      'dist' => 1,
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
      'x' => 34,
      'y' => 5,
      'l' => 1,
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
      'addr' => 202,
      'type' => 10,
      'majorDevice' => 1,
      'minorDevice' => 0,
    ),
    'HMI' => 
    array (
      'or' => 'fr',
      'x' => 14,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'BS3' => 
  array (
    'element' => 'BSE',
    'D' => 
    array (
      'name' => 'BG50',
      'dist' => 1,
    ),
    'HMI' => 
    array (
      'x' => 49,
      'y' => 6,
      'l' => 1,
    ),
  ),
  'BS2' => 
  array (
    'element' => 'BSB',
    'U' => 
    array (
      'name' => 'BG4',
      'dist' => 1,
    ),
    'HMI' => 
    array (
      'x' => 5,
      'y' => 2,
      'l' => 1,
    ),
  ),
  'BG5' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S4',
      'dist' => 23,
    ),
    'D' => 
    array (
      'name' => 'BG4',
      'dist' => 13,
    ),
    'ID' => '1E:00:57:5B:28',
  ),
);

// -------------------------------------------------- HMI
$HMI = array (
  'label' => 
  array (
    0 => 
    array (
      'x' => 48,
      'y' => 5,
      'text' => 'Langtbortistan',
    ),
    1 => 
    array (
      'x' => 29,
      'y' => 1,
      'text' => 'Christiania',
    ),
    2 => 
    array (
      'x' => 7,
      'y' => 1,
      'text' => 'Christianshavn',
    ),
    3 => 
    array (
      'x' => 20,
      'y' => 1,
      'text' => 'Kystbanen',
    ),
    4 => 
    array (
      'x' => 36,
      'y' => 5,
      'text' => 'Alperne',
    ),
    5 => 
    array (
      'x' => 1,
      'y' => 3,
      'text' => 'Stalden',
    ),
    6 => 
    array (
      'x' => 49,
      'y' => 9,
      'text' => 'Højbanen',
    ),
    7 => 
    array (
      'x' => 3,
      'y' => 6,
      'text' => 'Port',
    ),
  ),
  'arsIndicator' => 
  array (
    'x' => 5,
    'y' => 8.375,
  ),
  'eStopIndicator' => 
  array (
    'x' => 8,
    'y' => 8.375,
  ),
  'projectName' => 'WinterHut_2021',
  'baliseTrack' => 
  array (
    'tr7' => 
    array (
      'balises' => 
      array (
        0 => 'BG16',
      ),
      'or' => 'd',
      'x' => 16,
      'y' => 4,
      'l' => 1,
    ),
    'tr2' => 
    array (
      'balises' => 
      array (
        0 => 'BG4',
        1 => 'BG5',
      ),
      'or' => 's',
      'x' => 6,
      'y' => 2,
      'l' => 1,
    ),
    'tr3' => 
    array (
      'balises' => 
      array (
        0 => 'BG6',
        1 => 'BG7',
      ),
      'or' => 's',
      'x' => 6,
      'y' => 4,
      'l' => 1,
    ),
    'tr4' => 
    array (
      'balises' => 
      array (
        0 => 'BG13',
      ),
      'or' => 's',
      'x' => 12,
      'y' => 2,
      'l' => 1,
    ),
    'tr15' => 
    array (
      'balises' => 
      array (
        0 => 'BG26',
        1 => 'BG27',
      ),
      'or' => 's',
      'x' => 25,
      'y' => 6,
      'l' => 1,
    ),
    'tr14' => 
    array (
      'balises' => 
      array (
        0 => 'BG28',
        1 => 'BG29',
      ),
      'or' => 's',
      'x' => 28,
      'y' => 2,
      'l' => 1,
    ),
    'tr17' => 
    array (
      'balises' => 
      array (
        0 => 'BG30',
        1 => 'BG32',
      ),
      'or' => 's',
      'x' => 28,
      'y' => 6,
      'l' => 1,
    ),
    'tr19' => 
    array (
      'balises' => 
      array (
        0 => 'BG37',
        1 => 'BG38',
      ),
      'or' => 's',
      'x' => 35,
      'y' => 6,
      'l' => 1,
    ),
    'tr25' => 
    array (
      'balises' => 
      array (
        0 => 'BG48',
        1 => 'BG50',
      ),
      'or' => 's',
      'x' => 48,
      'y' => 6,
      'l' => 1,
    ),
    'tr21' => 
    array (
      'balises' => 
      array (
        0 => 'BG42',
        1 => 'BG43',
      ),
      'or' => 's',
      'x' => 43,
      'y' => 6,
      'l' => 1,
    ),
    'tr5' => 
    array (
      'balises' => 
      array (
        0 => 'BG15',
      ),
      'or' => 's',
      'x' => 16,
      'y' => 2,
      'l' => 1,
    ),
    'tr9' => 
    array (
      'balises' => 
      array (
        0 => 'BG20',
      ),
      'or' => 's',
      'x' => 21,
      'y' => 2,
      'l' => 1,
    ),
    'tr10' => 
    array (
      'balises' => 
      array (
        0 => 'BG19',
      ),
      'or' => 's',
      'x' => 19,
      'y' => 6,
      'l' => 1,
    ),
    'tr20' => 
    array (
      'balises' => 
      array (
        0 => 'BG39',
        1 => 'BG40',
      ),
      'or' => 's',
      'x' => 36,
      'y' => 6,
      'l' => 1,
    ),
    'tr1' => 
    array (
      'balises' => 
      array (
        0 => 'BG1',
        1 => 'BG2',
      ),
      'or' => 's',
      'x' => 1,
      'y' => 4,
      'l' => 1,
    ),
    'tr23' => 
    array (
      'balises' => 
      array (
        0 => 'BG44',
      ),
      'or' => 's',
      'x' => 45,
      'y' => 6,
      'l' => 1,
    ),
    'tr24' => 
    array (
      'balises' => 
      array (
        0 => 'BG44',
        1 => 'BG44a',
        2 => 'BG44b',
      ),
      'or' => 's',
      'x' => 46,
      'y' => 6,
      'l' => 1,
    ),
    'tr6' => 
    array (
      'balises' => 
      array (
        0 => 'BG17',
      ),
      'or' => 's',
      'x' => 19,
      'y' => 2,
      'l' => 1,
    ),
    'tr11' => 
    array (
      'balises' => 
      array (
        0 => 'BG21',
      ),
      'or' => 's',
      'x' => 20,
      'y' => 6,
      'l' => 1,
    ),
    'tr13' => 
    array (
      'balises' => 
      array (
        0 => 'BG23',
      ),
      'or' => 's',
      'x' => 21,
      'y' => 6,
      'l' => 1,
    ),
    'tr8' => 
    array (
      'balises' => 
      array (
        0 => 'BG18',
      ),
      'or' => 's',
      'x' => 20,
      'y' => 2,
      'l' => 1,
    ),
    'tr12' => 
    array (
      'balises' => 
      array (
        0 => 'BG22',
        1 => 'BG24',
      ),
      'or' => 's',
      'x' => 24,
      'y' => 2,
      'l' => 1,
    ),
    'tr16' => 
    array (
      'balises' => 
      array (
        0 => 'BG31',
        1 => 'BG33',
      ),
      'or' => 'd',
      'x' => 31,
      'y' => 2,
      'l' => 1,
    ),
    'tr18' => 
    array (
      'balises' => 
      array (
        0 => 'BG34',
        1 => 'BG35',
      ),
      'or' => 's',
      'x' => 31,
      'y' => 6,
      'l' => 1,
    ),
    'tr22' => 
    array (
      'balises' => 
      array (
        0 => 'BG46',
      ),
      'or' => 'd',
      'x' => 41,
      'y' => 8,
      'l' => 1,
    ),
    'tr26' => 
    array (
      'balises' => 
      array (
        0 => 'BG45',
        1 => 'BG47',
      ),
      'or' => 's',
      'x' => 44,
      'y' => 10,
      'l' => 1,
    ),
    'tr27' => 
    array (
      'balises' => 
      array (
        0 => 'BG49',
      ),
      'or' => 's',
      'x' => 46,
      'y' => 10,
      'l' => 1,
    ),
    'tr28' => 
    array (
      'balises' => 
      array (
        0 => 'BG52',
        1 => 'BG53',
      ),
      'or' => 's',
      'x' => 49,
      'y' => 10,
      'l' => 1,
    ),
    'tr1b' => 
    array (
      'balises' => 
      array (
        0 => 'BG3',
      ),
      'or' => 's',
      'x' => 4,
      'y' => 4,
      'l' => 1,
    ),
    'tr27a' => 
    array (
      'balises' => 
      array (
        0 => 'BG51',
      ),
      'or' => 's',
      'x' => 47,
      'y' => 10,
      'l' => 1,
    ),
  ),
);
?>