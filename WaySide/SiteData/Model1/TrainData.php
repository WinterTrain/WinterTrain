<?php

$trainData = [
[
  "deployment" => "R", // R: real, S: Simulated, I: Inactive (not present)
  "ID" => 20,
  "name" => "Cargo",
  "lengthFront" => 20,
  "lengthBehind" => 15,
  "wheelFactor" => 5,
  "simFile" => "",
  "SRallowed" => false, // Default allowed modes
  "SHallowed" => false,
  "FSallowed" => true,
  "ATOallowed" => true,
  ],
[
  "deployment" => "S", // R: real, S: Simulated, I: Inactive (not present)
  "ID" => 22,
  "name" => "Circus",
  "lengthFront" => 20,
  "lengthBehind" => 15,
  "wheelFactor" => 5,
  "simFile" => "Train50_sim3.txt",
  "SRallowed" => false, // Default allowed modes
  "SHallowed" => false,
  "FSallowed" => true,
  "ATOallowed" => true,  
  ],
[
  "deployment" => "S", // R: real, S: Simulated, I: Inactive
  "ID" => 50,
  "name" => "Cargo",
  "lengthFront" => 20,
  "lengthBehind" => 15,
  "wheelFactor" => 5,
  "simFile" => "Train50_sim1.txt",
  "SRallowed" => true, // Default allowed modes
  "SHallowed" => true,
  "FSallowed" => true,
  "ATOallowed" => true,  
  ],
];
?>
