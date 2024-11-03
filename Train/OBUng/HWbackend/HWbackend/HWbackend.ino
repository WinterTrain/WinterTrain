// WinterTrain - HW backend for OBU next generation
// OBU hardware type: Locomotive

#include <Wire.h>
#include <avr/wdt.h>
#include "TrainConf.h" // Include specific train configuration 


#define MODULE_I2C_ADDR 0x3f
#define MAXBUF 24 // I2C buffer size  FIXME

// Enummeration
// TAG reader, type 7941E
#define TR_START 1
#define TR_LENGTH 2
#define TR_TYPE 3
#define TR_TAGNO 4
#define TR_CHECK_SUM 5
#define TR_END 6



// Timing
// Note: due to changed PWM freq. 1 sec ~ 64000 counts
// const unsigned long DMI_POLL = 16000; // 0.25 sec
#define TIMER_OBU_CONNECTION 32000

// ----------------------------------------- Variables

// Timing
unsigned long lastMillis, deltaMillis, thisMillis;
long timerOBUconnection;

byte i2cRxBuf[MAXBUF];
byte i2cTxBuf[MAXBUF];
byte reg, regIndex;

byte baliseBuf[5];

byte dirOrder, motorOrder;

void setup() {
  TCCR0B = (TCCR0B & 0b11111000) | 0x01; // Set PWM freq for pin 5 to 62.5 kHz This is affecting delay() and millis() as 1 sec ~ 64000 counts

  wdt_enable(WDTO_1S); // FIXME necessary?
  
  Wire.begin(MODULE_I2C_ADDR);
  Wire.onReceive(WireReceiver);
  Wire.onRequest(WireSender);

}

void loop() {
  readBalise();
}

void WireReceiver(int count) {
  byte b;
//  for (b = 0; b < MAXBUF; b++) i2cRxBuf[b] = 0xFF;
  timerOBUconnection = TIMER_OBU_CONNECTION;
  b = 0;
  while (Wire.available()) {
    i2cRxBuf[b] = Wire.read();
    if (b < MAXBUF) b++;
  }
  reg = i2cRxBuf[0]; // First byte received via I2C is the I2C register number, remaining is data written to this register
  regIndex = 0;
  switch (reg) {
    case 1: // Heart beat
    break;
    case 2: // DirectionOrder
      dirOrder = i2cRxBuf[1];
    break;
    case 3: // MotorOrder
      motorOrder = i2cRxBuf[1];
    break;
    case 4: // Headlight
      digitalWrite(OBU_PIN_FLIGHT, i2cRxBuf[1] && 0x01);
      digitalWrite(OBU_PIN_RLIGHT, i2cRxBuf[1] && 0x02);
    break;
  }
}

void WireSender() {
  switch (reg) {
    case 10: // LRBG
      Wire.write(baliseBuf[regIndex]);
      regIndex++;
      if (regIndex > 5) regIndex = 0;
    break;
  }
}


boolean readBalise() {
  static boolean newBalise, diff, csError;
  static byte i = 255, s;
  static int data[6] = {0, 0, 0, 0, 0, 0}; // Includes space for checksum
  static byte d, cs;
  static char txt[3] = "  ";
  static byte trState = TR_START;

  newBalise = false;

#ifdef TAG_READER_7941E
  if (Serial.available()) {
    d = Serial.read();
    switch (trState) {
      case TR_START:
        if (d == 2) {
          cs = 0; diff = false; i = 0;
          trState = TR_LENGTH;
        }
        break;
      case TR_LENGTH:
        if (d == 10) { // TAG number of the used TAGs are always 5 byte => packet length of 10 bytes
          cs = cs ^ d;
          trState = TR_TYPE;
        } else {
          trState = TR_START;
        }
        break;
      case TR_TYPE:
        cs = cs ^ d; // ignore card/TAG type
        trState = TR_TAGNO;
        break;
      case TR_TAGNO:
        cs = cs ^ d;
        data[i] = d;
        diff = diff or (baliseBuf[i] != d);
        i += 1;
        if (i == 5) trState = TR_CHECK_SUM;
        break;
      case TR_CHECK_SUM:
        csError = (cs ^ d);
        trState = TR_END;
        break;
      case TR_END:
        if (d == 3) { // packet end reached
          newBalise = not csError and (data[0] != 0) and diff ; //                           FIXME or (nomDriveDir != oldNomDriveDir)
          if (newBalise) {
            for (s = 0; s < 5; s++) {
              baliseBuf[s] = data[s];
            }
          }
        } // else packet terminated incorectly; discard packet.
        trState = TR_START; // wait for new packet
        break;
    }
  }
#endif

#ifdef TAG_READER_RDM6300
  if (Serial.available()) {
    d = Serial.read();
    switch (d) {
      case 2: // Package start
        i = 0; s = 0; diff = false; csError = false;
        break;
      case 3: // Package end
        cs = 0;
        for (s = 0; s < 5; s++) {
          cs = cs ^ data[s];
        }
        csError = (cs != data[5]);
        newBalise = not csError and (data[0] != 0) and diff;  //                     FIXME   or (nomDriveDir != oldNomDriveDir)
        if (newBalise) {
          for (s = 0; s < 5; s++) {
            baliseBuf[s] = data[s];
          }
        }
        i = 255;
        break;
      default:
        if (i < 6) { // Process payload
          txt[s] = d;
          s++;
          if (s == 2) {
            sscanf(txt, "%x", &data[i]);
            if (i < 5) diff = diff or (baliseBuf[i] != data[i]); // index 5, checksum not to be compared
            s = 0;
            i++;
          }
        } // else error, too long payload
    }
  }
#endif
  return newBalise;
}

// ----------------------------------------------- utility
void timing() {
  deltaMillis = 0; // clear last result
  thisMillis = millis();
  if (thisMillis != lastMillis) {
    deltaMillis = thisMillis - lastMillis; // note this works even if millis() has rolled over back to 0
    lastMillis = thisMillis;
    timerOBUconnection -= deltaMillis;
  
  }
}
