#include <JeeLib.h>

#include "TrainConf.h" // Include specific train configuration

// RF12
const byte BROADCAST = 0; // RF12 header for broadcast
const byte ID_MASK = 0x1F;
const byte GROUP = 101;

// RF12 Package type
const byte DMI = 21; // Specific for this DMI

const int OBU_TIMEOUT = 1000; // Timeout for lost connection to OBU
const unsigned long DYN = 20;

// Timing
unsigned long lastMillis, deltaMillis, thisMillis;
long timerOBUTimeout, timerDyn;

byte modeSel, dirSel, driveSel, vMeter, vReq;
boolean txWaiting = false;
byte txQ[10] = {0, 0, 0, 0, 0, 0, 0, 0, 0, 0};

/*
char* m[] = {"(DMIoff)", "OBUoff", "SR", "FS", "ATO"};
char* r[] = {"Udef", "Rev", "Neut", "Forw"};
char* d[] = {"Udef", "Br2", "Br1", "Neut", "Dr1", "Dr2"};
*/

void setup() {
  Serial.begin(115200);
  Serial.println("DMI 02P02");
  pinMode(DMI_PIN_BLUE, OUTPUT);
  pinMode(DMI_PIN_RED, OUTPUT);
  pinMode(DMI_PIN_RED2, OUTPUT);
  pinMode(DMI_PIN_YELLOW, OUTPUT);
  pinMode(DMI_PIN_GREEN, OUTPUT);
  pinMode(DMI_PIN_METER, OUTPUT);
  pinMode(DMI_PIN_MODE_SEL, INPUT);
  pinMode(DMI_PIN_DRIVE_SEL, INPUT);
  pinMode(DMI_PIN_DIR_SEL, INPUT_PULLUP);
  rf12_initialize(DMI_ID, RF12_868MHZ, GROUP);
  
  digitalWrite(DMI_PIN_BLUE, HIGH);
  digitalWrite(DMI_PIN_RED2, HIGH);
  digitalWrite(DMI_PIN_RED, HIGH);
  digitalWrite(DMI_PIN_YELLOW, HIGH);
  digitalWrite(DMI_PIN_GREEN, HIGH);
  delay(300);
  digitalWrite(DMI_PIN_BLUE, LOW);
  digitalWrite(DMI_PIN_RED2, LOW);
  digitalWrite(DMI_PIN_RED, LOW);
  digitalWrite(DMI_PIN_YELLOW, LOW);
  digitalWrite(DMI_PIN_GREEN, LOW);
  delay(300);
  digitalWrite(DMI_PIN_BLUE, HIGH);
  digitalWrite(DMI_PIN_RED2, HIGH);
  digitalWrite(DMI_PIN_RED, HIGH);
  digitalWrite(DMI_PIN_YELLOW, HIGH);
  digitalWrite(DMI_PIN_GREEN, HIGH);
  delay(300);
  digitalWrite(DMI_PIN_BLUE, LOW);
  digitalWrite(DMI_PIN_RED2, LOW);
  digitalWrite(DMI_PIN_RED, LOW);
  digitalWrite(DMI_PIN_YELLOW, LOW);
  digitalWrite(DMI_PIN_GREEN, LOW);
  delay(300);  lastMillis = millis();
}

void loop() {
  timerOBUTimeout -= deltaMillis;
  if (timerOBUTimeout < 100) {
    digitalWrite(DMI_PIN_BLUE,LOW);
  }
  if (timerOBUTimeout <= 0) {
    timerOBUTimeout += OBU_TIMEOUT;
    analogWrite(DMI_PIN_METER, 0);
    digitalWrite(DMI_PIN_BLUE, HIGH);
  }
  timerDyn -= deltaMillis;
  if (timerDyn <= 0) { // Dynamic meter
    timerDyn += DYN;
    meterDyn();
  }
  
  rf12Transceive();
  timing();
}

void sendCmd() {
  modeSel = ((analogRead(DMI_PIN_MODE_SEL) >> 4) + 24) / 16 - 1;
  driveSel = ((analogRead(DMI_PIN_DRIVE_SEL) >> 4) + 24) / 16;
  dirSel = ((analogRead(DMI_PIN_DIR_SEL) >> 5) + 24) / 16;
  txQ[0] = OBU_ID;
  txQ[1] = modeSel | dirSel << 3 | driveSel << 5;
  txWaiting = true;
}

void rf12Transceive() {
  if (rf12_recvDone() and rf12_crc == 0 and
      (rf12_hdr & ID_MASK) == OBU_ID and rf12_data[0] == DMI) {
    vReq = rf12_data[2];
    digitalWrite(DMI_PIN_RED2, rf12_data[1] & 0x10);
    digitalWrite(DMI_PIN_RED, rf12_data[1] & 0x04);
    digitalWrite(DMI_PIN_YELLOW, rf12_data[1] & 0x02);
    digitalWrite(DMI_PIN_GREEN, rf12_data[1] & 0x01);
    sendCmd();
    timerOBUTimeout = OBU_TIMEOUT;
    digitalWrite(DMI_PIN_BLUE, LOW);
  }
  if (txWaiting and rf12_canSend()) {
    rf12_sendStart(BROADCAST, &txQ, 2);
    rf12_sendWait(0);
    txWaiting = false;
  }
}


void meterDyn() {
  if (vMeter != vReq) {
    if (vMeter > vReq) {
      vMeter--;
    } else {
      vMeter++;
    }
  }
  analogWrite(DMI_PIN_METER, vMeter);
}


void timing() {
  deltaMillis = 0; // clear last result
  thisMillis = millis();
  if (thisMillis != lastMillis) {
    deltaMillis = thisMillis - lastMillis; // note this works even if millis() has rolled over back to 0
    lastMillis = thisMillis;
  }
}
