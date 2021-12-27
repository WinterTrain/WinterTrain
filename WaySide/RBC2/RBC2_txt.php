<?php
// WinterTrain, RBC2
// Text and language support

// -------------------- TrainData
$TD_TXT_MODE = [0 => "Udef", 1 => "SR", 2 => "SH", 3 => "FS", 4 => "ATO", 5 => "N", ];
$TD_TXT_DIR = [D_UDEF => "Udef", D_DOWN => "Down", D_UP => "Up", D_STOP => "Stop",];
$TD_TXT_MADIR = [MD_NODIR => "ND", MD_DOWN => "D", MD_UP => "U", MD_BOTH => "UD", ];
$TD_TXT_PWR = [0 => "NoComm", 1 => "R", 2 => "L", 3 => "No PWR",];
$TD_TXT_ACK = [0 => "NO_MA", 1 => "MA_ACK"];
$TD_TXT_RTOMODE = [RTO_UDEF => "Udef.", RTO_DMI => "DMI", RTO_REMOTE => "Remote take-over", RTO_PEND_REMOTE => "Pending take-over",
                RTO_PEND_RELEASE => "Pending release"];
$TD_TXT_UNAMB = [true => "", false => "Ambiguous"];


// ------------------- TMS
$TMS_STATUS_TXT = [TMS_UDEF => "TMS: state undefined", TMS_NO_TT => "TMS: Running, Error in Time Table", TMS_OK => "TMS: Running", 
                   TMS_NO_TMS => "TMS: not running"];
                   
// ------------------- Element states
// Track Vacancy State
$TVS_TXT_SH[V_UNDEFINED] = "UDEF";
$TVS_TXT_SH[V_OCCUPIED] = "OCC";
$TVS_TXT_SH[V_CLEAR] = "CLR";

// Route Locking State
$RLS_TXT_SH[R_UNDEFINED] = "UDEF";
$RLS_TXT_SH[R_IDLE] = "IDLE";
$RLS_TXT_SH[R_LOCKED] = "LCKD";
$RLS_TXT_SH[R_RELEASING] = "REL";

// Route Locking Type
$RLT_TXT_SH[RT_IDLE] = "-";
$RLT_TXT_SH[RT_START_POINT] = "SP";
$RLT_TXT_SH[RT_END_POINT] = "EP";
$RLT_TXT_SH[RT_VIA] = "VIA";
$RLT_TXT_SH[RT_VIA_REVERSE] = "VIAR";
$RLT_TXT_SH[RT_RIGHT] = "R";
$RLT_TXT_SH[RT_LEFT] = "L";

// Signalling
$SIGNALLING_TXT[SIG_UDEF] = "Udef.";
$SIGNALLING_TXT[SIG_ERROR] = "Error";
$SIGNALLING_TXT[SIG_NOT_LOCKED] = "NotLocked";
$SIGNALLING_TXT[SIG_STOP] = "STOP";
$SIGNALLING_TXT[SIG_PROCEED] = "PROCEED";
$SIGNALLING_TXT[SIG_PROCEED_PROCEED] = "PROCEED_PROCEED";
$SIGNALLING_TXT[SIG_CLOSED] = "Closed.";

// Signalling, short hand
$SIGNALLING_TXT_SH[SIG_UDEF] = "U";
$SIGNALLING_TXT_SH[SIG_ERROR] = "E";
$SIGNALLING_TXT_SH[SIG_NOT_LOCKED] = "NL";
$SIGNALLING_TXT_SH[SIG_STOP] = "S";
$SIGNALLING_TXT_SH[SIG_PROCEED] = "P";
$SIGNALLING_TXT_SH[SIG_PROCEED_PROCEED] = "PP";
$SIGNALLING_TXT_SH[SIG_CLOSED] = "C";


// Route Locking Diretion
$RDIR_TXT_SH[true] = "Up";
$RDIR_TXT_SH[false] = "Dn";   

// Point state
$PS_TXT_SH[P_UNDEFINED] = "?"; 
$PS_TXT_SH[P_UNSUPERVISED] = "U"; 
$PS_TXT_SH[P_SUPERVISED_RIGHT] = "R";
$PS_TXT_SH[P_SUPERVISED_LEFT] = "L"; 
?>
