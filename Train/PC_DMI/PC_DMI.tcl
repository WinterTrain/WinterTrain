#!/usr/bin/wish
package require Tk

set version 02P03
set IPaddress 0.0.0.0
set IPport 9902
set ttyName "/dev/ttyUSB0"

set trainID(1) 20
set dmiID(1) 21
set trainName(1) "Cargo"

set trainID(2) 24
set dmiID(2) 25
set trainName(2) "Circus"

set winWidth 620
set winHeight 380
set winX +50
set winY +50
set labelFontSize 14
set buttonFontSize 12

set eColor "blue"
set rColor "red"
set yColor "yellow"
set gColor "green"
set nColor "lightgrey"

set dmiPacket 21

set powerI "   "
set errorI "____"
set mode 5
set dir 2
set drive 1
set speed 0
set vMotor 0


set trainIndex 1
set debug no
set expand no
set reqTrainID no
set reqDmiID no
set tcp no
set reply yes 
# reply to OBU poll

set train "$trainName($trainIndex) ($trainID($trainIndex))"
set reqIP no
foreach arg $argv {
#  if {$reqTrainID} {
#    set trainID $arg
#    set reqTrainID no
#  } elseif {$reqDmiID} {
#    set dmiID $arg
#    set reqDmiID no
#  } else {
    switch $arg {
    --help {
      puts "
Usage:
--d: Debug
--e: Expand display
--t <train ID>: Set ID of train to controle
--m <dmiID>: Set ID of DMI
--n: network connection
--r: Receive only
"
      exit
      }
    --d {
      set debug yes
      }
    --e {
      set expand yes
      }
    --t {
      set reqTrainID yes
      }
    --m {
      set reqDmiID yes
      }
    --r {
      set reply no
      }
    --n {
      set tcp yes
      }
    default {
      puts "Unknown option: $arg"  
      exit  
      }
    }
#  }
}


ttk::style configure TButton -font "'Helvetica', $buttonFontSize"
ttk::style configure TLabel -font "'Helvetica', $labelFontSize"

wm title . "WinterTrain PC_DMI version $version"
wm geometry . $winWidth\x$winHeight$winX$winY
grid columnconfigure . 0 -weight 1; grid rowconfigure . 0 -weight 1

grid [ttk::frame .f -padding "3 3 12 12"] -column 0 -row 0 -sticky nwes
grid columnconfigure .f 5 -weight 1; grid rowconfigure .f 10 -weight 1

grid [ttk::label .f.title1 -text "PC "] -column 1 -row 1 -padx 5 -pady 5 -sticky we
grid [ttk::label .f.title2 -text "DMI"] -column 2 -row 1 -padx 5 -pady 5 -sticky we

grid [ttk::frame .f.fControl -padding "3 3 3 3" -relief solid -borderwidth 2] -column 2 -columnspan 2 -row 7  -sticky nwes
grid [ttk::label .f.fControl.powerX -text "__" -foreground $nColor -background $nColor] -column 3 -row 1 -sticky we
grid [ttk::label .f.fControl.errorX -text "__" -foreground $nColor -background $nColor] -column 3 -row 2 -sticky we

grid [ttk::label .f.fControl.modeOFFX -text "OFF "] -column 1 -row 5 -sticky we -padx 5 -pady 5
grid [ttk::radiobutton .f.fControl.modeOFF -value "5" -variable mode -command modeSel] -column 1 -row 6
grid [ttk::label .f.fControl.modeSRX -text "SR "] -column 2 -row 5 -sticky we -padx 5 -pady 5
grid [ttk::radiobutton .f.fControl.modeSR -value "1" -variable mode -command modeSel] -column 2 -row 6
grid [ttk::label .f.fControl.modeSHX -text "SH "] -column 3 -row 5 -sticky we -padx 5 -pady 5
grid [ttk::radiobutton .f.fControl.modeSH -value "2" -variable mode -command modeSel] -column 3 -row 6
grid [ttk::label .f.fControl.modeFSX -text "FS "] -column 4 -row 5 -sticky we -padx 5 -pady 5
grid [ttk::radiobutton .f.fControl.modeFS -value "3" -variable mode -command modeSel] -column 4 -row 6
grid [ttk::label .f.fControl.modeATOX -text "ATO"] -column 5 -row 5 -sticky we -padx 5 -pady 5
grid [ttk::radiobutton .f.fControl.modeATO -value "4" -variable mode -command modeSel] -column 5 -row 6

grid [ttk::label .f.fControl.meterX -textvariable speed -anchor center] -column 8 -row 2 -sticky we
grid [ttk::label .f.fControl.redX -text "___" -foreground $nColor -background $nColor] -column 7 -row 6 -sticky we
grid [ttk::label .f.fControl.yellowX -text "___" -foreground $nColor -background $nColor] -column 8 -row 6 -sticky we
grid [ttk::label .f.fControl.greenX -text "___" -foreground $nColor -background $nColor] -column 9 -row 6 -sticky we
grid [ttk::label .f.fControl.dirRX -text "Bak "] -column 11 -row 1 -sticky we -padx 5 -pady 5
grid [ttk::radiobutton .f.fControl.dirR -value "1" -variable dir ] -column 11 -row 2
grid [ttk::label .f.fControl.dirNX -text "N "] -column 12 -row 1 -sticky we -padx 5 -pady 5
grid [ttk::radiobutton .f.fControl.dirN -value "2" -variable dir ] -column 12 -row 2
grid [ttk::label .f.fControl.dirFX -text "Frem"] -column 13 -row 1 -sticky we -padx 5 -pady 5
grid [ttk::radiobutton .f.fControl.dirF -value "3" -variable dir ] -column 13 -row 2

grid [ttk::label .f.fControl.driveFX -text "Fuld"] -column 11 -row 4 -sticky we
grid [ttk::radiobutton .f.fControl.drive5 -value "5" -variable drive ] -column 12 -row 4 -sticky we
grid [ttk::radiobutton .f.fControl.drive4 -value "4" -variable drive ] -column 12 -row 5 -sticky we
grid [ttk::radiobutton .f.fControl.drive3 -value "3" -variable drive ] -column 12 -row 6 -sticky we
grid [ttk::radiobutton .f.fControl.drive2 -value "2" -variable drive ] -column 12 -row 7 -sticky we
grid [ttk::label .f.fControl.driveSTOPX -text "Stop"] -column 11 -row 8 -sticky we
grid [ttk::radiobutton .f.fControl.drive1 -value "1" -variable drive ] -column 12 -row 8 -sticky we

if {$expand} {
  grid [ttk::label .f.motor -text "Motor:"] -column 2 -row 9 -sticky w
  grid [ttk::label .f.motorX -textvariable vMotor] -column 3 -row 9 -sticky w
  
}
#grid [ttk::label .f.trainNameX -text "Train :"] -column 1 -row 10 -columnspan 4 -sticky w
grid [ttk::label .f.trainName -textvariable train] -column 2 -row 10 -columnspan 4 -sticky w
grid [ttk::button .f.buttonT -text "Switch Train" -command toggleTrain] -column 2 -row 11 -sticky e
grid [ttk::button .f.buttonE -text "Exit" -command exit] -column 3 -row 11 -sticky e

bind . "4" {set drive 5}
bind . "3" {set drive 4}
bind . "2" {set drive 3}
bind . "1" {set drive 2}
bind . "0" {set drive 1}
bind . <space> {set drive 1}
bind . "b" {set dir 1} 
bind . "n" {set dir 2} 
bind . "f" {set dir 3} 
bind . "r" {set mode 1; modeSel} 
bind . "h" {set mode 2; modeSel} 
bind . "s" {set mode 3; modeSel} 
bind . "a" {set mode 4; modeSel} 
bind . "t" {set mode 4; modeSel} 
bind . "o" {set mode 5; modeSel} 

proc modeSel {} {
global mode yColor nColor
  if {$mode == "5"} {
    after cancel comTimeout
    .f.fControl.powerX configure -foreground $nColor -background $nColor
    .f.fControl.errorX configure -foreground $nColor -background $nColor
    .f.fControl.redX configure -foreground $nColor -background $nColor
    .f.fControl.yellowX configure -foreground $nColor -background $nColor
    .f.fControl.greenX configure -foreground $nColor -background $nColor
  } else {
    .f.fControl.powerX configure -foreground $yColor -background $yColor
    after 2000 comTimeout
  }
}

proc toggleTrain {} {
upvar trainName LtrainName
upvar trainID LtrainID
global trainIndex mode dir train
  set mode 5
  modeSel
  set dir 2
  if {$trainIndex == 1} {
    set trainIndex 2
  } else {
    set trainIndex 1
  }
  set train "$LtrainName($trainIndex) ($LtrainID($trainIndex))"
  initRadio
}

proc readHandler {} {
upvar trainID LtrainID
global tty dmiPacket data speed nColor mode dir drive rColor yColor gColor bColor nColor vMotor debug reply trainIndex
  if {[gets $tty line] >= 0 && $line != ""} {
    if {$debug} {
      puts $line
    }
    if {$mode != 5 && [lindex $line 0] == "OK" && [lindex $line 1] == $LtrainID($trainIndex) && [lindex $line 2] == $dmiPacket} {
      set d [lindex $line 3]
      if {$d & 0x01} {
        .f.fControl.greenX configure -foreground $gColor -background $gColor
      } else {
        .f.fControl.greenX configure -foreground $nColor -background $nColor
      }
      if {$d & 0x02} {
        .f.fControl.yellowX configure -foreground $yColor -background $yColor
      } else {
        .f.fControl.yellowX configure -foreground $nColor -background $nColor
      }
      if {$d & 0x04} {
        .f.fControl.redX configure -foreground $rColor -background $rColor
      } else {
        .f.fControl.redX configure -foreground $nColor -background $nColor
      }
      if {$d & 0x08} {
        .f.fControl.errorX configure -foreground $bColor -background $bColor
      } else {
        .f.fControl.errorX configure -foreground $nColor -background $nColor
      }
      set speed [lindex $line 4] 
      set vMotor [lindex $line 5] 
     
      set d [expr $mode | ($dir << 3) | ($drive << 5)]
      if ($reply) {
        puts $tty "20,$d,0s\n"
      }
      if {$debug} {
        puts "Send: 20 $d 0 s\n"
      }
      .f.fControl.errorX configure -foreground $nColor -background $nColor
      after cancel comTimeout
      after 2500 comTimeout
    }
  }
}

proc comTimeout {} {
global eColor
    .f.fControl.errorX configure -foreground $eColor -background $eColor
}

proc openSocket {} {
global tty IPaddress IPport
  set tty [socket -async $IPaddress $IPport]
  chan configure $tty -blocking 0 -buffering line
  chan event $tty writable writeHandler 
}

proc writeHandler {} {
global tty clientPassword
  chan event $tty writable {} ;# delete event handler as a failed channel remains writable. And it is not used for other purpose
  if {[set err [chan configure $tty -error]] ne ""} {
    puts stderr "Failed to open socket: $err"
    close $tty
    after 10000 openSocket
    return
  }
  chan event $tty readable readHandler
}

proc initRadio {} {
upvar ::dmiID LdmiID
global trainIndex tty
#puts "$LdmiID(2)\n"
  puts $tty "1l\n" 
  puts $tty "101g\n"
  puts $tty "$LdmiID($trainIndex)\i\n"
}

if {$tcp} {
  openSocket
} else {
  set tty [open $ttyName r+]
  fconfigure $tty -mode 57600,n,8,1 -blocking false -buffering line
  fileevent $tty readable readHandler
}

initRadio
after 2000
vwait forever

