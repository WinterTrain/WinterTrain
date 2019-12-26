<?php
$PT1_VERSION = "FIXME";

// -------------------------------------------------- PT1
$PT1 = array (
  'BG332' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG33',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BG32',
      'dist' => 0,
    ),
    'ID' => '1F:00:62:A3:D5',
  ),
  'PHT2' => 
  array (
    'element' => 'PHTD',
    'U' => 
    array (
      'name' => 'BG28',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BG27',
      'dist' => 0,
    ),
    'holdPoint' => 'P3',
  ),
  'PHT1' => 
  array (
    'element' => 'PHTU',
    'U' => 
    array (
      'name' => 'BG10',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BG9',
      'dist' => 0,
    ),
    'holdPoint' => 'P2',
  ),
  'BG18' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG21',
      'dist' => 57,
    ),
    'D' => 
    array (
      'name' => 'S13',
      'dist' => 113,
    ),
    'ID' => '1E:00:8E:B8:97',
  ),
  'BG15' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG17',
      'dist' => 70,
    ),
    'D' => 
    array (
      'name' => 'S11',
      'dist' => 140,
    ),
    'ID' => '1F:00:62:25:B2',
  ),
  'BG7' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S6',
      'dist' => 157,
    ),
    'D' => 
    array (
      'name' => 'BG6',
      'dist' => 60,
    ),
    'ID' => '76:00:0C:FA:7D',
  ),
  'BG25' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S23',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P4',
      'dist' => 7,
    ),
    'ID' => '1F:00:50:3C:80',
  ),
  'BS4' => 
  array (
    'element' => 'BSB',
    'U' => 
    array (
      'name' => 'S22',
      'dist' => 96,
    ),
    'HMI' => 
    array (
      'x' => 22,
      'y' => 4,
      'l' => 1,
    ),
  ),
  'BS3' => 
  array (
    'element' => 'BSB',
    'U' => 
    array (
      'name' => 'S20',
      'dist' => 93,
    ),
    'HMI' => 
    array (
      'x' => 22,
      'y' => 2,
      'l' => 1,
    ),
  ),
  'S20' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG16',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BS3',
      'dist' => 1,
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
      'x' => 23,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'S22' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG20',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BS4',
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
      'x' => 23,
      'y' => 4,
      'l' => 2,
    ),
  ),
  'BG20' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG22',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'S22',
      'dist' => 7,
    ),
    'ID' => '73:00:56:93:AA',
  ),
  'BG16' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG19',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'S20',
      'dist' => 7,
    ),
    'ID' => '1F:00:4D:6A:29',
  ),
  'BG22' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P4',
      'dist' => 6,
    ),
    'D' => 
    array (
      'name' => 'BG20',
      'dist' => 7,
    ),
    'ID' => '76:00:0D:0B:F7',
  ),
  'BG19' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P4',
      'dist' => 6,
    ),
    'D' => 
    array (
      'name' => 'BG16',
      'dist' => 7,
    ),
    'ID' => '1F:00:13:C9:5E',
  ),
  'P4' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'BG25',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG19',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG22',
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
  'S23' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG26',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG25',
      'dist' => 2,
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
      'x' => 27,
      'y' => 1,
      'l' => 1,
    ),
  ),
  'BG26' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S24',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'S23',
      'dist' => 119,
    ),
    'ID' => '1E:00:EA:E8:E9',
  ),
  'S24' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG29',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BG26',
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
      'x' => 29,
      'y' => 2,
      'l' => 1,
    ),
  ),
  'BG29' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S21',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'S24',
      'dist' => 0,
    ),
    'ID' => '1E:00:57:5B:28',
  ),
  'S21' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG32',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG29',
      'dist' => 10,
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
      'x' => 30,
      'y' => 1,
      'l' => 1,
    ),
  ),
  'BG32' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG332',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'S21',
      'dist' => 0,
    ),
    'ID' => '1F:00:4A:FE:5F',
  ),
  'BG33' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S32',
      'dist' => 48,
    ),
    'D' => 
    array (
      'name' => 'BG332',
      'dist' => 0,
    ),
    'ID' => '1E:00:AC:98:25',
  ),
  'BG44' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S36',
      'dist' => 59,
    ),
    'D' => 
    array (
      'name' => 'BG43',
      'dist' => 11,
    ),
    'ID' => '76:00:0C:B7:36',
  ),
  'BG43' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG44',
      'dist' => 11,
    ),
    'D' => 
    array (
      'name' => 'BG42',
      'dist' => 10,
    ),
    'ID' => '74:00:11:07:0B',
  ),
  'BG42' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG43',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'S5',
      'dist' => 18,
    ),
    'ID' => '1F:00:50:6E:08',
  ),
  'S36' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG46',
      'dist' => 4,
    ),
    'D' => 
    array (
      'name' => 'BG44',
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
      'x' => 39,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'BG46' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG48',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'S36',
      'dist' => 5,
    ),
    'ID' => '76:00:0C:FC:CB',
  ),
  'BG48' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P7',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG46',
      'dist' => 10,
    ),
    'ID' => '73:00:6E:C8:15',
  ),
  'BS5' => 
  array (
    'element' => 'BSE',
    'D' => 
    array (
      'name' => 'BG51',
      'dist' => 60,
    ),
    'HMI' => 
    array (
      'x' => 46,
      'y' => 6,
      'l' => 1,
    ),
  ),
  'BG51' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BS5',
      'dist' => 81,
    ),
    'D' => 
    array (
      'name' => 'BG50',
      'dist' => 41,
    ),
    'ID' => '73:00:70:98:69',
  ),
  'BG50' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG51',
      'dist' => 41,
    ),
    'D' => 
    array (
      'name' => 'S1',
      'dist' => 21,
    ),
    'ID' => '1E:00:90:0C:8B',
  ),
  'S1' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG50',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P7',
      'dist' => 2,
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
      'x' => 44,
      'y' => 5,
      'l' => 1,
    ),
  ),
  'P7' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'S1',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG48',
      'dist' => 28,
    ),
    'L' => 
    array (
      'name' => 'BG49',
      'dist' => 28,
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
      'or' => 'tr',
      'x' => 42,
      'y' => 4,
      'l' => 2,
    ),
  ),
  'BG49' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P7',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG47',
      'dist' => 9,
    ),
    'ID' => '76:00:0C:A4:29',
  ),
  'BG47' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG49',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'S34',
      'dist' => 1,
    ),
    'ID' => '74:00:10:F3:3E',
  ),
  'S34' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG47',
      'dist' => 9,
    ),
    'D' => 
    array (
      'name' => 'BG45',
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
      'x' => 39,
      'y' => 6,
      'l' => 2,
    ),
  ),
  'BG45' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S34',
      'dist' => 81,
    ),
    'D' => 
    array (
      'name' => 'S3',
      'dist' => 42,
    ),
    'ID' => '76:00:0D:19:5B',
  ),
  'S5' => 
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
      'dist' => 4,
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
      'x' => 37,
      'y' => 1,
      'l' => 1,
    ),
  ),
  'S32' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG37',
      'dist' => 5,
    ),
    'D' => 
    array (
      'name' => 'BG33',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EC' => 
    array (
      'addr' => 203,
      'type' => 41,
      'majorDevice' => 2,
    ),
    'HMI' => 
    array (
      'x' => 33,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'S3' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG45',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG41',
      'dist' => 1,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 203,
      'type' => 45,
      'majorDevice' => 3,
    ),
    'HMI' => 
    array (
      'x' => 36,
      'y' => 5,
      'l' => 2,
    ),
  ),
  'BG37' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG38',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'S32',
      'dist' => 6,
    ),
    'ID' => '74:00:15:55:26',
  ),
  'BG41' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S3',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG40',
      'dist' => 9,
    ),
    'ID' => '76:00:0D:1A:2E',
  ),
  'BG38' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P6',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG37',
      'dist' => 11,
    ),
    'ID' => '74:00:15:50:C0',
  ),
  'P6' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'S5',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG38',
      'dist' => 28,
    ),
    'L' => 
    array (
      'name' => 'BG39',
      'dist' => 29,
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
      'x' => 35,
      'y' => 2,
      'l' => 2,
    ),
  ),
  'BG39' => 
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
    'ID' => '74:00:15:65:29',
  ),
  'BG40' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG41',
      'dist' => 9,
    ),
    'D' => 
    array (
      'name' => 'P5',
      'dist' => 2,
    ),
    'ID' => '73:00:56:C0:72',
  ),
  'P5' => 
  array (
    'element' => 'PF',
    'T' => 
    array (
      'name' => 'S30',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG40',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG39',
      'dist' => 29,
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
      'or' => 'fl',
      'x' => 33,
      'y' => 4,
      'l' => 2,
    ),
  ),
  'S30' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'P5',
      'dist' => 5,
    ),
    'D' => 
    array (
      'name' => 'BG36',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EC' => 
    array (
      'addr' => 203,
      'type' => 41,
      'majorDevice' => 1,
    ),
    'HMI' => 
    array (
      'x' => 32,
      'y' => 6,
      'l' => 1,
    ),
  ),
  'BG36' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S30',
      'dist' => 14,
    ),
    'D' => 
    array (
      'name' => 'BG35',
      'dist' => 45,
    ),
    'ID' => '1F:00:4A:F2:D2',
  ),
  'BG34' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG35',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'S7',
      'dist' => 0,
    ),
    'ID' => '75:00:14:FB:94',
  ),
  'BG35' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG36',
      'dist' => 45,
    ),
    'D' => 
    array (
      'name' => 'BG34',
      'dist' => 0,
    ),
    'ID' => '1F:00:69:F3:BB',
  ),
  'S14' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'S7',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BG31',
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
      'x' => 27,
      'y' => 8,
      'l' => 1,
    ),
  ),
  'S7' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG34',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S14',
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
      'x' => 28,
      'y' => 7,
      'l' => 1,
    ),
  ),
  'BG31' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S14',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BG30',
      'dist' => 0,
    ),
    'ID' => '73:00:56:D6:F2',
  ),
  'BG30' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG31',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BG28',
      'dist' => 0,
    ),
    'ID' => '73:00:56:D9:B2',
  ),
  'BG28' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG30',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'PHT2',
      'dist' => 0,
    ),
    'ID' => '1F:00:69:C9:3C',
  ),
  'BG27' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'PHT2',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'S9',
      'dist' => 0,
    ),
    'ID' => '1E:00:8E:70:11',
  ),
  'S9' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG27',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'P3',
      'dist' => 2,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 44,
      'majorDevice' => 12,
    ),
    'HMI' => 
    array (
      'x' => 24,
      'y' => 7,
      'l' => 1,
    ),
  ),
  'P3' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'S9',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG23',
      'dist' => 30,
    ),
    'L' => 
    array (
      'name' => 'BG24',
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
      'x' => 22,
      'y' => 8,
      'l' => 2,
    ),
  ),
  'BG24' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P3',
      'dist' => 5,
    ),
    'D' => 
    array (
      'name' => 'S12',
      'dist' => 10,
    ),
    'ID' => '1E:00:AC:75:9B',
  ),
  'BG23' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P3',
      'dist' => 5,
    ),
    'D' => 
    array (
      'name' => 'S10',
      'dist' => 9,
    ),
    'ID' => '1E:00:B0:50:33',
  ),
  'S10' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG23',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BG17',
      'dist' => 1,
    ),
    'type' => 'MS3',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 44,
      'majorDevice' => 9,
    ),
    'HMI' => 
    array (
      'x' => 20,
      'y' => 8,
      'l' => 2,
    ),
  ),
  'S12' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG24',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'BG21',
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
      'x' => 20,
      'y' => 10,
      'l' => 2,
    ),
  ),
  'BG21' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S12',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG18',
      'dist' => 57,
    ),
    'ID' => '1F:00:4D:FF:CC',
  ),
  'BG17' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S10',
      'dist' => 3,
    ),
    'D' => 
    array (
      'name' => 'BG15',
      'dist' => 70,
    ),
    'ID' => '1F:00:62:74:36',
  ),
  'S13' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG18',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG14',
      'dist' => 3,
    ),
    'type' => 'MS2',
    'EC' => 
    array (
      'addr' => 201,
      'type' => 40,
      'majorDevice' => 7,
    ),
    'HMI' => 
    array (
      'x' => 17,
      'y' => 9,
      'l' => 2,
    ),
  ),
  'S11' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG15',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG12',
      'dist' => 3,
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
      'x' => 17,
      'y' => 7,
      'l' => 2,
    ),
  ),
  'BG13' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG14',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'P2',
      'dist' => 4,
    ),
    'ID' => '1F:00:78:EF:FD',
  ),
  'BG14' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S13',
      'dist' => 4,
    ),
    'D' => 
    array (
      'name' => 'BG13',
      'dist' => 10,
    ),
    'ID' => '1F:00:4D:6D:73',
  ),
  'BG11' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG12',
      'dist' => 10,
    ),
    'D' => 
    array (
      'name' => 'P2',
      'dist' => 2,
    ),
    'ID' => '1F:00:69:A9:3C',
  ),
  'BG12' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S11',
      'dist' => 4,
    ),
    'D' => 
    array (
      'name' => 'BG11',
      'dist' => 9,
    ),
    'ID' => '1E:00:8E:DF:1F',
  ),
  'P2' => 
  array (
    'element' => 'PF',
    'T' => 
    array (
      'name' => 'S8',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG13',
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
      'addr' => 201,
      'type' => 10,
      'majorDevice' => 1,
      'minorDevice' => 0,
    ),
    'HMI' => 
    array (
      'or' => 'fr',
      'x' => 15,
      'y' => 8,
      'l' => 2,
    ),
  ),
  'S8' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'P2',
      'dist' => 12,
    ),
    'D' => 
    array (
      'name' => 'BG10',
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
      'x' => 14,
      'y' => 8,
      'l' => 1,
    ),
  ),
  'BG10' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S8',
      'dist' => 11,
    ),
    'D' => 
    array (
      'name' => 'PHT1',
      'dist' => 45,
    ),
    'ID' => '1E:00:57:35:F9',
  ),
  'BG9' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'PHT1',
      'dist' => 44,
    ),
    'D' => 
    array (
      'name' => 'BG8',
      'dist' => 146,
    ),
    'ID' => '1E:00:8E:57:F6',
  ),
  'S15' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG8',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S6',
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
      'x' => 11,
      'y' => 7,
      'l' => 1,
    ),
  ),
  'S6' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'S15',
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
      'x' => 10,
      'y' => 8,
      'l' => 1,
    ),
  ),
  'BG8' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG9',
      'dist' => 147,
    ),
    'D' => 
    array (
      'name' => 'S15',
      'dist' => 90,
    ),
    'ID' => '73:00:56:9F:A1',
  ),
  'S17' => 
  array (
    'element' => 'SD',
    'U' => 
    array (
      'name' => 'BG6',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BG5',
      'dist' => 10,
    ),
    'type' => 'MS2',
    'EC' => 
    array (
      'addr' => 203,
      'type' => 41,
      'majorDevice' => 6,
    ),
    'HMI' => 
    array (
      'x' => 7,
      'y' => 7,
      'l' => 1,
    ),
  ),
  'BG6' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'BG7',
      'dist' => 60,
    ),
    'D' => 
    array (
      'name' => 'S17',
      'dist' => 188,
    ),
    'ID' => '76:00:0C:E3:A8',
  ),
  'BG5' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S17',
      'dist' => 30,
    ),
    'D' => 
    array (
      'name' => 'P1',
      'dist' => 19,
    ),
    'ID' => '1E:00:98:B1:C9',
  ),
  'P1' => 
  array (
    'element' => 'PT',
    'T' => 
    array (
      'name' => 'BG5',
      'dist' => 1,
    ),
    'R' => 
    array (
      'name' => 'BG3',
      'dist' => 29,
    ),
    'L' => 
    array (
      'name' => 'BG4',
      'dist' => 29,
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
      'x' => 5,
      'y' => 6,
      'l' => 2,
    ),
  ),
  'BG4' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P1',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S2',
      'dist' => 9,
    ),
    'ID' => '1E:00:90:0C:A5',
  ),
  'BG3' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'P1',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'S4',
      'dist' => 9,
    ),
    'ID' => '1F:00:61:E5:87',
  ),
  'S4' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG3',
      'dist' => 0,
    ),
    'D' => 
    array (
      'name' => 'BG1',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EC' => 
    array (
      'addr' => 203,
      'type' => 41,
      'majorDevice' => 7,
    ),
    'HMI' => 
    array (
      'x' => 3,
      'y' => 6,
      'l' => 2,
    ),
  ),
  'S2' => 
  array (
    'element' => 'SU',
    'U' => 
    array (
      'name' => 'BG4',
      'dist' => 9,
    ),
    'D' => 
    array (
      'name' => 'BG2',
      'dist' => 1,
    ),
    'type' => 'MS2',
    'EC' => 
    array (
      'addr' => 203,
      'type' => 41,
      'majorDevice' => 8,
    ),
    'HMI' => 
    array (
      'x' => 3,
      'y' => 8,
      'l' => 2,
    ),
  ),
  'BG2' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S2',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BS2',
      'dist' => 80,
    ),
    'ID' => '1F:00:84:7C:A5',
  ),
  'BG1' => 
  array (
    'element' => 'BL',
    'U' => 
    array (
      'name' => 'S4',
      'dist' => 1,
    ),
    'D' => 
    array (
      'name' => 'BS1',
      'dist' => 95,
    ),
    'ID' => '1E:00:EC:F9:E1',
  ),
  'BS1' => 
  array (
    'element' => 'BSB',
    'U' => 
    array (
      'name' => 'BG1',
      'dist' => 60,
    ),
    'HMI' => 
    array (
      'x' => 1,
      'y' => 6,
      'l' => 1,
    ),
  ),
  'BS2' => 
  array (
    'element' => 'BSB',
    'U' => 
    array (
      'name' => 'BG2',
      'dist' => 60,
    ),
    'HMI' => 
    array (
      'x' => 1,
      'y' => 8,
      'l' => 1,
    ),
  ),
);

// -------------------------------------------------- HMI
$HMI = array (
  'eStopIndicator' => 
  array (
    'x' => 2,
    'y' => 1,
  ),
  'arsIndicator' => 
  array (
    'x' => 2,
    'y' => 2,
  ),
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
      'y' => 6,
      'l' => 1,
    ),
    'tr2' => 
    array (
      'balises' => 
      array (
        0 => 'BG2',
      ),
      'or' => 's',
      'x' => 2,
      'y' => 8,
      'l' => 1,
    ),
    'tr3' => 
    array (
      'balises' => 
      array (
        0 => 'BG6',
      ),
      'or' => 's',
      'x' => 8,
      'y' => 8,
      'l' => 1,
    ),
    'tr4' => 
    array (
      'balises' => 
      array (
        0 => 'BG7',
      ),
      'or' => 's',
      'x' => 9,
      'y' => 8,
      'l' => 1,
    ),
    'tr5' => 
    array (
      'balises' => 
      array (
        0 => 'BG8',
      ),
      'or' => 's',
      'x' => 12,
      'y' => 8,
      'l' => 1,
    ),
    'tr9' => 
    array (
      'balises' => 
      array (
        0 => 'BG15',
        1 => 'BG17',
      ),
      'or' => 's',
      'x' => 19,
      'y' => 8,
      'l' => 1,
    ),
    'tr10' => 
    array (
      'balises' => 
      array (
        0 => 'BG18',
        1 => 'BG21',
      ),
      'or' => 's',
      'x' => 19,
      'y' => 10,
      'l' => 1,
    ),
    'tr6' => 
    array (
      'balises' => 
      array (
        0 => 'BG9',
        1 => 'BG10',
      ),
      'or' => 's',
      'x' => 13,
      'y' => 8,
      'l' => 1,
    ),
    'tr13' => 
    array (
      'balises' => 
      array (
        0 => 'BG27',
        1 => 'BG28',
      ),
      'or' => 's',
      'x' => 25,
      'y' => 8,
      'l' => 1,
    ),
    'tr15' => 
    array (
      'balises' => 
      array (
        0 => 'BG30',
        1 => 'BG31',
      ),
      'or' => 's',
      'x' => 26,
      'y' => 8,
      'l' => 1,
    ),
    'tr17' => 
    array (
      'balises' => 
      array (
        0 => 'BG35',
      ),
      'or' => 'u',
      'x' => 30,
      'y' => 6,
      'l' => 1,
    ),
    'tr21' => 
    array (
      'balises' => 
      array (
        0 => 'BG40',
        1 => 'BG41',
      ),
      'or' => 's',
      'x' => 35,
      'y' => 6,
      'l' => 1,
    ),
    'tr23' => 
    array (
      'balises' => 
      array (
        0 => 'BG45',
      ),
      'or' => 's',
      'x' => 38,
      'y' => 6,
      'l' => 1,
    ),
    'tr20' => 
    array (
      'balises' => 
      array (
        0 => 'BG42',
        1 => 'BG43',
        2 => 'BG44',
      ),
      'or' => 's',
      'x' => 38,
      'y' => 2,
      'l' => 1,
    ),
    'tr22' => 
    array (
      'balises' => 
      array (
        0 => 'BG46',
        1 => 'BG48',
      ),
      'or' => 'd',
      'x' => 41,
      'y' => 2,
      'l' => 1,
    ),
    'tr25' => 
    array (
      'balises' => 
      array (
        0 => 'BG50',
        1 => 'BG51',
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
        0 => 'BG47',
        1 => 'BG49',
      ),
      'or' => 's',
      'x' => 41,
      'y' => 6,
      'l' => 1,
    ),
    'tr14' => 
    array (
      'balises' => 
      array (
        0 => 'BG32',
      ),
      'or' => 's',
      'x' => 31,
      'y' => 2,
      'l' => 1,
    ),
    'tr16' => 
    array (
      'balises' => 
      array (
        0 => 'BG33',
        1 => 'BG332',
      ),
      'or' => 's',
      'x' => 32,
      'y' => 2,
      'l' => 1,
    ),
    'tr12' => 
    array (
      'balises' => 
      array (
        0 => 'BG26',
      ),
      'or' => 's',
      'x' => 28,
      'y' => 2,
      'l' => 1,
    ),
    'tr18' => 
    array (
      'balises' => 
      array (
        0 => 'BG34',
      ),
      'or' => 's',
      'x' => 29,
      'y' => 8,
      'l' => 1,
    ),
    'tr19' => 
    array (
      'balises' => 
      array (
        0 => 'BG36',
      ),
      'or' => 's',
      'x' => 31,
      'y' => 6,
      'l' => 1,
    ),
  ),
  'label' => 
  array (
    0 => 
    array (
      'x' => 1.875,
      'y' => 9.75,
      'text' => 'Christianshavn',
    ),
    1 => 
    array (
      'x' => 19,
      'y' => 7,
      'text' => 'Alperne',
    ),
    2 => 
    array (
      'x' => 37.875,
      'y' => 7.5,
      'text' => 'Christiana',
    ),
    3 => 
    array (
      'x' => 22,
      'y' => 1,
      'text' => 'Langtbortistan',
    ),
  ),
);
?>