<?php
// Track Panel
// Configuration data

$PM = array( // I2C address => ModuleConfiguration (Pin no => use)
/*
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
  ), */

  0x40 => array( // Arduino to be updated
    "type" =>"EVERY",
    "pins" => array(
      0 => "S11.lG", // S G
      1 => "S11.lR", // S R
      2 => "tr8.tR",  // Tr R
      3 => "tr8.tG",  // Tr G
      4 => "CTS9S10.tR", // Tr R
      5 => "CTS9S10.tG", // Tr G
      6 => "tr7.tR",  // Tr R
      7 => "tr7.tG",  // Tr G
      8 => "tr12.tR", // Tr R
      9 => "tr12.tG", // Tr G
      10 => "tr11.tR", // Tr R
      11 => "tr11.tG", // Tr G
      12 => "S12.lR", // S R
      13 => "S12.lG", // S G
      14 => "CTS11S12.tR", // Tr R
      15 => "CTS11S12.tG", // Tr G
      16 => "S9.lR", // S R
      21 => "S11.S", // Push
      20 => "S12.S", // Push
      17 => "S9.lG", // S G
      ),
  ), 
  
  0x41 => array( // Arduino to be updated
    "type" =>"EVERY",
    "pins" => array(
      0 => "S16.lR", // ok
      1 => "",
      2 => "tr15.tR",
      3 => "tr15.tG",
      4 => "P4.lR",
      5 => "P4.lG",
      6 => "S15.tR",
      7 => "S15.tG",
      8 => "S15.lR", // ok
      9 => "S13.lG", // ok
      10 => "S13.lR", // ok
      11 => "S15.lG", // ok
      12 => "", // Push
      13 => "S16.lG", // ok
      14 => "S16.S", // ok
      15 => "S15.S", // ok
      17 => "S14.S", // ok
      20 => "S13.S", // ok
      21 => "P4.S", // ok
      16 => "P3.S", // ok
      ),
  ),

  0x42 => array(  // Arduino to be updated
    "type" =>"EVERY",
    "pins" => array(
      0 => "S10.lG",
      1 => "S10.lR",
      2 => "S7.lR",
      3 => "S7.lG",
      4 => "S8.lR",
      5 => "S8.lG",
      6 => "",
      7 => "",
      8 => "",
      9 => "",
      10 => "",
      11 => "",
      12 => "",
      13 => "",
      14 => "",
      15 => "S9.S",
      16 => "S10.S",
      17 => "S7.S",
      21 => "S8.S",
      20 => "P2.S"),
  ), 

  0x43 => array( // Arduino to be updated
    "type" =>"EVERY",
    "pins" => array(
      0 => "tr6.tG",
      1 => "tr6.tR",
      2 => "S7.tR",
      3 => "S7.tG",
      4 => "P2.lR",
      5 => "P2.lG",
      6 => "tr4.tR",
      7 => "tr4.tG",
      8 => "tr9.tR",
      9 => "tr9.tG",
      10 => "S8.tR",
      11 => "S8.tG",
      12 => "P2.rR",
      13 => "P2.rG",
      14 => "P2.bW",
      15 => "",
      16 => "",
      17 => "",
      20 => "",
      21 => ""),
  ), 

  0x44 => array( 
    "type" =>"EVERY",
    "pins" => array(
      0 => "COM.B",
      1 => "COM.N",
      2 => "COM.R",
      3 => "COM.E",
      5 => "COM.O",
      4 => "COM.F",
      6 => "COM.I", // Push
      7 => "", // Push
      8 => "COM.eR", // eStop
      9 => "", // Forced release timer running
      10 => "COM.oR", // Operation R
      11 => "COM.oG", // Operation G
      12 => "",
      13 => "COM.nR",
      14 => "COM.nG",
      15 => "",
      16 => "",
      17 => "",
      20 => "",
      21 => ""),
  ), 

  0x45 => array(
    "type" =>"EVERY",
    "pins" => array(
      0 => "P1.S",
      1 => "S3.S",
      2 => "S4.S",
      3 => "S2.S.S",
      4 => "BS2.S",
      5 => "S1.S",
      6 => "S3.lR",
      7 => "S3.lG",
      8 => "S4.lR",
      9 => "S4.lG",
      10 => "BS1.S",
      11 => "tr2.tR",
      12 => "tr2.tG",
      13 => "S3.tR",
      14 => "S3.tG",
      15 => "P1.rR",
      16 => "P1.rG",
      17 => "P1.bW",
      20 => "S1.lR", 
      21 => "S1.lG"), 
  ), 

  0x46 => array( //
    "type" =>"EVERY",
    "pins" => array(
      0 => "P1.lR",
      1 => "P1.lG",
      2 => "S4.tR",
      3 => "S4.tG",
      4 => "",
      5 => "",
      6 => "", // BS2.tR
      7 => "", // BS2.tG
      8 => "tr3.tR",
      9 => "tr3.tG",
      10 => "CTS2tr33.tR",
      11 => "CTS2tr33.tG",
      12 => "BS2.rR",
      13 => "CTS1tr32.tR",
      14 => "CTS1tr32.tG",
      15 => "tr1.tR",
      16 => "tr1.tG",
      17 => "S2.lR",
      20 => "S2.lG",
      21 => "BS1.rR"),
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

  0x20 => array(
    "type" =>"MCP23017",
    "pins" => array(
      0 => "",
      1 => "",
      2 => "P3.bW",
      3 => "S14.lR",
      4 => "S14.lG", // ok
      5 => "tr14.tR",
      6 => "tr14.tG",
      7 => "P4.bW",
      8 => "P4.rR",
      9 => "P4.rG",
      10 => "CTS13tr13.tR",
      11 => "CTS13tr13.tG",
      12 => "tr10.tR",
      13 => "tr10.tG",
      14 => "tr18.tR",
      15 => "tr18.tG"),
  ), 
  0x21 => array(
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
      8 => "CTS16tr16.tR",
      9 => "CTS16tr16.tG",
      10 => "P3.rR",
      11 => "P3.rG",
      12 => "S14.tR",
      13 => "S14.tG",
      14 => "P3.lR",
      15 => "P3.lG"),
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
  "CTS9S10" => array(
    "S9",
    "S10",
  ),
  "CTS11S12" => array(
    "S11",
    "S12",
  ),
  "CTS16tr16" => array(
    "S16",
    "tr16",
  ),
  "CTS13tr13" => array(
    "S13",
    "tr13",
  ),
  "CTS1tr32" => array(
    "S1",
    "tr32",
  ),
  "CTS2tr33" => array(
    "S2",
    "tr33",
  ),
  "" => array( // Template
    "",
    "",
    "",
  ),
);

?>
