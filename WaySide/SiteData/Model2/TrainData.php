<?php

$trainData = [
0 => [
  "ID" => 20,
  "name" => "Gods",
  "lengthFront" => 41,
  "lengthBehind" => 46,
  "wheelFactor" => 5,
  "deployment" => "S", // R: real, S: Simulated, I: Inactive (not present)
  "simFile" => "Train22_sim1N.txt",
  "SRallowed" => false, // Default allowed modes
  "SHallowed" => false,
  "FSallowed" => true,
  "ATOallowed" => true,
  ],
1 => [
  "ID" => 22,
  "name" => "Cirkus",
  "lengthFront" => 41,
  "lengthBehind" => 46,
  "wheelFactor" => 5,
  "ATOmaxSpeed" => 50,
  "deployment" => "S", // R: real, S: Simulated, I: Inactive (not present)
  "simFile" => "Train22_sim1.txt",
  "SRallowed" => false, // Default allowed modes
  "SHallowed" => false,
  "FSallowed" => true,
  "ATOallowed" => true,
  ],
2 => [
  "ID" => 24,
  "name" => "Passager",
  "lengthFront" => 41,
  "lengthBehind" => 46,
  "wheelFactor" => 5,
  "deployment" => "I", // R: real, S: Simulated, I: Inactive (not present)
  "simFile" => "",
  "SRallowed" => false, // Default allowed modes
  "SHallowed" => false,
  "FSallowed" => true,
  "ATOallowed" => true,
  ],
];

?>
