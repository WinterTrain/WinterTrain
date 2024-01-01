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

  0x40 => array(
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
      17 => "S9.lG", // S G
      20 => "S12.S", // Push
      21 => "S11.S", // Push
      ),
  ), 
  
  0x41 => array(
    "type" =>"EVERY",
    "pins" => array(
      0 => "S16.lR",
      1 => "",
      2 => "tr15.tR",
      3 => "tr15.tG",
      4 => "P4.lR",
      5 => "P4.lG",
      6 => "S15.tR",
      7 => "S15.tG",
      8 => "S15.lR",
      9 => "S13.lG",
      10 => "S13.lR",
      11 => "S15.lG",
      12 => "COM.N",
      21 => "P4.S",
      13 => "S16.lG",
      14 => "S16.S",  
      15 => "S15.S",  
      16 => "P3.S",  
      17 => "S14.S", 
      20 => "S13.S", 
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
  0x20 => array(
    "type" =>"MCP23017",
    "pins" => array(
      0 => "",
      1 => "",
      2 => "",
      3 => "S14.lR",
      4 => "S14.lG", // ok
      5 => "tr14.tR",
      6 => "tr14.tG",
      7 => "",
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
  "" => array( // Template
    "",
    "",
    "",
  ),
);

?>
