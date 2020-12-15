<?php
// ------------------------------------------------- Sources
$PT2_SIGNALLING_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/Parterre_2020/signallingLayout.sch";
$PT2_SIGNALLING_LAYOUT_FILE_DATE = "2020-12-15 15:32:10";
$PT2_SCREEN_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/Parterre_2020/screenLayout.sch";
$PT2_SCREEN_LAYOUT_FILE_DATE = "2020-12-15 15:06:28";
$PT2_GENERATION_TIME = "2020-12-15 15:32:28";
$PT1_PROJECT_NAME = "Parterre_2020";
$PT1_DATE = "2020-12-15";
$PT1_AUTHOR = "JB";
$HMI_PROJECT_NAME = "Parterre_2020";

// -------------------------------------------------- PT1
$PT1 = array (
  'BG8' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG15',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'S4',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG7' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S2',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'P1',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG6' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P2',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'P1',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG9' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG11',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'S2',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
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
      'majorDevice' => 7,
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
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BG9',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG13' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P4',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'S7',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG2' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG4',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BS2',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG4' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S1',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BG2',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG1' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG3',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BS4',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG3' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S3',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BG1',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG5' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P2',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'S3',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG10' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG12',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BS6',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG12' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S5',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BG10',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG16' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG17',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'S6',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG24' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BS1',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BG23',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG23' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG24',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'S8',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG15' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S11',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BG8',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG22' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BS3',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BG21',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG14' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P4',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'S5',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG21' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG22',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'S10',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG18' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S10',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'P5',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG17' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S9',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BG16',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG20' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P6',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'S9',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG19' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P6',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'P5',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
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
      'majorDevice' => 8,
    ),
    'HMI' => 
    array (
      'x' => 15,
      'y' => 6,
      'l' => 1,
    ),
  ),
  'S11' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'P5',
      'dist' => 10,
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
      'majorDevice' => 7,
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
      'dist' => 10,
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
      'dist' => 10,
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
      'y' => 5,
      'l' => 1,
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
      'majorDevice' => 1,
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
      'dist' => 0,
    ),
    'HMI' => 
    array (
      'x' => 20,
      'y' => 6,
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
      'majorDevice' => 2,
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
      'dist' => 0,
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
      'majorDevice' => 1,
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
      'dist' => 0,
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
      'dist' => 10,
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
      'majorDevice' => 4,
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
      'majorDevice' => 10,
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
      'x' => 18,
      'y' => 5,
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
      'x' => 16,
      'y' => 4,
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
      'majorDevice' => 4,
      'minorDevice' => 0,
    ),
    'HMI' => 
    array (
      'or' => 'tl',
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
      'name' => 'BG6',
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
      'dist' => 0,
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
      'dist' => 0,
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
      'y' => 3.75,
      'text' => 'Christiania',
    ),
    1 => 
    array (
      'x' => 1,
      'y' => 4,
      'text' => 'Christianshavn',
    ),
    2 => 
    array (
      'x' => 10,
      'y' => 0,
      'text' => '-------------- Vindue -------------',
    ),
  ),
  'arsIndicator' => 
  array (
    'x' => 8,
    'y' => 10,
  ),
  'eStopIndicator' => 
  array (
    'x' => 2,
    'y' => 10,
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
      'x' => 14,
      'y' => 6,
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
      'x' => 19,
      'y' => 6,
      'l' => 1,
    ),
  ),
);
?>