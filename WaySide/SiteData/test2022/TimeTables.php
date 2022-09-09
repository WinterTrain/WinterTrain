<?php
// ------------------------------------------------- 

$PT2_GENERATION_TIME = "2022-04-16 22:03:12";

// -------------------------------------------------- TimeTables
$timeTables = array (
  21 => 
  array (
    'protection' => '',
    'start' => 'Spisebord',
    'destination' => 'Reol',
    'description' => 'Krydsning, Op',
    'locationTable' => 
    array (
      0 => 
      array (
        'station' => 'Spisebord',
        'location' => 'S1',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'S4',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
          1 => 
          array (
            'dest' => 'S5',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      1 => 
      array (
        'station' => '',
        'location' => 'S4',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'S7',
            'delay' => '10',
            'action' => 'W',
            'xTrn' => '32',
            'xSig' => 'S3',
          ),
        ),
      ),
      2 => 
      array (
        'station' => '',
        'location' => 'S5',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'S7',
            'delay' => '10',
            'action' => 'W',
            'xTrn' => '32',
            'xSig' => 'S2',
          ),
        ),
      ),
      3 => 
      array (
        'station' => '',
        'location' => 'S7',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'BS2',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
          1 => 
          array (
            'dest' => 'BS3',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      4 => 
      array (
        'station' => 'Reol',
        'location' => 'BS2',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => '',
            'delay' => '10',
            'action' => 'N',
            'xTrn' => '32',
            'xSig' => '',
          ),
        ),
      ),
      5 => 
      array (
        'station' => '',
        'location' => 'BS3',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => '',
            'delay' => '20',
            'action' => 'N',
            'xTrn' => '32',
            'xSig' => '',
          ),
        ),
      ),
    ),
  ),
  32 => 
  array (
    'protection' => '',
    'start' => 'Reol',
    'destination' => 'Spisebord',
    'description' => 'Krydsning, Ned',
    'locationTable' => 
    array (
      0 => 
      array (
        'station' => 'Reol',
        'location' => 'S8',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'S6',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      1 => 
      array (
        'station' => '',
        'location' => 'S9',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'S6',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      2 => 
      array (
        'station' => '',
        'location' => 'S6',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'S2',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
          1 => 
          array (
            'dest' => 'S3',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      3 => 
      array (
        'station' => '',
        'location' => 'S2',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'BS1',
            'delay' => '10',
            'action' => 'W',
            'xTrn' => '21',
            'xSig' => 'S5',
          ),
        ),
      ),
      4 => 
      array (
        'station' => '',
        'location' => 'S3',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'BS1',
            'delay' => '10',
            'action' => 'W',
            'xTrn' => '21',
            'xSig' => 'S4',
          ),
        ),
      ),
      5 => 
      array (
        'station' => 'Spisebord',
        'location' => 'BS1',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => '',
            'delay' => '30',
            'action' => 'N',
            'xTrn' => '21',
            'xSig' => '',
          ),
        ),
      ),
    ),
  ),
  11 => 
  array (
    'protection' => 'R',
    'start' => 'Spisebord',
    'destination' => 'Reol',
    'description' => 'Op',
    'locationTable' => 
    array (
      0 => 
      array (
        'station' => '',
        'location' => 'S1',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'S4',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
          1 => 
          array (
            'dest' => 'S5',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      1 => 
      array (
        'station' => '',
        'location' => 'S4',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'S7',
            'delay' => '10',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      2 => 
      array (
        'station' => '',
        'location' => 'S5',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'S7',
            'delay' => '10',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      3 => 
      array (
        'station' => '',
        'location' => 'S7',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'BS2',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
          1 => 
          array (
            'dest' => 'BS3',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      4 => 
      array (
        'station' => '',
        'location' => 'BS2',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => '',
            'delay' => '20',
            'action' => 'N',
            'xTrn' => '12',
            'xSig' => '',
          ),
        ),
      ),
      5 => 
      array (
        'station' => '',
        'location' => 'BS3',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => '',
            'delay' => '20',
            'action' => 'N',
            'xTrn' => '12',
            'xSig' => '',
          ),
        ),
      ),
    ),
  ),
  12 => 
  array (
    'protection' => 'R',
    'start' => 'Reol',
    'destination' => 'Spisebord',
    'description' => 'Ned',
    'locationTable' => 
    array (
      0 => 
      array (
        'station' => '',
        'location' => 'S8',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'S6',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      1 => 
      array (
        'station' => '',
        'location' => 'S9',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'S6',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      2 => 
      array (
        'station' => '',
        'location' => 'S6',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'S2',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
          1 => 
          array (
            'dest' => 'S3',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      3 => 
      array (
        'station' => '',
        'location' => 'S3',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'BS1',
            'delay' => '10',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      4 => 
      array (
        'station' => '',
        'location' => 'S2',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'BS1',
            'delay' => '10',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      5 => 
      array (
        'station' => '',
        'location' => 'BS1',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => '',
            'delay' => '20',
            'action' => 'N',
            'xTrn' => '11',
            'xSig' => '',
          ),
        ),
      ),
    ),
  ),
  102 => 
  array (
    'protection' => 'E',
    'start' => 'Gulvet, S5',
    'destination' => 'Gulvet, S3',
    'description' => 'Vending, reol BS3',
    'locationTable' => 
    array (
      0 => 
      array (
        'station' => '',
        'location' => 'S5',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'BS3',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      1 => 
      array (
        'station' => '',
        'location' => 'BS3',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => '',
            'delay' => '',
            'action' => 'D',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      2 => 
      array (
        'station' => '',
        'location' => 'S9',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'S3',
            'delay' => '10',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      3 => 
      array (
        'station' => '',
        'location' => 'S3',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => '',
            'delay' => '',
            'action' => 'E',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
    ),
  ),
  101 => 
  array (
    'protection' => 'E',
    'start' => 'Gulvet, S4',
    'destination' => 'Gulvet, S2',
    'description' => 'Vending, reol BS2',
    'locationTable' => 
    array (
      0 => 
      array (
        'station' => 'Gulvet',
        'location' => 'S4',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'BS2',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      1 => 
      array (
        'station' => '',
        'location' => 'BS2',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => '',
            'delay' => '',
            'action' => 'D',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      2 => 
      array (
        'station' => 'Reolen',
        'location' => 'S8',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'S2',
            'delay' => '10',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      3 => 
      array (
        'station' => 'Gulvet',
        'location' => 'S2',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => '',
            'delay' => '',
            'action' => 'E',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
    ),
  ),
  111 => 
  array (
    'protection' => '',
    'start' => 'Gulvet, S3',
    'destination' => 'Gulvet, S5',
    'description' => 'Vending reol, BS2',
    'locationTable' => 
    array (
      0 => 
      array (
        'station' => '',
        'approach' => 'on',
        'location' => 'S4',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'BS2',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
          1 => 
          array (
            'dest' => 'BS3',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      1 => 
      array (
        'station' => '',
        'approach' => 'on',
        'location' => 'S5',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'BS3',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
          1 => 
          array (
            'dest' => 'BS2',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      2 => 
      array (
        'station' => '',
        'location' => 'BS3',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => '',
            'delay' => '',
            'action' => 'D',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      3 => 
      array (
        'station' => '',
        'location' => 'BS2',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => '',
            'delay' => '',
            'action' => 'D',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      4 => 
      array (
        'station' => '',
        'approach' => 'on',
        'location' => 'S8',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'S2',
            'delay' => '',
            'action' => 'W',
            'xTrn' => '112',
            'xSig' => 'S9',
          ),
        ),
      ),
      5 => 
      array (
        'station' => '',
        'approach' => 'on',
        'location' => 'S9',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'S3',
            'delay' => '',
            'action' => 'W',
            'xTrn' => '112',
            'xSig' => 'S8',
          ),
        ),
      ),
      6 => 
      array (
        'station' => '',
        'location' => 'S2',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => '',
            'delay' => '20',
            'action' => 'N',
            'xTrn' => '111',
            'xSig' => '',
          ),
        ),
      ),
      7 => 
      array (
        'station' => '',
        'location' => 'S3',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => '',
            'delay' => '20',
            'action' => 'N',
            'xTrn' => '111',
            'xSig' => '',
          ),
        ),
      ),
    ),
  ),
  112 => 
  array (
    'protection' => '',
    'start' => 'Gulvet, S5 (Her)',
    'destination' => 'Gulvet, S3 (Der)',
    'description' => 'Vending, reol BS3',
    'locationTable' => 
    array (
      0 => 
      array (
        'station' => 'Her',
        'approach' => 'on',
        'location' => 'S5',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'BS3',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
          1 => 
          array (
            'dest' => 'BS2',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      1 => 
      array (
        'station' => '',
        'approach' => 'on',
        'location' => 'S4',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'BS2',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
          1 => 
          array (
            'dest' => 'BS3',
            'delay' => '',
            'action' => ' ',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      2 => 
      array (
        'station' => 'Via',
        'location' => 'BS3',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => '',
            'delay' => '10',
            'action' => 'D',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      3 => 
      array (
        'station' => '',
        'location' => 'BS2',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => '',
            'delay' => '20',
            'action' => 'D',
            'xTrn' => '',
            'xSig' => '',
          ),
        ),
      ),
      4 => 
      array (
        'station' => '',
        'approach' => 'on',
        'location' => 'S9',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'S3',
            'delay' => '',
            'action' => 'W',
            'xTrn' => '111',
            'xSig' => 'S8',
          ),
        ),
      ),
      5 => 
      array (
        'station' => '',
        'approach' => 'on',
        'location' => 'S8',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => 'S2',
            'delay' => '',
            'action' => 'W',
            'xTrn' => '111',
            'xSig' => 'S9',
          ),
        ),
      ),
      6 => 
      array (
        'station' => 'Der',
        'location' => 'S3',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => '',
            'delay' => '20',
            'action' => 'N',
            'xTrn' => '112',
            'xSig' => '',
          ),
        ),
      ),
      7 => 
      array (
        'station' => '',
        'location' => 'S2',
        'actionTable' => 
        array (
          0 => 
          array (
            'dest' => '',
            'delay' => '20',
            'action' => 'N',
            'xTrn' => '112',
            'xSig' => '',
          ),
        ),
      ),
    ),
  ),
);

?>