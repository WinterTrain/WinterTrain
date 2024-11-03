<?php
// WinterTrain, OBUng
// TrainData handlers

function verifyProfile() { //------------------------------------------  Read and verify OBU, loco and running profiles
  global $DIRECTORY, $TRAIN_DATA_FILE,
    $OBUprofileConfig, $activeOBUprofile, $activeOBUprofileID,
    $locoProfileConfig, $activeLocoProfile, $activeLocoProfileID,
    $runningProfileConfig, $activeRunningProfile, $activeRunningProfileID, $run;
  require("$DIRECTORY/$TRAIN_DATA_FILE"); // FIXME does this re-read the file??
  //                                                                                  FIXME check completeness of data
  if (isset($OBUprofileConfig[$activeOBUprofileID])) {
    $activeOBUprofile = $OBUprofileConfig[$activeOBUprofileID];
    $activeLocoProfileID = $activeOBUprofile["locoProfile"];
    if (isset($locoProfileConfig[$activeLocoProfileID])) {
      $activeLocoProfile = $locoProfileConfig[$activeLocoProfileID];
      $activeRunningProfileID = $activeLocoProfile["runningProfile"];
      if (isset($runningProfileConfig[$activeRunningProfileID])) {
        $activeRunningProfile = $runningProfileConfig[$activeRunningProfileID];
        msgLog("Using: OBU profile: $activeOBUprofileID Loco profile: $activeLocoProfileID Running profile: $activeRunningProfileID");
        return true;
      } else {
        errLog("Error: Running profile $activeRunningProfileID not defined in train data \"$DIRECTORY/$TRAIN_DATA_FILE\" for Loco profile $activeLocoProfileID");
        $run = false;
      }
    } else {
      errLog("Error: Loco profile $activeLocoProfileID not defined in train data \"$DIRECTORY/$TRAIN_DATA_FILE\" for OBU profile $activeOBUprofileID");
        $run = false;
    }
  } else {
    errLog("Error: OBU profile $activeOBUprofileID not defined in train data \"$DIRECTORY/$TRAIN_DATA_FILE\"");
        $run = false;
  }
  return false;
}

function applyStaticData() {
  global $activeOBUprofile, $activeLocoProfile, $activeRunningProfile;
  if (verifyProfile()) {
  
  }
}

function applyDynamicData() {
  global $activeOBUprofile, $activeLocoProfile, $activeRunningProfile;
  if (verifyProfile()) {
    configureHWbackend(); // FIXME error handling for train data
  }
}
?>
