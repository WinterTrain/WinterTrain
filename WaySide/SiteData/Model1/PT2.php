<?php
// ------------------------------------------------- Sources
$PT2_SIGNALLING_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/Model1/signallingLayout.sch";
$PT2_SIGNALLING_LAYOUT_FILE_DATE = "2021-05-13 23:07:07";
$PT2_SCREEN_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/Model1/screenLayout.sch";
$PT2_SCREEN_LAYOUT_FILE_DATE = "2021-10-03 16:31:08";
$PT2_GENERATION_TIME = "2021-10-03 16:31:18";
$PT1_PROJECT_NAME = "Model1";
$PT1_DATE = "2021-04-10";
$PT1_AUTHOR = "JB";
$HMI_PROJECT_NAME = "Model1";

// -------------------------------------------------- PT1
$PT1 = array (
  'BG7' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S6',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BG6',
      'dist' => 0,
    ),
    'ID' => '00:00:00:00:01',
  ),
  'BG6' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG7',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'P3',
      'dist' => 0,
    ),
    'ID' => '00:00:00:00:02',
  ),
  'P3' => 
  array (
    'element' => 'PF',
    'T' => 
    array (
      'name' => 'BG3',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG6',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'S3',
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
      'x' => 9,
      'y' => 3,
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
      'name' => 'BG7',
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
      'x' => 16,
      'y' => 5,
      'l' => 2,
    ),
  ),
  'S7' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BS4',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P2',
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
      'x' => 20,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'P2' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'S7',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG5',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'S6',
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
      'or' => 'tl',
      'x' => 18,
      'y' => 3,
      'l' => 2,
    ),
  ),
  'BG1' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S1',
      'dist' => 20,
    ),
    'D' => 
    array (
      'name' => 'BS1',
      'dist' => 20,
    ),
    'ID' => '00:00:00:00:03',
  ),
  'BG2' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG3',
      'dist' => 60,
    ),
    'D' => 
    array (
      'name' => 'S2',
      'dist' => 50,
    ),
    'ID' => '00:00:00:00:04',
  ),
  'BG3' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P3',
      'dist' => 80,
    ),
    'D' => 
    array (
      'name' => 'BG2',
      'dist' => 70,
    ),
    'ID' => '00:00:00:00:05',
  ),
  'BG5' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P2',
      'dist' => 40,
    ),
    'D' => 
    array (
      'name' => 'S4',
      'dist' => 60,
    ),
    'ID' => '00:00:00:00:06',
  ),
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
      'name' => 'BG1',
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
      'x' => 3,
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
      'name' => 'P3',
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
      'x' => 11,
      'y' => 3,
      'l' => 2,
    ),
  ),
  'S5' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BS2',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG4',
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
      'x' => 15,
      'y' => 0,
      'l' => 2,
    ),
  ),
  'S2' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG2',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S1',
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
      'x' => 5,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'S4' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG5',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P1',
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
      'x' => 15,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'P1' => 
  array (
    'element' => 'PF',
    'T' => 
    array (
      'name' => 'S3',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'S4',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG4',
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
      'or' => 'fl',
      'x' => 13,
      'y' => 1,
      'l' => 2,
    ),
  ),
  'BS4' => 
  array (
    'element' => 'BSE',
    'D' => 
    array (
      'name' => 'S7',
      'dist' => 10,
    ),
    'HMI' => 
    array (
      'x' => 22,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'BS2' => 
  array (
    'element' => 'BSE',
    'D' => 
    array (
      'name' => 'S5',
      'dist' => 100,
    ),
    'HMI' => 
    array (
      'x' => 17,
      'y' => 1,
      'l' => 1,
    ),
  ),
  'BS1' => 
  array (
    'element' => 'BSB',
    'U' => 
    array (
      'name' => 'BG1',
      'dist' => 50,
    ),
    'HMI' => 
    array (
      'x' => 1,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'BG4' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S5',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P1',
      'dist' => 1,
    ),
    'ID' => '00:00:00:00:07',
  ),
);

// -------------------------------------------------- HMI
$HMI = array (
  'label' => 
  array (
    0 => 
    array (
      'x' => 20.125,
      'y' => 0.75,
      'text' => 'Vest',
    ),
    1 => 
    array (
      'x' => 1.125,
      'y' => 5.375,
      'text' => 'Øst',
    ),
  ),
  'projectName' => 'Model1',
  'baliseTrack' => 
  array (
    'tr1' => 
    array (
      'balises' => 
      array (
        0 => 'BG1',
      ),
      'or' => 's',
      'x' => 2,
      'y' => 3,
      'l' => 1,
    ),
    'tr2' => 
    array (
      'balises' => 
      array (
        0 => 'BG2',
      ),
      'or' => 's',
      'x' => 7,
      'y' => 3,
      'l' => 1,
    ),
    'tr3' => 
    array (
      'balises' => 
      array (
        0 => 'BG3',
      ),
      'or' => 's',
      'x' => 8,
      'y' => 3,
      'l' => 1,
    ),
    'tr4' => 
    array (
      'balises' => 
      array (
        0 => 'BG5',
      ),
      'or' => 's',
      'x' => 17,
      'y' => 3,
      'l' => 1,
    ),
    'tr5' => 
    array (
      'balises' => 
      array (
        0 => 'BG6',
      ),
      'or' => 's',
      'x' => 11,
      'y' => 5,
      'l' => 2,
    ),
    'tr6' => 
    array (
      'balises' => 
      array (
        0 => 'BG7',
      ),
      'or' => 's',
      'x' => 13,
      'y' => 5,
      'l' => 3,
    ),
  ),
  'eStopIndicator' => 
  array (
    'x' => 1,
    'y' => 1,
  ),
  'arsIndicator' => 
  array (
    'x' => 8,
    'y' => 1,
  ),
);
?>