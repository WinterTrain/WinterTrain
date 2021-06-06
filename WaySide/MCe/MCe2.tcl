#!/usr/bin/wish
# MCe version 2

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
set setBaliseName "" 
set liveT 42
set live 0
set liveC 0
set liveTxt {\\ | / -}
set entryFontSize 12
set labelFontSize 12
set buttonFontSize 12

set nECframe 0
set nSimFrame 0

#----------------------------------------------------------------------------- Display elements

proc SimFrame {index trainName trainID} { # Dynamic frames for Train Simulation
global nSimFrame entryFontSize simNextPos simMA simMode

  incr nSimFrame
  set simMA($index) "<no MA>"
  set simNextPos($index) "<no sim script>"
  set simMode($index) 5
    
  grid [ttk::frame .f.fStatus.fSim.t$index -padding "3 3 12 12" -relief solid -borderwidth 2] -column 1 -row [expr $nSimFrame + 4] -sticky nwes
  grid [ttk::label .f.fStatus.fSim.t$index.nameX -text "Train name: $trainName ($trainID)" ] -column 0 -columnspan 2 -row 0 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.fSim.t$index.maX -textvariable simMA($index) ] -column 2 -columnspan 10 -row 0 -padx 5 -pady 5 -sticky we

  grid [ttk::label .f.fStatus.fSim.t$index.simPosX -text "Pos Rep:" ] -column 0 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::button .f.fStatus.fSim.t$index.posRepDrv -text "Driving" -command "posRepDrv $index"] -column 1 -row 1 -sticky we
  grid [ttk::button .f.fStatus.fSim.t$index.posRepSt -text "Stand still" -command "posRepSt $index"] -column 2 -row 1 -sticky we
  grid [ttk::button .f.fStatus.fSim.t$index.posRepUdef -text "Unknown" -command "posRepUdef $index"] -column 3 -row 1 -sticky we
  grid [ttk::label .f.fStatus.fSim.t$index.modeTxtOff -text "Off" ] -column 4 -row 1 -padx 5 -pady 5 -sticky we  
  grid [ttk::radiobutton .f.fStatus.fSim.t$index.modeOff -value "5" -variable simMode($index) ] -column 5 -row 1
  grid [ttk::label .f.fStatus.fSim.t$index.modeTxtSR -text "SR" ] -column 6 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::radiobutton .f.fStatus.fSim.t$index.modeSR -value "1" -variable simMode($index) ] -column 7 -row 1
  grid [ttk::label .f.fStatus.fSim.t$index.modeTxtSH -text "SH" ] -column 8 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::radiobutton .f.fStatus.fSim.t$index.modeSH -value "2" -variable simMode($index) ] -column 9 -row 1
  grid [ttk::label .f.fStatus.fSim.t$index.modeTxtFS -text "FS" ] -column 10 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::radiobutton .f.fStatus.fSim.t$index.modeFS -value "3" -variable simMode($index) ] -column 11 -row 1
  grid [ttk::label .f.fStatus.fSim.t$index.modeTxtATO -text "ATO" ] -column 12 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::radiobutton .f.fStatus.fSim.t$index.modeATO -value "4" -variable simMode($index) ] -column 13 -row 1

  grid [ttk::label .f.fStatus.fSim.t$index.simScript -text "Script: " ] -column 0 -row 2 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.fSim.t$index.simNextPosX -textvariable simNextPos($index) ] -column 1 -columnspan 10 -row 2 -padx 5 -pady 5 -sticky we
  grid [ttk::button .f.fStatus.fSim.t$index.reload -text "Reload Script" -command "sendCommand {reloadSim $index}"] -column 1 -row 3 -sticky we
  grid [ttk::button .f.fStatus.fSim.t$index.next -text "Next line" -command "sendCommand {nextSim $index}"] -column 2 -row 3 -sticky we
  grid [ttk::button .f.fStatus.fSim.t$index.prev -text "Prev line" -command "sendCommand {prevSim $index}"] -column 3 -row 3 -sticky we

#  grid [ttk::label .f.fStatus.fSim.t$index.uptime -textvariable ECuptime($addr)] -column 1 -row 1 -padx 5 -pady 5 -sticky we
#  grid [ttk::label .f.fStatus.fSim.t$index.onlineX -text "Status:" ] -column 2 -row 1 -padx 5 -pady 5 -sticky we
#  grid [ttk::label .f.fStatus.fSim.t$index.online -textvariable EConline($addr)] -column 3 -row 1 -padx 5 -pady 5 -sticky we
#  grid [ttk::label .f.fStatus.fSim.t$index.elementX -text "Actual configuration: " ] -column 0 -row 2 -padx 5 -pady 5 -sticky we
#  grid [ttk::label .f.fStatus.fSim.t$index.element -textvariable elementConf($addr)] -column 1 -row 2 -padx 5 -pady 5 -sticky we
#  grid [ttk::label .f.fStatus.fSim.t$index.hwX -text "Capability: " ] -column 0 -row 3 -padx 5 -pady 5 -sticky we
#  grid [ttk::label .f.fStatus.fSim.t$index.nElementX -text "E: " ] -column 1 -row 3 -padx 5 -pady 5 -sticky we
#  grid [ttk::label .f.fStatus.fSim.t$index.nElement -textvariable N_ELEMENT($addr)] -column 2 -row 3 -padx 5 -pady 5 -sticky we
#  grid [ttk::label .f.fStatus.fSim.t$index.nPdeviceX -text "P: " ] -column 3 -row 3 -padx 5 -pady 5 -sticky we
#  grid [ttk::label .f.fStatus.fSim.t$index.nPdevice -textvariable N_PDEVICE($addr)] -column 4 -row 3 -padx 5 -pady 5 -sticky we
#  grid [ttk::label .f.fStatus.fSim.t$index.nUdeviceX -text "U: " ] -column 5 -row 3 -padx 5 -pady 5 -sticky we
#  grid [ttk::label .f.fStatus.fSim.t$index.nUdevice -textvariable N_UDEVICE($addr)] -column 6 -row 3 -padx 5 -pady 5 -sticky we
#  grid [ttk::label .f.fStatus.fSim.t$index.nLdeviceX -text "L: " ] -column 7 -row 3 -padx 5 -pady 5 -sticky we
#  grid [ttk::label .f.fStatus.fSim.t$index.nLdevice -textvariable N_LDEVICE($addr)] -column 8 -row 3 -padx 5 -pady 5 -sticky we

.f.fStatus.fSim.t$index.posRepDrv state disabled
.f.fStatus.fSim.t$index.posRepSt state disabled
.f.fStatus.fSim.t$index.posRepUdef state disabled
.f.fStatus.fSim.t$index.reload state disabled
.f.fStatus.fSim.t$index.next state disabled
.f.fStatus.fSim.t$index.prev state disabled
.f.fStatus.fSim.t$index.modeOff state disabled
.f.fStatus.fSim.t$index.modeSR state disabled
.f.fStatus.fSim.t$index.modeSH state disabled
.f.fStatus.fSim.t$index.modeFS state disabled
.f.fStatus.fSim.t$index.modeATO state disabled
}


proc ECframe {addr} { # Dynamic frames for EC status
global nECframe entryFontSize

  incr nECframe
  grid [ttk::frame .f.fStatus.fEC.t$addr -padding "3 3 12 12" -relief solid -borderwidth 2] -column 1 -row [expr $nECframe + 4] -sticky nwes
  grid [ttk::label .f.fStatus.fEC.t$addr.name -text "EC ($addr)" ] -column 0 -row 0 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.fEC.t$addr.uptimeX -text "Uptime:" ] -column 0 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.fEC.t$addr.uptime -textvariable ECuptime($addr)] -column 1 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.fEC.t$addr.onlineX -text "Status:" ] -column 2 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.fEC.t$addr.online -textvariable EConline($addr)] -column 3 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.fEC.t$addr.elementX -text "Actual configuration: " ] -column 0 -row 2 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.fEC.t$addr.element -textvariable elementConf($addr)] -column 1 -row 2 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.fEC.t$addr.hwX -text "Capability: " ] -column 0 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.fEC.t$addr.nElementX -text "E: " ] -column 1 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.fEC.t$addr.nElement -textvariable N_ELEMENT($addr)] -column 2 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.fEC.t$addr.nPdeviceX -text "P: " ] -column 3 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.fEC.t$addr.nPdevice -textvariable N_PDEVICE($addr)] -column 4 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.fEC.t$addr.nUdeviceX -text "U: " ] -column 5 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.fEC.t$addr.nUdevice -textvariable N_UDEVICE($addr)] -column 6 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.fEC.t$addr.nLdeviceX -text "L: " ] -column 7 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.fEC.t$addr.nLdevice -textvariable N_LDEVICE($addr)] -column 8 -row 3 -padx 5 -pady 5 -sticky we
}

proc destroyDynFrame {} {
global nECframe nSimFrame
  destroy .f.fStatus.fEC
  grid [ttk::frame .f.fStatus.fEC -padding "3 3 12 12" -relief solid -borderwidth 2] -column 1 -columnspan 2 -row 5 -sticky nwes
  set nECframe 0
  destroy .f.fStatus.fSim
  grid [ttk::frame .f.fStatus.fSim -padding "3 3 12 12" -relief solid -borderwidth 2] -column 1 -columnspan 2 -row 5 -sticky nwes
  set nSimFrame 0
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
  .f.buttonOpr configure -text "Release Operation" -command "sendCommand Rl"
  enableButtons
}

proc oprReleased {} {
global status response
  set status "No operation"
  set response ""
  .f.buttonOpr configure -text "Request Operation" -command "sendCommand Rq"
  disableButtons
}

proc displayResponse {txt} {
global response
  set response $txt
}

#--------------------------------------------------------------------------------------------------- command handlers

proc assignBaliseName {} { # Assign balise name
global setBaliseName baliseID
  sendCommand "aBN $baliseID $setBaliseName"
  set setBaliseName ""
}

proc posRepDrv {index} {
global simMode
  sendCommand "posRepDrv $index $simMode($index)"
}

proc posRepSt {index} {
global simMode
  sendCommand "posRepSt $index $simMode($index)"
}

proc posRepUdef {index} {
global simMode
  sendCommand "posRepUdef $index $simMode($index)"
}

proc disableButtons {} {
global nSimFrame
#  .f.buttonPoint state disabled
#  .f.buttonSignal state disabled
  .f.buttonGetECstatus state disabled
#  .f.buttonLX state disabled
  .f.buttonERBC state disabled
  .f.buttonETMS state disabled
  .f.buttonRLRBC state disabled
  .f.buttonRLTMS state disabled
  .f.buttonT1 state disabled
  .f.buttonT2 state disabled
  .f.buttonT3 state disabled
  .f.buttonT4 state disabled
  .f.fStatus.balise.baliseNameInp state disabled
  .f.fStatus.balise.setName state disabled
  .f.fStatus.balise.dumpAll state disabled
  for {set x 0} {$x < $nSimFrame} {incr x} {
    .f.fStatus.fSim.t$x.posRepDrv state disabled
    .f.fStatus.fSim.t$x.posRepSt state disabled
    .f.fStatus.fSim.t$x.posRepUdef state disabled
    .f.fStatus.fSim.t$x.modeOff state disabled
    .f.fStatus.fSim.t$x.modeSR state disabled
    .f.fStatus.fSim.t$x.modeSH state disabled
    .f.fStatus.fSim.t$x.modeFS state disabled
    .f.fStatus.fSim.t$x.modeATO state disabled

    .f.fStatus.fSim.t$x.reload state disabled
    .f.fStatus.fSim.t$x.next state disabled
    .f.fStatus.fSim.t$x.prev state disabled
  }
}

proc enableButtons {} {
global nSimFrame
#  .f.buttonPoint state !disabled
#  .f.buttonSignal state !disabled
  .f.buttonGetECstatus state !disabled
#  .f.buttonLX state !disabled
  .f.buttonERBC state !disabled
  .f.buttonETMS state !disabled
  .f.buttonRLRBC state !disabled
  .f.buttonRLTMS state !disabled
  .f.buttonT1 state !disabled
  .f.buttonT2 state !disabled
  .f.buttonT3 state !disabled
  .f.buttonT4 state !disabled
  .f.fStatus.balise.baliseNameInp state !disabled
  .f.fStatus.balise.setName state !disabled
  .f.fStatus.balise.dumpAll state !disabled
  for {set x 0} {$x < $nSimFrame} {incr x} {
    .f.fStatus.fSim.t$x.posRepDrv state !disabled
    .f.fStatus.fSim.t$x.posRepSt state !disabled
    .f.fStatus.fSim.t$x.posRepUdef state !disabled
    .f.fStatus.fSim.t$x.modeOff state !disabled
    .f.fStatus.fSim.t$x.modeSR state !disabled
    .f.fStatus.fSim.t$x.modeSH state !disabled
    .f.fStatus.fSim.t$x.modeFS state !disabled
    .f.fStatus.fSim.t$x.modeATO state !disabled

    .f.fStatus.fSim.t$x.reload state !disabled
    .f.fStatus.fSim.t$x.next state !disabled
    .f.fStatus.fSim.t$x.prev state !disabled
  }
}


proc cmd1 { } {
  sendCommand "CMD1"
}

proc cmd2 { } {
  sendCommand "CMD2"
}

proc cmd3 { } {
  sendCommand "CMD3"
}

proc cmd4 { } {
  sendCommand "CMD4"
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
  .f.buttonOpr configure -text "Request Operation" -command "sendCommand Rq"
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
--l             Set server address to localhost (127.0.0.1) 
--d: Debug
"
      }
    --test {
      set startLoop no
      }
    --IP {
      set reqIP yes
      }
    --l {
      set IPaddress "127.0.0.1"
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
#grid columnconfigure .f 4 -weight 1; grid rowconfigure .f 3 -weight 1

grid [ttk::label .f.menu -text ""] -column 1 -row 1 -padx 5 -pady 5 -sticky we
grid [ttk::label .f.response -textvariable response] -column 2 -columnspan 4 -row 6 -padx 5 -pady 5 -sticky we
grid [ttk::label .f.status -textvariable status] -column 2 -row 5 -padx 5 -pady 5 -sticky we
grid [ttk::label .f.versions -textvariable versions] -column 6 -columnspan 2 -row 5 -padx 5 -pady 5 -sticky e
grid [ttk::label .f.live -textvariable liveIndicator] -column 8 -row 5 -padx 5 -pady 5 -sticky e
grid [ttk::button .f.buttonGetECstatus -text "Get EC status" -command "sendCommand ECstatus"] -column 2 -row 1 -sticky we
#grid [ttk::button .f.buttonPoint -text "Spsk" -command buttonPoint] -column 3 -row 1 -sticky we
#grid [ttk::button .f.buttonLX -text "Ovk" -command buttonLX] -column 4 -row 1 -sticky we
grid [ttk::button .f.buttonOpr -text "Request operation" -command "sendCommand Rq"] -column 5 -row 1 -sticky e
grid [ttk::button .f.buttonEHMI -text "Exit MCe" -command exit] -column 6 -row 1 -sticky e
grid [ttk::button .f.buttonERBC -text "Exit RBC" -command "sendCommand exitRBC"] -column 8 -row 1 -sticky e
grid [ttk::button .f.buttonETMS -text "Exit TMS" -command "sendCommand exitTMS"] -column 9 -row 1 -sticky e
grid [ttk::button .f.buttonRLRBC -text "Reload RBC" -command "sendCommand rlRBC"] -column 10 -row 1 -sticky e
grid [ttk::button .f.buttonRLTMS -text "Reload TMS" -command "sendCommand rlTMS"] -column 11 -row 1 -sticky e
grid [ttk::button .f.buttonT1 -text "DumpTrackModel" -command cmd1] -column 2 -row 2 -sticky e
grid [ttk::button .f.buttonT2 -text "CMD2" -command cmd2] -column 3 -row 2 -sticky e
grid [ttk::button .f.buttonT3 -text "CMD3" -command cmd3] -column 4 -row 2 -sticky e
grid [ttk::button .f.buttonT4 -text "CMD4" -command cmd4] -column 5 -row 2 -sticky w

#grid [tk::scrollbar .f.sbh -orient horizontal -command ".f.canvas xview"] -column 2 -columnspan 7 -row 4 -sticky we
#grid [tk::scrollbar .f.sbv -orient vertical -command ".f.canvas yview"] -column 9 -row 3 -sticky ns

grid [ttk::frame .f.fStatus -padding "3 3 3 3" -relief solid -borderwidth 2] -column 1 -row 4 -columnspan 10 -sticky nwes
# -------------------------------------------------- Balise and Server frames
  grid [ttk::frame .f.fStatus.server -padding "3 3 12 12" -relief solid -borderwidth 2] -column 1 -row 2 -sticky nwes
  grid [ttk::label .f.fStatus.server.name -text "Server"] -column 0 -row 0 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.server.uptimeX -text "Uptime:"] -column 0 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.server.uptime -textvariable serverUptime] -column 1 -row 1 -padx 5 -pady 5 -sticky we

  grid [ttk::frame .f.fStatus.rbc -padding "3 3 12 12" -relief solid -borderwidth 2] -column 1 -row 3 -sticky nwes
  grid [ttk::label .f.fStatus.rbc.name -text "RBC"] -column 0 -row 0 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.rbc.uptimeX -text "Uptime:"] -column 0 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.rbc.uptime -textvariable RBCuptime] -column 1 -row 1 -padx 5 -pady 5 -sticky we

  grid [ttk::frame .f.fStatus.tms -padding "3 3 12 12" -relief solid -borderwidth 2] -column 1 -row 4 -sticky nwes
  grid [ttk::label .f.fStatus.tms.name -text "TMS"] -column 0 -row 0 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.tms.uptimeX -text "Status:"] -column 0 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.tms.uptime -textvariable tmsStatus] -column 1 -row 1 -padx 5 -pady 5 -sticky we

  grid [ttk::frame .f.fStatus.balise -padding "3 3 12 12" -relief solid -borderwidth 2] -column 2 -row 2 -rowspan 2 -sticky nwes
  grid [ttk::label .f.fStatus.balise.name -text "HHT Balise Reader"] -column 0 -row 0 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.balise.baliseID -textvariable baliseID] -column 0 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.balise.baliseName -textvariable baliseName] -column 1 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::entry .f.fStatus.balise.baliseNameInp -textvariable setBaliseName -width 10] -column 2 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::button .f.fStatus.balise.setName -text "Assign ID to balise" -command "assignBaliseName"] -column 3 -row 1 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.balise.status -textvariable baliseStatus] -column 0 -row 2 -padx 5 -pady 5 -sticky we
  grid [ttk::button .f.fStatus.balise.dumpAll -text "Dump Balise List" -command "sendCommand dBL"] -column 0 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.balise.countX -text "Balise count (total/unassigned):"] -column 1 -columnspan 2 -row 3 -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.fStatus.balise.count -textvariable baliseCount] -column 3 -row 3 -padx 5 -pady 5 -sticky we

  grid [ttk::frame .f.fStatus.fSimFrame -padding "3 3 12 12" -relief solid -borderwidth 2] -column 2 -columnspan 2 -row 6 -sticky nwes
  grid [ttk::frame .f.fStatus.fEC -padding "3 3 12 12" -relief solid -borderwidth 2] -column 1 -columnspan 2 -row 5 -sticky nwes
 

disconnected
openSocket

puts "WinterTrain MCe $MCeVersion"
if {$startLoop} {
  vwait forever
} else {
  puts "Testmode"
}

