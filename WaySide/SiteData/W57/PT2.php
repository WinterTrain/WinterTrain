<?php
// ------------------------------------------------- Sources
$PT2_SIGNALLING_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/W57/signallingLayout.sch";
$PT2_SIGNALLING_LAYOUT_FILE_DATE = "2020-10-02 19:00:19";
$PT2_SCREEN_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/W57/screenLayout.sch";
$PT2_SCREEN_LAYOUT_FILE_DATE = "2020-10-02 19:00:11";
$PT2_GENERATION_TIME = "2020-10-02 19:00:30";
$PT1_PROJECT_NAME = "W57_2020";
$PT1_DATE = "2020-10-01";
$PT1_AUTHOR = "Jan B";
$HMI_PROJECT_NAME = "W57_2020";

// -------------------------------------------------- PT1
$PT1 = array (
  'S6' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG08',
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
      'x' => 12,
      'y' => 2,
      'l' => 1,
    ),
  ),
  'S3' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG06',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BG04',
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
      'x' => 8,
      'y' => 1,
      'l' => 2,
    ),
  ),
  'BG08' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BS2',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'S6',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG06' => 
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
  'BG07' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P2',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'S5',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG05' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S5',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'S4',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG04' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S3',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'S2',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'BG01' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S1',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BS1',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'S2' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG04',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG02',
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
      'y' => 0,
      'l' => 2,
    ),
  ),
  'BG03' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S4',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'P1',
      'dist' => 0,
    ),
    'ID' => 'FF:FF:FF:FF:FF',
  ),
  'S5' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG07',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BG05',
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
      'x' => 8,
      'y' => 3,
      'l' => 2,
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
      'name' => 'BG01',
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
      'y' => 3,
      'l' => 1,
    ),
  ),
  'S4' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG05',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG03',
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
  'P2' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'S6',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG06',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG07',
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
      'x' => 10,
      'y' => 1,
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
      'name' => 'BG03',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG02',
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
      'x' => 3,
      'y' => 1,
      'l' => 2,
    ),
  ),
  'BS2' => 
  array (
    'element' => 'BSE',
    'D' => 
    array (
      'name' => 'BG08',
      'dist' => 0,
    ),
    'HMI' => 
    array (
      'x' => 14,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'BS1' => 
  array (
    'element' => 'BSB',
    'U' => 
    array (
      'name' => 'BG01',
      'dist' => 0,
    ),
    'HMI' => 
    array (
      'x' => 0,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'BG02' => 
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
);

// -------------------------------------------------- HMI
$HMI = array (
  'projectName' => 'W57_2020',
  'baliseTrack' => 
  array (
    'tr1' => 
    array (
      'balises' => 
      array (
        0 => 'BG08',
      ),
      'or' => 's',
      'x' => 13,
      'y' => 3,
      'l' => 1,
    ),
  ),
);
?>