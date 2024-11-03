<?php
// WinterTrain, OBUng
// TrainProfiles

// ----------------------------------------------------- OBU profiles

$OBUprofileConfig = array( // ID => profile
  0 => array(
    "profileName" => "Default OBU Profile",
    "HWconfig" => SINGLE_BACKEND,
    "locoProfile" => 0,
    "wheelFactor" => 5,
    "HWbackendI2C" => 0x30,
// configuration of cabin light  FIXME in OBU profile or loco profile???
  ),

  20 => array(
    "profileName" => "Godstog",
    "HWconfig" => SINGLE_BACKEND,
    "locoProfile" => 201,
    "wheelFactor" => 5,
    "HWbackendI2C" => 0x30,
// configuration of cabin light  FIXME
  ),
  
  21 => array(
    "profileName" => "Cirkustog",
    "HWconfig" => SINGLE_BACKEND,
    "locoProfile" => 101,
    "wheelFactor" => 5,
    "HWbackendI2C" => 0x30,
// configuration of cabin light  FIXME
  ),

  22 => array(
    "profileName" => "Passagertog",
    "HWconfig" => SINGLE_BACKEND,
    "locoProfile" => 102,
    "wheelFactor" => 5,
    "HWbackendI2C" => 0x30,
// configuration of cabin light  FIXME
  ),

  30 => array(
    "profileName" => "Skinnebus",
    "HWconfig" => DOUBLE_BACKEND,
    "locoProfile" => 301,
    "wheelFactor" => 5,
    "HWbackendI2C" => 0x30, // FIXME I2C addr for backend in trailer, backend in motor car via serial
// configuration of cabin light  FIXME
  ),

);

// ---------------------------------------------- Loco profiles

$locoProfileConfig = array( // ID => profile
  0 => array(
    "profileName" => "Default Loco Profile",
    "trainClass" => "",
    "maxMotorControl" => 100,
    "runningProfile" => 0,
    "frontLightNormal" => 50,
    "frontLightBright" => 255,
    "rearLightNormal" => 50,
    "cabinLightR" => 0xff,
    "cabinLightG" => 0x70,
    "cabinLightB" => 0x0c,
  ),

  101 => array(
    "profileName" => "Sort Stainz",
    "trainClass" => "Stainz",
    "maxMotorControl" => 100,
    "runningProfile" => 0,
  ),

  102 => array(
    "profileName" => "Grøn Stainz",
    "trainClass" => "Stainz",
    "maxMotorControl" => 100,
    "runningProfile" => 0,
  ),

  201 => array(
    "profileName" => "Diesel loko",
    "trainClass" => "Diesel",
    "maxMotorControl" => 100,
    "runningProfile" => 0,
  ),

  301 => array(
    "profileName" => "Skinnebus",
    "trainClass" => "Railbus",
    "maxMotorControl" => 100,
    "runningProfile" => 1,
    "frontLightNormal" => 50,
    "frontLightBright" => 255,
    "rearLightNormal" => 50,
    "cabinLightR" => 0xff,
    "cabinLightG" => 0x70,
    "cabinLightB" => 0x0c,
  ),
);

// -------------------------------------------- Running profiles

$runningProfileConfig = array( // ID => profile
  0 => array(
    "profileName" => "Default running profile",
    "maxSpeed" => 100,
    "acceleration" => "",
    "deceleration" => "",
    "" => "",
  ),
  1 => array(
    "profileName" => "Test running profile",
    "maxSpeed" => 100,
    "acceleration" => "",
    "deceleration" => "",
    "" => "",
  ),
);

?>
