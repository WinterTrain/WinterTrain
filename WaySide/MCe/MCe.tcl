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

set nECframe 0

#----------------------------------------------------------------------------- Display elements

proc serverFrames {} {
global entryFontSize

  grid [ttk::frame .f.fStatus.server -padding "3 3 12 12" -relief solid -borderwidth 2] -column 1 -row 2 -sticky nwes
  grid [ttk::label .f.fStatus.server.name -text "Server"] -column 0 -row 0 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.server.uptimeX -text "Uptime:"] -column 0 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.server.uptime -textvariable serverUptime] -column 1 -row 1 -padx 5 -pady 5 -sticky we

  grid [ttk::frame .f.fStatus.rbc -padding "3 3 12 12" -relief solid -borderwidth 2] -column 1 -row 3 -sticky nwes
  grid [ttk::label .f.fStatus.rbc.name -text "RBC"] -column 0 -row 0 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.rbc.uptimeX -text "Uptime:"] -column 0 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.rbc.uptime -textvariable RBCuptime] -column 1 -row 1 -padx 5 -pady 5 -sticky we
}

proc ECframe {addr} {
global nECframe entryFontSize

  incr nECframe
  grid [ttk::frame .f.fStatus.t$addr -padding "3 3 12 12" -relief solid -borderwidth 2] -column 1 -row [expr $nECframe + 3] -sticky nwes
  grid [ttk::label .f.fStatus.t$addr.name -text "EC ($addr)" ] -column 0 -row 0 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.t$addr.uptimeX -text "Uptime:" ] -column 0 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.t$addr.uptime -textvariable ECuptime($addr)] -column 1 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.t$addr.onlineX -text "Status:" ] -column 2 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.t$addr.online -textvariable EConline($addr)] -column 3 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.t$addr.elementX -text "Actual configuration: " ] -column 0 -row 2 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.t$addr.element -textvariable elementConf($addr)] -column 1 -row 2 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.t$addr.hwX -text "Capability: " ] -column 0 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.t$addr.nElementX -text "E: " ] -column 1 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.t$addr.nElement -textvariable N_ELEMENT($addr)] -column 2 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.t$addr.nPdeviceX -text "P: " ] -column 3 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.t$addr.nPdevice -textvariable N_PDEVICE($addr)] -column 4 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.t$addr.nUdeviceX -text "U: " ] -column 5 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.t$addr.nUdevice -textvariable N_UDEVICE($addr)] -column 6 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.t$addr.nLdeviceX -text "L: " ] -column 7 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.t$addr.nLdevice -textvariable N_LDEVICE($addr)] -column 8 -row 3 -padx 5 -pady 5 -sticky we
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
proc getECstatus {} {
  sendCommand "ECstatus"
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


proc disableButtons {} {
global nTrainFrame
  .f.buttonPoint state disabled
#  .f.buttonSignal state disabled
  .f.buttonGetECstatus state disabled
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
  .f.buttonGetECstatus state !disabled
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
grid [ttk::button .f.buttonGetECstatus -text "Get EC status" -command getECstatus] -column 2 -row 1 -sticky we
grid [ttk::button .f.buttonPoint -text "Spsk" -command buttonPoint] -column 3 -row 1 -sticky we
grid [ttk::button .f.buttonLX -text "Ovk" -command buttonLX] -column 4 -row 1 -sticky we
grid [ttk::button .f.buttonOpr -text "Request operation" -command rqopr] -column 5 -row 1 -sticky e
grid [ttk::button .f.buttonEHMI -text "Exit MCe" -command exit] -column 8 -row 1 -sticky e
grid [ttk::button .f.buttonERBC -text "Exit RBC" -command exitRBC] -column 9 -row 1 -sticky e
grid [ttk::button .f.buttonT1 -text "TrainData" -command test1] -column 2 -row 2 -sticky e
grid [ttk::button .f.buttonT2 -text "LockedRoutes" -command test2] -column 3 -row 2 -sticky e
grid [ttk::button .f.buttonT3 -text "TEST3" -command test3] -column 4 -row 2 -sticky e
grid [ttk::button .f.buttonT4 -text "TEST4" -command test4] -column 5 -row 2 -sticky e

#grid [tk::scrollbar .f.sbh -orient horizontal -command ".f.canvas xview"] -column 2 -columnspan 7 -row 4 -sticky we
#grid [tk::scrollbar .f.sbv -orient vertical -command ".f.canvas yview"] -column 9 -row 3 -sticky ns

grid [ttk::frame .f.fStatus -padding "3 3 3 3" -relief solid -borderwidth 2] -column 1 -row 4 -columnspan 8 -sticky nwes

disconnected
openSocket

puts "WinterTrain MCe $MCeVersion"
if {$startLoop} {
  vwait forever
} else {
  puts "Testmode"
}

