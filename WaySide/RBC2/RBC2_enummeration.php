<?php
// WinterTrain, RBC2
// Enummerations

// ------------------------------------------------------------------------------------- RBC enummeration
// ----------------------------------- Element states
// Track Vacancy State
define("V_UNDEFINED", 0);
define("V_OCCUPIED", 1);
define("V_CLEAR", 2);

// Route Locking State
define("R_UNDEFINED", 0);
define("R_IDLE", 1);
define("R_LOCKED", 2);
define("R_SUPERVISED", 3);
define("R_UNSUPERVISED", 4);
define("R_CAN_RELEASE", 5); // Waiting for previous element to be released 

// Route Locking Type
define("RT_IDLE", 1);
define("RT_START_POINT", 2);
define("RT_END_POINT", 3);
define("RT_VIA_REVERSE", 4);
define("RT_VIA", 5);
define("RT_RIGHT", 7);
define("RT_LEFT", 8);

// Locking state in relation to element FIXME Used?
define("L_NOT_LOCKED", 1);
define("L_LOCKED", 2);
define("L_LOCKED_RIGHT", 3);
define("L_LOCKED_LEFT", 4);

// Blocking state in relation to operator commands
define("B_NOT_BLOCKED", 1);
define("B_BLOCKED_START_VIA", 2); // Signal blocked against locking in route as Start Point or Via 
define("B_BLOCKED_RIGHT", 11);    // Point in lie right blocked against throwing
define("B_BLOCKED_LEFT", 12);     // Point in lie left blocked against throwing
define("B_CLAMPED_RIGHT", 13);    // Point in lie right clamped by configuration
define("B_CLAMPED_LEFT", 14);     // Point in lie left clamped by configuration


// Functional Element state, point
define("P_UNDEFINED", 0);         // No information
define("P_UNSUPERVISED", 1);      // Point detected as unsupervised
define("P_SUPERVISED_RIGHT", 2);
define("P_SUPERVISED_LEFT", 3);

// Functional Element state, signal
define("S_UNDEFINED", 0);         // No information
define("S_STOP", 1);
define("S_PROCEED", 2);
define("S_PROCEED_PROCEED", 3);

// ARS status
define("ARS_DISABLED",0);
define("ARS_ENABLED",1);

// ------------------------------------ Train
// Train mode
define("M_UDEF",0);
define("M_N",5);
define("M_SR",1);
define("M_SH",2);
define("M_FS",3);
define("M_ATO",4);
define("M_ESTOP",7);
// Train power mode
define("P_UDEF",0);
define("P_R",1);
define("P_L",2);
define("P_NOPWR",3);
// Train Remote Take-over mode
define("RTO_UDEF", 0);
define("RTO_DMI", 1);
define("RTO_PEND_REMOTE", 2);
define("RTO_REMOTE", 3);
define("RTO_PEND_RELEASE", 4);
// Direction
define("D_UDEF",0);
define("D_DOWN",1);
define("D_UP",2);
define("D_STOP",3);

// --------------------------------- HMI interface

// Return codes for commands
define("CMD_UNDEFINED", 0);
define("CMD_OK", 1);
define("CMD_NA",2);                         // Command N/A
define("CMD_REJECTED_NOT_START_POINT", 3);
define("CMD_REJECTED", 10);

// ---------------------------------------- TMS enummeration
define("TRN_UDEF",0);
define("TRN_NORMAL",1);
define("TRN_COMPLETED",2);
define("TRN_FAILED",3);
define("TRN_DISABLED",4);
define("TRN_BLOCKED",5);
define("TRN_WAITING",6);
define("TRN_CONFIRM",7);
define("TRN_UNKNOWN",8);


// FOllowing must be aligned with TMS
define("TMS_UDEF",0);
define("TMS_NO_TT",1);
define("TMS_OK",2);
define("TMS_NO_TMS",3);


?>
