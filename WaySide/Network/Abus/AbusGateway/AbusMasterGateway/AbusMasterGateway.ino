// AbusMaster Gateway
// WinterTrain

#include <Wire.h>
#include <AbusMaster.h>

// Gateway
#define GATEWAY_I2C_ADDR 0x33

// Abus
#define ENABLE_PIN 2
#define  RECEIVE_TIMEOUT 10

#define MAXBUF 24     // I2C Rx and Tx buffer size. >= MAXABUSBUF + 1 (Abus return status) + 1 (i2c register)
#define MAXABUSBUF 20 // Abus buffer

// HW
#define RED_PIN 10     // Red LED is used to indicate Abus timeout and other errors
#define YELLOW_PIN 11
#define GREEN_PIN 12  // Green LED is used to indicate activity at the Abus
#define BLUE_PIN 13

// Objects
AbusMaster Abus(ENABLE_PIN);

// Varaibles
unsigned int addrError, complError, crcError, timeout, oflowError;
boolean doGateway;

byte reg, regIndex;
unsigned long ms;

byte i2cRxBuf[MAXBUF];
byte i2cTxBuf[MAXBUF];

void setup() {
  Wire.begin(GATEWAY_I2C_ADDR);
  Wire.onReceive(WireReceiver);
  Wire.onRequest(WireSender);
  pinMode(RED_PIN, OUTPUT);
  pinMode(YELLOW_PIN, OUTPUT);
  pinMode(GREEN_PIN, OUTPUT);
  pinMode(BLUE_PIN, OUTPUT);
  digitalWrite(RED_PIN, LOW);
  digitalWrite(YELLOW_PIN, LOW);
  digitalWrite(GREEN_PIN, LOW);
  digitalWrite(BLUE_PIN, LOW);
  for (byte b = 3; b < 10; b++) pinMode(b, INPUT_PULLUP); // Pin 0..2 are used by Abus HW
  delay(1000);
  Serial.begin(115200); // Abus
}

void loop() {
  gateway();
}

void gateway() {
  int res;
  if (doGateway) {
    // Prepare I2C Tx buffer with dummy Abus packet and status pending in case the I2C master reads register 101 before Abus slave has responded
    i2cTxBuf[0] = 8; // Abus pending
    for (byte b = 0; b < MAXABUSBUF; b++) {
      Abus.toS[b] = i2cRxBuf[b + 1];
      i2cTxBuf[b + 1] = 0xFF;
    }
    res = AbusPoll(Abus.toS[0], RECEIVE_TIMEOUT);
    if (res > 0) {
      i2cTxBuf[0] = 0; // Status OK  FIXME = res?
      for (byte b = 0; b < MAXABUSBUF; b++) i2cTxBuf[b + 1] = Abus.fromS[b];
    } else {
      i2cTxBuf[0] = -res; // Abus.send() provides negative return codes (which do not fit in one byte)
    }
    doGateway = false;
  }
}

void WireReceiver(int count) {
  byte b;
  for (b = 0; b < MAXBUF; b++) i2cRxBuf[b] = 0xFF;
  b = 0;
  while (Wire.available()) {
    i2cRxBuf[b] = Wire.read();
    if (b < MAXBUF) b++;
  }
  reg = i2cRxBuf[0]; // First byte received via I2C is the I2C register number, remaining is data written to this register
  regIndex = 0;
  switch (reg) {
    case 50: // Indicator RED
      digitalWrite(RED_PIN, i2cRxBuf[1]);
      break;
    case 51: // Indicator YELLOW
      digitalWrite(YELLOW_PIN, i2cRxBuf[1]);
      break;
    case 52: // Indicator GREEN
      digitalWrite(GREEN_PIN, i2cRxBuf[1]);
      break;
    case 53: // Indicator BLUE
      digitalWrite(BLUE_PIN, i2cRxBuf[1]);
      break;
    case 101: //                                                                       Gateway function
      doGateway = true;
      // For what ever reason Abus.send() is not working if called inside the WireReceiver function. Hence this is done in the main loop
      break;
    //                                                                                 Gateway status
    case 201: // Uptime in mS
      ms = millis();
      for (int i = 3; i >= 0; i--) { // Most significant byte first
        i2cTxBuf[i] = (byte) ms;
        ms = ms >> 8;
      }
      break;
    case 202: // Abus statistic
      i2cTxBuf[0] = highByte(addrError);
      i2cTxBuf[1] = lowByte(addrError);
      i2cTxBuf[2] = highByte(complError);
      i2cTxBuf[3] = lowByte(complError);
      i2cTxBuf[4] = highByte(crcError);
      i2cTxBuf[5] = lowByte(crcError);
      i2cTxBuf[6] = highByte(oflowError);
      i2cTxBuf[7] = lowByte(oflowError);
      i2cTxBuf[8] = highByte(timeout);
      i2cTxBuf[9] = lowByte(timeout);
      break;
  }
}

void WireSender() {
  switch (reg) {
    case 101: // Gateway
      Wire.write(i2cTxBuf[regIndex]);
      regIndex++;
      if (regIndex == MAXABUSBUF + 1) regIndex = 0;
      break;
    case 201: // Uptime
      Wire.write(i2cTxBuf[regIndex]);
      regIndex++;
      if (regIndex > 3) regIndex = 0;
      break;
    case 202: // Abus statistik
      Wire.write(i2cTxBuf[regIndex]);
      regIndex++;
      if (regIndex == 10) regIndex = 0;
      break;
    case 203:  // Digital read D0 .. D7
      Wire.write(PIND);
      break;
    case 204:  // Digital read D8 .. D13
      Wire.write(PINB);
      break;
    default:
      //      Wire.write(0xFF);
      break;
  }
}

void broadcast1() {

  AbusBroadcast();
}

int AbusPoll(byte address, int timeOut) { // Wrapper for Abus.send() to allow collecting transmission status
  digitalWrite(GREEN_PIN, HIGH);
  digitalWrite(RED_PIN, LOW);
  int res = Abus.Send(address, timeOut);
  if (res <= 0) {
    switch (res) {
      case -1: // Fejl i modtager eller sender adresse i svar fra slave
        addrError++;
        break;
      case -4: // Complement fejl
        complError++;
        break;
      case -5: // CRC fejl
        crcError++;
        break;
      case -6: // overflow
        oflowError++;
        break;
      case -7: // Timeout
        timeout++;
        break;
    }
    digitalWrite(RED_PIN, HIGH);
  }
  digitalWrite(GREEN_PIN, LOW);
  return res;
}

void AbusBroadcast() {
  digitalWrite(GREEN_PIN, HIGH);
  Abus.Broadcast();
  digitalWrite(GREEN_PIN, LOW);
};
