<?php
// ------------------------------------------------- Sources
$PT2_SIGNALLING_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/Parterre_2020/signallingLayout.sch";
$PT2_SIGNALLING_LAYOUT_FILE_DATE = "2020-12-26 09:44:07";
$PT2_SCREEN_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/Parterre_2020/screenLayout.sch";
$PT2_SCREEN_LAYOUT_FILE_DATE = "2020-12-26 09:45:14";
$PT2_GENERATION_TIME = "2020-12-26 09:45:27";
$PT1_PROJECT_NAME = "Parterre_2020";
$PT1_DATE = "2020-12-15";
$PT1_AUTHOR = "JB";
$HMI_PROJECT_NAME = "Parterre_2020";

// -------------------------------------------------- PT1
$PT1 = array (
  'BG206' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG106',
      'dist' => 15,
    ),
    'D' => 
    array (
      'name' => 'P1',
      'dist' => 1,
    ),
    'ID' => '1F:00:69:F3:BB',
  ),
  'BG106' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG6',
      'dist' => 13,
    ),
    'D' => 
    array (
      'name' => 'BG206',
      'dist' => 15,
    ),
    'ID' => '1E:00:8E:B8:97',
  ),
  'BG8' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG15',
      'dist' => 159,
    ),
    'D' => 
    array (
      'name' => 'S4',
      'dist' => 3,
    ),
    'ID' => '1E:00:8E:DF:1F',
  ),
  'BG7' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S2',
      'dist' => 14,
    ),
    'D' => 
    array (
      'name' => 'P1',
      'dist' => 1,
    ),
    'ID' => '1F:00:4A:FE:5F',
  ),
  'BG6' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P2',
      'dist' => 2,
    ),
    'D' => 
    array (
      'name' => 'BG106',
      'dist' => 14,
    ),
    'ID' => '1E:00:57:35:F9',
  ),
  'BG9' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG11',
      'dist' => 61,
    ),
    'D' => 
    array (
      'name' => 'S2',
      'dist' => 3,
    ),
    'ID' => '1F:00:4A:F2:D2',
  ),
  'S7' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG13',
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
      'addr' => 201,
      'type' => 44,
      'majorDevice' => 10,
    ),
    'HMI' => 
    array (
      'x' => 9,
      'y' => 6,
      'l' => 2,
    ),
  ),
  'S2' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG9',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG7',
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
      'x' => 6,
      'y' => 5,
      'l' => 2,
    ),
  ),
  'BG11' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S7',
      'dist' => 3,
    ),
    'D' => 
    array (
      'name' => 'BG9',
      'dist' => 61,
    ),
    'ID' => '1F:00:78:EF:FD',
  ),
  'BG13' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P4',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S7',
      'dist' => 16,
    ),
    'ID' => '1E:00:EC:F9:E1',
  ),
  'BG2' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG4',
      'dist' => 40,
    ),
    'D' => 
    array (
      'name' => 'BS2',
      'dist' => 23,
    ),
    'ID' => '1E:00:56:DE:2A',
  ),
  'BG4' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S1',
      'dist' => 3,
    ),
    'D' => 
    array (
      'name' => 'BG2',
      'dist' => 41,
    ),
    'ID' => '1F:00:68:D5:C8',
  ),
  'BG1' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG3',
      'dist' => 20,
    ),
    'D' => 
    array (
      'name' => 'BS4',
      'dist' => 30,
    ),
    'ID' => '1F:00:62:A3:D5',
  ),
  'BG3' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S3',
      'dist' => 3,
    ),
    'D' => 
    array (
      'name' => 'BG1',
      'dist' => 21,
    ),
    'ID' => '1F:00:62:74:36',
  ),
  'BG5' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P2',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S3',
      'dist' => 2,
    ),
    'ID' => '1F:00:61:E5:87',
  ),
  'BG10' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG12',
      'dist' => 23,
    ),
    'D' => 
    array (
      'name' => 'BS6',
      'dist' => 30,
    ),
    'ID' => '1F:00:62:25:B2',
  ),
  'BG12' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S5',
      'dist' => 3,
    ),
    'D' => 
    array (
      'name' => 'BG10',
      'dist' => 23,
    ),
    'ID' => '1E:00:57:5B:28',
  ),
  'BG16' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG17',
      'dist' => 87,
    ),
    'D' => 
    array (
      'name' => 'S6',
      'dist' => 3,
    ),
    'ID' => '1F:00:69:A9:3C',
  ),
  'BG24' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BS1',
      'dist' => 35,
    ),
    'D' => 
    array (
      'name' => 'BG23',
      'dist' => 21,
    ),
    'ID' => '1F:00:4D:FF:CC',
  ),
  'BG23' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG24',
      'dist' => 21,
    ),
    'D' => 
    array (
      'name' => 'S8',
      'dist' => 5,
    ),
    'ID' => '1F:00:69:C9:3C',
  ),
  'BG15' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S11',
      'dist' => 3,
    ),
    'D' => 
    array (
      'name' => 'BG8',
      'dist' => 159,
    ),
    'ID' => '1F:00:50:3C:80',
  ),
  'BG22' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BS3',
      'dist' => 37,
    ),
    'D' => 
    array (
      'name' => 'BG21',
      'dist' => 22,
    ),
    'ID' => '1E:00:EA:E8:E9',
  ),
  'BG14' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P4',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S5',
      'dist' => 16,
    ),
    'ID' => '1F:00:50:6E:08',
  ),
  'BG21' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG22',
      'dist' => 22,
    ),
    'D' => 
    array (
      'name' => 'S10',
      'dist' => 3,
    ),
    'ID' => '1E:00:AC:75:9B',
  ),
  'BG18' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S10',
      'dist' => 2,
    ),
    'D' => 
    array (
      'name' => 'P5',
      'dist' => 1,
    ),
    'ID' => '1E:00:AC:98:25',
  ),
  'BG17' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S9',
      'dist' => 5,
    ),
    'D' => 
    array (
      'name' => 'BG16',
      'dist' => 87,
    ),
    'ID' => '1E:00:90:0C:A5',
  ),
  'BG20' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P6',
      'dist' => 16,
    ),
    'D' => 
    array (
      'name' => 'S9',
      'dist' => 6,
    ),
    'ID' => '1F:00:4D:6A:29',
  ),
  'BG19' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P6',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P5',
      'dist' => 1,
    ),
    'ID' => '1E:00:8E:57:F6',
  ),
  'S9' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG20',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BG17',
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
      'x' => 16,
      'y' => 8,
      'l' => 1,
    ),
  ),
  'S11' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'P5',
      'dist' => 8,
    ),
    'D' => 
    array (
      'name' => 'BG15',
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
      'x' => 13,
      'y' => 2,
      'l' => 1,
    ),
  ),
  'S4' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG8',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P2',
      'dist' => 4,
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
      'x' => 8,
      'y' => 1,
      'l' => 1,
    ),
  ),
  'S6' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG16',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P4',
      'dist' => 14,
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
      'x' => 13,
      'y' => 7,
      'l' => 2,
    ),
  ),
  'S10' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG21',
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
      'addr' => 202,
      'type' => 45,
      'majorDevice' => 4,
    ),
    'HMI' => 
    array (
      'x' => 16,
      'y' => 1,
      'l' => 2,
    ),
  ),
  'BS1' => 
  array (
    'element' => 'BSE',
    'D' => 
    array (
      'name' => 'BG24',
      'dist' => 40,
    ),
    'HMI' => 
    array (
      'x' => 21,
      'y' => 8,
      'l' => 1,
    ),
  ),
  'P2' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'S4',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG5',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG6',
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
      'or' => 'tl',
      'x' => 6,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'BS6' => 
  array (
    'element' => 'BSB',
    'U' => 
    array (
      'name' => 'BG10',
      'dist' => 40,
    ),
    'HMI' => 
    array (
      'x' => 7,
      'y' => 8,
      'l' => 1,
    ),
  ),
  'S3' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG5',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BG3',
      'dist' => 1,
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
      'x' => 4,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'BS2' => 
  array (
    'element' => 'BSB',
    'U' => 
    array (
      'name' => 'BG2',
      'dist' => 40,
    ),
    'HMI' => 
    array (
      'x' => 1,
      'y' => 6,
      'l' => 1,
    ),
  ),
  'S1' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'P1',
      'dist' => 5,
    ),
    'D' => 
    array (
      'name' => 'BG4',
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
      'x' => 3,
      'y' => 6,
      'l' => 1,
    ),
  ),
  'S5' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG14',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BG12',
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
      'x' => 9,
      'y' => 8,
      'l' => 2,
    ),
  ),
  'S8' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG23',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P6',
      'dist' => 1,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 45,
      'majorDevice' => 1,
    ),
    'HMI' => 
    array (
      'x' => 19,
      'y' => 7,
      'l' => 1,
    ),
  ),
  'P6' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'S8',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG19',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG20',
      'dist' => 30,
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
      'x' => 17,
      'y' => 6,
      'l' => 2,
    ),
  ),
  'P4' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'S6',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG13',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG14',
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
      'x' => 11,
      'y' => 6,
      'l' => 2,
    ),
  ),
  'P5' => 
  array (
    'element' => 'PF',
    'T' => 
    array (
      'name' => 'S11',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG19',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG18',
      'dist' => 27,
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
  'P1' => 
  array (
    'element' => 'PF',
    'T' => 
    array (
      'name' => 'S1',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG7',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG206',
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
      'x' => 4,
      'y' => 4,
      'l' => 2,
    ),
  ),
  'BS3' => 
  array (
    'element' => 'BSE',
    'D' => 
    array (
      'name' => 'BG22',
      'dist' => 40,
    ),
    'HMI' => 
    array (
      'x' => 19,
      'y' => 2,
      'l' => 1,
    ),
  ),
  'BS4' => 
  array (
    'element' => 'BSB',
    'U' => 
    array (
      'name' => 'BG1',
      'dist' => 40,
    ),
    'HMI' => 
    array (
      'x' => 2,
      'y' => 2,
      'l' => 1,
    ),
  ),
);

// -------------------------------------------------- HMI
$HMI = array (
  'label' => 
  array (
    0 => 
    array (
      'x' => 19,
      'y' => 4,
      'text' => 'Christiania',
    ),
    1 => 
    array (
      'x' => 2,
      'y' => 4,
      'text' => 'Christianshavn',
    ),
    2 => 
    array (
      'x' => 10,
      'y' => 0,
      'text' => '-------------- Vindue -------------',
    ),
    3 => 
    array (
      'x' => 19,
      'y' => 3,
      'text' => 'Spor 4',
    ),
    4 => 
    array (
      'x' => 19,
      'y' => 9,
      'text' => 'Spor 5',
    ),
    5 => 
    array (
      'x' => 7,
      'y' => 9,
      'text' => 'Spor 3',
    ),
    6 => 
    array (
      'x' => 1,
      'y' => 7,
      'text' => 'Spor 2',
    ),
    7 => 
    array (
      'x' => 2,
      'y' => 3,
      'text' => 'Spor 1',
    ),
  ),
  'arsIndicator' => 
  array (
    'x' => 9,
    'y' => 4,
  ),
  'eStopIndicator' => 
  array (
    'x' => 8,
    'y' => 3,
  ),
  'projectName' => 'Parterre_2020',
  'baliseTrack' => 
  array (
    'tr1' => 
    array (
      'balises' => 
      array (
        0 => 'BG1',
        1 => 'BG3',
      ),
      'or' => 's',
      'x' => 3,
      'y' => 2,
      'l' => 1,
    ),
    'tr3' => 
    array (
      'balises' => 
      array (
        0 => 'BG8',
      ),
      'or' => 's',
      'x' => 9,
      'y' => 2,
      'l' => 2,
    ),
    'tr2' => 
    array (
      'balises' => 
      array (
        0 => 'BG2',
        1 => 'BG4',
      ),
      'or' => 's',
      'x' => 2,
      'y' => 6,
      'l' => 1,
    ),
    'tr6' => 
    array (
      'balises' => 
      array (
        0 => 'BG10',
        1 => 'BG12',
      ),
      'or' => 's',
      'x' => 8,
      'y' => 8,
      'l' => 1,
    ),
    'tr5' => 
    array (
      'balises' => 
      array (
        0 => 'BG9',
        1 => 'BG11',
      ),
      'or' => 's',
      'x' => 8,
      'y' => 6,
      'l' => 1,
    ),
    'tr7' => 
    array (
      'balises' => 
      array (
        0 => 'BG16',
        1 => 'BG17',
      ),
      'or' => 's',
      'x' => 15,
      'y' => 8,
      'l' => 1,
    ),
    'tr4' => 
    array (
      'balises' => 
      array (
        0 => 'BG15',
      ),
      'or' => 's',
      'x' => 11,
      'y' => 2,
      'l' => 2,
    ),
    'tr8' => 
    array (
      'balises' => 
      array (
        0 => 'BG21',
        1 => 'BG22',
      ),
      'or' => 's',
      'x' => 18,
      'y' => 2,
      'l' => 1,
    ),
    'tr9' => 
    array (
      'balises' => 
      array (
        0 => 'BG23',
        1 => 'BG24',
      ),
      'or' => 's',
      'x' => 20,
      'y' => 8,
      'l' => 1,
    ),
    'tr10' => 
    array (
      'balises' => 
      array (
        0 => 'BG19',
      ),
      'or' => 'd',
      'x' => 16,
      'y' => 4,
      'l' => 1,
    ),
  ),
);
?>