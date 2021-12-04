<?php
// WinterTrain, RBC2
// Element Controller handlers

function elementStatusEC($addr, $data) { // Analyse element status for one EC
global $EC, $PT2, $triggerHMIupdate;
  if (isset($EC[$addr]) and $data[3] < count($EC[$addr]["index"])) { // Check EC configuration
    errLog("EC ($addr) not configured: #conf. element EC: {$data[3]}, RBC: ".count($EC[$addr]["index"]));
    unset($EC[$addr]);
    initEC($addr);
  } else {
    if (!$EC[$addr]["EConline"]) { // Was off-line
      errLog("EC ($addr) on-line");
      $EC[$addr]["EConline"] = true;
    }
  }
  $EC[$addr]["validTimer"] = time() + EC_TIMEOUT;
    
// Analyse EC status for all elements of EC and apply consequences FIXME   see old impl
/*

  foreach() {
    switch (elementType) {
      case "PF":
      case "PT":
        $this->pointState = physical supervision state reported by EC
        if (supervised in correct lie acc logicalLieRight and $this->routeLockingState != R_IDLE) {
        // Point is locked in route, check if throwing is to be locked
          if ($this->logicalLieRight == ($this->routeLockingType == RT_RIGHT)) {
            $this->throwLockedByRoute = true;
            $triggerHMIupdate = true;
          //  generate MA?? FIXME  Right place??
          }
        };
      break;
      case "LX":
        fatalErr("LX status not implemented);
      break;
      default:
      break;
    }
  }
  */
}

function pollNextEC() { // Poll one EC at a time
global $EC, $pollEC;
  if ($pollEC) {
    requestElementStatusEC(key($EC));
    if (next($EC) === FALSE) {
      reset($EC);
      $pollEC = false;
    }
  }
}

function checkECtimeout() {
global $PT2, $EC, $now, $radioLinkAddr;
  foreach ($EC as $addr => &$ec) {
    if ($now > $ec["validTimer"]) { // EC not providing status - EC assumed offline
      if ($ec["EConline"]) { // Was online
        errLog("EC ($addr) off-line");
        $ec["EConline"] = false;
      }
      // Apply consequences  FIXME
      // call point state and LX state handler, optional call generateMA()

    }
  }
}

function AbusReceivedFrom($addr, $data) { // Call-back function called by Abus interface handler when receiving data from Abus slave
global $EC, $radioInterface;
//print "receivedFromEC: addr >$addr< ";
//print_r($data);
//print "
//";

  if ($addr) {
    switch ($data[2]) { // packet type
      case 01: // Element status
      case 10: // Element status
        elementStatusEC($addr, $data);
        break;
      case 02: // EC status
        $uptime = 0;
        for ($i = 3; $i >= 0; $i--) {
          $uptime = 256 * $uptime + (int)$data[$i + 3];
        }
        $EC[$addr]["uptime"] = round($uptime / 1000);
        $EC[$addr]["elementConf"] = $data[7];
        $EC[$addr]["N_ELEMENT"] = $data[8];
        $EC[$addr]["N_UDEVICE"] = $data[9];
        $EC[$addr]["N_LDEVICE"] = $data[10];
        $EC[$addr]["N_PDEVICE"] = $data[11];
        break;  
      case 03: // position report from radio via Abus
        if ($radioInterface == "ABUS") {
          fatalError("Position report via Abus connected ratio not implemented");
          // processPositionReport(   ); // Unpack packet from Abus module
        }
        break;
      case 20: // EC configuration report
        if ($data[3] > 0) {
          errLog("EC ($addr), Configuration error: ".$data[3]);
        } else {
          debugPrint ("EC ($addr), Configuration OK");
        }
        break;
      default:
        errLog("EC ($addr) Unknown Abus packet: ".$data[2]);
    }
  } // else ignore empty packet
}

function initEC($specificEC = "") { // Initialize all or one specific EC from data in PT2
global $PT2, $EC;

  if ($specificEC == "") $EC = array(); // Enforce a rebuild of EC table from PT2 as PT2 might have been reloaded in this case
  foreach ($PT2 as $name => &$element) {
    if ($specificEC == "" or (isset($element["EC"]["addr"]) and $element["EC"]["addr"] == $specificEC)) {
      switch ($element["element"]) {
        case "PF":
        case "PT":
        if ($element["EC"]["type"] != 0) {
          $addr = $element["EC"]["addr"];
          if (!isset($EC[$addr])) {
            addEC($addr);
          }
          configureEC($addr, $element["EC"]["type"], $element["EC"]["majorDevice"]);
          $element["EC"]["index"] = count($EC[$addr]["index"]);
          $EC[$addr]["index"][] = $name;
        }
        break;
        case "SU":
        case "SD":
        if ($element["EC"]["type"] != 0) {
          $addr = $element["EC"]["addr"];
          if (!isset($EC[$addr])) {
            addEC($addr);
          }
          configureEC($addr, $element["EC"]["type"], $element["EC"]["majorDevice"]);
          $element["EC"]["index"] = count($EC[$addr]["index"]);
          $EC[$addr]["index"][] = $name;
        }
        break;
        case "LX":
        if ($element["ECbarrier"]["type"] != 0) {
          $addr = $element["ECbarrier"]["addr"];
          if (!isset($EC[$addr])) {
            addEC($addr);
          }
          configureEC($addr, $element["ECbarrier"]["type"], $element["ECbarrier"]["majorDevice"]);
          $element["ECbarrier"]["index"] = count($EC[$addr]["index"]);
          $EC[$addr]["index"][] = $name;
        }
        if ($element["ECsignal"]["type"] != 0) {
          $addr = $element["ECsignal"]["addr"];
          if (!isset($EC[$addr])) {
            addEC($addr);
          }
          configureEC($addr, $element["ECsignal"]["type"], $element["ECsignal"]["majorDevice"]);
          $element["ECsignal"]["index"] = count($EC[$addr]["index"]);
          $EC[$addr]["index"][] = $name;
        }
        break;
      }
    }
  }
}

// ------------------------------------------------------------------------------------------------- Low level command handlers for EC
function addEC($addr) { // Add new Element Controller data to EC table
global $EC;
  $EC[$addr]["index"] = array();
  $EC[$addr]["validTimer"] = 0;
  $EC[$addr]["EConline"] = false;
  $EC[$addr]["uptime"] = "*";
  $EC[$addr]["elementConf"] = "*";
  $EC[$addr]["N_ELEMENT"] = "*";
  $EC[$addr]["N_UDEVICE"] = "*";
  $EC[$addr]["N_LDEVICE"] = "*";
  $EC[$addr]["N_PDEVICE"] = "*";
  resetEC($addr);
}

function resetEC($addr) {
  $packet[2] = 20;
  $packet[3] = 00;
  AbusSendPacket($addr, $packet);
}

function configureEC($addr, $elementType, $majorDevice, $minorDevice = 0) {
  $packet[2] = 20;
  $packet[3] = 01;
  $packet[4] = $elementType;
  $packet[5] = $majorDevice;
  $packet[6] = $minorDevice;
  AbusSendPacket($addr, $packet);
}

function orderEC($addr, $index, $order) {
  $packet[2] = 10;
  $packet[3] = $index;
  $packet[4] = $order;
  AbusSendPacket($addr, $packet, 5);
}

function requestECstatus($addr) {
  $packet[2] = 02;
  AbusSendPacket($addr, $packet);
}

function requestElementStatusEC($addr) {
  $packet[2] = 01;
  AbusSendPacket($addr, $packet);
}

?>
