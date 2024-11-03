// WinterTrain - HW backend for OBU next generation
// OBU hardware type: Rail Bus, motor car and trailer

//#include <avr/wdt.h> FIXME
#include <Adafruit_NeoPixel.h>

//#define COMPILE_MOTOR_CAR
#define DEBUG_PRINT

#include "TrainConf.h" // Include specific train configuration 
#include <util/atomic.h>
#include <Wire.h>

// ----------------------------------------------------------------------------- General definitions
#define MAXBUF 24 // I2C buffer size FIXME

// Timing
// Note, motor car: Due to changed PWM freq. 1 sec ~ 64000 counts
#define TIMER_OBU_CONNECTION 320000
#define TIMER_FLASH 16000

// ----------------------------------------------------------------------------- Enummeration
// --------------- Common
#define SE_NO_EFFECT 0

// --------------- Motor car

// --------------- Trailer
// TAG reader, type 7941E
#define TR_START 1
#define TR_LENGTH 2
#define TR_TYPE 3
#define TR_TAGNO 4
#define TR_CHECK_SUM 5
#define TR_END 6

// Supply state
#define PWR_UDEF 0
#define PWR_OFF 1
#define PWR_UNSTABLE 2
#define PWR_STABLE 3

// UPS states
#define UPS_BOOT 0
#define UPS_UNSTABLE 1
#define UPS_CHARGING 2
#define UPS_STABLE_ON 3
#define UPS_UNSTABLE_ON 4
#define UPS_SHUTDOWN_ON 5
#define UPS_OFF 6


// ----------------------------------------------------------------------------- Variables
// --------------- Common
// Timing
unsigned long lastMillis, deltaMillis, thisMillis, uptime, timerPwrOn, timerPwrOff;
long timerOBUconnection, timerFlash, timerUps;

byte reg, order, regIndex;
boolean flash, offLine, wasOffLine, motorLimitation, i2cCollission, show, rightWheel, leftWheel, upsStateChanged;
byte whiteLightNormal = DEFAULT_WHITE_LIGHT_NORMAL, whiteLightBright = DEFAULT_WHITE_LIGHT_BRIGHT, redLight = DEFAULT_RED_LIGHT;
byte orderWhiteLight, orderRedLight, orderMotor, orderCabinLight, orderDriverCabinLight;
byte cabinLightRed = DEFAULT_CABIN_LIGHT_RED;
byte cabinLightGreen = DEFAULT_CABIN_LIGHT_GREEN;
byte cabinLightBlue = DEFAULT_CABIN_LIGHT_BLUE;
byte specialEffect, prevSpecialEffect;
byte upsState = UPS_BOOT, prevUpsState = UPS_BOOT, pwrState = PWR_UDEF, prevPwrState = PWR_OFF;

#ifdef COMPILE_MOTOR_CAR // ---------------- Motor car


#else // ----------------------------------- Trailer
byte i2cRxBuf[MAXBUF];
byte i2cTxBuf[MAXBUF];
volatile unsigned int tacometer;
unsigned int tacoReading;

byte baliseBuf[5];
boolean newBaliseRead, frontUp, frontDown;
#endif

Adafruit_NeoPixel strip = Adafruit_NeoPixel(7, PIN_CABIN_LIGHT, NEO_GRB + NEO_KHZ800);

void setup() {
//  wdt_enable(WDTO_1S); // FIXME necessary?
  pinMode(PIN_WHITE_LIGHT, OUTPUT);
  pinMode(PIN_RED_LIGHT, OUTPUT);
  pinMode(PIN_CABIN_LIGHT, OUTPUT);
  digitalWrite(PIN_WHITE_LIGHT, HIGH);
  digitalWrite(PIN_RED_LIGHT, HIGH);
  pinMode(PIN_INTERNAL_LED, OUTPUT);
#ifdef COMPILE_MOTOR_CAR // --------------------------------- Motor car
  TCCR0B = (TCCR0B & 0b11111000) | 0x01; // Set PWM freq for pin 5 to 62.5 kHz This is affecting delay() and millis() as 1 sec ~ 64000 counts
  pinMode(PIN_MOTOR, OUTPUT);
  pinMode(PIN_DIR_CONTROL, OUTPUT);
  digitalWrite(PIN_DIR_CONTROL, HIGH);
#else // ---------------------------------------------------- Trailer
  pinMode(PIN_UPS_ENABLE, OUTPUT);
  digitalWrite(PIN_UPS_ENABLE, LOW);
  pinMode(PIN_CAP_VOLTAGE, INPUT);
  pinMode(PIN_OBU_SHUTDOWN, OUTPUT);
  digitalWrite(PIN_OBU_SHUTDOWN, LOW);
  pinMode(PIN_RIGHT_WHEEL, INPUT_PULLUP); 
  pinMode(PIN_LEFT_WHEEL, INPUT_PULLUP); 
  pinMode(PIN_WHEEL, INPUT_PULLUP);
  attachInterrupt(digitalPinToInterrupt(PIN_WHEEL), countWheel, RISING);
  Wire.begin(I2C_ADDR);
  Wire.onReceive(WireReceiver);
  Wire.onRequest(WireSender);
#endif

  digitalWrite(PIN_INTERNAL_LED, HIGH);
  delay(200);
  digitalWrite(PIN_INTERNAL_LED, LOW);

  strip.begin();
  strip.show(); // Initialize all pixels to 'off'

  Serial.begin(9600); //
  Serial.println("RailCar");
}

void loop() {
  delay(200);
#ifdef COMPILE_MOTOR_CAR // -------------------- Motor Car
  readCommands();
#else // --------------------------------------- Trailer
  powerHandler();
  checkBaliseReader();
#endif

  offLineHandler();
  communicationHandler();
  specialEffects();
  timing();
}

void offLineHandler() {
  if (timerOBUconnection <= 0) {  // No OBU
    if (!offLine) {
      offLine = true;
      orderWhiteLight = 0;
      cmdWhiteLight(orderWhiteLight);
    }
    if (timerFlash < 0) {
      timerFlash = TIMER_FLASH;
      flash = !flash;
      orderRedLight = flash ? 255 : 0;
      cmdRedLight(orderRedLight);
    }
#ifdef COMPILE_MOTOR_CAR
    orderMotor = 0;
    cmdMotor(0);
#endif
  }
  if (wasOffLine) {
    wasOffLine = false;
    orderRedLight = 0;
    cmdRedLight(orderRedLight);
  }
}

void communicationHandler() {
unsigned int t;
  switch(reg) {
    case 0: // no action
    case 1: // Heart beat
    break;
    case 10: // Control light
      orderWhiteLight = order & 0x01 ? order & 0x02 ? whiteLightBright : whiteLightNormal : 0;
      orderRedLight = order & 0x04 ? redLight : 0;
      orderCabinLight = order & 0x08;
      orderDriverCabinLight = order & 0x10;
      cmdWhiteLight(orderWhiteLight);
      cmdRedLight(orderRedLight);
      cmdCabinLight();
    break;
    case 12: // Set cabin light intencity R
      cabinLightRed = order;
    break;
    case 13: // Set cabin light intencity G
      cabinLightGreen = order;
    break;
    case 14: // Set cabin light intencity B
      cabinLightBlue = order;
    break;
    case 15: // Set white light normal intencity
      whiteLightNormal = order;
    break;
    case 16: // Set white light bright intencity
      whiteLightBright = order;
    break;
    case 17: // Set red light intencity
      redLight = order;
    break;
    case 201:
      specialEffect = order;
    break;
#ifdef COMPILE_MOTOR_CAR // ---------------------------------------- Motor Car
    case 20: // Motor order
      if (order > MAX_MOTOR_PWM) {
        orderMotor = MAX_MOTOR_PWM;
        motorLimitation = true;        
      }else {
        orderMotor = order;
        motorLimitation = false;
      }
      cmdMotor(orderMotor);
    break;
    case 21: // Direction order
      switch (order) {
        case 1:
          if (orderMotor == 0) digitalWrite(PIN_DIR_CONTROL, DIR_CONTROL_FORWARD);
        break;
        case 2:
          if (orderMotor == 0) digitalWrite(PIN_DIR_CONTROL, DIR_CONTROL_REVERSE);
        break;
      }
    break;
#else // --------------------------------------------------------- Trailer
    case 50: // Read LRBG
      for (byte b = 0; b < 5; b++) {
        i2cTxBuf[b] = baliseBuf[b];
      }
      newBaliseRead = false; 
    break;
    case 51: // Read Tacometer
      t = getTacometer();
      i2cTxBuf[0] = lowByte(t);
      i2cTxBuf[1] = highByte(t);
    break;
    case 52: // Read Status
      i2cTxBuf[0] = pwrState & 0x03 | frontUp << 2 | frontDown << 3 | newBaliseRead << 4 | motorLimitation << 5 | i2cCollission << 6;
    break;
    case 55: // Read Uptime
      uptime = millis();
      for (byte b = 0; b < 4; b++) {
        i2cTxBuf[b] = uptime & 0x000000FF;
        uptime = uptime >> 8;
      }
    break;
    case 56: // Read Version
       i2cTxBuf[0] = lowByte(VERSION);
       i2cTxBuf[1] = highByte(VERSION);
    break;
    default:
      for (byte b = 0; b < 8; b++) {
        i2cRxBuf[b] = 0;
      }
    break;
#endif
  }
  reg = 0;
}

void cmdWhiteLight(byte level) {
  analogWrite(PIN_WHITE_LIGHT, 255 - level);
}

void cmdRedLight(byte level) {
  analogWrite(PIN_RED_LIGHT, 255 - level);
}

void cmdCabinLight() {
  if (specialEffect == SE_NO_EFFECT) {
    if (orderCabinLight) { // Cabin light
      for (byte l = 0; l < 4; l++) strip.setPixelColor(l,  strip.Color(cabinLightRed, cabinLightGreen, cabinLightBlue));
    } else {
      for (byte l = 0; l < 4; l++) strip.setPixelColor(l,  strip.Color(0, 0, 0));
    }
    if (orderDriverCabinLight) { // Driver cabin light
      strip.setPixelColor(4,  strip.Color(cabinLightRed, cabinLightGreen, cabinLightBlue));
    } else {
      strip.setPixelColor(4,  strip.Color(0, 0, 0));
    }
    strip.show();
  }
}

void specialEffects() {
  switch (specialEffect) {
    case SE_NO_EFFECT:
      if (specialEffect != prevSpecialEffect) cmdCabinLight();
    
    break;
    case 1:
      if (specialEffect != prevSpecialEffect) show = true;
      strip.setPixelColor(0,  strip.Color(255, 0, 0));
      strip.setPixelColor(1,  strip.Color(0, 255, 0));
      strip.setPixelColor(2,  strip.Color(0, 0, 255));
      strip.setPixelColor(3,  strip.Color(0, 100, 128));
      strip.setPixelColor(4,  strip.Color(100, 0, 128));
    break;
    case 2:
      if (specialEffect != prevSpecialEffect) show = true;
      strip.setPixelColor(0,  strip.Color(0, 0, 255));
      strip.setPixelColor(1,  strip.Color(0, 0, 255));
      strip.setPixelColor(2,  strip.Color(0, 0, 255));
      strip.setPixelColor(3,  strip.Color(0, 0, 255));
      strip.setPixelColor(4,  strip.Color(0, 0, 255));
    break;
    default:
    break;
  }
  if (show) {
    strip.show();
    show = false;
  }
  prevSpecialEffect = specialEffect;
}




#ifdef COMPILE_MOTOR_CAR // ------------------------------------------------------------------------------------------- Motor car

void readCommands() {
static byte cCount, cLength, tmpReg, tmpOrder;
  while (Serial.available()) {
    char c = Serial.read();
    switch (c) {
      case '\n':
        if (cCount == cLength) {
          reg = tmpReg;
          order = tmpOrder;
        }
        tmpOrder = 0;
        cCount = 0;
        timerOBUconnection = TIMER_OBU_CONNECTION;
        if (offLine) {
          offLine = false;
          wasOffLine = true;
        }
      break;
      case '0':
      case '1':
      case '2':
      case '3':
      case '4':
      case '5':
      case '6':
      case '7':
      case '8':
      case '9':
        tmpOrder = c - 48 + 16 * tmpOrder;
        cCount++;
      break;
      case 'a':
      case 'b':
      case 'c':
      case 'd':
      case 'e':
      case 'f':
        tmpOrder = c - 87 + 16 * tmpOrder;
        cCount++;
      break;      
      case 'A':
      case 'B':
      case 'C':
      case 'D':
      case 'E':
      case 'F':
        tmpOrder = c - 55 + 16 * tmpOrder;
        cCount++;
      break;      
      case 'h': // Heart beat
      case 'H':
        tmpReg = 1;
        cLength = 0;
      case 'l':
        tmpReg = 10;
        cLength = 2;
      break;
      case 'R':
        tmpReg = 17;
        cLength = 2;
      break;
      case 'w':
        tmpReg = 15;
        cLength = 2;
      break;
      case 'W':
        tmpReg = 16;
        cLength = 2;
      break;
      case 'x':
        tmpReg = 12;
        cLength = 2;
      break;
      case 'y':
        tmpReg = 13;
        cLength = 2;
      break;
      case 'z':
        tmpReg = 14;
        cLength = 2;
      break;
      case 'm':
        tmpReg = 20;
        cLength = 2;
      break;
      case 'r':
        tmpReg = 21;
        cLength = 2;
      break;
      case 's':
        tmpReg = 201;
        cLength = 2;
      break;
      default:
        cCount = 0;
        cLength = 0;
        tmpReg = 0;
        tmpOrder = 0;
      break;
    }
  }
}

void cmdMotor(byte level) {
  analogWrite(PIN_MOTOR, level);
}

#else // --------------------------------------------------------------------------------------------------- Trailer

void powerHandler() {
  rightWheel = digitalRead(PIN_RIGHT_WHEEL);
  leftWheel = digitalRead(PIN_LEFT_WHEEL);
  if (rightWheel and leftWheel) { // Power off
    timerPwrOn = 0;
  } else {
    timerPwrOff = 0;
  }
  if (timerPwrOn > T_ON_MIN) {
    pwrState = PWR_STABLE;
    frontUp = rightWheel;
    frontDown = leftWheel;
  } else if (timerPwrOff > T_OFF_MIN) {
    pwrState = PWR_OFF;
  } else {
    pwrState = PWR_UNSTABLE;
  }
#ifdef DEBUG_PRINT
  if (pwrState != prevPwrState) {
    switch (pwrState) {
      case PWR_UNSTABLE:
        Serial.println("pwrUnstable");
      break;
      case PWR_STABLE:
        Serial.println("pwrStable");
      break;
      case PWR_OFF:
        Serial.println("pwrOff");
      break;
    }
    prevPwrState = pwrState;
  }
#endif
Serial.println(analogRead(PIN_CAP_VOLTAGE));
  switch(upsState) {
    case UPS_BOOT:
      upsState = UPS_UNSTABLE;
#ifdef DEBUG_PRINT
  Serial.println("UPS_BOOT");
#endif
    break;
    case UPS_UNSTABLE:
      if (upsStateChanged) {
#ifdef DEBUG_PRINT
  Serial.println("UPS_UNSTABLE");
#endif
      }
      if (pwrState == PWR_STABLE) upsState = UPS_CHARGING;
    break;
    case UPS_CHARGING:
      if (upsStateChanged) {
#ifdef DEBUG_PRINT
  Serial.println("UPS_CHARGING");
#endif
      }
      if (pwrState == PWR_UNSTABLE) upsState = UPS_UNSTABLE;
      if (analogRead(PIN_CAP_VOLTAGE) > VCAP_80) upsState = UPS_STABLE_ON;
    break;
    case UPS_STABLE_ON:
      if (upsStateChanged) {
        digitalWrite(PIN_UPS_ENABLE, HIGH); // logic level??
#ifdef DEBUG_PRINT
  Serial.println("UPS_STABLE_ON");
#endif
      }
      if (pwrState == PWR_UNSTABLE) upsState = UPS_UNSTABLE_ON;
      if (analogRead(PIN_CAP_VOLTAGE) < VCAP_MIN) upsState = UPS_SHUTDOWN_ON;
    break;
    case UPS_UNSTABLE_ON:
      if (upsStateChanged) {
        digitalWrite(PIN_UPS_ENABLE, HIGH); // logic level??      nødvendig??
#ifdef DEBUG_PRINT
  Serial.println("UPS_UNSTABLE_ON");
#endif      
      }
      if (pwrState == PWR_STABLE) upsState = UPS_STABLE_ON;
      if (analogRead(PIN_CAP_VOLTAGE) < VCAP_MIN) upsState = UPS_SHUTDOWN_ON; // time out efter power off?
    break;
    case UPS_SHUTDOWN_ON:
      if (upsStateChanged) {
        digitalWrite(PIN_OBU_SHUTDOWN, HIGH); // logic level??
        timerUps = UPS_SHUTDOWN_TIME;
#ifdef DEBUG_PRINT
  Serial.println("UPS_SHUTDOWN_ON");
#endif
      }
      if (timerUps < 0) upsState = UPS_OFF;
    break;
    case UPS_OFF:
      if(upsStateChanged) {
        digitalWrite(PIN_UPS_ENABLE, LOW); // logic level??      
        digitalWrite(PIN_OBU_SHUTDOWN, LOW); // logic level??
        timerUps = UPS_WAIT_TIME;
#ifdef DEBUG_PRINT
  Serial.println("UPS_OFF");
#endif      
      }
      if (timerUps < 0) upsState = UPS_UNSTABLE;
    break;
  }
  upsStateChanged = (upsState != prevUpsState);
  prevUpsState = upsState;
}

void WireReceiver(int count) {
  timerOBUconnection = TIMER_OBU_CONNECTION;
  if (offLine) {
    offLine = false;
    wasOffLine = true;
  }
  byte b = 0;
  while (Wire.available()) {
    i2cRxBuf[b] = Wire.read();
    if (b < MAXBUF - 1) b++;
  }
  if (reg > 0) { // Previous register write not execurted - ignore write
    i2cCollission = true;
    digitalWrite(PIN_INTERNAL_LED, HIGH);
  } else {
    reg = i2cRxBuf[0]; // First byte received via I2C is the I2C register number, remaining is data written to this register
    order = i2cRxBuf[1];
    regIndex = 0;
  }  
}

void WireSender() {
  Wire.write(i2cTxBuf[regIndex]);
  regIndex++;
  if (regIndex == MAXBUF) regIndex = 0;  
}

void countWheel() {
  tacometer++;
}

unsigned getTacometer() {
  unsigned int t;
  ATOMIC_BLOCK(ATOMIC_RESTORESTATE) {
    t = tacometer;
  }
  return t;
}

void checkBaliseReader() {
  if (readBalise()) {
    newBaliseRead = true;
#ifdef DEBUG_PRINT
  Serial.println("BALISE READ");
#endif
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
#endif // -------------- Trailer


// ----------------------------------------------- utility
void timing() {
  deltaMillis = 0; // clear last result
  thisMillis = millis();
  if (thisMillis != lastMillis) {
    deltaMillis = thisMillis - lastMillis; // note this works even if millis() has rolled over back to 0
    lastMillis = thisMillis;
    timerOBUconnection -= deltaMillis;
    timerFlash -= deltaMillis;
#ifdef COMPILE_MOTOR_CAR
#else // -------------------------------- Trailer
  timerPwrOn += deltaMillis;
  timerPwrOff += deltaMillis;
  timerUps -= deltaMillis;
#endif
  }
}
