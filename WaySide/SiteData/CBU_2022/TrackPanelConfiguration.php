<?php
// Track Panel
// Configuration data

$PM = array( // I2C address => ModuleConfiguration (Pin no => use)

  0x41 => array( // Prototype I / Alperne station, up
    "type" =>"EVERY",
    "pins" => array(
      0 => "BS4.rR",
      1 => "",
      2 => "S20.tR",
      3 => "S20.tG",
      4 => "P6.rR",
      5 => "P6.rG",
      6 => "P6.lR",
      7 => "P6.lG",
      8 => "S20.lR",
      9 => "S20.lG",
      10 => "S19.lR",
      11 => "S19.lG",
      12 => "COM.N",
      21 => "P6.S",
      13 => "COM.oG",
      14 => "S17.S",
      15 => "S20.S",
      16 => "S26.S",
      17 => "BS4.S",
      20 => "S19.S",
      ),
  ),
    0x3f => array(
    "type" =>"EVERY",
    "pins" => array(
      0 => "",
      1 => "",
      2 => "S13.S",
      3 => "S10.S",
      4 => "S15.S",
      5 => "S16.S",
      9 => "S12.S",
      6 => "S9.S",
      7 => "S14.S",
      8 => "",
      20 => "S11.S",
      21 => "",
      10 => "",
      11 => "",
      12 => "",
      17 => "",
      13 => "S23.lG",
      14 => "",
      15 => "",
      16 => "",
      ),
  ),  

  0x40 => array(
    "type" =>"EVERY",
    "pins" => array(
      0 => "S18.lG",
      1 => "S18.lR",
      2 => "P5.lR",
      3 => "P5.lG",
      4 => "S17.tR",
      5 => "S17.tG",
      6 => "P5.rR",
      7 => "P5.rG",
      8 => "CT2.tR",
      9 => "CT2.tG",
      10 => "CT1.tR",
      11 => "CT1.tG",
      12 => "S26.lR",
      13 => "S26.lG",
      14 => "tr30.tR",
      15 => "tr30.tG",
      16 => "S17.lR",
      17 => "S17.lG",
      20 => "COM.R",
      21 => "COM.O",
      ),
  ), 
  0 => array( // Template
    "type" =>"EVERY",
    "pins" => array(
      0 => "",
      1 => "",
      2 => "",
      3 => "",
      4 => "",
      5 => "",
      6 => "",
      7 => "",
      8 => "",
      9 => "",
      10 => "",
      11 => "",
      12 => "",
      13 => "",
      14 => "",
      15 => "",
      16 => "",
      17 => "",
      20 => "",
      21 => ""),
  ), 
  0 => array( // Template
    "type" =>"MCP23017",
    "pins" => array(
      0 => "",
      1 => "",
      2 => "",
      3 => "",
      4 => "",
      5 => "",
      6 => "",
      7 => "",
      8 => "",
      9 => "",
      10 => "",
      11 => "",
      12 => "",
      13 => "",
      14 => "",
      15 => ""),
  ), 
);

$combinedTrackIndication = array(
  "CT1" => array(
    "S23",
    "tr31",
    "tr32",
    "S26"
  ),
  "CT2" => array(
    "tr33",
    "tr34",
  ),
  "" => array( // Template
    "",
    "",
    "",
  ),
);

?>
