#!/usr/bin/wish
# MMI for OBUng

package require Tk

# ---------------------- Configuration
set IPaddress 192.168.8.230
set IPport 9911

# --------------------------------------------------------------------------------------------------- Layout
proc layout {} { 
  set winWidth 1850
  set winHeight 800
  set winX +50
  set winY +50
  set labelFontSize 14
  set buttonFontSize 14
  
  set cmdRow 1
  set statusRow 2
  set bufRow 0
  
  ttk::style configure TButton -font "'Helvetica', $buttonFontSize"
  ttk::style configure TLabel -font "'Helvetica', $labelFontSize"
  wm title . "WinterTrain MMI for OBUng"
  wm geometry . $winWidth\x$winHeight$winX$winY
  grid columnconfigure . 0 -weight 1; grid rowconfigure . 0 -weight 1

  grid [ttk::frame .f -padding "3 3 12 12" ] -column 0 -row 0 -sticky nwes

  grid [ttk::button .f.buttonOpr -text "Request operation" -command "sendCommand Rq"] -column 0 -row $cmdRow -sticky w
  grid [ttk::button .f.buttonEMMI -text "Exit MMI" -command exit] -column 1 -row $cmdRow -sticky e
  grid [ttk::label .f.response -textvariable response] -column 1 -columnspan 4 -row $statusRow -padx 5 -pady 5 -sticky we
  grid [ttk::label .f.status -textvariable status] -column 0 -row $statusRow -padx 5 -pady 5 -sticky we


# --------- MMI display buffer
  grid [ttk::frame .f.buf -padding "3 3 12 12"] -column 0 -columnspan 10 -row $bufRow -sticky nwes
  grid [ttk::frame .f.buf.buffer -padding "3 3 12 12" -relief solid -borderwidth 2] -column 0 -row 0 -sticky nw

}

# -------------------------------------------------------------------------------------------------- indication handlers


#--------------------------------------------------------------------------------------------------- command handlers


proc enableButtons {} {

}

proc disableButtons {} {

}



# ---------------------------------------------------------------------------------------------------- Communication
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

proc processIndication {line} { ;# Process indications from OBU
global debug
  if {$debug} {
    puts "Modtaget: $line"
  }
#  rotate
  eval $line
}

proc sendCommand {line} { ;# Send command to OBU
global server
  catch {puts $server $line}
}

proc disconnected {} {
global response status
  disableButtons
  .f.buttonOpr state disabled
  .f.buttonOpr configure -text "Request Operation" -command "sendCommand Rq"
  .f.buf.buffer state disabled
  set status "Not connected"
  set response "-"
}

proc reconnected {} {
  global status
  .f.buttonOpr state !disabled
  .f.buf.buffer state !disabled
  set status "Connected"
}

# -------------------------------------------------------------------------------- command line arg

proc commandLineArg {} {
  global argv IPaddress IPport debug
  set reqIP no
  set reqOBUport no
  foreach arg $argv {
    puts $arg
    if {$reqIP} {
      set IPaddress $arg
      set reqIP no
    } else {
        if ($reqOBUport) {
          set IPport $arg
          set reqOBUport no
        } else {
        switch $arg {
        --help {
          puts "
Usage:
--test:           Do not enter event loop 
--IP <address>:   Set OBU address
--l               Set OBU address to localhost (127.0.0.1) 
--OBUport <port>  Use <port> for OBU interface
--d: Debug
"
          exit
          }
        --test {
          set startLoop no
          }
        --IP {
          set reqIP yes
          }
        --OBUport {
          set reqOBUport yes
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
  }
}


# --------------------------------------------------------------------------------------------------------- Main
set command ""
set startLoop yes
set debug no

commandLineArg
layout
disconnected
openSocket

puts "WinterTrain MMI for OBUng"
if {$startLoop} {
  vwait forever
} else {
  puts "Testmode"
}


