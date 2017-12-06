#!/usr/bin/wish
package require Tk

set HMIversion 02P01
set IPaddress 0.0.0.0
set IPport 9900

# Default configuration
set trackWidth 0.15
set lineWidth 0.05
set winWidth 1850
set winHeight 800
set winX +50
set winY +50
set cWidth 1750
set cHeight 350
set fColor blue       ;# failure color
set tColor black      ;# Clear track, not locked in route
set toColor red       ;# Occupied track, locked or not locked in route
set tcColor green     ;# Clear track, locked in route
set oColor lightgreen ;# signal open
set cColor red        ;# signal closed
set aColor green      ;# Select buttom for elements
#>>JP:TRAIN_ID
set trIdColor orange      ;# Train ID when occupied
#<<JP:TRAIN_ID
set nColor grey
set lColor black ;# lines
set xColor black ;# text
set mColor yellow     ;# barrier moving
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

#----------------------------------------------------------------------------- Display elements
proc label {text x y} {
  dLabelStatic $x $y 0 0 $text
}

proc track {name x y length  {or s}} {
  switch $or {
    s {
      dLabel $x $y 0.5 0.9 $name "$name label"
      dTrack $x $y 0 0.5 $length 0.5 "$name track"
#>>JP:TRAIN_ID
      dTrainIDLabel  $x $y 0.5 0.2 "TEST" "$name trainIdLabel"
#<<JP:TRAIN_ID
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
      dTrack $x $y 0 0.3 0 0.7 
      dTrainIDLabel  $x $y 0.5 0.2 "TEST" "$name trainIdLabel"
      dButton $x $y 0.3 0.5 0.4 $name selectBufferStop
    }
    e {
      dLabel $x $y [expr $length - 0.6] 0.9 $name "$name label"
      dTrack $x $y 0 0.5 $length 0.5 "$name track"
      dTrack $x $y $length 0.3 $length 0.7 
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
      dTrainIDLabel  $x $y 0.5 0.2 "TEST" "$name trainIdLabel"
      dTrack $x $y 0 0.5 1 0.5 "$name track"
      dTrack $x $y 1 0.5 1.5 0.5 "$name left"
      dTrack $x $y 1.5 0.5 2 0.5 "$name trackleft"
      dTrack $x $y 1 0.5 1.3 1.1 "$name right"
      dTrack $x $y 1.3 1.1 2 2.5 "$name trackright"
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
      dButton $x $y 1 0.5 0.4 $name selectPoint
    }
  }
}

proc trainFrame {index} {
global nTrainFrame trainMAbalise trainMAdist entryFontSize

  incr nTrainFrame
  grid [ttk::frame .f.fTrain.t$index -padding "3 3 12 12" -relief solid -borderwidth 2] -column [expr $index + 1] -row 1 -sticky nwes
  grid [ttk::label .f.fTrain.t$index.nameX -text "Train name"] -column 0 -row 0 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fTrain.t$index.name -text "-----" -textvariable trainName($index)] -column 1 -row 0 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fTrain.t$index.valid -text "VOID" -textvariable trainValid($index)] -column 3 -row 0 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fTrain.t$index.modeX -text "Mode:"] -column 0 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fTrain.t$index.mode -text "--" -textvariable trainMode($index)] -column 1 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fTrain.t$index.ack -text "--" -textvariable trainACK($index)] -column 3 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fTrain.t$index.positionX -text "Position:"] -column 0 -row 2 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fTrain.t$index.position -text "--- ---" -textvariable trainPosition($index)] -column 1 -columnspan 3 -row 2 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fTrain.t$index.speedX -text "Speed"] -column 0 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fTrain.t$index.speed -text "---" -textvariable trainSpeed($index)] -column 1 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fTrain.t$index.dirX -text "Dir"] -column 2 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fTrain.t$index.dir -text "---" -textvariable trainNomDir($index)] -column 3 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fTrain.t$index.lengthX -text "Length"] -column 0 -row 4 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fTrain.t$index.length -text "---" -textvariable trainLength($index)] -column 1 -row 4 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fTrain.t$index.pwrX -text "PWR"] -column 2 -row 4 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fTrain.t$index.pwr -text "---" -textvariable trainPWR($index)] -column 3 -row 4 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fTrain.t$index.maX -text "MA"] -column 0 -row 5 -padx 5 -pady 5 -sticky we
  grid [ttk::entry .f.fTrain.t$index.maBalise -width 5 -textvariable trainMAbalise($index) -font "'Helvetica', $entryFontSize"] -column 1 -row 5 -padx 5 -pady 5 -sticky we
  grid [ttk::entry .f.fTrain.t$index.maDistance -width 5 -textvariable trainMAdist($index) -font "'Helvetica', $entryFontSize"] -column 2 -row 5 -padx 5 -pady 5 -sticky we
  grid [ttk::button .f.fTrain.t$index.maSend -text "Send" -command "sendMA $index"] -column 3 -row 5 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fTrain.t$index.sr_allowedX -text "SR:"] -column 0 -row 7 -padx 5 -pady 5 -sticky we
  grid [ttk::checkbutton .f.fTrain.t$index.sr_allowed -variable sr($index) -command "setSR $index" ] -column 1 -row 7 -padx 5 -pady 5 -sticky we
  .f.fTrain.t$index.sr_allowed state disabled
  grid [ttk::label .f.fTrain.t$index.sh_allowedX -text "SH:"] -column 2 -row 7 -padx 5 -pady 5 -sticky we
  grid [ttk::checkbutton .f.fTrain.t$index.sh_allowed -variable sh($index) -command "setSH $index" ] -column 3 -row 7 -padx 5 -pady 5 -sticky we
  .f.fTrain.t$index.sh_allowed state disabled

  grid [ttk::label .f.fTrain.t$index.fs_allowedX -text "FS:"] -column 0 -row 8 -padx 5 -pady 5 -sticky we
  grid [ttk::checkbutton .f.fTrain.t$index.fs_allowed -variable fs($index) -command "setFS $index" ] -column 1 -row 8 -padx 5 -pady 5 -sticky we
  .f.fTrain.t$index.fs_allowed state disabled
  grid [ttk::label .f.fTrain.t$index.ato_allowedX -text "ATO:"] -column 2 -row 8 -padx 5 -pady 5 -sticky we
  grid [ttk::checkbutton .f.fTrain.t$index.ato_allowed -variable ato($index) -command "setATO $index" ] -column 3 -row 8 -padx 5 -pady 5 -sticky we
  .f.fTrain.t$index.ato_allowed state disabled
  set trainMAbalise($index) ""
  set trainMAdist($index) ""
# Comment next line to allow maSend
  .f.fTrain.t$index.maSend state disabled
}

proc destroyTrainFrame {} {
global nTrainFrame
  for {set index 0} {$index < $nTrainFrame} {incr index} {
    destroy .f.fTrain.t$index
  }
  set nTrainFrame 0
}

#-------------------------------------------------- Display proc
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

#>>JP:TRAIN_ID
#draw Train ID when element is occupied
proc dTrainIDLabel {x y x1 y1 text {tags ""}} { 
global scale xOffset yOffset trIdColor
  .f.canvas create text [expr $xOffset+($x+$x1)*$scale] [expr $yOffset+($y+$y1)*$scale] -text $text -tags $tags -fill $trIdColor
}
#<<JP:TRAIN_ID

proc dLabelStatic {x y x1 y1 text {tags ""}} { 
global scale xOffset yOffset xColor
  .f.canvas create text [expr $xOffset+($x+$x1)*$scale] [expr $yOffset+($y+$y1)*$scale] -text $text -tags $tags -fill $xColor
}

proc dGrid {} {
global xOffset yOffset scale cHeight cWidth showGrid
  set showGrid no
  .f.buttonShowGrid configure -text "Show Grid"
  for {set x $xOffset} {$x < $cWidth} {set x [expr $x + $scale]} {
    .f.canvas create line $x $yOffset $x $cHeight -fill grey -width 1 -tags grid -state hidden
  }
  for {set y $yOffset} {$y < $cHeight} {set y [expr $y + $scale]} {
    .f.canvas create line $xOffset $y $cWidth $y -fill grey -width 1 -tags grid -state hidden
  }
}

#------------------------------------------------- indication handlers

proc pointState {name state trackState {trainID ""}} {
global fColor tColor toColor tcColor
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
      .f.canvas itemconfigure "$name&&(track||trackleft||trackright||left||right)" -fill $tColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
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
    4 { ;# Locked
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

proc levelcrossingState {name state trackState {trainID ""}} {
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
      .f.canvas itemconfigure "$name&&(track||p)" -fill $tColor
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
    4 { ;#Locked
      .f.canvas itemconfigure "$name&&(track||p)" -fill $tcColor
    }
  }
}
proc signalState {name state trackState {trainID ""}} {
global fColor oColor cColor toColor tcColor tColor
  switch $state {
    11 {
    .f.canvas itemconfigure "$name&&aspect" -fill $oColor
    }
    10 {
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
      .f.canvas itemconfigure "$name&&track" -fill $tColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
    }
    1 -
    2 -
    3 { ;# Occupied
      .f.canvas itemconfigure "$name&&track" -fill $toColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -text $trainID
      .f.canvas itemconfigure "$name&&trainIdLabel" -state normal
    }
    4 { ;#Locked
      .f.canvas itemconfigure "$name&&track" -fill $tcColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
    }
  }
}

proc bufferStopState {name trackState {trainID ""}} {
global fColor tColor tcColor toColor
  switch $trackState {
    0 { ;# Unsupervised
      .f.canvas itemconfigure "$name&&track" -fill $fColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
    }
    5 { ;# Clear
      .f.canvas itemconfigure "$name&&track" -fill $tColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
    }
    1 -
    2 -
    3 { ;# Occupied
      .f.canvas itemconfigure "$name&&track" -fill $toColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -text $trainID
      .f.canvas itemconfigure "$name&&trainIdLabel" -state normal
    }
    4 { ;#Locked
      .f.canvas itemconfigure "$name&&track" -fill $tcColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
    }
  }
}


proc trState {name trackState {trainID ""}} {
global fColor tColor tcColor toColor
  switch $trackState {
    0 { ;# Unsupervised
      .f.canvas itemconfigure "$name&&track" -fill $fColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
    }
    5 { ;# Clear
      .f.canvas itemconfigure "$name&&track" -fill $tColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -state hidden
    }
    1 -
    2 -
    3 { ;# Occupied
      .f.canvas itemconfigure "$name&&track" -fill $toColor
      .f.canvas itemconfigure "$name&&trainIdLabel" -text $trainID
      .f.canvas itemconfigure "$name&&trainIdLabel" -state normal
    }
    4 { ;#Locked
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

proc trainDataD {trainIndex mode balise distance speed nomDir pwr maAck valid} { ;# dynamic train data
global trainMode trainPosition trainSpeed trainNomDir trainPWR trainACK trainValid
  set trainMode($trainIndex) $mode
  set trainPosition($trainIndex) "$balise $distance"
  set trainSpeed($trainIndex) $speed
  set trainNomDir($trainIndex) $nomDir
  set trainPWR($trainIndex) $pwr
  set trainACK($trainIndex) $maAck
  set trainValid($trainIndex) $valid
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

#--------------------------------------------------------------------------------------------------- command handlers
proc sendMA {index} {
global trainMAbalise trainMAdist
#  puts "SendMA $index $trainMAbalise($index) $trainMAdist($index) \n"
  sendCommand "MA $index $trainMAbalise($index) $trainMAdist($index)"
  set trainMAdist($index) ""
}

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
  }
}

#proc buttonSignal {} {
#global command
#if {$command == "_s"} {
#  set command ""
#  .f.buttonSignal configure -text "Signal"
#  } else {
#  set command "_s"
#  .f.buttonSignal configure -text "SIGNAL"
#  .f.buttonPoint configure -text "Spsk"
#  .f.buttonLX configure -text "Ovk"
#  }
#}

proc buttonRelease {} {
global command
if {$command == "_r"} {
  set command ""
  .f.buttonRelease configure -text "Release"
  } else {
  set command "_r"
  .f.buttonRelease configure -text "RELEASE"
  .f.buttonPoint configure -text "Spsk"
  .f.buttonLX configure -text "Ovk"
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
  .f.buttonRelease configure -text "Release"
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
      "_p" {
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
global command aColor
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
# puts "Release $ID"
      }
      "_s" {
        set command ""
        .f.buttonSignal configure -text "Signal"
        sendCommand "so $ID"
      }
      "" {
        set command $ID
        .f.canvas itemconfigure "$ID&&button" -activefill "" -activeoutline "" -fill $aColor -outline $aColor
      }
      default {
        #Asking RBC to set route if possible
        sendCommand "tr $command $ID"
        puts "Valgt: $command og $ID"
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

proc rqopr {} {
  sendCommand "Rq"
}

proc rlopr {} {
  sendCommand "Rl"
}

proc exitRBC {} {
  sendCommand "exitRBC"
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
#  .f.buttonSignal state disabled
  .f.buttonRelease state disabled
  .f.buttonLX state disabled
  .f.buttonERBC state disabled
  .f.buttonT state disabled
  for {set x 0} {$x < $nTrainFrame} {incr x} {
    .f.fTrain.t$x.sr_allowed state disabled
    .f.fTrain.t$x.sh_allowed state disabled
    .f.fTrain.t$x.fs_allowed state disabled
    .f.fTrain.t$x.ato_allowed state disabled
  }
  .f.canvas itemconfigure button -activefill "" -activeoutline ""
}

proc enableButtons {} {
global aColor nTrainFrame
  .f.buttonPoint state !disabled
#  .f.buttonSignal state !disabled
  .f.buttonRelease state !disabled
  .f.buttonLX state !disabled
  .f.buttonERBC state !disabled
  .f.buttonT state !disabled
  for {set x 0} {$x < $nTrainFrame} {incr x} {
    .f.fTrain.t$x.sr_allowed state !disabled
    .f.fTrain.t$x.sh_allowed state !disabled
    .f.fTrain.t$x.fs_allowed state !disabled
    .f.fTrain.t$x.ato_allowed state !disabled
  }
  .f.canvas itemconfigure button -activefill $aColor -activeoutline $aColor
}

proc test { } {
  sendCommand "test"
}
#---------------------------------------------------- Communication
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

proc processIndication {line} { ;# from Process indications from HMI server
global debug
  if {$debug} {
    puts "Modtaget: $line"
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
  set response ""
  .f.canvas itemconfigure {!button} -fill $nColor
  .f.canvas itemconfigure button -activefill "" -activeoutline ""
  for {set index 0} {$index < $nTrainFrame} {incr index} {
    trainDataD $index "--" "--:--:--:--:--" "---" "---" "--" "--"
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

set reqIP no
foreach arg $argv {
  puts $arg
  if {$reqIP} {
    set IPaddress $arg
    set reqIP no
  } else {
    switch $arg {
    --help {
      puts "
Usage:
--grid: Display gridlines
--test: Do not enter event loop 
--IP <address>: Set server address
--d: Debug
"
      }
    --grid {
      set showGrid yes
      }
    --test {
      set startLoop no
      }
    --IP {
      set reqIP yes
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
#ttk::style configure TEntry -font "'Helvetica', 24"  // virker ikke jf web

wm title . "WinterTrain HMI $HMIversion"
wm geometry . $winWidth\x$winHeight$winX$winY
grid columnconfigure . 0 -weight 1; grid rowconfigure . 0 -weight 1

grid [ttk::frame .f -padding "3 3 12 12"] -column 0 -row 0 -sticky nwes
grid columnconfigure .f 6 -weight 1; grid rowconfigure .f 3 -weight 1

grid [ttk::label .f.menu -text ""] -column 1 -row 1 -padx 5 -pady 5 -sticky we
grid [ttk::label .f.response -textvariable response] -column 2 -columnspan 5 -row 6 -padx 5 -pady 5 -sticky we
grid [ttk::label .f.status -textvariable status] -column 2 -row 5 -padx 5 -pady 5 -sticky we
grid [ttk::label .f.versions -textvariable versions] -column 6 -columnspan 2 -row 5 -padx 5 -pady 5 -sticky e
#grid [ttk::label .f.versionPT1 -textvariable PT1version] -column 7 -row 5 -padx 5 -pady 5 -sticky e
grid [ttk::label .f.live -textvariable liveIndicator] -column 8 -row 5 -padx 5 -pady 5 -sticky e
#grid [ttk::button .f.buttonSignal -text "Signal" -command buttonSignal] -column 2 -row 1 -sticky we
grid [ttk::button .f.buttonRelease -text "Release" -command buttonRelease] -column 2 -row 1 -sticky we
grid [ttk::button .f.buttonPoint -text "Spsk" -command buttonPoint] -column 3 -row 1 -sticky we
grid [ttk::button .f.buttonLX -text "Ovk" -command buttonLX] -column 4 -row 1 -sticky we
grid [ttk::button .f.buttonOpr -text "Request operation" -command rqopr] -column 5 -row 1 -sticky e
grid [ttk::button .f.buttonShowGrid -text "Show Grid" -command showGrid] -column 6 -row 1 -sticky e
grid [ttk::button .f.buttonShowLabel -text "Show Label" -command showLabel] -column 7 -row 1 -sticky e
grid [ttk::button .f.buttonEHMI -text "Exit HMI" -command exit] -column 8 -row 1 -sticky e
grid [ttk::button .f.buttonERBC -text "Exit RBC" -command exitRBC] -column 9 -row 1 -sticky e
grid [ttk::button .f.buttonT -text "TEST" -command test] -column 10 -row 1 -sticky e

grid [tk::canvas .f.canvas -scrollregion "0 0 $cWidth $cHeight" -yscrollcommand ".f.sbv set" -xscrollcommand ".f.sbh set"] -sticky nwes -column 2 -columnspan 9 -row 3
grid [tk::scrollbar .f.sbh -orient horizontal -command ".f.canvas xview"] -column 2 -columnspan 7 -row 4 -sticky we
grid [tk::scrollbar .f.sbv -orient vertical -command ".f.canvas yview"] -column 9 -row 3 -sticky ns

grid [ttk::frame .f.fTrain -padding "3 3 3 3" -relief solid -borderwidth 2] -column 1 -row 7 -columnspan 8 -sticky nwes

dGrid
disconnected
openSocket

puts "WinterTrain HMI $HMIversion"
if {$startLoop} {
  vwait forever
} else {
  puts "Testmode"
}

