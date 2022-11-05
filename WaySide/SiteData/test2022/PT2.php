<?php
// ------------------------------------------------- Sources
$PT2_SIGNALLING_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/test2022/signallingLayout.sch";
$PT2_SIGNALLING_LAYOUT_FILE_DATE = "2022-10-22 12:31:23";
$PT2_SCREEN_LAYOUT_FILE = "/home/jabe/Desktop/Projekter/Git/WinterTrain/WaySide/SiteData/test2022/screenLayout.sch";
$PT2_SCREEN_LAYOUT_FILE_DATE = "2022-01-20 16:01:55";
$PT2_GENERATION_TIME = "2022-10-22 12:31:41";
$PT1_PROJECT_NAME = "Mikkels tegning";
$PT1_DATE = "15-10-2022";
$PT1_AUTHOR = "Mikkel/Jan";
$HMI_PROJECT_NAME = "?";

// -------------------------------------------------- PT1
$PT1 = array (
  'BG15' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P2',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'S4',
      'dist' => 0,
    ),
    'ID' => '00:00:00:00:01',
  ),
  'BG16' => 
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
    'ID' => '00:00:00:00:02',
  ),
  'BG14' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S3',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'P1',
      'dist' => 0,
    ),
    'ID' => '00:00:00:00:03',
  ),
  'BG13' => 
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
    'ID' => '00:00:00:00:04',
  ),
  'S6' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG7',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P2',
      'dist' => 1,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 44,
      'majorDevice' => 14,
    ),
    'HMI' => 
    array (
      'x' => 13,
      'y' => 1,
      'l' => 2,
    ),
  ),
  'S8' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG9',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P3',
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
      'x' => 20,
      'y' => 1,
      'l' => 2,
    ),
  ),
  'S9' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG11',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P3',
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
      'x' => 20,
      'y' => 3,
      'l' => 2,
    ),
  ),
  'S5' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG16',
      'dist' => 11,
    ),
    'D' => 
    array (
      'name' => 'BG6',
      'dist' => 1,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 44,
      'majorDevice' => 4,
    ),
    'HMI' => 
    array (
      'x' => 9,
      'y' => 4,
      'l' => 2,
    ),
  ),
  'S4' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG15',
      'dist' => 11,
    ),
    'D' => 
    array (
      'name' => 'BG4',
      'dist' => 1,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 44,
      'majorDevice' => 7,
    ),
    'HMI' => 
    array (
      'x' => 9,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'S3' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG5',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG14',
      'dist' => 11,
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
      'x' => 6,
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
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG2',
      'dist' => 1,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 202,
      'type' => 44,
      'majorDevice' => 10,
    ),
    'HMI' => 
    array (
      'x' => 2,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'S7' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'P3',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG8',
      'dist' => 1,
    ),
    'type' => 'MB',
    'EC' => 
    array (
      'addr' => 0,
      'type' => 44,
      'majorDevice' => 4,
    ),
    'HMI' => 
    array (
      'x' => 16,
      'y' => 2,
      'l' => 2,
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
      'name' => 'BG13',
      'dist' => 11,
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
      'x' => 6,
      'y' => 1,
      'l' => 2,
    ),
  ),
  'BG12' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BS3',
      'dist' => 74,
    ),
    'D' => 
    array (
      'name' => 'BG11',
      'dist' => 24,
    ),
    'ID' => '76:00:0C:E3:A8',
  ),
  'BS2' => 
  array (
    'element' => 'BSE',
    'D' => 
    array (
      'name' => 'BG10',
      'dist' => 1,
    ),
    'HMI' => 
    array (
      'x' => 23,
      'y' => 2,
      'l' => 1,
    ),
  ),
  'BS3' => 
  array (
    'element' => 'BSE',
    'D' => 
    array (
      'name' => 'BG12',
      'dist' => 1,
    ),
    'HMI' => 
    array (
      'x' => 23,
      'y' => 4,
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
    'HMI' => 
    array (
      'x' => 0,
      'y' => 2,
      'l' => 1,
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
      'name' => 'BG15',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG16',
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
      'x' => 11,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'P3' => 
  array (
    'element' => 'PF',
    'T' => 
    array (
      'name' => 'S7',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'S9',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'S8',
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
      'or' => 'fr',
      'x' => 18,
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
      'name' => 'BG14',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG13',
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
      'x' => 4,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'BG9' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG10',
      'dist' => 44,
    ),
    'D' => 
    array (
      'name' => 'S8',
      'dist' => 24,
    ),
    'ID' => '74:00:15:50:C0',
  ),
  'BG11' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG12',
      'dist' => 44,
    ),
    'D' => 
    array (
      'name' => 'S9',
      'dist' => 24,
    ),
    'ID' => '74:00:11:07:0B',
  ),
  'BG10' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BS2',
      'dist' => 74,
    ),
    'D' => 
    array (
      'name' => 'BG9',
      'dist' => 24,
    ),
    'ID' => '73:00:56:9F:A1',
  ),
  'BG7' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG8',
      'dist' => 40,
    ),
    'D' => 
    array (
      'name' => 'S6',
      'dist' => 5,
    ),
    'ID' => '76:00:0C:FC:CB',
  ),
  'BG8' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S7',
      'dist' => 5,
    ),
    'D' => 
    array (
      'name' => 'BG7',
      'dist' => 40,
    ),
    'ID' => '74:00:15:65:29',
  ),
  'BG2' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S1',
      'dist' => 5,
    ),
    'D' => 
    array (
      'name' => 'BG1',
      'dist' => 40,
    ),
    'ID' => '73:00:56:D6:F2',
  ),
  'BG5' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG6',
      'dist' => 65,
    ),
    'D' => 
    array (
      'name' => 'S3',
      'dist' => 12,
    ),
    'ID' => '76:00:0C:B7:36',
  ),
  'BG3' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG4',
      'dist' => 65,
    ),
    'D' => 
    array (
      'name' => 'S2',
      'dist' => 12,
    ),
    'ID' => '76:00:0D:1A:2E',
  ),
  'BG6' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S5',
      'dist' => 12,
    ),
    'D' => 
    array (
      'name' => 'BG5',
      'dist' => 65,
    ),
    'ID' => '73:00:56:C0:72',
  ),
  'BG4' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S4',
      'dist' => 12,
    ),
    'D' => 
    array (
      'name' => 'BG3',
      'dist' => 65,
    ),
    'ID' => '73:00:70:98:69',
  ),
  'BG1' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG2',
      'dist' => 40,
    ),
    'D' => 
    array (
      'name' => 'BS1',
      'dist' => 50,
    ),
    'ID' => '76:00:0C:A4:29',
  ),
);

// -------------------------------------------------- HMI
$HMI = array (
  'label' => 
  array (
  ),
  'projectName' => '?',
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
      'y' => 2,
      'l' => 1,
    ),
    'tr2' => 
    array (
      'balises' => 
      array (
        0 => 'BG3',
        1 => 'BG4',
      ),
      'or' => 's',
      'x' => 8,
      'y' => 2,
      'l' => 1,
    ),
    'tr3' => 
    array (
      'balises' => 
      array (
        0 => 'BG5',
        1 => 'BG6',
      ),
      'or' => 's',
      'x' => 8,
      'y' => 4,
      'l' => 1,
    ),
    'tr4' => 
    array (
      'balises' => 
      array (
        0 => 'BG7',
        1 => 'BG8',
      ),
      'or' => 's',
      'x' => 15,
      'y' => 2,
      'l' => 1,
    ),
    'tr5' => 
    array (
      'balises' => 
      array (
        0 => 'BG9',
        1 => 'BG10',
      ),
      'or' => 's',
      'x' => 22,
      'y' => 2,
      'l' => 1,
    ),
    'tr6' => 
    array (
      'balises' => 
      array (
        0 => 'BG11',
        1 => 'BG12',
      ),
      'or' => 's',
      'x' => 22,
      'y' => 4,
      'l' => 1,
    ),
  ),
  'arsIndicator' => 
  array (
    'x' => 1,
    'y' => 0,
  ),
  'eStopIndicator' => 
  array (
    'x' => 5,
    'y' => 0,
  ),
);
?>