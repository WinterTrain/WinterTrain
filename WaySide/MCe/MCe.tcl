#!/usr/bin/wish
package require Tk

set MCeVersion 01P01
set IPaddress 0.0.0.0
set IPport 9901

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

#------------------------------------------------- indication handlers

proc Welcome {a b c} {
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

#--------------------------------------------------------------------------------------------------- command handlers
proc rqopr {} {
  sendCommand "Rq"
}

proc rlopr {} {
  sendCommand "Rl"
}

proc exitRBC {} {
  sendCommand "exitRBC"
}


proc disableButtons {} {
global nTrainFrame
  .f.buttonPoint state disabled
#  .f.buttonSignal state disabled
  .f.buttonRelease state disabled
  .f.buttonLX state disabled
  .f.buttonERBC state disabled
  .f.buttonT1 state disabled
  .f.buttonT2 state disabled
  .f.buttonT3 state disabled
  .f.buttonT4 state disabled
}

proc enableButtons {} {
global aColor nTrainFrame
  .f.buttonPoint state !disabled
#  .f.buttonSignal state !disabled
  .f.buttonRelease state !disabled
  .f.buttonLX state !disabled
  .f.buttonERBC state !disabled
  .f.buttonT1 state !disabled
  .f.buttonT2 state !disabled
  .f.buttonT3 state !disabled
  .f.buttonT4 state !disabled
}

proc test1 { } {
  sendCommand "test1"
}

proc test2 { } {
  sendCommand "test2"
}

proc test3 { } {
  sendCommand "test3"
}

proc test4 { } {
  sendCommand "test4"
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
}

proc reconnected {} {
global status
  .f.buttonOpr state !disabled
  set status "Connected"
}

#----------------------------------------------- Main
set command ""
#set showLabel no
#set showGrid no
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
--test: Do not enter event loop 
--IP <address>: Set server address
--d: Debug
"
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

wm title . "WinterTrain MCe $MCeVersion"
wm geometry . $winWidth\x$winHeight$winX$winY
grid columnconfigure . 0 -weight 1; grid rowconfigure . 0 -weight 1

grid [ttk::frame .f -padding "3 3 12 12"] -column 0 -row 0 -sticky nwes
grid columnconfigure .f 6 -weight 1; grid rowconfigure .f 3 -weight 1

grid [ttk::label .f.menu -text ""] -column 1 -row 1 -padx 5 -pady 5 -sticky we
grid [ttk::label .f.response -textvariable response] -column 2 -columnspan 5 -row 6 -padx 5 -pady 5 -sticky we
grid [ttk::label .f.status -textvariable status] -column 2 -row 5 -padx 5 -pady 5 -sticky we
grid [ttk::label .f.versions -textvariable versions] -column 6 -columnspan 2 -row 5 -padx 5 -pady 5 -sticky e
grid [ttk::label .f.live -textvariable liveIndicator] -column 8 -row 5 -padx 5 -pady 5 -sticky e
#grid [ttk::button .f.buttonSignal -text "Signal" -command buttonSignal] -column 2 -row 1 -sticky we
grid [ttk::button .f.buttonRelease -text "Release" -command buttonRelease] -column 2 -row 1 -sticky we
grid [ttk::button .f.buttonPoint -text "Spsk" -command buttonPoint] -column 3 -row 1 -sticky we
grid [ttk::button .f.buttonLX -text "Ovk" -command buttonLX] -column 4 -row 1 -sticky we
grid [ttk::button .f.buttonOpr -text "Request operation" -command rqopr] -column 5 -row 1 -sticky e
#grid [ttk::button .f.buttonShowGrid -text "Show Grid" -command showGrid] -column 6 -row 1 -sticky e
#grid [ttk::button .f.buttonShowLabel -text "Show Label" -command showLabel] -column 7 -row 1 -sticky e
grid [ttk::button .f.buttonEHMI -text "Exit MCe" -command exit] -column 8 -row 1 -sticky e
grid [ttk::button .f.buttonERBC -text "Exit RBC" -command exitRBC] -column 9 -row 1 -sticky e
grid [ttk::button .f.buttonT1 -text "TEST1" -command test1] -column 2 -row 2 -sticky e
grid [ttk::button .f.buttonT2 -text "TEST2" -command test2] -column 3 -row 2 -sticky e
grid [ttk::button .f.buttonT3 -text "TEST3" -command test3] -column 4 -row 2 -sticky e
grid [ttk::button .f.buttonT4 -text "TEST4" -command test4] -column 5 -row 2 -sticky e

#grid [tk::scrollbar .f.sbh -orient horizontal -command ".f.canvas xview"] -column 2 -columnspan 7 -row 4 -sticky we
#grid [tk::scrollbar .f.sbv -orient vertical -command ".f.canvas yview"] -column 9 -row 3 -sticky ns

grid [ttk::frame .f.fTrain -padding "3 3 3 3" -relief solid -borderwidth 2] -column 1 -row 7 -columnspan 8 -sticky nwes

disconnected
openSocket

puts "WinterTrain MCe $MCeVersion"
if {$startLoop} {
  vwait forever
} else {
  puts "Testmode"
}

