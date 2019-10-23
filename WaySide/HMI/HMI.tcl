#!/usr/bin/wish
package require Tk



set ttyName "/dev/ttyUSB0"
set panelEnabled no ;# Enable test interface to operator panel prototype for proof of concept.

set HMIversion 03P01  
set IPaddress 192.168.8.230
set IPport 9900

# Default configuration
set trackWidth 0.15
set lineWidth 0.05
set markWidth 0.08
set winWidth 1800
set winHeight 800
set winX +50
set winY +50
set cWidth 1600
set cHeight 350

# Note, following color definitions may by overwritten by specifications in PT1 data
set fColor blue         ;# failure color
set tColor black        ;# Clear track, not locked in route
set toColor red         ;# Occupied track, locked or not locked in route
set tcColor green       ;# Clear track, locked in route
set clColor red         ;# point clamped
set blColor orange      ;# point blocked
set oColor lightgreen   ;# signal open
set oppColor lightgreen ;# signal open proceed proceed
set cColor darkgrey     ;# signal closed, not locked in route
set dColor red          ;# signal closed, locked in route
set aColor yellow       ;# Select button for elements
set sColor orange       ;# Selected button
set trIdColor blue      ;# Train ID when occupied
set nColor grey
set lColor black        ;# lines
set xColor black        ;# text
set mColor yellow       ;# barrier moving
set arsColor yellow     ;# ARS disabled
set scale 45
set xOffset 5
set yOffset 10
set nTrainFrame 0
set liveT 42
set live 0
set liveC 0
set liveTxt {\\ | / -}
set entryFontSize 12
set labelFontSize 12
set buttonFontSize 12

set trnStatusColor {red lightgreen grey red yellow darkcyan green orange lightgrey}
# Status enummeration from TMS engine:
#define("TRN_UDEF",0);
#define("TRN_NORMAL",1);
#define("TRN_COMPLETED",2);
#define("TRN_FAILED",3);
#define("TRN_ARS_DISABLED",4);
#define("TRN_BLOCKED",5);
#define("TRN_WAITING",6);
#define("TRN_CONFIRM",7);
#define("TRN_UNKNOWN",8);

set emergencyStop false

#----------------------------------------------------------------------------------------------------------------- Display elements
proc label {text x y} {
  dLabelStatic $x $y 0 0 $text
}

proc eStopIndicator {x y} {
  dRectangle $x $y 0 0 6 1 "eStopIndicator eStopRectangle"
  .f.canvas itemconfigure eStopRectangle -fill orange 
  dLabel $x $y 3 0.5 "Energency STOP activated" "eStopIndicator"
}

proc arsIndicator {x y} {
  dRectangle $x $y 0 0 3 1 "arsIndicator arsRectangle"
  .f.canvas itemconfigure arsRectangle -fill yellow 
  dLabel $x $y 1.5 0.5 "Ars Disabled" "arsIndicator"
}

proc track {name x y length  {or s}} {
  switch $or {
    s {
      dLabel $x $y 0.5 0.9 $name "$name label"
      dTrack $x $y 0 0.5 $length 0.5 "$name track"
      dTrainIDLabel  $x $y 0.5 0.2 "TEST" "$name trainIdLabel"
    }
    u {
      dLabel $x $y 0.2 1 $name "$name label"
      dTrack $x $y 0 [expr 2.5 * $length] $length 0.5 "$name track"
      dTrainIDLabel  $x $y 0.2 0.8 "TEST" "$name trainIdLabel"
    }
    d {
      dLabel $x $y 0.3 1.9 $name "$name label"
      dTrack $x $y 0 0.5 $length [expr 2.5 * $length] "$name track"
      dTrainIDLabel  $x $y 0.7 0.2 "TEST" "$name trainIdLabel"
    }
  }
}


proc levelcrossing {name x y} {
  dLabel $x $y 0.5 0.4 $name "$name label"
  dTrack $x $y 0 1.5 0.2 1.5 "$name track"
  dTrack $x $y 0.2 1.5 0.8 1.5 "$name track p"
  dTrack $x $y 0.8 1.5 1 1.5 "$name track"
  dLine $x $y 0.5 1.1 0.5 1.9 "$name u"
  dLine $x $y 0.5 0.9 0.5 1.1 "$name r"
  dLine $x $y 0.5 1.9 0.5 2.1 "$name r"
  dLine $x $y 0.2 0.9 0.8 0.9 "$name r"
  dLine $x $y 0.2 2.1 0.8 2.1 "$name r" 
  dButton $x $y 0.5 1.5 0.4 $name selectLX
}

proc bufferStop {name x y layout {length 1}} {
  switch $layout {
    b {
      dLabel $x $y 0.5 0.9 $name "$name label"
      dTrack $x $y 0 0.5 $length 0.5 "$name track"
      dTrack $x $y 0 0.3 0 0.7 "$name buffer"
      dTrainIDLabel  $x $y 0.5 0.2 "TEST" "$name trainIdLabel"
      dButton $x $y 0.3 0.5 0.4 $name selectBufferStop
    }
    e {
      dLabel $x $y [expr $length - 0.6] 0.9 $name "$name label"
      dTrack $x $y 0 0.5 $length 0.5 "$name track"
      dTrack $x $y $length 0.3 $length 0.7 "$name buffer"
      dTrainIDLabel  $x $y 0.5 0.2 "TEST" "$name trainIdLabel"
      dButton $x $y 0.7 0.5 0.4 $name selectBufferStop
    }
  }
}

proc signal {name x y layout} {
  switch $layout {
    f {
      dLabel $x $y 0.4 1.6 $name "$name label"
      dTrack $x $y 0 0.5 2 0.5 "$name track"
      dTrainIDLabel  $x $y 0.7 0.2 "TEST" "$name trainIdLabel"
      dRectangle $x $y 0.9 0.9 1.9 1.5 "$name ars"
      dLine $x $y 0.4 0.5 0.4 1.2 $name
      dLine $x $y 0.4 1.2 1 1.2 $name
      dArcL $x $y 1.2 1.2 0.2 "$name aspect"
      dArcR $x $y 1.6 1.2 0.2 "$name aspect"
      dRectangle $x $y 1.2 1 1.6 1.4 "$name aspect"
      dButton $x $y 1.4 1.2 0.4 $name selectSignal
    }
    r {
      dLabel $x $y 1.5 0.4 $name "$name label"
      dTrack $x $y 0 1.5 2 1.5 "$name track"
      dTrainIDLabel  $x $y 0.5 1.2 "TEST" "$name trainIdLabel"
      dRectangle $x $y 1.1 1.1 0.1 0.5 "$name ars"
      dLine $x $y 1.6 1.5 1.6 0.8 $name
      dLine $x $y 1 0.8 1.6 0.8 $name
      dArcR $x $y 0.8 0.8 0.2 "$name aspect"
      dArcL $x $y 0.4 0.8 0.2 "$name aspect"
      dRectangle $x $y 0.4 0.6 0.8 1 "$name aspect"
      dButton $x $y 0.6 0.8 0.4 $name selectSignal
    }
  }
}

proc point {name x y layout} {
  switch $layout {
    fr { ;# facing, right branch is diverging
      dLabel $x $y 1 0.1 $name "$name label"
      dTrainIDLabel  $x $y 0.3 0.2 "TEST" "$name trainIdLabel"
      dTrack $x $y 0 0.5 1 0.5 "$name track"
      dTrack $x $y 1 0.5 1.5 0.5 "$name left"
      dTrack $x $y 1.5 0.5 2 0.5 "$name trackleft"
      dTrack $x $y 1 0.5 1.3 1.1 "$name right"
      dTrack $x $y 1.3 1.1 2 2.5 "$name trackright"
      dMarkLine $x $y 0.5 0.3 1.5 0.3 "$name lockleft"
      dMarkLine $x $y 0.5 0.7 0.9 0.7 "$name lockright"
      dMarkLine $x $y 0.9 0.7 1.1 1.1 "$name lockright"
      dButton $x $y 1 0.5 0.4 $name selectPoint
    }
    fl { ;# facing, left branch is diverging
      dLabel $x $y 1 2.9 $name "$name label"
      dTrainIDLabel  $x $y 0.5 2.8 "TEST" "$name trainIdLabel"
      dTrack $x $y 0 2.5 1 2.5 "$name track"
      dTrack $x $y 1 2.5 1.5 2.5 "$name right"
      dTrack $x $y 1.5 2.5 2 2.5 "$name trackright"
      dTrack $x $y 1 2.5 1.3 1.9 "$name left"
      dTrack $x $y 1.3 1.9 2 0.5 "$name trackleft"
      dMarkLine $x $y 0.5 2.8 1.5 2.8 "$name lockright"
      dMarkLine $x $y 0.5 2.3 0.9 2.3 "$name lockleft"
      dMarkLine $x $y 0.9 2.3 1.1 1.9 "$name lockleft"
      dButton $x $y 1 2.5 0.4 $name selectPoint
    }
    tl { ;# trailing, left branch is diverging
      dLabel $x $y 1 0.1 $name "$name label"
      dTrainIDLabel  $x $y 1.5 0.2 "TEST" "$name trainIdLabel"
      dTrack $x $y 0 0.5 0.5 0.5 "$name trackright"
      dTrack $x $y 0.5 0.5 1 0.5 "$name right"
      dTrack $x $y 1 0.5 2 0.5 "$name track"
      dTrack $x $y 1 0.5 0.7 1.1 "$name left"
      dTrack $x $y 0.7 1.1 0 2.5 "$name trackleft"
      dMarkLine $x $y 0.5 0.3 1.5 0.3 "$name lockright"
      dMarkLine $x $y 1.5 0.7 1.1 0.7 "$name lockleft"
      dMarkLine $x $y 1.1 0.7 0.9 1.1 "$name lockleft"
      dButton $x $y 1 0.5 0.4 $name selectPoint
    }
  }
}

proc trainFrame {index} {
global nTrainFrame  trainMA entryFontSize toState toMode toDrive toDir trnSetValue

  set toMode($index) 5
  set toDrive($index) 1
  set toDir($index) 2
  set trnSetValue($index) ""

  incr nTrainFrame
# ----------------- Frame for Train status
  grid [ttk::frame .f.fTrain.t$index -padding "3 3 12 12" -relief solid -borderwidth 2] -column [expr $index + 1] -row 1 -sticky nwes
  grid [ttk::label .f.fTrain.t$index.nameX -text "Train name"] -column 0 -row 0 -padx 5 -pady 2 -sticky we
  grid [ttk::label .f.fTrain.t$index.name -text "-----" -textvariable trainName($index)] -column 1 -row 0 -padx 5 -pady 2 -sticky we
  grid [ttk::label .f.fTrain.t$index.valid -text "VOID" -textvariable trainValid($index)] -column 3 -row 0 -padx 5 -pady 2 -sticky we

  grid [ttk::label .f.fTrain.t$index.tnX -text "Running number"] -column 0 -row 1 -padx 5 -pady 2 -sticky we
  grid [ttk::label .f.fTrain.t$index.trn -textvariable trainTRN($index)] -column 1 -row 1 -padx 5 -pady 2 -sticky we
  grid [ttk::entry .f.fTrain.t$index.trnInp -textvariable trnSetValue($index) -width 1] -column 2 -row 1 -padx 5 -pady 2 -sticky we
  .f.fTrain.t$index.trnInp state disabled
  grid [ttk::button .f.fTrain.t$index.trnSet -text "Set" -command "trnSet $index"] -column 3 -row 1 -padx 5 -pady 2 -sticky we
  .f.fTrain.t$index.trnSet state disabled
  
  grid [ttk::label .f.fTrain.t$index.modeX -text "Mode:"] -column 0 -row 2 -padx 5 -pady 2 -sticky we
  grid [ttk::label .f.fTrain.t$index.mode -text "--" -textvariable trainMode($index)] -column 1 -row 2 -padx 5 -pady 2 -sticky we
  grid [ttk::label .f.fTrain.t$index.ack -text "--" -textvariable trainACK($index)] -column 3 -row 2 -padx 5 -pady 2 -sticky we

  grid [ttk::label .f.fTrain.t$index.positionX -text "Position:"] -column 0 -row 3 -padx 5 -pady 2 -sticky we
  grid [ttk::label .f.fTrain.t$index.position -text "--- ---" -textvariable trainPosition($index)] -column 1 -columnspan 3 -row 3 -padx 5 -pady 2 -sticky we

  grid [ttk::label .f.fTrain.t$index.speedX -text "Speed"] -column 0 -row 4 -padx 5 -pady 2 -sticky we
  grid [ttk::label .f.fTrain.t$index.speed -text "---" -textvariable trainSpeed($index)] -column 1 -row 4 -padx 5 -pady 2 -sticky we
  grid [ttk::label .f.fTrain.t$index.dirX -text "Dir"] -column 2 -row 4 -padx 5 -pady 2 -sticky we
  grid [ttk::label .f.fTrain.t$index.dir -text "---" -textvariable trainNomDir($index)] -column 3 -row 4 -padx 5 -pady 2 -sticky we

  grid [ttk::label .f.fTrain.t$index.lengthX -text "Length"] -column 0 -row 5 -padx 5 -pady 2 -sticky we
  grid [ttk::label .f.fTrain.t$index.length -text "---" -textvariable trainLength($index)] -column 1 -row 5 -padx 5 -pady 2 -sticky we
  grid [ttk::label .f.fTrain.t$index.pwrX -text "PWR"] -column 2 -row 5 -padx 5 -pady 2 -sticky we
  grid [ttk::label .f.fTrain.t$index.pwr -text "---" -textvariable trainPWR($index)] -column 3 -row 5 -padx 5 -pady 2 -sticky we

  grid [ttk::label .f.fTrain.t$index.maX -text "MA"] -column 0 -row 6 -padx 5 -pady 2 -sticky we
  grid [ttk::label .f.fTrain.t$index.maBalise -width 5 -textvariable trainMA($index)] -column 1 -columnspan 3 -row 6 -padx 5 -pady 2 -sticky we
#  grid [ttk::label .f.fTrain.t$index.maDistance -width 5 -textvariable trainMAdist($index) -font "'Helvetica', $entryFontSize"] -column 2 -row 6 -padx 5 -pady 2 -sticky we
  grid [ttk::label .f.fTrain.t$index.sr_allowedX -text "SR:"] -column 0 -row 7 -padx 5 -pady 2 -sticky we
  grid [ttk::checkbutton .f.fTrain.t$index.sr_allowed -variable sr($index) -command "setSR $index" ] -column 1 -row 7 -padx 5 -pady 2 -sticky we
  .f.fTrain.t$index.sr_allowed state disabled
  grid [ttk::label .f.fTrain.t$index.sh_allowedX -text "SH:"] -column 2 -row 7 -padx 5 -pady 2 -sticky we
  grid [ttk::checkbutton .f.fTrain.t$index.sh_allowed -variable sh($index) -command "setSH $index" ] -column 3 -row 7 -padx 5 -pady 2 -sticky we
  .f.fTrain.t$index.sh_allowed state disabled

  grid [ttk::label .f.fTrain.t$index.fs_allowedX -text "FS:"] -column 0 -row 8 -padx 5 -pady 2 -sticky we
  grid [ttk::checkbutton .f.fTrain.t$index.fs_allowed -variable fs($index) -command "setFS $index" ] -column 1 -row 8 -padx 5 -pady 2 -sticky we
  .f.fTrain.t$index.fs_allowed state disabled
  grid [ttk::label .f.fTrain.t$index.ato_allowedX -text "ATO:"] -column 2 -row 8 -padx 5 -pady 2 -sticky we
  grid [ttk::checkbutton .f.fTrain.t$index.ato_allowed -variable ato($index) -command "setATO $index" ] -column 3 -row 8 -padx 5 -pady 2 -sticky we
  .f.fTrain.t$index.ato_allowed state disabled


  
# ---------------- Frame for Remote take-over
  grid [ttk::label .f.fTrain.t$index.toX -text "Remote take-over"] -column 0 -row 9 -padx 5 -pady 2 -sticky we
  grid [ttk::label .f.fTrain.t$index.to -text "----" -textvariable toStatus($index)] -column 1 -columnspan 2 -row 9 -padx 5 -pady 2 -sticky we
  grid [ttk::button .f.fTrain.t$index.reqTo -text "Request" -command "reqToRt$index"] -column 2 -row 10 -padx 5 -pady 2 -sticky we
  .f.fTrain.t$index.reqTo state disabled
   grid [ttk::button .f.fTrain.t$index.relTo -text "Release" -command "relRto $index"] -column 3 -row 10 -padx 5 -pady 2 -sticky we
  .f.fTrain.t$index.relTo state disabled
   
  grid [ttk::frame .f.fTrain.t$index.oMode -padding "3 3 12 12" ] -column 0 -row 10 -columnspan 2 -sticky nwes
  grid [ttk::label .f.fTrain.t$index.oMode.modeOFFX -text "N "] -column 1 -row 0 -sticky we -padx 5 -pady 2
  grid [ttk::label .f.fTrain.t$index.oMode.modeSRX -text "SR "] -column 2 -row 0 -sticky we -padx 5 -pady 2
  grid [ttk::label .f.fTrain.t$index.oMode.modeSHX -text "SH "] -column 3 -row 0 -sticky we -padx 5 -pady 2
  grid [ttk::label .f.fTrain.t$index.oMode.modeFSX -text "FS "] -column 4 -row 0 -sticky we -padx 5 -pady 2
  grid [ttk::label .f.fTrain.t$index.oMode.modeATOX -text "ATO"] -column 5 -row 0 -sticky we -padx 5 -pady 2
  grid [ttk::radiobutton .f.fTrain.t$index.oMode.modeOFF -value "5" -variable toMode($index) -command "txRto $index"] -column 1 -row 1
  .f.fTrain.t$index.oMode.modeOFF state disabled
  grid [ttk::radiobutton .f.fTrain.t$index.oMode.modeSR -value "1" -variable toMode($index) -command  "txRto $index"] -column 2 -row 1
  .f.fTrain.t$index.oMode.modeSR state disabled
  grid [ttk::radiobutton .f.fTrain.t$index.oMode.modeSH -value "2" -variable toMode($index) -command  "txRto $index"] -column 3 -row 1
  .f.fTrain.t$index.oMode.modeSH state disabled
  grid [ttk::radiobutton .f.fTrain.t$index.oMode.modeFS -value "3" -variable toMode($index) -command  "txRto $index"] -column 4 -row 1
  .f.fTrain.t$index.oMode.modeFS state disabled
  grid [ttk::radiobutton .f.fTrain.t$index.oMode.modeATO -value "4" -variable toMode($index) -command  "txRto $index"] -column 5 -row 1
  .f.fTrain.t$index.oMode.modeATO state disabled

  grid [ttk::frame .f.fTrain.t$index.oDrive -padding "3 3 12 12" ] -column 0 -row 11 -columnspan 2 -sticky nwes
grid [ttk::label .f.fTrain.t$index.oDrive.driveSTOPX -text "S "] -column 0 -row 0 -sticky we
grid [ttk::label .f.fTrain.t$index.oDrive.drive1X -text "1 "] -column 1 -row 0 -sticky we
grid [ttk::label .f.fTrain.t$index.oDrive.drive2X -text "2 "] -column 2 -row 0 -sticky we
grid [ttk::label .f.fTrain.t$index.oDrive.drive3X -text "3 "] -column 3 -row 0 -sticky we
grid [ttk::label .f.fTrain.t$index.oDrive.drive4X -text "4 "] -column 4 -row 0 -sticky we
grid [ttk::radiobutton .f.fTrain.t$index.oDrive.driveSTOP -value "1" -variable toDrive($index)  -command  "txRto $index"] -column 0 -row 1 -sticky we
.f.fTrain.t$index.oDrive.driveSTOP state disabled
grid [ttk::radiobutton .f.fTrain.t$index.oDrive.drive1 -value "2" -variable toDrive($index)  -command  "txRto $index"] -column 1 -row 1 -sticky we
.f.fTrain.t$index.oDrive.drive1 state disabled
grid [ttk::radiobutton .f.fTrain.t$index.oDrive.drive2 -value "3" -variable toDrive($index)  -command  "txRto $index"] -column 2 -row 1 -sticky we
.f.fTrain.t$index.oDrive.drive2 state disabled
grid [ttk::radiobutton .f.fTrain.t$index.oDrive.drive3 -value "4" -variable toDrive($index)  -command  "txRto $index"] -column 3 -row 1 -sticky we
.f.fTrain.t$index.oDrive.drive3 state disabled
grid [ttk::radiobutton .f.fTrain.t$index.oDrive.drive4 -value "5" -variable toDrive($index)  -command  "txRto $index"] -column 4 -row 1 -sticky we
.f.fTrain.t$index.oDrive.drive4 state disabled

#  grid [ttk::frame .f.fTrain.t$index.oDir -padding "3 3 12 12" ] -column 1 -row 11 -sticky nwes
grid [ttk::label .f.fTrain.t$index.oDrive.dirRX -text "R "] -column 6 -row 0 -sticky we -padx 5 -pady 2
grid [ttk::label .f.fTrain.t$index.oDrive.dirNX -text "N "] -column 7 -row 0 -sticky we -padx 5 -pady 2
grid [ttk::label .f.fTrain.t$index.oDrive.dirFX -text "F"] -column 8 -row 0 -sticky we -padx 5 -pady 2
grid [ttk::radiobutton .f.fTrain.t$index.oDrive.dirR -value "1" -variable toDir($index)  -command  "txRto $index"] -column 6 -row 1
.f.fTrain.t$index.oDrive.dirR state disabled
grid [ttk::radiobutton .f.fTrain.t$index.oDrive.dirN -value "2" -variable toDir($index)  -command  "txRto $index"] -column 7 -row 1
.f.fTrain.t$index.oDrive.dirN state disabled
grid [ttk::radiobutton .f.fTrain.t$index.oDrive.dirF -value "3" -variable toDir($index)  -command  "txRto $index"] -column 8 -row 1
.f.fTrain.t$index.oDrive.dirF state disabled

}

proc destroyTrainFrame {} {
global nTrainFrame
  for {set index 0} {$index < $nTrainFrame} {incr index} {
    destroy .f.fTrain.t$index
  }
  set nTrainFrame 0
}

#--------------------------------------------------------------------------------------------------------- Display procedures
proc dButton {x y x1 y1 rad name cmd} {
global scale xOffset yOffset
  .f.canvas bind [.f.canvas create oval [expr $xOffset+($x+$x1-$rad)*$scale] [expr $yOffset+($y+$y1-$rad)*$scale] [expr $xOffset+($x+$x1+$rad)*$scale] [expr $yOffset+($y+$y1+$rad)*$scale] -fill "" -activefill ""  -outline "" -activeoutline "" -tags "button $name"] <1> "$cmd $name"
}

proc dTrack {x y x1 y1 x2 y2 {tags ""}} {
global trackWidth tColor scale xOffset yOffset
  .f.canvas create line [expr $xOffset+($x+$x1)*$scale] [expr $yOffset+($y+$y1)*$scale] [expr $xOffset+($x+$x2)*$scale] [expr $yOffset+($y+$y2)*$scale] -fill $tColor -width [expr $trackWidth*$scale] -tags $tags
}

proc dLine {x y x1 y1 x2 y2 {tags ""}} {
global lineWidth lColor scale xOffset yOffset
  .f.canvas create line [expr $xOffset+($x+$x1)*$scale] [expr $yOffset+($y+$y1)*$scale] [expr $xOffset+($x+$x2)*$scale] [expr $yOffset+($y+$y2)*$scale] -fill $lColor -width [expr $lineWidth*$scale] -tags $tags -smooth raw
}

proc dMarkLine {x y x1 y1 x2 y2 {tags ""}} {
global markWidth lColor scale xOffset yOffset
  .f.canvas create line [expr $xOffset+($x+$x1)*$scale] [expr $yOffset+($y+$y1)*$scale] [expr $xOffset+($x+$x2)*$scale] [expr $yOffset+($y+$y2)*$scale] -fill $lColor -width [expr $markWidth*$scale] -tags $tags -smooth raw
}

proc dRectangle {x y x1 y1 x2 y2 {tags ""}} {
global fColor scale xOffset yOffset
  .f.canvas create rectangle [expr $xOffset+($x+$x1)*$scale] [expr $yOffset+($y+$y1)*$scale] [expr $xOffset+($x+$x2)*$scale] [expr $yOffset+($y+$y2)*$scale] -fill $fColor -width 0 -tags $tags
}

proc dArcL {x y x1 y1 rad {tags ""}} {
global scale xOffset yOffset fColor
  .f.canvas create arc [expr $xOffset+($x+$x1-$rad)*$scale] [expr $yOffset+($y+$y1-$rad)*$scale] [expr $xOffset+($x+$x1+$rad)*$scale] [expr $yOffset+($y+$y1+$rad)*$scale] -fill $fColor -outline "" -start +90 -extent 180 -tags $tags
}

proc dArcR {x y x1 y1 rad {tags ""}} {
global scale xOffset yOffset fColor
  .f.canvas create arc [expr $xOffset+($x+$x1-$rad)*$scale] [expr $yOffset+($y+$y1-$rad)*$scale] [expr $xOffset+($x+$x1+$rad)*$scale] [expr $yOffset+($y+$y1+$rad)*$scale] -fill $fColor -outline "" -start -90 -extent 180 -tags $tags
}

proc dLabel {x y x1 y1 text {tags ""}} { 
global scale xOffset yOffset xColor
  .f.canvas create text [expr $xOffset+($x+$x1)*$scale] [expr $yOffset+($y+$y1)*$scale] -text $text -tags $tags -fill $xColor -state hidden
}

proc dTrainIDLabel {x y x1 y1 text {tags ""}} { 
global scale xOffset yOffset trIdColor
  .f.canvas create text [expr $xOffset+($x+$x1)*$scale] [expr $yOffset+($y+$y1)*$scale] -text $text -tags $tags -fill $trIdColor
}

proc dLabelStatic {x y x1 y1 text {tags ""}} { 
global scale xOffset yOffset xColor
  .f.canvas create text [expr $xOffset+($x+$x1)*$scale] [expr $yOffset+($y+$y1)*$scale] -text $text -tags $tags -fill $xColor
}

proc dGrid {} {
global xOffset yOffset scale cHeight cWidth showGrid nXGrid nYGrid
  set showGrid no
  set nXGrid [expr $cWidth / $scale]
  set nYGrid [expr $cHeight / $scale]
  .f.buttonShowGrid configure -text "Show Grid"
  for {set x 0} {$x < $nXGrid} {incr x} {
    .f.canvas create line [expr $xOffset+$x*$scale] $yOffset [expr $xOffset+$x*$scale] $cHeight -fill grey -width 1 -tags grid -state hidden
  }
  for {set y 0} {$y < $nYGrid} {incr y} {
    .f.canvas create line $xOffset [expr $yOffset+$y*$scale] $cWidth [expr $yOffset+$y*$scale] -fill grey -width 1 -tags grid -state hidden
  }
}

#------------------------------------------------------------------------------------------------------------ indication handlers

proc pointState {name state routeState trackState lockState {trainID ""}} {
global fColor tColor toColor tcColor clColor blColor panelEnabled
  if {$panelEnabled && $name == "P1"} {
    writeTty  [format "IP%02d%02d%02d" $state $routeState $trackState]
  }
  switch $lockState {
    10 { ;# B_UNBLOCKED / normal
    .f.canvas itemconfigure "$name&&lockright" -state hidden
    .f.canvas itemconfigure "$name&&lockleft" -state hidden
    }
    11 { ;# B_BLOCKED_RIGHT
    .f.canvas itemconfigure "$name&&lockright" -state normal -fill $blColor
    .f.canvas itemconfigure "$name&&lockleft" -state hidden
    }
    12 { ;# B_BLOCKED_LEFT
    .f.canvas itemconfigure "$name&&lockright" -state hidden
    .f.canvas itemconfigure "$name&&lockleft" -state normal -fill $blColor  
    }
    13 { ;# B_CLAMPED_RIGHT
    .f.canvas itemconfigure "$name&&lockright" -state normal -fill $clColor
    .f.canvas itemconfigure "$name&&lockleft" -state hidden
    }
    14 { ;# B_CLAMPED_LEFT
    .f.canvas itemconfigure "$name&&lockright" -state hidden
    .f.canvas itemconfigure "$name&&lockleft" -state normal -fill $clColor  
    }
  } 
  switch $state {
    20 { ;# left
    .f.canvas itemconfigure "$name&&right" -state hidden
    .f.canvas itemconfigure "$name&&left" -state normal
    }
    21 { ;# right
    .f.canvas itemconfigure "$name&&right" -state normal
    .f.canvas itemconfigure "$name&&left" -state hidden
    }
    0 { ;# unsupervised
    .f.canvas itemconfigure "$name&&right" -state hidden
    .f.canvas itemconfigure "$name&&left" -state hidden
    }
  }
  switch $trackState {
    0 { ;# Unsupervised
      .f.canvas itemconfigure "$name&&(track||trackleft||trackright||left||right)" -fill $fColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
    }
    5 { ;# Clear
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
      if {$routeState != 2} { # not locked
        .f.canvas itemconfigure "$name&&(track||trackleft||trackright||left||right)" -fill $tColor
      } else {
        switch $state {
          20 {
            .f.canvas itemconfigure "$name&&(track||trackleft||left)" -fill $tcColor
          }
          21 {
            .f.canvas itemconfigure "$name&&(track||trackright||right)" -fill $tcColor
          }
        }
      }
    }
    1 -
    2 -
    3 { ;# Occupied
      .f.canvas itemconfigure "$name&&trainIdLabel" -text $trainID
      .f.canvas itemconfigure "$name&&trainIdLabel" -state normal
      switch $state {
        20 {
          .f.canvas itemconfigure "$name&&(track||trackleft||left)" -fill $toColor
        }
        21 {
          .f.canvas itemconfigure "$name&&(track||trackright||right)" -fill $toColor
        }
      }
    }
    4 { ;# Clear, locked in route // FIXME obsolete
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
      switch $state {
        20 {
          .f.canvas itemconfigure "$name&&(track||trackleft||left)" -fill $tcColor
        }
        21 {
          .f.canvas itemconfigure "$name&&(track||trackright||right)" -fill $tcColor
        }
      }
    }
  }
}

proc levelcrossingState {name state routeState trackState {trainID ""}} {
global fColor tColor toColor tcColor mColor lColor
  switch $state {
    0 { ;# Unsupervised
    .f.canvas itemconfigure "$name&&u" -state hidden
    .f.canvas itemconfigure "$name&&p" -state hidden
    .f.canvas itemconfigure "$name&&r" -fill $fColor
    }
    1 { ;# Proctected
    .f.canvas itemconfigure "$name&&u" -state hidden
    .f.canvas itemconfigure "$name&&p" -state normal
    .f.canvas itemconfigure "$name&&r" -fill $lColor
    }
    2 { ;# Unprotected
    .f.canvas itemconfigure "$name&&u" -state normal
    .f.canvas itemconfigure "$name&&p" -state hidden
    .f.canvas itemconfigure "$name&&r" -fill $lColor
    }
    3 { ;# Opening
    .f.canvas itemconfigure "$name&&u" -state hidden
    .f.canvas itemconfigure "$name&&p" -state hidden
    .f.canvas itemconfigure "$name&&r" -fill $mColor
    }
    4 { ;# Closing
    .f.canvas itemconfigure "$name&&u" -state hidden
    .f.canvas itemconfigure "$name&&p" -state hidden
    .f.canvas itemconfigure "$name&&r" -fill $mColor
    }
  }
  switch $trackState {
    0 { ;# Unsupervised
      .f.canvas itemconfigure "$name&&(track||p||u)" -fill $fColor
    }
    5 { ;# Clear
      if {$routeState != 2} { # not locked
        .f.canvas itemconfigure "$name&&(track||p)" -fill $tColor
      } else {
        .f.canvas itemconfigure "$name&&(track||p)" -fill $tcColor      
      }
    }
    1 -
    2 -
    3 { ;# Occupied
      switch $state {
        2 { ;# Unprotected
          .f.canvas itemconfigure "$name&&(track||p)" -fill $toColor
        }
        1 { ;# Protected
          .f.canvas itemconfigure "$name&&(track||p)" -fill $toColor
        }
      }
    }
    4 { ;#Locked FIXME obsolete
      .f.canvas itemconfigure "$name&&(track||p)" -fill $tcColor
    }
  }
}
proc signalState {name state routeState trackState arsState {trainID ""}} {
global nColor fColor oColor cColor toColor tcColor tColor oppColor dColor arsColor panelEnabled
  if {$panelEnabled && $name == "S3"} {
    writeTty  [format "IS%02d%02d%02d" $state $routeState $trackState]
  }
  switch $state {
    13 { # E_STOP_FACING
    .f.canvas itemconfigure "$name&&aspect" -fill $dColor
    }
    12 { # E_PROCEEDPROCEED
    .f.canvas itemconfigure "$name&&aspect" -fill $oppColor
    }
    11 { # E_PROCEED
    .f.canvas itemconfigure "$name&&aspect" -fill $oColor
    }
    10 { # E_STOP
      .f.canvas itemconfigure "$name&&aspect" -fill $cColor
    }
    0 {
    .f.canvas itemconfigure "$name&&aspect" -fill $fColor
    }
  }
  switch $trackState {
    0 { ;# Unsupervised
      .f.canvas itemconfigure "$name&&track" -fill $fColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
    }
    5 { ;# Clear
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
      if {$routeState != 2} { # not locked
        .f.canvas itemconfigure "$name&&track" -fill $tColor
      } else {
        .f.canvas itemconfigure "$name&&track" -fill $tcColor
      }
    }
    1 -
    2 -
    3 { ;# Occupied
      .f.canvas itemconfigure "$name&&track" -fill $toColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -text $trainID
      .f.canvas itemconfigure "$name&&trainIdLabel" -state normal
    }
    4 { ;#Locked FIXME obsolete
      .f.canvas itemconfigure "$name&&track" -fill $tcColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
    }
  }
  switch $arsState {
    0 { ;# ARS disabled
      .f.canvas itemconfigure "$name&&ars" -fill $arsColor
      .f.canvas itemconfigure "$name&&ars" -state normal
    }
    1 { ;# ARS enabled
      .f.canvas itemconfigure "$name&&ars" -state hidden
    }
  }
}

proc bufferStopState {name state routeState trackState {trainID ""}} {
global fColor tColor tcColor toColor dColor panelEnabled
  if {$panelEnabled && $name == "B1"} {
    writeTty [format "IB%02d%02d%02d" $state $routeState $trackState]
  }
  switch $state {
    10 {
        .f.canvas itemconfigure "$name&&buffer" -fill $tColor    }
    13 {
        .f.canvas itemconfigure "$name&&buffer" -fill $dColor    }
  }
  switch $trackState {
    0 { ;# Unsupervised
      .f.canvas itemconfigure "$name&&track" -fill $fColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
    }
    5 { ;# Clear
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
      if {$routeState != 2} { # not locked
        .f.canvas itemconfigure "$name&&track" -fill $tColor
      } else {
        .f.canvas itemconfigure "$name&&track" -fill $tcColor
      }
    }
    1 -
    2 -
    3 { ;# Occupied
      .f.canvas itemconfigure "$name&&track" -fill $toColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -text $trainID
      .f.canvas itemconfigure "$name&&trainIdLabel" -state normal
    }
    4 { ;#Locked FIXME obsolete
      .f.canvas itemconfigure "$name&&track" -fill $tcColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
    }
  }
}


proc trState {name routeState trackState {trainID ""}} {
global fColor tColor tcColor toColor
  switch $trackState {
    0 { ;# Unsupervised
      .f.canvas itemconfigure "$name&&track" -fill $fColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
    }
    5 { ;# Clear
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
      if {$routeState != 2} { # not locked
        .f.canvas itemconfigure "$name&&track" -fill $tColor
      } else {
        .f.canvas itemconfigure "$name&&track" -fill $tcColor      }
    }
    1 -
    2 -
    3 { ;# Occupied
      .f.canvas itemconfigure "$name&&track" -fill $toColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -text $trainID
      .f.canvas itemconfigure "$name&&trainIdLabel" -state normal
    }
    4 { ;#Locked FIXME obsolete
      .f.canvas itemconfigure "$name&&track" -fill $tcColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
    }
  }
}

proc SRmode {trainIndex allowed} {
global sr
  set sr($trainIndex) $allowed
}

proc SHmode {trainIndex allowed} {
global sh
  set sh($trainIndex) $allowed
}

proc FSmode {trainIndex allowed} {
global fs
  set fs($trainIndex) $allowed
}

proc ATOmode {trainIndex allowed} {
global ato
  set ato($trainIndex) $allowed
}

proc trainDataS {trainIndex name length} { ;# static train data
global trainName trainLength
  set trainName($trainIndex) $name
  set trainLength($trainIndex) $length
}

proc trainDataD {trainIndex mode balise distance speed nomDir pwr maAck valid status MAbalise MAdist trn trnStatus} { ;# dynamic train data
global trainMode trainPosition trainSpeed trainNomDir trainPWR trainACK trainValid toStatus trainMA trainTRN trnStatusColor
  set trainMode($trainIndex) $mode
  set trainPosition($trainIndex) "$balise $distance"
  set trainSpeed($trainIndex) $speed
  set trainNomDir($trainIndex) $nomDir
  set trainPWR($trainIndex) $pwr
  set trainACK($trainIndex) $maAck
  set trainValid($trainIndex) $valid
  set toStatus($trainIndex) $status
  set trainMA($trainIndex) "$MAbalise $MAdist"
  set trainTRN($trainIndex) $trn
  .f.fTrain.t$trainIndex.trn configure -background [lindex $trnStatusColor $trnStatus]
}

proc RBCversion {RBC PT1} {
global versions HMIversion
  set versions "HMI: $HMIversion RBC: $RBC PT1: $PT1"
}

proc oprAllowed {} {
global status response
  set status "Operation allowed"
  set response ""
  .f.buttonOpr configure -text "Release Operation" -command rlopr
  enableButtons
}

proc oprReleased {} {
global status response
  set status "No operation"
  set response ""
  .f.buttonOpr configure -text "Request Operation" -command rqopr
  disableButtons
}

proc displayResponse {txt} {
global response
  set response $txt
}

proc resetLabel {} {
global showLabel
  .f.buttonShowLabel configure -text "Show Label"
  set showLabel no
}

proc eStopInd {state} {
global showLabel
  if {$state} {
    .f.buttonStop configure -text " - Emergency Stop, Release"
    .f.canvas itemconfigure eStopIndicator -state normal 
  } else {
    .f.buttonStop configure -text "  -- - Emergency STOP - - - "
    .f.canvas itemconfigure eStopIndicator -state hidden 
  }
}

proc arsAllInd {state} {
global showLabel
  if {$state} {
  .f.buttonARSALL configure -text "Disable Ars" 
    .f.canvas itemconfigure arsIndicator -state hidden
  } else {
    .f.buttonARSALL configure -text "Enable Ars"
    .f.canvas itemconfigure arsIndicator -state normal 
  }
}
#--------------------------------------------------------------------------------------------------------------- command handlers

proc buttonPoint {} {
global command
if {$command == "_p"} {
  set command ""
  .f.buttonPoint configure -text "Spsk"
  } else {
  set command "_p"
  .f.buttonPoint configure -text "SPSK"
  .f.buttonRelease configure -text "Release"
  .f.buttonLX configure -text "Ovk"
  .f.buttonARS configure -text "Ars"
  .f.buttonPointBlock configure -text "Block"
  }
}

proc buttonPointBlock {} {
global command
if {$command == "_pb"} {
  set command ""
  .f.buttonPointBlock configure -text "Block"
  } else {
  set command "_pb"
  .f.buttonPointBlock configure -text "BLOCK"
  .f.buttonPoint configure -text "Spsk"
  .f.buttonRelease configure -text "Release"
  .f.buttonLX configure -text "Ovk"
  .f.buttonARS configure -text "Ars"
  }
}

proc buttonRelease {} {
global command
if {$command == "_r"} {
  set command ""
  .f.buttonRelease configure -text "Release"
  } else {
  set command "_r"
  .f.buttonRelease configure -text "RELEASE"
  .f.buttonPoint configure -text "Spsk"
  .f.buttonPointBlock configure -text "Block"
  .f.buttonLX configure -text "Ovk"
  .f.buttonARS configure -text "Ars"
  }
}

proc buttonLX {} {
global command
if {$command == "_l"} {
  set command ""
  .f.buttonLX configure -text "Ovk"
  } else {
  set command "_l"
  .f.buttonLX configure -text "OVK"
  .f.buttonPoint configure -text "Spsk"
  .f.buttonPointBlock configure -text "Block"
  .f.buttonRelease configure -text "Release"
  .f.buttonARS configure -text "Ars"
  }
}

proc buttonARS {} {
global command
if {$command == "_ars"} {
  set command ""
  .f.buttonARS configure -text "Ars"
  } else {
  set command "_ars"
  .f.buttonARS configure -text "ARS"
  .f.buttonPointBlock configure -text "Block"
  .f.buttonPoint configure -text "Spsk"
  .f.buttonRelease configure -text "Release"
  .f.buttonLX configure -text "Ovk"
  }
}

proc selectBufferStop {ID} {
global command aColor
  if {$command == "$ID"} {
      set command ""
      .f.canvas itemconfigure "$ID&&button" -fill "" -outline "" -activefill $aColor -activeoutline $aColor
  } else {
    switch $command {
      "_l" -
      "_p" -
      "_ars" {
      }
      "_r" {
        set command ""
        .f.buttonRelease configure -text "Release"
      sendCommand "rr $ID"
# puts "Release $ID"
      }
      "" {
        set command $ID
        .f.canvas itemconfigure "$ID&&button" -activefill "" -activeoutline "" -fill $aColor -outline $aColor
      }
      $ID {
        set command ""
        .f.canvas itemconfigure "$ID&&button" -fill "" -outline "" -activefill $aColor -activeoutline $aColor
      }
      default {
        #Asking RBC to set route if possible
        sendCommand "tr $command $ID"
#        puts "Valgt: $command og $ID"
        .f.canvas itemconfigure "$command&&button" -fill "" -outline "" -activefill $aColor -activeoutline $aColor
        set command ""
      }
    }
  }
}

proc selectSignal {ID} {
global command aColor sColor
  if {$command == "$ID"} {
      set command ""
      .f.canvas itemconfigure "$ID&&button" -fill "" -outline "" -activefill $aColor -activeoutline $aColor
  } else {
    switch $command {
      "_l" -
      "_p" {
      }
      "_r" {
        set command ""
        .f.buttonRelease configure -text "Release"
      sendCommand "rr $ID"
      }
      "_s" {
        set command ""
        .f.buttonSignal configure -text "Signal"
        sendCommand "so $ID"
      }
      "" {
        set command $ID
        .f.canvas itemconfigure "$ID&&button" -activefill "" -activeoutline "" -fill $sColor -outline $sColor
      }
      "_ars" {
        set command ""
        .f.buttonARS configure -text "Ars"
        sendCommand "ars $ID"
      }
      default {
        #Asking RBC to set route if possible
        sendCommand "tr $command $ID"
        .f.canvas itemconfigure "$command&&button" -fill "" -outline "" -activefill $aColor -activeoutline $aColor
        set command ""
      }
    }
  }
}

proc selectPoint {ID} {
global command
  switch $command {
    "_p" {
      set command ""
      .f.buttonPoint configure -text "Spsk"
      sendCommand "pt $ID"
    }
    "_pb" {
      set command ""
      .f.buttonPointBlock configure -text "Block"
      sendCommand "pb $ID"
    }
  }
}

proc selectLX {ID} {
global command
  switch $command {
    "_l" {
      set command ""
      .f.buttonLX configure -text "Ovk"
      sendCommand "lo $ID"
    }
  }
}

proc buttonARSALL {} { ;# toggle ARS overall
  sendCommand "arsAll"
}

proc setSR {trainIndex} {
global sr
  sendCommand "SR $trainIndex $sr($trainIndex)"
}

proc setSH {trainIndex} {
global sh
  sendCommand "SH $trainIndex $sh($trainIndex)"
}

proc setFS {trainIndex} {
global fs
  sendCommand "FS $trainIndex $fs($trainIndex)"
}

proc setATO {trainIndex} {
global ato
  sendCommand "ATO $trainIndex $ato($trainIndex)"
}

proc eStop {} {
  sendCommand "eStop"
}

proc rqopr {} {
  sendCommand "Rq"
}

proc rlopr {} {
  sendCommand "Rl"
}

proc trnSet {trainIndex} {
global trnSetValue
  sendCommand "trnSet $trainIndex $trnSetValue($trainIndex)"
  set trnSetValue($trainIndex) ""
}

proc reqRto {trainIndex} {
  set toMode($trainIndex) 5
  set toDrive($trainIndex) 1
  set toDir($trainIndex) 2
  sendCommand "reqTo $trainIndex"
}

proc relRto {trainIndex} {
  sendCommand "relTo $trainIndex"
}

proc txRto {trainIndex} {
global toMode toDrive toDir
  sendCommand "txTo $trainIndex $toMode($trainIndex) $toDrive($trainIndex) $toDir($trainIndex)"
}

proc exitRBC {} {
  sendCommand "exitRBC"
}

proc loadTT {} {
  sendCommand "loadTT"
}

proc showLabel {} {
global showLabel
  if {$showLabel} {
    .f.canvas itemconfigure label -state hidden
    .f.buttonShowLabel configure -text "Show Label"
    set showLabel no
  } else {
    .f.canvas itemconfigure label -state normal  
    .f.buttonShowLabel configure -text "Hide Label"
    set showLabel yes
  }
}

proc showGrid {} {
global showGrid
  if {$showGrid} {
    .f.canvas itemconfigure grid -state hidden
    .f.buttonShowGrid configure -text "Show Grid"
    set showGrid no
  } else {
    .f.canvas itemconfigure grid -state normal  
    .f.buttonShowGrid configure -text "Hide Grid"
    set showGrid yes
  }
}

proc disableButtons {} {
global nTrainFrame
  .f.buttonPoint state disabled
  .f.buttonPointBlock state disabled
  .f.buttonRelease state disabled
  .f.buttonLX state disabled
  .f.buttonARS state disabled
  .f.buttonStop state disabled
  .f.buttonARSALL state disabled
#  .f.buttonERBC state disabled
  .f.buttonT state disabled
  for {set x 0} {$x < $nTrainFrame} {incr x} {
    .f.fTrain.t$x.sr_allowed state disabled
    .f.fTrain.t$x.sh_allowed state disabled
    .f.fTrain.t$x.fs_allowed state disabled
    .f.fTrain.t$x.ato_allowed state disabled
    .f.fTrain.t$x.trnInp state disabled
    .f.fTrain.t$x.trnSet state disabled
    .f.fTrain.t$x.reqTo state disabled
    .f.fTrain.t$x.relTo state disabled
    .f.fTrain.t$x.oMode.modeOFF state disabled
    .f.fTrain.t$x.oMode.modeSR state disabled
    .f.fTrain.t$x.oMode.modeSH state disabled
    .f.fTrain.t$x.oMode.modeFS state disabled
    .f.fTrain.t$x.oMode.modeATO state disabled
    .f.fTrain.t$x.oDrive.drive5 state disabled
    .f.fTrain.t$x.oDrive.drive4 state disabled
    .f.fTrain.t$x.oDrive.drive3 state disabled
    .f.fTrain.t$x.oDrive.drive2 state disabled
    .f.fTrain.t$x.oDrive.drive1 state disabled
    .f.fTrain.t$x.oDrive.dirR state disabled
    .f.fTrain.t$x.oDrive.dirN state disabled
    .f.fTrain.t$x.oDrive.dirF state disabled

  }
  .f.canvas itemconfigure button -activefill "" -activeoutline ""
}

proc enableButtons {} {
global aColor nTrainFrame
  .f.buttonPoint state !disabled
  .f.buttonPointBlock state !disabled
#  .f.buttonSignal state !disabled
  .f.buttonRelease state !disabled
  .f.buttonLX state !disabled
  .f.buttonARS state !disabled
  .f.buttonStop state !disabled
  .f.buttonARSALL state !disabled

#  .f.buttonERBC state !disabled
  .f.buttonT state !disabled
  for {set x 0} {$x < $nTrainFrame} {incr x} {
    .f.fTrain.t$x.sr_allowed state !disabled
    .f.fTrain.t$x.sh_allowed state !disabled
    .f.fTrain.t$x.fs_allowed state !disabled
    .f.fTrain.t$x.ato_allowed state !disabled
    .f.fTrain.t$x.trnInp state !disabled
    .f.fTrain.t$x.trnSet state !disabled
    .f.fTrain.t$x.reqTo state !disabled
    .f.fTrain.t$x.relTo state !disabled
    .f.fTrain.t$x.oMode.modeOFF state !disabled
    .f.fTrain.t$x.oMode.modeSR state !disabled
    .f.fTrain.t$x.oMode.modeSH state !disabled
    .f.fTrain.t$x.oMode.modeFS state !disabled
    .f.fTrain.t$x.oMode.modeATO state !disabled
    .f.fTrain.t$x.oDrive.drive5 state !disabled
    .f.fTrain.t$x.oDrive.drive4 state !disabled
    .f.fTrain.t$x.oDrive.drive3 state !disabled
    .f.fTrain.t$x.oDrive.drive2 state !disabled
    .f.fTrain.t$x.oDrive.drive1 state !disabled
    .f.fTrain.t$x.oDrive.dirR state !disabled
    .f.fTrain.t$x.oDrive.dirN state !disabled
    .f.fTrain.t$x.oDrive.dirF state !disabled
  }
  .f.canvas itemconfigure button -activefill $aColor -activeoutline $aColor
}

proc test { } {
  sendCommand "test"
}
#----------------------------------------------------------------------------------------------------------------- Communication
proc openSocket {} {
global server IPaddress IPport
  set server [socket -async $IPaddress $IPport]
  chan configure $server -blocking 0 -buffering line
  chan event $server writable writeHandler 
}

proc writeHandler {} {
global server clientPassword
  chan event $server writable {} ;# delete event handler as a failed channel remains writable. And it is not used for other purpose
  if {[set err [chan configure $server -error]] ne ""} {
    puts stderr "Failed to open socket: $err"
    close $server
    after 10000 openSocket
    return
  }
  chan event $server readable readHandler
  reconnected
}

proc readHandler {} {
global server
  if {[chan eof $server]} {
    chan event $server readable {} ;# Delete event handler as a broken channel remains readable
    puts stderr "Broken pipe"
    disconnected
    close $server
    openSocket
  } else {
    processIndication [chan gets $server]
  }
}

proc openTty {} { ;# interface to track table demo
global ttyName tty panelEnabled
  if {$panelEnabled} {
    set tty [open $ttyName r+]
    fconfigure $tty -mode 57600,n,8,1 -blocking false -buffering line
    fileevent $tty readable readHandlerTty
  }
}

proc readHandlerTty {} {
global tty debug
  if {[gets $tty line] >= 0 && $line != ""} {
    if {$debug} {
      puts "From panel: >$line<\n"
    }
    sendCommand $line
  }
}

proc writeTty {line} {
global debug tty
  if {$debug} {
    puts "To panel: >$line<\n"
  }
  puts $tty $line
}
proc processIndication {line} { ;# from Process indications from HMI server
global debug
  if {$debug} {
    puts "Recieved: $line"
  }
  rotate
  eval $line
}

proc sendCommand {line} { ;# Send command to HMI server
global server
  catch {puts $server $line}
}

proc disconnected {} {
global response status nColor nTrainFrame 
  disableButtons
  .f.buttonOpr state disabled
  .f.buttonOpr configure -text "Request Operation" -command rqopr
  set status "Not connected"
  set tmsStatus "Udef."
  set response ""
  .f.canvas itemconfigure {!button} -fill $nColor
  .f.canvas itemconfigure button -activefill "" -activeoutline ""
  for {set index 0} {$index < $nTrainFrame} {incr index} {
#  proc trainDataD {trainIndex mode balise distance speed nomDir pwr maAck valid status MAbalise MAdist trn trnStatus} ;# dynamic train data

    trainDataD $index "--" "--:--:--:--:--" "---" "---" "--" "--" "--" "VOID" "--" "--:--:--:--:--" "--" "" 8
  }
}

proc reconnected {} {
global status
  .f.buttonOpr state !disabled
  set status "Connected"
}

#----------------------------------------------- Main
set command ""
set showLabel no
set showGrid no
set startLoop yes
set debug no
set status "Udef."
set tmsStatus "Udef."

set reqIP no
set reqScale no
set reqFsize no

foreach arg $argv {
  if {$reqIP} {
    set IPaddress $arg
    set reqIP no
  } else {
    if {$reqScale} {
    set scale $arg
    set reqScale no
    } else {
      if {$reqFsize} {
      set labelFontSize $arg
      set buttonFontSize $arg
      set reqFsize no
      } else {
        switch $arg {
        --help {
        puts "
WinterTrain HMI
Usage:
--scale <int>   Set scale factor of track layout
--font <int>    Set font size
--test          Do not enter event loop 
--IP <address>  Set server address
--l             Set server address to localhost (127.0.0.1) 
--p             Enable connection to HMI panel
--d             Debug
"
          exit
          }
        --test {
          set startLoop no
          }
        --IP {
          set reqIP yes
          }
        --scale {
          set reqScale yes
          }
        --l {
          set IPaddress "127.0.0.1"
          }
        --font {
          set reqFsize yes
        }
        --p {
          set panelEnabled yes
          }
        --d {
          set debug yes
          }
        default {
          puts "Unknown option: $arg"  
          exit  
          }
        }
      }
    }
  }
}

#----------------------------------------------- window layout
proc rotate {} {
global liveT live liveTxt liveIndicator liveC
  incr liveC
  if {[expr $liveC > $liveT]} {
    set liveC 0
    incr live
    if {[expr $live > 3]} { set live 0}
    set liveIndicator [lindex $liveTxt $live]
  }
}

ttk::style configure TButton -font "'Helvetica', $buttonFontSize"
ttk::style configure TLabel -font "'Helvetica', $labelFontSize"
# ttk::style configure TEntry -font "'Helvetica', 16"  ;#// virker ikke jf web

wm title . "WinterTrain HMI $HMIversion"
wm geometry . "=$winWidth\x$winHeight"

grid columnconfigure . 0 -weight 1; grid rowconfigure . 0 -weight 1

grid [ttk::frame .f -padding "3 3 12 12"] -column 0 -row 0 -sticky nwes
grid columnconfigure .f all -weight 1
grid rowconfigure .f 3 -weight 1

# Buttons
grid [ttk::button .f.buttonRelease -text "Release" -command buttonRelease] -column 2 -row 2 -sticky we
grid [ttk::button .f.buttonPoint -text "Spsk" -command buttonPoint] -column 3 -row 2 -sticky we
grid [ttk::button .f.buttonPointBlock -text "Block" -command buttonPointBlock] -column 4 -row 2 -sticky we
grid [ttk::button .f.buttonLX -text "Ovk" -command buttonLX] -column 5 -row 2 -sticky we
grid [ttk::button .f.buttonARS -text "Ars" -command buttonARS] -column 6 -row 2 -sticky we
grid [ttk::button .f.buttonStop -text "" -command eStop] -column 7 -row 2 -sticky w
grid [ttk::button .f.buttonARSALL -text "Disable Ars" -command buttonARSALL] -column 8 -row 2 -sticky we

# Track Layout
grid [tk::canvas .f.canvas -scrollregion "0 0 $cWidth $cHeight" -yscrollcommand ".f.sbv set" -xscrollcommand ".f.sbh set"] -sticky nwes -column 1 -columnspan 12 -row 3
grid [tk::scrollbar .f.sbh -orient horizontal -command ".f.canvas xview"] -column 1 -columnspan 14 -row 4 -sticky we
grid [tk::scrollbar .f.sbv -orient vertical -command ".f.canvas yview"] -column 0 -row 3 -sticky ns

# Status and response
# grid [ttk::frame .f.fStatus -padding "3 3 3 3"] -column 1 -columnspan 14 -row 5 -sticky nwes
grid [ttk::label .f.live -textvariable liveIndicator -width 1] -column 1 -row 5 -padx 5 -pady 2 -sticky e
grid [ttk::label .f.status -textvariable status] -column 2 -columnspan 8 -row 5 -padx 5 -pady 2 -sticky w
grid [ttk::label .f.tmsStatus -textvariable tmsStatus] -column 12 -columnspan 2 -row 5 -padx 5 -pady 2 -sticky w
grid [ttk::label .f.response -textvariable response] -column 1 -columnspan 8 -row 6 -padx 5 -pady 2 -sticky w

# System and HMI commands
grid [ttk::button .f.buttonOpr -text "Request operation" -command rqopr] -column 7 -row 8 -sticky e
grid [ttk::button .f.buttonShowGrid -text "Show Grid" -command showGrid] -column 8 -row 8 -sticky e
grid [ttk::button .f.buttonShowLabel -text "Show Label" -command showLabel] -column 9 -row 8 -sticky e
grid [ttk::button .f.buttonReloadTT -text "Load Timetable" -command loadTT] -column 10 -row 8 -sticky e
grid [ttk::button .f.buttonEHMI -text "Exit HMI" -command exit] -column 11 -row 8 -sticky e
#grid [ttk::button .f.buttonERBC -text "Exit RBC" -command exitRBC] -column 12 -row 8 -sticky e
grid [ttk::button .f.buttonT -text "TEST" -command test] -column 13 -row 8 -sticky e

# Train data
grid [ttk::frame .f.fTrain -padding "3 3 3 3" -relief solid -borderwidth 2] -column 1 -row 9 -columnspan 14 -sticky nwes

grid [ttk::label .f.versions -textvariable versions] -column 2 -columnspan 6  -row 10 -padx 5 -pady 2 -sticky w

bind . <space> {eStop}

dGrid
disconnected
openSocket
openTty

puts "WinterTrain HMI $HMIversion"
if {$startLoop} {
  vwait forever
} else {
  puts "Testmode"
}

