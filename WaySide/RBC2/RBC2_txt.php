<?php
// WinterTrain, RBC2
// Text and language support

// -------------------- TrainData
$TD_TXT_MODE = [0 => "Udef", 1 => "SR", 2 => "SH", 3 => "FS", 4 => "ATO", 5 => "N", ];
$TD_TXT_DIR = [0 => "Udef", 1 => "Down", 2 => "Up", 3 => "Stop",];
$TD_TXT_MADIR = [MD_NODIR => "ND", MD_BACKWARD => "B", MD_FORWARD => "F", MD_BOTH => "BF", ];
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
$RLS_TXT_SH[R_SUPERVISED] = "SUPV";
$RLS_TXT_SH[R_UNSUPERVISED] = "UNSP";
$RLS_TXT_SH[R_CAN_RELEASE] = "RREL";

// Route Locking Type
$RLT_TXT_SH[RT_IDLE] = "-";
$RLT_TXT_SH[RT_START_POINT] = "SP";
$RLT_TXT_SH[RT_END_POINT] = "EP";
$RLT_TXT_SH[RT_VIA] = "VIA";
$RLT_TXT_SH[RT_VIA_REVERSE] = "VIAR";
$RLT_TXT_SH[RT_RIGHT] = "R";
$RLT_TXT_SH[RT_LEFT] = "L";
        
// Route Locking Diretion
$RDIR_TXT_SH[true] = "Up";
$RDIR_TXT_SH[false] = "Dn";    
?>
