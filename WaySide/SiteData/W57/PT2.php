<?php
// ------------------------------------------------- Sources
$PT2_SIGNALLING_LAYOUT_FILE = "/home/jabe/Desktop/Git/WinterTrain/WaySide/SiteData/W57/signallingLayout.sch";
$PT2_SIGNALLING_LAYOUT_FILE_DATE = "2024-02-08 21:14:29";
$PT2_SCREEN_LAYOUT_FILE = "/home/jabe/Desktop/Git/WinterTrain/WaySide/SiteData/W57/screenLayout.sch";
$PT2_SCREEN_LAYOUT_FILE_DATE = "2024-02-08 21:14:29";
$PT2_GENERATION_TIME = "2024-09-14 16:03:43";
$PT1_PROJECT_NAME = "W57 testbane";
$PT1_DATE = "2020-10-30";
$PT1_AUTHOR = "JB";
$HMI_PROJECT_NAME = "W57_2020";

// -------------------------------------------------- PT1
$PT1 = array (
  'BG01' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG02',
      'dist' => 60,
    ),
    'D' => 
    array (
      'name' => 'BS1',
      'dist' => 60,
    ),
    'ID' => '1E:00:57:5B:28',
  ),
  'BG02' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG03',
      'dist' => 30,
    ),
    'D' => 
    array (
      'name' => 'BG01',
      'dist' => 55,
    ),
    'ID' => '74:00:10:F3:3E',
  ),
  'BG03' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG04',
      'dist' => 40,
    ),
    'D' => 
    array (
      'name' => 'BG02',
      'dist' => 45,
    ),
    'ID' => '76:00:0D:1A:2E',
  ),
  'BG04' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S1',
      'dist' => 90,
    ),
    'D' => 
    array (
      'name' => 'BG03',
      'dist' => 30,
    ),
    'ID' => '73:00:6E:C8:15',
  ),
  'BG5' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P1',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S1',
      'dist' => 1,
    ),
    'ID' => '1F:00:61:E5:87',
  ),
  'BG6' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S2',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P1',
      'dist' => 1,
    ),
    'ID' => '1E:00:EA:E8:E9',
  ),
  'BG7' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S3',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P1',
      'dist' => 1,
    ),
    'ID' => '1E:00:B0:50:33',
  ),
  'BG8' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P2',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S4',
      'dist' => 1,
    ),
    'ID' => '1E:00:EC:F9:E1',
  ),
  'BG9' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P2',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S5',
      'dist' => 1,
    ),
    'ID' => '73:00:56:D9:B2',
  ),
  'BG10' => 
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
      'dist' => 1,
    ),
    'ID' => '74:00:11:07:0B',
  ),
  'BG11' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BS2',
      'dist' => 25,
    ),
    'D' => 
    array (
      'name' => 'S6',
      'dist' => 63,
    ),
    'ID' => '1F:00:4D:6D:73',
  ),
  'BS1' => 
  array (
    'element' => 'BSB',
    'U' => 
    array (
      'name' => 'BG01',
      'dist' => 60,
    ),
    'routeInfo' => 0,
    'HMI' => 
    array (
      'x' => 0,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'BS2' => 
  array (
    'element' => 'BSE',
    'D' => 
    array (
      'name' => 'BG11',
      'dist' => 30,
    ),
    'routeInfo' => 0,
    'HMI' => 
    array (
      'x' => 16,
      'y' => 3,
      'l' => 1,
    ),
  ),
  'P1' => 
  array (
    'element' => 'PF',
    'T' => 
    array (
      'name' => 'BG5',
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
    'supervisionState' => 'CL',
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
      'x' => 6,
      'y' => 3,
      'l' => 2,
    ),
  ),
  'P2' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'BG10',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG8',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG9',
      'dist' => 30,
    ),
    'supervisionState' => 'CR',
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
      'x' => 13,
      'y' => 3,
      'l' => 2,
    ),
  ),
  'S1' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG5',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BG04',
      'dist' => 1,
    ),
    'type' => 'MB',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 0,
      'type' => 0,
      'majorDevice' => 0,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 4,
      'y' => 3,
      'l' => 2,
    ),
  ),
  'S2' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'VBG01',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG6',
      'dist' => 10,
    ),
    'type' => 'MB',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 0,
      'type' => 0,
      'majorDevice' => 0,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 8,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'S3' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'VBG02',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG7',
      'dist' => 10,
    ),
    'type' => 'MB',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 0,
      'type' => 0,
      'majorDevice' => 0,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 8,
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
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'VBG01',
      'dist' => 1,
    ),
    'type' => 'MB',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 0,
      'type' => 0,
      'majorDevice' => 0,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
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
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG9',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'VBG02',
      'dist' => 1,
    ),
    'type' => 'MB',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 0,
      'type' => 0,
      'majorDevice' => 0,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 11,
      'y' => 5,
      'l' => 2,
    ),
  ),
  'S6' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG11',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG10',
      'dist' => 10,
    ),
    'type' => 'MB',
    'EPdist' => 0,
    'riType' => 'NRI',
    'routeInfo' => 0,
    'EC' => 
    array (
      'addr' => 0,
      'type' => 0,
      'majorDevice' => 0,
      'riAddr' => 0,
      'riType' => 0,
      'riMajorDevice' => 0,
    ),
    'HMI' => 
    array (
      'x' => 15,
      'y' => 2,
      'l' => 1,
    ),
  ),
  'VBG01' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S4',
      'dist' => 50,
    ),
    'D' => 
    array (
      'name' => 'S2',
      'dist' => 50,
    ),
    'ID' => '00:00:00:00:01',
  ),
  'VBG02' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S5',
      'dist' => 50,
    ),
    'D' => 
    array (
      'name' => 'S3',
      'dist' => 50,
    ),
    'ID' => '00:00:00:00:02',
  ),
);

// -------------------------------------------------- HMI
$HMI = array (
  'label' => 
  array (
  ),
  'baliseTrack' => 
  array (
    'tr1' => 
    array (
      'balises' => 
      array (
        0 => 'BG01',
      ),
      'or' => 's',
      'x' => 1,
      'y' => 3,
      'l' => 1,
    ),
    'tr2' => 
    array (
      'balises' => 
      array (
        0 => 'BG02',
      ),
      'or' => 's',
      'x' => 2,
      'y' => 3,
      'l' => 1,
    ),
    'tr3' => 
    array (
      'balises' => 
      array (
        0 => 'BG03',
        1 => 'BG04',
      ),
      'or' => 's',
      'x' => 3,
      'y' => 3,
      'l' => 1,
    ),
    'tr4' => 
    array (
      'balises' => 
      array (
        0 => 'VBG01',
      ),
      'or' => 's',
      'x' => 10,
      'y' => 3,
      'l' => 1,
    ),
    'tr5' => 
    array (
      'balises' => 
      array (
        0 => 'VBG02',
      ),
      'or' => 's',
      'x' => 10,
      'y' => 5,
      'l' => 1,
    ),
  ),
  'projectName' => 'W57_2020',
  'arsIndicator' => 
  array (
    'x' => 0,
    'y' => 1,
  ),
  'eStopIndicator' => 
  array (
    'x' => 0,
    'y' => 7,
  ),
);
?>