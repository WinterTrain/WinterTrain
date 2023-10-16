// WinterTrain DMI next generation
#include <ArduinoLowPower.h>
#include <SPI.h>
#include <WiFiNINA.h>
#include <BQ24195.h>
#include <stdlib.h>

#include "TrainConf.h" // Include specific train configuration

byte indPin[6] = PIN_IND;
byte indState[6];
int  wifiState = ST_WIFI_BOOT, prevState = ST_WIFI_UDEF, dmiState = ST_DMI_BOOT, ws, cs;
int  oprSel, modeSel, driveSel, dirSel, wifiRetryCount, dmiRetryCount, wifiConnectedCount;
int  prevDirSel, prevOprSel = 255;

IPAddress myAddress, obuAddr(10,0,0,206);  // FIXME Dependent on actual AP, move to configuration

boolean flash1State, flash5State;

// Timing
unsigned long lastMillis, deltaMillis, thisMillis;
long timer, oprTimer, wifiTimer, flash1timer, flash5timer;


WiFiServer server(80);
WiFiClient dmiClient; // Client towards OBU
WiFiClient webClient; // Client towards DMI web

char ssid[] = "Protokol-Oberst";
char pass[] = "sAEbespAAner";  // FIXME Where to store??

void setup() {
  Serial.begin(SERIAL_SPEED);
//  Serial.setTimeout(100);
  analogReference(AR_DEFAULT);
  analogReadResolution(12);
  PMIC.begin();
  PMIC.setMinimumSystemVoltage(VBAT_EMPTY);
  PMIC.setChargeVoltage(VBAT_FULL);
  PMIC.setChargeCurrent(C_BAT/2);
  PMIC.enableCharge();
  WiFiDrv::pinMode(25, OUTPUT); // FIXME is RGB used??
  WiFiDrv::pinMode(26, OUTPUT);
  WiFiDrv::pinMode(27, OUTPUT);
  for (byte i = 0; i < 6; i++) pinMode(indPin[i], OUTPUT);
  pinMode(PIN_METER, OUTPUT);
  pinMode(PIN_OPERATION_SEL, INPUT);
  pinMode(PIN_MODE_SEL, INPUT);
  pinMode(PIN_DRIVE_SEL, INPUT);
  pinMode(PIN_DIR_SEL, INPUT);
  analogWrite(PIN_METER, 0);
  for (byte f = 0; f < 4; f++) {
    allIndOn();
    delay(300);
    allIndOff();
    delay(300); 
  }
  Serial.println("DMIng booting");
  indicator(IND_RED, FLASH1);
}

void loop() {
  scanSelector();
  wifiStateMachine();
  dmiStateMachine();
  dmi();
  indication();
  flashInd();
  webServer();
  timing();
}

void dmi() {
  if (dmiClient.available()) { // FIXME ---- here or in dmiStateMachine
        Serial.print("OBU: ");
        Serial.println(dmiClient.readStringUntil('\n'));
  }
  switch (true) {
    case 'A': // Login accepted
    break;
    case 'R': // Login rejected
    break;
    case 'P':
    // reset connection timer
      dmiClient.print("P");
      dmiClient.print(modeSel);
      dmiClient.print(" ");
      dmiClient.print(dirSel);
      dmiClient.print(" ");
      dmiClient.println(driveSel);
    break;
    case 'S':
      dmiClient.print("S");
      dmiClient.print(batteryCharge());
      dmiClient.print("");
      dmiClient.print(PMIC.chargeStatus());
      dmiClient.print(" ");
      dmiClient.println(WiFi.RSSI(), 4);
    break;
  }
}

void dmiStateMachine() { // Controlling connection to OBU
  switch (dmiState) {
    case ST_DMI_BOOT:
      dmiRetryCount = 0;
      dmiState = ST_DMI_STDBY;
    break;
    case ST_DMI_STDBY:
      if (oprSel == OPR_OPR) {
        analogWrite(PIN_METER, 0);
        dmiState = ST_DMI_IDLE;
        break;
      }
      if (oprTimer < 0) {
        oprTimer = 1000; // FIXME move to configuration?
        switch (modeSel) {
          case MODE_SH:
            analogWrite(PIN_METER, batteryCharge() * 256 / 100);
          break;
          case MODE_SR:
            if (wifiState == ST_WIFI_AVAILABLE) {
              analogWrite(PIN_METER, map(constrain(WiFi.RSSI(), -90, -10), -90, -10, 0, 255));
            }
          break;
          default:
            analogWrite(PIN_METER, 0);
          break;
        }
      }
    break;
    case ST_DMI_IDLE: // Waiting for wifi
      if (oprSel == OPR_STDBY) {
//        dmiClient.println("O");
//        dmiClient.stop();
        dmiState = ST_DMI_STDBY;
        break;
      }
      if (wifiState = ST_WIFI_AVAILABLE) {
        dmiState = ST_DMI_TCP;
      }
    break;
    case ST_DMI_TCP:
      if (oprSel == OPR_STDBY) {
        Serial.println("O");
        dmiClient.println("O");
        dmiClient.stop();
        dmiState = ST_DMI_STDBY;
        break;
      }
      Serial.println("Opening TCP to OBU");
      cs = dmiClient.connect(obuAddr, OBU_PORT);
      timing(); // Refresh all timers as dmiClient.connect() blocks the loop while connecting to the server
      if (cs) {
        Serial.println("TCP to OBU established, logging in");
        dmiClient.setTimeout(500);
        dmiClient.println("L DMI1");
        dmiState = ST_DMI_LOGIN;
      } else {
        Serial.print("OBU not reachable, cs: ");
        Serial.print(cs);
        Serial.print(" wifi: ");
        Serial.println(WiFi.status());
        if (dmiRetryCount < MAX_DMI_RETRY) {
          dmiState = ST_DMI_TCP_WAIT;
          timer = TIMER_OBU_CONNECT;
        } else {
          Serial.print("TCP failed, Wifi,status(): ");
          Serial.println(WiFi.status());
          dmiRetryCount = 0;
          dmiState = ST_DMI_FAILED;
        }
      }
    break;
    case ST_DMI_TCP_WAIT:
      if (oprSel == OPR_STDBY) {
        Serial.println("Going stdby");
//        dmiClient.println("O");
//        dmiClient.stop();
        dmiState = ST_DMI_STDBY;
        break;
      }
      if (timer < 0) {
        Serial.println("timeout");
        dmiState = ST_DMI_TCP;
        dmiRetryCount++;
      }
    break;
    case ST_DMI_LOGIN:
      if (oprSel == OPR_STDBY) {
        dmiClient.println("O");
        dmiClient.stop();
        dmiState = ST_DMI_IDLE;
        break;
      }
      dmiClient.print("L ");
      dmiClient.println(DMI_ID);
      dmiState = ST_DMI_LOGIN_WAIT;
      
/*      if (dmiClient.available()) { // --------------------------------- here or in dmi()??
        switch (char c = dmiClient.read()) {  
          case 'A':
            dmiState = ST_DMI_RUN;
          break;
          case 'R':
            dmiState = ST_DMI_REJECTED;
            Serial.println("DMI rejected by OBU");
            dmiClient.stop();
          break;
          default:
            Serial.print("Unexpected code from OBU: ");
            Serial.println(c);
        }
        
      } */ 
    break;
    case ST_DMI_LOGIN_WAIT:
      // case "A" accept login or "R" reject login
    break;
    case ST_DMI_RUN:
      if (oprSel == OPR_STDBY) {
        Serial.println("Going stdby");
        dmiClient.println("O");
        dmiClient.stop();
        dmiState = ST_DMI_STDBY;
        break;
      }
      if (!dmiClient.connected()) {
        Serial.println();
        Serial.println("TCP disconnected.");
        dmiClient.stop();
        dmiState = ST_DMI_IDLE;
        dmiRetryCount = 0;
        break;
      }
    break;
    case ST_DMI_REJECTED:
      if (oprSel == OPR_STDBY) {
        Serial.println("Going stdby");
        dmiClient.println("O");
        dmiClient.stop();
        dmiState = ST_DMI_STDBY;
        break;
      }
    break;
    case ST_DMI_FAILED:
      if (oprSel == OPR_STDBY) {
        Serial.println("Going stdby");
        dmiClient.println("O");
        dmiClient.stop();
        dmiState = ST_DMI_STDBY;
        break;
      }
    break;
  }
}

void wifiStateMachine() { // Controlling connection to wifi AP
  if (oprSel == OPR_OFF) wifiState = ST_WIFI_GOTO_SLEEP;
  switch (wifiState) {
    case ST_WIFI_BOOT:
      wifiState = ST_WIFI_START;
      wifiRetryCount = 0;
      wifiConnectedCount = 0;
    break;
    case ST_WIFI_START: // Connect to WiFi
      Serial.println("Connecting to wifi");
      ws = WiFi.begin(ssid, pass);
      Serial.print("WifiBegin Status ");
      Serial.println(ws);
      switch (ws) {
        case WL_CONNECTED:
          wifiConnectedCount++;
          wifiState = ST_WIFI_AVAILABLE;
          wifiTimer = TIMER_CHECK_AVAIABILITY;
          myAddress = WiFi.localIP();
          server.begin();
          Serial.print("Wifi connected, local IP address: ");
          Serial.println(myAddress);
        break;
        default:
          WiFi.end();
          if (wifiRetryCount < MAX_WIFI_RETRY) {
            wifiState = ST_WIFI_WAIT;
            timer = TIMER_WIFI_RETRY;
            Serial.println("No wifi, waiting");
          } else {
            wifiState = ST_WIFI_FAILED;
            Serial.print("Wifi failed... ");
          }
        break;
      }
    break;
    case ST_WIFI_AVAILABLE:
      if (wifiTimer < 0) {
        wifiTimer = TIMER_CHECK_AVAIABILITY;
        if (ws = WiFi.status() != WL_CONNECTED) {
          Serial.print("Wifi lost: ");
          Serial.println(ws);
          wifiState = ST_WIFI_START;
        }
      }
    break;
    case ST_WIFI_WAIT:
      if (timer < 0) {
        wifiState = ST_WIFI_START;
        wifiRetryCount++;
      }
    break;
    case ST_WIFI_FAILED:
      // Do nothing
    break;
    case ST_WIFI_GOTO_SLEEP: // Good night
      Serial.println("Going to sleep");
      analogWrite(PIN_METER, 0);
      allIndOff(); delay(200); allIndOn(); delay(200); allIndOff();
// FIXME inform OBU and maintenace, then close all IP connectins
      WiFi.end();
      LowPower.deepSleep();
      Serial.println("Waking up");
      wifiState = ST_WIFI_BOOT; // At wakeup reboot
      dmiState = ST_DMI_BOOT;
    break;
    default: // FIXME
      Serial.print("Unknown wifiState ");
      Serial.println(wifiState);
      wifiState = ST_WIFI_FAILED;
  }
  if (wifiState != prevState) {
    Serial.print("wifiStateMachine: ");
    Serial.println(wifiState);
    prevState = wifiState;
  }
}

void indication() {
  switch (oprSel) {
    case OPR_STDBY:
      switch (wifiState) {
        case ST_WIFI_BOOT:
        case ST_WIFI_START:
        case ST_WIFI_WAIT:
          indicator(IND_WHITE, OFF); indicator(IND_RED_OPR, ON); indicator(IND_BLUE, OFF);
        break;
        case ST_WIFI_FAILED:
          indicator(IND_WHITE, OFF); indicator(IND_RED_OPR, OFF); indicator(IND_BLUE, FLASH5);
        break;
        case ST_WIFI_AVAILABLE:
            indicator(IND_WHITE, ON); indicator(IND_RED_OPR, OFF); indicator(IND_BLUE, OFF);
        break;
        case ST_WIFI_GOTO_SLEEP:
          indicator(IND_WHITE, OFF); indicator(IND_RED_OPR, OFF); indicator(IND_BLUE, OFF);
        break;
        case ST_WIFI_UDEF:
          indicator(IND_WHITE, ON); indicator(IND_RED_OPR, ON); indicator(IND_BLUE, ON);
        break;
      }
    break;
    case OPR_OPR:
      switch (wifiState) {
        case ST_WIFI_BOOT:
        case ST_WIFI_START:
        case ST_WIFI_WAIT:
          indicator(IND_WHITE, OFF); indicator(IND_RED_OPR, ON); indicator(IND_BLUE, OFF);
        break;
        case ST_WIFI_FAILED:
          indicator(IND_WHITE, OFF); indicator(IND_RED_OPR, OFF); indicator(IND_BLUE, FLASH5);
        break;
        case ST_WIFI_AVAILABLE:
          switch (dmiState) {
            case ST_DMI_BOOT:
              indicator(IND_WHITE, OFF); indicator(IND_RED_OPR, ON); indicator(IND_BLUE, OFF);
            break;
            case ST_DMI_TCP:
            case ST_DMI_TCP_WAIT:
            case ST_DMI_LOGIN:
              indicator(IND_WHITE, OFF); indicator(IND_RED_OPR, ON); indicator(IND_BLUE, OFF);
            break;
            case ST_DMI_RUN:
              indicator(IND_WHITE, ON); indicator(IND_RED_OPR, OFF); indicator(IND_BLUE, OFF);
            break;
            case ST_DMI_REJECTED:
              indicator(IND_WHITE, ON); indicator(IND_RED_OPR, OFF); indicator(IND_BLUE, ON);
            break;
            case ST_DMI_FAILED:
              indicator(IND_WHITE, OFF); indicator(IND_RED_OPR, OFF); indicator(IND_BLUE, FLASH5);
            break;
          }
        break;
        case ST_WIFI_GOTO_SLEEP:
          indicator(IND_WHITE, OFF); indicator(IND_RED_OPR, OFF); indicator(IND_BLUE, OFF);
        break;
        case ST_WIFI_UDEF:
          indicator(IND_WHITE, ON); indicator(IND_RED_OPR, ON); indicator(IND_BLUE, ON);
        break;
      }
    break;
    default:
    break;
  }
}

void webServer() {
  if (wifiState == ST_WIFI_AVAILABLE and (webClient = server.available())) {
    Serial.println("new webClient");           // print a message out the serial port
    String currentLine = "";                // make a String to hold incoming data from the client
    while (webClient.connected()) {            // loop while the client's connected
      if (webClient.available()) {             // if there's bytes to read from the client,
        char c = webClient.read();             // read a byte, then
//        Serial.write(c);                    // print it out the serial monitor
        if (c == '\n') {                    // if the byte is a newline character

          // if the current line is blank, you got two newline characters in a row.
          // that's the end of the client HTTP request, so send a response:
          if (currentLine.length() == 0) {

            // HTTP headers always start with a response code (e.g. HTTP/1.1 200 OK)
            // and a content-type so the client knows what's coming, then a blank line:
            webClient.println("HTTP/1.1 200 OK");
            webClient.println("Content-type:text/html");
            webClient.println();
           
            //create the buttons
//            webClient.print("Click <a href=\"/H\">here</a> turn the LED on<br>");
//            webClient.print("Click <a href=\"/L\">here</a> turn the LED off<br><br>");
            int VbatRaw = analogRead(ADC_BATTERY);
            float Vbat  = VbatRaw * BAT_ADC_FACTOR;
            byte charge = Vbat >= VBAT_FULL ? 100 :  (Vbat - VBAT_EMPTY) * BAT_C_PERCENT;
            webClient.print("<p>Vbat: ");
            webClient.print(Vbat);
            webClient.print(" / ");
            webClient.print(VbatRaw);
            webClient.print("<p>Charge: ");
            webClient.print(charge);
            webClient.print("<p>Opr ");
            webClient.println(oprSel);
            webClient.print("<p>Mode ");
            webClient.println(modeSel);
            webClient.print("<p>Dir ");
            webClient.println(dirSel);
            webClient.print("<p>Drive ");
            webClient.println(driveSel);
            webClient.print("<p>connectedCount ");
            webClient.println(wifiConnectedCount);
            webClient.print("<p>wifiRetryCount ");
            webClient.println(wifiRetryCount);
            webClient.print("<p>PMIC.getChargeFault: ");
            webClient.println(PMIC.getChargeFault()); 
            webClient.print("<p>PMIC.chargeStatus: ");
            webClient.println(PMIC.chargeStatus()); 
            webClient.print("<p>getInputVoltageLimit: ");
            webClient.println(PMIC.getInputVoltageLimit()); 
            webClient.print("<p>getInputCurrentLimit: ");
            webClient.println(PMIC.getInputCurrentLimit()); 
            webClient.print("<p>getMinimumSystemVoltage: ");
            webClient.println(PMIC.getMinimumSystemVoltage()); 
            webClient.print("<p>getChargeCurrent: ");
            webClient.println(PMIC.getChargeCurrent()); 
            webClient.print("<p>getPreChargeCurrent: ");
            webClient.println(PMIC.getPreChargeCurrent()); 
            webClient.print("<p>getTermChargeCurrent: ");
            webClient.println(PMIC.getTermChargeCurrent()); 
            webClient.print("<p>getChargeVoltage: ");
            webClient.println(PMIC.getChargeVoltage()); 
            webClient.print("<p>Uptime ");
            webClient.println(millis()); 
            webClient.println("<form method='post'><input type='submit' name='S' value='Opdater billede'></form>");
            webClient.println();           // The HTTP response ends with another blank line:
            // break out of the while loop:
            break;
          }
          else {      // if you got a newline, then clear currentLine:
            currentLine = "";
          }
        }
        else if (c != '\r') {    // if you got anything else but a carriage return character,
          currentLine += c;      // add it to the end of the currentLine
        }

/*        
        if (currentLine.endsWith("GET /H")) {
          writeRGB(255, 255, 255);
        }
        if (currentLine.endsWith("GET /L")) {
          writeRGB(0, 0, 0);
        }
*/
      }
    }
    // close the connection:
    webClient.stop();
    Serial.println("webClient disconnected");
  }
}

void scanSelector() {
  oprSel = (analogRead(PIN_OPERATION_SEL) + 1024) / 2048 + 1; // 1 - 3
  modeSel = (analogRead(PIN_MODE_SEL) + 512) / 1024 + 1; // 1 - 5  4096 / 4
  driveSel = (analogRead(PIN_DRIVE_SEL) + 341) / 682; // 0 - 6  4096 / 6
  dirSel = (analogRead(PIN_DIR_SEL) + 1024) / 2048 + 1; // 1 - 3  4096 / 2
}

void flashInd() {
  if (flash1timer < 0) {
    flash1timer = FLASH1TIME;
    flash1State = !flash1State;
    for (byte i = 0; i < 6; i++) if (indState[i] == FLASH1) digitalWrite(indPin[i], flash1State);
  }
  if (flash5timer < 0) {
    flash5timer = FLASH5TIME;
    flash5State = !flash5State;
    for (byte i = 0; i < 6; i++) if (indState[i] == FLASH5) digitalWrite(indPin[i], flash5State);
  }
}

void indicator(byte index, byte state) {
  indState[index] = state;
  switch (state) {
    case OFF:
      digitalWrite(indPin[index], LOW);
    break;
    case ON:
      digitalWrite(indPin[index], HIGH);
    break;
  }
}

void timing() {
  deltaMillis = 0; // clear last result
  thisMillis = millis();
  if (thisMillis != lastMillis) {
    deltaMillis = thisMillis - lastMillis; // note this works even if millis() has rolled over back to 0
    lastMillis = thisMillis;
    timer -=  deltaMillis;
    wifiTimer -=  deltaMillis;
    flash1timer -= deltaMillis;
    flash5timer -= deltaMillis;
    oprTimer -= deltaMillis;
  }
}

void allIndOn() {
  for (byte i = 0; i < 6; i++) {
    indState[i] = ON;
    digitalWrite(indPin[i], HIGH);
  }
}

void allIndOff() {
  for (byte i = 0; i < 6; i++) {
    indState[i] = OFF;
    digitalWrite(indPin[i], LOW);
  }
}

byte batteryCharge() { // battery charge in %
  float Vbat  = analogRead(ADC_BATTERY) * BAT_ADC_FACTOR;
  return Vbat >= VBAT_FULL ? 100 :  (Vbat - VBAT_EMPTY) * BAT_C_PERCENT;
}

void writeRGB(byte r, byte g, byte b) {
  WiFiDrv::analogWrite(25, r);  //RED
  WiFiDrv::analogWrite(26, g);  //Green
  WiFiDrv::analogWrite(27, b);  //BLUE
}
/*
 typedef enum {
 255    WL_NO_SHIELD = 255,
 255    WL_NO_MODULE = WL_NO_SHIELD,
   0    WL_IDLE_STATUS = 0,
   1    WL_NO_SSID_AVAIL,
   2    WL_SCAN_COMPLETED,
   3    WL_CONNECTED,
   4    WL_CONNECT_FAILED,
   5    WL_CONNECTION_LOST,
   6    WL_DISCONNECTED,
   7    WL_AP_LISTENING,
   8    WL_AP_CONNECTED,
   9    WL_AP_FAILED
} wl_status_t;
 
 */
