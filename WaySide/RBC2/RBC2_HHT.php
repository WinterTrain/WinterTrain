<?php
// WinterTrain, RBC2
// HHT handlers

function hhtRequest($data) { // 
global $balisesID, $hhtFoundCount, $hhtFoundSum, $PT2, $hhtBaliseID, $hhtBaliseName;
// Check sender ID: $data[1] & $RF12_ID_MASK  FIXME
  switch ($data[3]) { // Request code
    case 1: // Balise lookup
      $balise = sprintf("%'02X:%'02X:%'02X:%'02X:%'02X",$data[4],$data[5],$data[6],$data[7],$data[8]);
      $hhtBaliseID = $balise;
      if (isset($balisesID[$balise])) {
        sendHHTresponse(1, $balise, $balisesID[$balise]);
        $hhtBaliseName = $balisesID[$balise];
      } else {
        sendHHTresponse(2, $balise, "(unknown)");
        $hhtBaliseName = "(Unknown)";
      }
      updateMCe();
    break;
    case 2: // Distance lookup
      $curBalise = sprintf("%'02X:%'02X:%'02X:%'02X:%'02X",$data[4],$data[5],$data[6],$data[7],$data[8]);
      $prevBalise = sprintf("%'02X:%'02X:%'02X:%'02X:%'02X",$data[9],$data[10],$data[11],$data[12],$data[13]);
      $hhtFoundCount = 0;
      $hhtFoundSum = 0;
      distance($PT2[$balisesID[$curBalise]]["U"]["dist"], $PT2[$balisesID[$curBalise]]["U"]["name"],$balisesID[$prevBalise],"U",
      $balisesID[$curBalise]);
      if ($hhtFoundCount == 1) {
        sendHHTresponse(4, $curBalise, "", $hhtFoundSum);
      } else {
        distance($PT2[$balisesID[$curBalise]]["D"]["dist"], $PT2[$balisesID[$curBalise]]["D"]["name"],$balisesID[$prevBalise],"D", 
            $balisesID[$curBalise]);
        if ($hhtFoundCount == 1) {
          sendHHTresponse(3, $curBalise, "", $hhtFoundSum);
        }
      }
      if ($hhtFoundCount > 1) {
        sendHHTresponse(5, $curBalise, "", 0);
      }
    break;
  }
}

function distance($sum, $element1, $element2, $direction, $previousElement) {
global $PT2, $hhtFoundCount, $hhtFoundSum;
  if ($element1 == $element2) {
    switch ($PT2[$element1]["element"]) {
      case "BL":
      case "SU":
      case "SD":
      case "PHTU":
      case "PHTD":
        $sum += $PT2[$element1][($direction == "U" ? "D" : "U")]["dist"];
      break;
      case "PF":
        $sum += $PT2[$element1][($direction == "U" ? "T" : ($previousElement == $PT2[$element1]["R"]["name"] ? "R" : "L"))]["dist"];
      break;
      case "PT":
        $sum += $PT2[$element1][($direction == "D" ? "T" : ($previousElement == $PT2[$element1]["R"]["name"] ? "R" : "L"))]["dist"];
      break;
    }
    $hhtFoundSum = $sum;
    $hhtFoundCount++;
    return;

  } else {
    switch ($PT2[$element1]["element"]) {
      case "BSB":
      case "BSE":
        return;
      break;
      case "PF":
        if ($direction == "U") {
          distance($PT2[$element1]["T"]["dist"] + $PT2[$element1]["R"]["dist"]
            + $sum, $PT2[$element1]["R"]["name"], $element2, $direction, $element1);
          distance($PT2[$element1]["T"]["dist"] + $PT2[$element1]["L"]["dist"] 
            + $sum, $PT2[$element1]["L"]["name"], $element2, $direction, $element1);
            return;
        } else {
          distance($PT2[$element1]["T"]["dist"] 
            + $PT2[$element1][($previousElement == $PT2[$element1]["R"]["name"] ? "R": "L")]["dist"] + $sum, 
              $PT2[$element1]["T"]["name"], $element2, $direction, $element1);
          return ;
        }
      break;
      case "PT":
        if ($direction == "D") {
          distance($PT2[$element1]["T"]["dist"] + $PT2[$element1]["R"]["dist"]
            + $sum, $PT2[$element1]["R"]["name"], $element2, $direction, $element1);
          distance($PT2[$element1]["T"]["dist"] + $PT2[$element1]["L"]["dist"] 
            + $sum, $PT2[$element1]["L"]["name"], $element2, $direction, $element1);
          return; 
        } else {
          distance($PT2[$element1]["T"]["dist"] 
            + $PT2[$element1][($previousElement == $PT2[$element1]["R"]["name"] ? "R": "L")]["dist"] + $sum, 
              $PT2[$element1]["T"]["name"], $element2, $direction, $element1);
          return;
        }
      break;
      case "BL":
      case "SU":
      case "SD":
      case "LX":
      case "PHTU":
      case "PHTD":
        distance($PT2[$element1]["D"]["dist"] + $PT2[$element1]["U"]["dist"] + $sum, 
          $PT2[$element1][$direction]["name"], $element2, $direction, $element1);
        return;
      break;
      default:
        print "Ups 1: {$PT2[$element1]["element"]}\n";
        exit(1);
      break;
    }
  }
}

function sendHHTresponse($responseCode, $balise, $elementName, $distance = 0) { 
global $radioLinkAddr, $radioLink, $radioInterface;
  switch ($radioInterface) { 
    case "USB":
    
    // use sendToRadioLink($packet);   FIXME
    

      fwrite($radioLink,"51,$responseCode,");
      $baliseArray = explode(":", $balise);
      for ($b = 0; $b < 5; $b++) fwrite($radioLink, hexdec($baliseArray[$b]).",");
      switch ($responseCode) {
        case 1: // Balise known
          $elementName .= "          "; // Ensure minimum 10 char
          for ($b = 0; $b < 10; $b++) fwrite($radioLink, ord($elementName[$b]).",");
        break;
        case 3: // Distance
        case 4:
          fwrite($radioLink, intdiv($distance, 256).",".($distance % 256).",");
        break;  
      }
      fwrite($radioLink, "0s\n");
    break;
    case "ABUS":
      print "Warning: HHT response via EC Link not implemented\n";
    break;
  }
}

?>
