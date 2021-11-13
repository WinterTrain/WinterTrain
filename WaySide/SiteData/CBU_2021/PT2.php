<?php
// ------------------------------------------------- Sources
$PT2_SIGNALLING_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/CBU_2021/signallingLayout.sch";
$PT2_SIGNALLING_LAYOUT_FILE_DATE = "2021-11-13 10:11:46";
$PT2_SCREEN_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/CBU_2021/screenLayout.sch";
$PT2_SCREEN_LAYOUT_FILE_DATE = "2021-11-13 10:17:38";
$PT2_GENERATION_TIME = "2021-11-13 10:17:44";
$PT1_PROJECT_NAME = "WinterHut _2021";
$PT1_DATE = "2021-11-13";
$PT1_AUTHOR = "JB";
$HMI_PROJECT_NAME = "WinterHut_2021";

// -------------------------------------------------- PT1
$PT1 = array (
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
      'dist' => 97,
    ),
    'D' => 
    array (
      'name' => 'BS2',
      'dist' => 5,
    ),
    'ID' => '00:00:00:00:02',
  ),
  'BG3' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S3',
      'dist' => 85,
    ),
    'D' => 
    array (
      'name' => 'S2',
      'dist' => 5,
    ),
    'ID' => '00:00:00:00:03',
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
      'name' => 'BG3',
      'dist' => 10,
    ),
    'type' => 'MB',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 41,
      'majorDevice' => 3,
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
      'dist' => 25,
    ),
    'D' => 
    array (
      'name' => 'S19',
      'dist' => 30,
    ),
    'ID' => '00:00:00:00:04',
  ),
  'BG47' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S21',
      'dist' => 25,
    ),
    'D' => 
    array (
      'name' => 'BG45',
      'dist' => 30,
    ),
    'ID' => '00:00:00:00:05',
  ),
  'BG49' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG51',
      'dist' => 25,
    ),
    'D' => 
    array (
      'name' => 'S21',
      'dist' => 30,
    ),
    'ID' => '00:00:00:00:06',
  ),
  'BG51' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BS4',
      'dist' => 25,
    ),
    'D' => 
    array (
      'name' => 'BG49',
      'dist' => 30,
    ),
    'ID' => '00:00:00:00:07',
  ),
  'S21' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG49',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG47',
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
      'x' => 46,
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
      'dist' => 7,
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
      'majorDevice' => 2,
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
      'dist' => 110,
    ),
    'D' => 
    array (
      'name' => 'BG18',
      'dist' => 63,
    ),
    'ID' => '00:00:00:00:08',
  ),
  'BG23' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S11',
      'dist' => 110,
    ),
    'D' => 
    array (
      'name' => 'BG21',
      'dist' => 63,
    ),
    'ID' => '00:00:00:00:09',
  ),
  'BG29' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S14',
      'dist' => 111,
    ),
    'D' => 
    array (
      'name' => 'BG28',
      'dist' => 59,
    ),
    'ID' => '00:00:00:00:0A',
  ),
  'BG32' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S15',
      'dist' => 111,
    ),
    'D' => 
    array (
      'name' => 'BG30',
      'dist' => 59,
    ),
    'ID' => '00:00:00:00:0B',
  ),
  'BG39' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG40',
      'dist' => 119,
    ),
    'D' => 
    array (
      'name' => 'BG38',
      'dist' => 100,
    ),
    'ID' => '00:00:00:00:0C',
  ),
  'BG38' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG39',
      'dist' => 119,
    ),
    'D' => 
    array (
      'name' => 'BG37',
      'dist' => 100,
    ),
    'ID' => '00:00:00:00:0D',
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
      'majorDevice' => 0,
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
      'majorDevice' => 0,
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
      'majorDevice' => 0,
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
  'BG43' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG44',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'BG42',
      'dist' => 1,
    ),
    'ID' => '00:00:00:00:0E',
  ),
  'BG42' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG43',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'S18',
      'dist' => 1,
    ),
    'ID' => '00:00:00:00:0F',
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
      'dist' => 7,
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
      'dist' => 7,
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
      'name' => 'BG41',
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
    'supervisionState' => 'CL',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 10,
      'majorDevice' => 0,
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
    'ID' => '00:00:00:00:10',
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
    'ID' => '00:00:00:00:11',
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
    'ID' => '00:00:00:00:12',
  ),
  'BG26' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG27',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'P3',
      'dist' => 16,
    ),
    'ID' => '00:00:00:00:13',
  ),
  'BG10' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG11',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'S5',
      'dist' => 7,
    ),
    'ID' => '00:00:00:00:14',
  ),
  'BG9' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P1',
      'dist' => 13,
    ),
    'D' => 
    array (
      'name' => 'BG8',
      'dist' => 6,
    ),
    'ID' => '00:00:00:00:15',
  ),
  'BG34' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG35',
      'dist' => 5,
    ),
    'D' => 
    array (
      'name' => 'S15',
      'dist' => 5,
    ),
    'ID' => '00:00:00:00:16',
  ),
  'BG7' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S5',
      'dist' => 107,
    ),
    'D' => 
    array (
      'name' => 'BG6',
      'dist' => 85,
    ),
    'ID' => '00:00:00:00:17',
  ),
  'BG50' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BS3',
      'dist' => 45,
    ),
    'D' => 
    array (
      'name' => 'BG48',
      'dist' => 25,
    ),
    'ID' => '00:00:00:00:18',
  ),
  'BG40' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S17',
      'dist' => 119,
    ),
    'D' => 
    array (
      'name' => 'BG39',
      'dist' => 100,
    ),
    'ID' => '00:00:00:00:19',
  ),
  'BS4' => 
  array (
    'element' => 'BSE',
    'D' => 
    array (
      'name' => 'BG51',
      'dist' => 40,
    ),
    'HMI' => 
    array (
      'x' => 48,
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
      'dist' => 25,
    ),
    'D' => 
    array (
      'name' => 'S20',
      'dist' => 30,
    ),
    'ID' => '00:00:00:00:1A',
  ),
  'BG44' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S20',
      'dist' => 7,
    ),
    'D' => 
    array (
      'name' => 'BG43',
      'dist' => 1,
    ),
    'ID' => '00:00:00:00:1B',
  ),
  'BG41' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P6',
      'dist' => 3,
    ),
    'D' => 
    array (
      'name' => 'S17',
      'dist' => 25,
    ),
    'ID' => '00:00:00:00:1C',
  ),
  'BG37' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG38',
      'dist' => 105,
    ),
    'D' => 
    array (
      'name' => 'S16',
      'dist' => 171,
    ),
    'ID' => '00:00:00:00:1D',
  ),
  'BG36' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S16',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P5',
      'dist' => 5,
    ),
    'ID' => '00:00:00:00:1E',
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
      'dist' => 9,
    ),
    'ID' => '00:00:00:00:1F',
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
      'dist' => 7,
    ),
    'ID' => '00:00:00:00:20',
  ),
  'BG30' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG32',
      'dist' => 111,
    ),
    'D' => 
    array (
      'name' => 'S13',
      'dist' => 59,
    ),
    'ID' => '00:00:00:00:21',
  ),
  'BG28' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG29',
      'dist' => 81,
    ),
    'D' => 
    array (
      'name' => 'S12',
      'dist' => 89,
    ),
    'ID' => '00:00:00:00:22',
  ),
  'BG24' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P4',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BG22',
      'dist' => 1,
    ),
    'ID' => '00:00:00:00:23',
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
      'dist' => 2,
    ),
    'D' => 
    array (
      'name' => 'BG26',
      'dist' => 7,
    ),
    'ID' => '00:00:00:00:24',
  ),
  'BG22' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG24',
      'dist' => 3,
    ),
    'D' => 
    array (
      'name' => 'S10',
      'dist' => 1,
    ),
    'ID' => '00:00:00:00:25',
  ),
  'BG21' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG23',
      'dist' => 110,
    ),
    'D' => 
    array (
      'name' => 'BG19',
      'dist' => 63,
    ),
    'ID' => '00:00:00:00:26',
  ),
  'S11' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'P3',
      'dist' => 10,
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
    'ID' => '00:00:00:00:27',
  ),
  'BG18' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG20',
      'dist' => 111,
    ),
    'D' => 
    array (
      'name' => 'BG17',
      'dist' => 64,
    ),
    'ID' => '00:00:00:00:28',
  ),
  'BG19' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG21',
      'dist' => 62,
    ),
    'D' => 
    array (
      'name' => 'S9',
      'dist' => 140,
    ),
    'ID' => '00:00:00:00:29',
  ),
  'BG17' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG18',
      'dist' => 65,
    ),
    'D' => 
    array (
      'name' => 'S8',
      'dist' => 139,
    ),
    'ID' => '00:00:00:00:2A',
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
      'dist' => 10,
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
      'dist' => 10,
    ),
    'type' => 'MS2',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 41,
      'majorDevice' => 13,
    ),
    'HMI' => 
    array (
      'x' => 17,
      'y' => 1,
      'l' => 2,
    ),
  ),
  'BG16' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S9',
      'dist' => 17,
    ),
    'D' => 
    array (
      'name' => 'P2',
      'dist' => 2,
    ),
    'ID' => '00:00:00:00:2B',
  ),
  'BG15' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S8',
      'dist' => 16,
    ),
    'D' => 
    array (
      'name' => 'P2',
      'dist' => 1,
    ),
    'ID' => '00:00:00:00:2C',
  ),
  'BG14' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P2',
      'dist' => 3,
    ),
    'D' => 
    array (
      'name' => 'S7',
      'dist' => 1,
    ),
    'ID' => '00:00:00:00:2D',
  ),
  'S7' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG14',
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
      'majorDevice' => 4,
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
      'dist' => 126,
    ),
    'D' => 
    array (
      'name' => 'S6',
      'dist' => 155,
    ),
    'ID' => '00:00:00:00:2E',
  ),
  'BG12' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S6',
      'dist' => 11,
    ),
    'D' => 
    array (
      'name' => 'P1',
      'dist' => 4,
    ),
    'ID' => '00:00:00:00:2F',
  ),
  'BG6' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG7',
      'dist' => 85,
    ),
    'D' => 
    array (
      'name' => 'S3',
      'dist' => 5,
    ),
    'ID' => '00:00:00:00:30',
  ),
  'BG11' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P1',
      'dist' => 12,
    ),
    'D' => 
    array (
      'name' => 'BG10',
      'dist' => 7,
    ),
    'ID' => '00:00:00:00:31',
  ),
  'BG8' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG9',
      'dist' => 6,
    ),
    'D' => 
    array (
      'name' => 'S4',
      'dist' => 6,
    ),
    'ID' => '00:00:00:00:32',
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
      'dist' => 7,
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
      'majorDevice' => 2,
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
      'name' => 'BG8',
      'dist' => 6,
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
      'majorDevice' => 1,
    ),
    'HMI' => 
    array (
      'x' => 7,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'S20' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG48',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG44',
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
      'x' => 46,
      'y' => 5,
      'l' => 1,
    ),
  ),
  'S17' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG41',
      'dist' => 10,
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
      'majorDevice' => 2,
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
      'dist' => 10,
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
      'dist' => 20,
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
      'name' => 'BG11',
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
      'majorDevice' => 1,
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
      'name' => 'BG36',
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
      'dist' => 50,
    ),
    'HMI' => 
    array (
      'x' => 48,
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
      'dist' => 50,
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
      'dist' => 97,
    ),
    'D' => 
    array (
      'name' => 'BG4',
      'dist' => 5,
    ),
    'ID' => '00:00:00:00:33',
  ),
);

// -------------------------------------------------- HMI
$HMI = array (
  'label' => 
  array (
    0 => 
    array (
      'x' => 47,
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
      'x' => 47,
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
      'x' => 47,
      'y' => 6,
      'l' => 1,
    ),
    'tr21' => 
    array (
      'balises' => 
      array (
        0 => 'BG42',
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
        0 => 'BG43',
      ),
      'or' => 's',
      'x' => 44,
      'y' => 6,
      'l' => 1,
    ),
    'tr24' => 
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
        0 => 'BG47',
      ),
      'or' => 's',
      'x' => 45,
      'y' => 10,
      'l' => 1,
    ),
    'tr28' => 
    array (
      'balises' => 
      array (
        0 => 'BG49',
        1 => 'BG51',
      ),
      'or' => 's',
      'x' => 47,
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
  ),
);
?>