#include <JeeLib.h>
#include <avr/wdt.h>

#include "TrainConf.h" // Include specific train configuration

// OBU
// WinterTrain v. 3
// ------------------------------------------- Configuration
#define MAJOR_VERSION 6
#define MINOR_VERSION 0
// Proporties
#define DETECT_NOM_DIR DOWN // UP, DOWN or AUTO_DETECT
#define BRAKING_DISTANCE 18
#define SH_BREAKE_DIST 0
#define MAX_DRIVE 100
#define VMAX_SH 60
#define SHORT_MA 30
#define STOP_MA 3 // Distance below this will show red on DMI
#define V_OFFSET 30
#define MIN_DIST 2 // Distance below this will be ignored
const unsigned long C_SPEED = 2500000; // Conversion factor for speed
const byte drive[6] = {0, 0, 20, 40, 60, 100}; // driveSel: 1-5

// Timing
// Note: due to changed PWM freq. 1 sec ~ 64000 counts
const unsigned long DMI_POLL = 16000; // 0.25 sec
const unsigned long DMI_TIMEOUT = 128000; // 2 sec
const unsigned long POS_REP = 100000; // ~1.6 sec
const unsigned long DISTANCE = 64000; // 1 sec
const unsigned long MODE_TIMEOUT = 640000; // 10 sec
const unsigned long DYN = 3200; // 0.05 sec

// Enummerations and codes
// Nominel direction
#define DOWN 1
#define UP 2
#define STAND_STILL 3
#define AUTO_DETECT 4
// Driving direction
#define REVERSE 1
#define NEUTRAL 2
#define FORWARD 3
// Operational modes
#define SR 1
#define SH 2
#define FS 3
#define ATO 4
#define N 5
#define ESTOP 7
// Remote Take-over states
#define RTO_UDEF 0
#define RTO_DMI 1
#define RTO_PEND_REMOTE 2
#define RTO_REMOTE 3
#define RTO_PEND_RELEASE 4

// Indications
#define ON 1
#define OFF 0
#define FLASH 2
#define GREEN 0
#define YELLOW 1
#define RED 2
#define BLUE 3
#define RED2 4


// RF12
const byte RBC_ID = 10;
const byte BROADCAST = 0; // RF12 header for broadcast
const byte ID_MASK = 0x1F;
const byte GROUP = 101;

// RF12 Package type
#define POSREP 10;
#define MA_REQ 11;
#define OBU_POLL 20;
#define DMI 21;
#define MA_PACK 31
#define RTO_PACK 32
#define POSREST_PACK 33

// TAG reader, type 7941E
#define TR_START 1
#define TR_LENGTH 2
#define TR_TYPE 3
#define TR_TAGNO 4
#define TR_CHECK_SUM 5
#define TR_END 6

// -------------------------------------------- Type definition
// Data type for "stop if in shunting" balises
struct SAbaliseType {
  byte balise[5];
  int borderDist;
  //  byte borderDir; // In which nominel direction is the train approaching the border?
};

struct posRepType {
  byte type; // package destination
  byte balise[5];
  int distance; // signed distance from frontend to balise
  byte v; // Speed
  byte stat; // B0-B1: Driving direction, UP, DOWN, STAND_STILL
  byte rtoMode;
  //  int tripMeter;
};

// Balises
struct baliseType {
  byte balise[5];
  int curDist, MAposEOA;
  byte vMax;
  boolean MAvalid, MAreceived;
};

SAbaliseType SAbalises[N_SABALISES] = SA_BALISES;

// ----------------------------------------- Variables

// Timing
unsigned long lastMillis, deltaMillis, thisMillis, speedMillis;
long timerDMIPoll, timerDMITimeout, timerPosRep, timerDistance, timerModeTimeout;
long timerDyn;

// DMI and RTO selector
byte modeSel, dirSel, driveSel;
byte dirSelDMI;

// Dynamic values
byte rtoMode = RTO_DMI; // State for remote take-over of driver interface (DMI)
byte reqMode = ATO; // as requested by DMI selector
byte authorisedMode = N; // as authorised by RBC (if necessary)
byte authorisation; // from RBC
byte v, vReq, vMotor; // Actual speed, requested drive, command to motor
byte vMax = VMAX_SH; // Max speed in SH
byte nomDir; // nominel direction (UP or DOWN) of train front end (forward)
byte nomDirOrder ; // nominel driving direction as ordered by driver or ATO (UP, STOP, DOWN)
byte nomDriveDir, oldNomDriveDir; // Actual nominel driving direction, reflecting motor operation (UP, DOWN or STAND_STILL)
byte driveDir; // Actual driving direction, reflecting motor (FORWARD, REVERSE or NEUTRAL)
byte dirOrder; // Resulting direction order (FORWARD, REVERSE, NEUTRAL)
byte driveOrder; // Resulting driving order
byte curBalise; // index to most recently read balise
int distance; // Current distance (with sign) from train to EOA, given a valid MA
boolean positionUnknown = true; // Position is unknown until first balise is read
int tripMeter; //
byte MAindex; // index to knownBalises of current MA
byte driveLimit;
boolean atShuntBorder; // if the train is close to a shunt border; current balise is a ShuntBorder balise
boolean shuntBorderStop; // Train has reached shunting border. Stop if in shunting
int borderDist; // Distance from current balise to shunt border (with sign), if the current balise is a shuntBorder balise
boolean emergencyStop; // Prevent motor power if set

// Buffers and triggers
byte txDMI[4], baliseBuf[5], MAindication, MAindFlash;
boolean txDMIW = false, txPosRep = false, runInd, blnk, DMIlost;

boolean overrideSR = false; // Allow mode SR even if no authorization from RBC
boolean overrideSH = false; // Allow mode SH even if no authorization from RBC

posRepType posRep;
baliseType knownBalises[MAX_BALISES];


void setup() {
  TCCR0B = (TCCR0B & 0b11111000) | 0x01; // Set PWM freq for pin 5 to 62.5 kHz This is affecting delay() and millis() as 1 sec ~ 64000 counts
  rf12_initialize(OBU_ID, RF12_868MHZ, GROUP);
  wdt_enable(WDTO_4S);
  Serial.begin(9600); // for TAG reader
  pinMode(OBU_PIN_MOTOR, OUTPUT);
  pinMode(OBU_PIN_REVERSE_DIR, OUTPUT);
#ifdef OBU_PIN_BLUE
  pinMode(OBU_PIN_BLUE, OUTPUT);
  digitalWrite(OBU_PIN_BLUE, LOW);
#endif
#ifdef OBU_PIN_OVERRIDE
  pinMode(OBU_PIN_OVERRIDE, INPUT_PULLUP);
#endif
  pinMode(OBU_PIN_WHEEL, INPUT_PULLUP);
  pinMode(OBU_PIN_TRACK_UP, INPUT);
  pinMode(OBU_PIN_TRACK_DOWN, INPUT);
  analogWrite(OBU_PIN_MOTOR, 0);
  delay(50000); // to ensure stabel track voltage when determing nominel direction
#ifdef OBU_PIN_BLUE
  digitalWrite(OBU_PIN_BLUE, HIGH);
#endif
#ifdef OVERRIDE_SR
  overrideSR = !digitalRead(OBU_PIN_OVERRIDE);
#endif
#ifdef OVERRIDE_SH
  overrideSH = !digitalRead(OBU_PIN_OVERRIDE);
#endif
  nomDir = (DETECT_NOM_DIR == AUTO_DETECT ? (digitalRead(OBU_PIN_TRACK_UP) ? UP : DOWN) : DETECT_NOM_DIR);
  // FIXME nomDir to be determined by stable power polarity
  //  txDMI[0] = 22;
  //  txDMI[1] = MAJOR_VERSION;
  //  txDMI[2] = MINOR_VERSION;
  knownBalises[0].balise[0] = 1;
  knownBalises[0].balise[1] = 0;
  knownBalises[0].balise[2] = 0;
  knownBalises[0].balise[3] = 0;
  knownBalises[0].balise[4] = 1;
  speedMillis = lastMillis = millis();
}

void loop() {
  timing();
  odometry();

  if (timerDMIPoll <= 0) { // ---------------------------- Poll DMI
    timerDMIPoll = DMI_POLL;
    // Init packet buffer (due to buffer being cleared by rf12_sendStart)
    txDMI[0] = 21; // Packet type
    txDMI[1] = blnk ? MAindication & ~MAindFlash : MAindication; // MA indications
    txDMI[2] = v; // Speed indication
    txDMI[3] = vMotor > 0 ? vMotor + V_OFFSET : 0;
    txDMIW = true;
    blnk = !blnk;
  }

  //  if (timerDMITimeout <= 0) {// ------------------------------ DMI lost
  //    timerDMITimeout = DMI_TIMEOUT;
  //    modeSel = 0; dirSel = 0; driveSel = 0;
  //  }

  if (!DMIlost and timerModeTimeout <= 0) {// ------------------------------ DMI lost for long time
    DMIlost = true;
    authorisedMode = N;
    reqMode = ATO;
  }

  if (timerPosRep <= 0) { // Send position report
    timerPosRep += POS_REP;
    sendPosition();
  }

  if (timerDyn <= 0) { // Dynamic movement
    timerDyn += DYN;
    vDyn();
  }

  checkBalise();
  rf12Transceive();
  shuntBorder();
  opMode();
  dirMode();
  driveMode();
  traction();
  headLight();
  indication();
  wdt_reset();
}

void odometry() { // position and speed
  byte w;
  static byte oldW;
  w = digitalRead(OBU_PIN_WHEEL);
  //  digitalWrite(OBU_PIN_BLUE, authorisedMode == SR ? w : HIGH);
  if (w and !oldW) {
    switch (nomDriveDir) {
      case UP:
        for (byte b = 0; b < MAX_BALISES; b++) knownBalises[b].curDist++;
        tripMeter++;
        v = min(C_SPEED / (millis() - speedMillis), 255);
        speedMillis = millis();
        break;
      case DOWN:
        for (byte b = 0; b < MAX_BALISES; b++) knownBalises[b].curDist--;
        tripMeter--;
        v = min(C_SPEED / (millis() - speedMillis), 255);
        speedMillis = millis();
        break;
      case STAND_STILL:
        v = 0;
        break;
    }
  }
  oldW = w;
}

void indication() { // MA indications at DMI
  switch (authorisedMode)  {
    case ESTOP:
      ind(GREEN, OFF); ind(YELLOW, FLASH); ind(RED, FLASH);
      break;
    case N:
      ind(GREEN, OFF); ind(YELLOW, OFF); ind(RED, ON);
      break;
    case SR:
      ind(GREEN, ON); ind(YELLOW, OFF); ind(RED, ON);
      break;
    case SH:
      if (shuntBorderStop) {
        ind(GREEN, OFF); ind(YELLOW, OFF); ind(RED, ON);
      } else {
        ind(GREEN, OFF); ind(YELLOW, ON); ind(RED, OFF);
      }
      break;
    case FS:
      if (dirSel != NEUTRAL and dirSel == MAdir()) {
        if (abs(distance) > SHORT_MA) {
          ind(GREEN, ON); ind(YELLOW, OFF); ind(RED, OFF);
        } else if (abs(distance) > STOP_MA) {
          ind(YELLOW, (drive[driveSel] < driveLimit ? ON : FLASH)); ind(GREEN, OFF); ind(RED, OFF);
        } else {
          ind(YELLOW, OFF); ind(GREEN, OFF); ind(RED, ON);
        }
      } else { // no MA for selected direction
        ind(GREEN, OFF); ind(YELLOW, OFF); ind(RED, ON);
      }
      break;
    case ATO:
      ind(GREEN, OFF); ind(YELLOW, OFF); ind(RED, OFF);
      break;
  }
}

void opMode() {
  //  Serial.print(authorisedMode);
  //  Serial.println(" Mode");
  if (rtoMode == RTO_DMI) ind(RED2, OFF); // clear old indication
  if (DMIlost and rtoMode == RTO_DMI) {
    if (nomDriveDir == STAND_STILL and authorisation == ATO) {
      authorisedMode = ATO;
      authorisation = N;
    }
  } else if (modeSel != reqMode or authorisedMode == N) {
    //    Serial.println("switch");
    switch (modeSel) {
      case SR:
        reqMode = SR;
        if (nomDriveDir == STAND_STILL and dirSel == NEUTRAL and (authorisation == SR or overrideSR)) {
          //          Serial.println("SR");
          authorisedMode = SR;
          authorisation = N;
          ind(RED2, OFF);
        } else {
          authorisedMode = N;
          ind(RED2, ON);
        }
        knownBalises[MAindex].MAreceived = false;
        knownBalises[MAindex].MAvalid = false; // clear current MA
        break;
      case SH:
        reqMode = SH;
        if (nomDriveDir == STAND_STILL and dirSel == NEUTRAL and (authorisation == SH or overrideSH)) {
          authorisedMode = SH;
          authorisation = N;
          ind(RED2, OFF);
        } else {
          authorisedMode = N;
          ind(RED2, ON);
        }
        knownBalises[MAindex].MAreceived = false;
        knownBalises[MAindex].MAvalid = false; // Clear current MA
        break;
      case FS:
        reqMode = FS;
        if (nomDriveDir == STAND_STILL and dirSel == NEUTRAL and authorisation == FS) {
          authorisedMode = FS;
          authorisation = N;
          ind(RED2, OFF);
        } else {
          authorisedMode = N;
          ind(RED2, ON);
        }
        break;
      case ATO:
        reqMode = ATO;
        if (nomDriveDir == STAND_STILL and dirSel == NEUTRAL and authorisation == ATO) {
          authorisedMode = ATO;
          authorisation = N;
          ind(RED2, OFF);
        } else {
          authorisedMode = N;
          ind(RED2, ON);
        }
        break;
      case N:
        reqMode = N;
        authorisedMode = N;
        authorisation = N;
        ind(RED2, OFF);
        break;
    }
  }
  switch (rtoMode) { // active RTO is overruling indication of authorizasion mode
    case RTO_REMOTE:
      ind(RED2, FLASH);
      break;
    case RTO_PEND_RELEASE:  // while waiting for dirSel to be neutral
      ind(RED2, ON);
      break;
  }
}

boolean shuntBorder() {
  if (atShuntBorder) {
    if (borderDist >= 0) {
      shuntBorderStop = (nomDriveDir == UP) && (borderDist < knownBalises[curBalise].curDist);
    } else {
      shuntBorderStop = (nomDriveDir == DOWN) && (borderDist > knownBalises[curBalise].curDist);
    }
  } else {
    shuntBorderStop = false;;
  }
}

byte MAspeed() {
  if (knownBalises[MAindex].MAvalid) {
    distance = knownBalises[MAindex].MAposEOA - knownBalises[MAindex].curDist;
    if (abs(distance) > BRAKING_DISTANCE) {
      driveLimit = knownBalises[MAindex].vMax;
    } else {
      driveLimit = knownBalises[MAindex].vMax * abs(distance) / BRAKING_DISTANCE;
    }
    return driveLimit;
  }
  driveLimit = 0;
  return driveLimit;
}

byte MAdir() { // Which direction (FORWARD or REVERSE) is allowed
  if (knownBalises[MAindex].MAvalid and abs(distance) > MIN_DIST) {
    return distance > 0 ?
           (nomDir == UP ? FORWARD : REVERSE) :
           (nomDir == UP ? REVERSE : FORWARD);
  } else {
    return NEUTRAL;
  }
  // Allowed driving direction to be included in MA  ----------------------------------------------------------------- FIXME
}

byte driveMode() { // Compute driving Order (i.e. speed)
  switch (authorisedMode) {
    case N:
      driveOrder = 0;
      break;
    case SR:
      driveOrder = drive[driveSel];
      break;
    case SH:
      driveOrder = shuntBorderStop ? 0 : min(drive[driveSel], vMax);
      break;
    case FS:
      driveOrder = min(drive[driveSel], MAspeed());
      break;
    case ATO:
      driveOrder = MAspeed();
      break;
    default:
      driveOrder = 0;
  }
}

byte dirMode() { // compute direction Order
  switch (authorisedMode) {
    case N:
      dirOrder = NEUTRAL;
      break;
    case SR:
      switch (driveDir) { // actual driving direction
        case FORWARD:
          if (dirSel != FORWARD) dirOrder = NEUTRAL;
          break;
        case REVERSE:
          if (dirSel != REVERSE) dirOrder = NEUTRAL;
          break;
        case NEUTRAL:
          dirOrder = dirSel;
          break;
      }
      break;
    case SH:
      //      if (shuntBorder(dirSel)) {
      //        dirOrder = NEUTRAL;
      //      } else {
      switch (driveDir) {
        case FORWARD:
          if (dirSel != FORWARD) dirOrder = NEUTRAL;
          break;
        case REVERSE:
          if (dirSel != REVERSE) dirOrder = NEUTRAL;
          break;
        case NEUTRAL:
          dirOrder = dirSel;
          break;
      }
      //      }
      break;
    case FS:
      dirOrder = dirSel == MAdir() ? dirSel : NEUTRAL;
      if (dirSel == NEUTRAL) {
        knownBalises[MAindex].MAreceived = false;
        knownBalises[MAindex].MAvalid = false; // clear current MA
      }
      break;
    case ATO:
      dirOrder = MAdir();
      break;
    default:
      dirOrder = NEUTRAL;
  }
}

void headLight() {
  if (dirOrder == FORWARD or driveDir == FORWARD) { // Front
    digitalWrite(OBU_PIN_FLIGHT, HIGH);
    digitalWrite(OBU_PIN_RLIGHT, LOW);
  } else if (dirOrder == REVERSE or driveDir == REVERSE) { // Back
    digitalWrite(OBU_PIN_FLIGHT, LOW);
    digitalWrite(OBU_PIN_RLIGHT, HIGH);
  } else { // Off
    digitalWrite(OBU_PIN_FLIGHT, LOW);
    digitalWrite(OBU_PIN_RLIGHT, LOW);
  }
}

void traction() {
  switch (dirOrder) { // Executing dirOrder and driveOrder
    case REVERSE:
      digitalWrite(OBU_PIN_REVERSE_DIR, HIGH);
      driveDir = REVERSE;
      vReq = driveOrder;
      nomDirOrder = nomDriveDir = (nomDir == UP ? DOWN : UP);
      break;
    case NEUTRAL:
      vReq = 0;
      nomDirOrder = STAND_STILL;
      break;
    case FORWARD:
      digitalWrite(OBU_PIN_REVERSE_DIR, LOW);
      driveDir = FORWARD;
      vReq = driveOrder;
      nomDirOrder = nomDriveDir = (nomDir == UP ? UP : DOWN);
      break;
    default:
      vReq = 0;
  }
}

void vDyn() {
  if (vMotor != vReq) {
    if (vMotor > vReq) {
      vMotor--;
    } else {
      vMotor++;
    }
  }
  if (vMotor == 0) {
    nomDriveDir = STAND_STILL;
    driveDir = NEUTRAL;
    v = 0;
  }
  analogWrite(OBU_PIN_MOTOR, !emergencyStop and vMotor > 0 ? vMotor + V_OFFSET : 0);
}

void checkBalise() {
  byte i;
  boolean hit;
  if (readBalise()) {
    positionUnknown = false;
    for (i = 0; i < MAX_BALISES; i++) {
      hit = true;
      for (byte x = 0; x < 5; x++) {
        if (knownBalises[i].balise[x] != baliseBuf[x]) {
          hit = false;
          break;
        }
      }
      if (hit) break;
    }
    if (hit) { // update known balise
      knownBalises[i].curDist = 0;
      curBalise = i;
    } else { // fifo
      curBalise++;
      if (curBalise == MAX_BALISES) curBalise = 0; // next index
      for (byte b = 0; b < 5; b++) knownBalises[curBalise].balise[b] = baliseBuf[b];
      knownBalises[curBalise].curDist = 0;
      knownBalises[curBalise].MAreceived = false;
      knownBalises[curBalise].MAvalid = false;
    }
    sendPosition();
    // check if balise is a shunt border balise
    for (i = 0; i < N_SABALISES; i++) {
      hit = true;
      for (byte x = 0; x < 5; x++) {
        if (SAbalises[i].balise[x] != knownBalises[curBalise].balise[x]) {
          hit = false;
          break;
        }
      }
      if (hit) break;
    }
    if (hit) { // balise is at shunt border
      atShuntBorder = true;
      borderDist = SAbalises[i].borderDist;
      // digitalWrite(OBU_PIN_BLUE,LOW);
    } else {
      atShuntBorder = false;
      // digitalWrite(OBU_PIN_BLUE,HIGH);
    }
  }
}

void sendPosition() {
  //  Serial.println('P');
  posRep.type = POSREP;
  for (byte b = 0; b < 5; b++) posRep.balise[b] = knownBalises[curBalise].balise[b];
  posRep.distance = knownBalises[curBalise].curDist;
  posRep.v = vReq;
  if (reqMode == FS or reqMode == ATO) {
    posRep.stat = reqMode | nomDriveDir << 3 |
                  digitalRead(OBU_PIN_TRACK_UP) << 5 |
                  digitalRead(OBU_PIN_TRACK_DOWN) << 6 |
                  knownBalises[curBalise].MAreceived << 7;
  } else {
    posRep.stat = reqMode | nomDriveDir << 3 |
                  digitalRead(OBU_PIN_TRACK_UP) << 5 |
                  digitalRead(OBU_PIN_TRACK_DOWN) << 6 |
                  (reqMode == authorisedMode) << 7;
  }
  posRep.rtoMode = rtoMode;
  //  posRep.tripMeter = tripMeter;
  txPosRep = true;
}

void rf12Transceive() {
  static boolean hit; static int d; byte index;

  if (rf12_recvDone() and rf12_crc == 0) {
    switch (rf12_hdr & ID_MASK) { // sender
      case DMI_ID: // DMI command
        // if rd12_data[0] == 20
        dirSelDMI = (rf12_data[1] & 0b00011000) >> 3;
        if (dirSelDMI == 0) dirSelDMI = NEUTRAL;
        if (dirSelDMI == NEUTRAL and rtoMode == RTO_PEND_RELEASE) {
          rtoMode = RTO_DMI;
        }
        if (rtoMode == RTO_DMI) {
          modeSel = rf12_data[1] & 0b00000111;
          dirSel = dirSelDMI;
          driveSel = (rf12_data[1] & 0b11100000) >> 5;
          if (driveSel > 5) driveSel = 0;
        }
        timerDMITimeout = DMI_TIMEOUT;
        timerModeTimeout = MODE_TIMEOUT;
        DMIlost = false;
        //        digitalWrite(OBU_PIN_BLUE, LOW);
        break;
      case RBC_ID:
        switch (rf12_data[0]) { // packet type
          case MA_PACK:
            if (rf12_data[1] == OBU_ID) {
              authorisation = rf12_data[2] & 0x07; // authorized mode
              switch (authorisation) {
                case ESTOP:
                  emergencyStop = true;
                  break;
                case FS:
                case ATO:
                  emergencyStop = false;
                  if (rf12_data[3] | rf12_data[4] | rf12_data[5] | rf12_data[6] | rf12_data[7]) { // Balise ID  0:0:0:0:0 indicates a void MA
                    d =  word(rf12_data[9], rf12_data[8]);
                    for (index = 0; index < MAX_BALISES; index++) {
                      hit = true;
                      for (byte x = 0; x < 5; x++) {
                        if (knownBalises[index].balise[x] != rf12_data[x + 3]) {
                          hit = false;
                          break;
                        }
                      }
                      if (hit) break;
                    }
                    if (index < MAX_BALISES) { //  balise in MA is known
                      MAindex = index;
                      knownBalises[MAindex].MAposEOA = d;
                      knownBalises[MAindex].MAreceived = true;
                      knownBalises[MAindex].MAvalid = true;
                      knownBalises[MAindex].vMax = ( rf12_data[10] <= MAX_DRIVE ? rf12_data[10] : MAX_DRIVE);
                    } // else MA with unknown balise is ignored
                  } // else clear MA --------------------------------------------------- FIXME
                  break;
                case SR:
                case SH:
                  emergencyStop = false;
                  vMax = ( rf12_data[10] <= MAX_DRIVE ? rf12_data[10] : MAX_DRIVE);
                  break;
              }
            }
            break;
          case RTO_PACK: // Remte take-over
            if (rf12_data[1] == OBU_ID) {
              switch (rf12_data[2]) { // Signaler RTO request
                case 1: // Request take-over
                  if (rtoMode == RTO_DMI or rtoMode == RTO_PEND_RELEASE) {
                    rtoMode = RTO_REMOTE;
                    authorisedMode = N;
                    authorisation = N;
                  }
                  break;
                case 2: // release take-over
                  if (rtoMode == RTO_REMOTE) {
                    if (DMIlost) {
                      rtoMode = RTO_DMI;
                      authorisedMode = N;
                      reqMode = ATO;
                      authorisation = N;
                    } else if (dirSelDMI == NEUTRAL) {
                      rtoMode = RTO_DMI;
                      authorisedMode = N;
                      authorisation = N;
                    } else {
                      rtoMode = RTO_PEND_RELEASE;
                    }
                  }
                  break;
              }
              if (rtoMode == RTO_REMOTE) {
                modeSel = rf12_data[3] & 0b00000111;
                dirSel = (rf12_data[3] & 0b00011000) >> 3;
                if (dirSel == 0) dirSel = NEUTRAL;
                driveSel = (rf12_data[3] & 0b11100000) >> 5;
                if (driveSel > 5) driveSel = 0;
              }
            }
            break;
          case POSREST_PACK: // Position restore after reboot
            if (positionUnknown and rf12_data[1] == OBU_ID) {
              positionUnknown = false;
              for (byte b = 0; b < 5; b++) knownBalises[0].balise[b] = rf12_data[b + 2];
              knownBalises[0].curDist = word(rf12_data[8], rf12_data[7]);
              sendPosition();
            }
            break;
        } // switch packet type
        break;
    }
  }
  if (txDMIW and rf12_canSend()) {
    rf12_sendStart(BROADCAST, &txDMI, sizeof(txDMI));
    rf12_sendWait(0);
    txDMIW = false;
  } else if (txPosRep and rf12_canSend()) {
    rf12_sendStart(BROADCAST, &posRep, sizeof(posRep));
    rf12_sendWait(0);
    txPosRep = false;
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
          newBalise = not csError and (data[0] != 0) and (diff or (nomDriveDir != oldNomDriveDir)) ;
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
        newBalise = not csError and (data[0] != 0) and
                    (diff or (nomDriveDir != oldNomDriveDir)) ;
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
  oldNomDriveDir = nomDriveDir;
  return newBalise;
}

// ----------------------------------------------- utility
void timing() {
  deltaMillis = 0; // clear last result
  thisMillis = millis();
  if (thisMillis != lastMillis) {
    deltaMillis = thisMillis - lastMillis; // note this works even if millis() has rolled over back to 0
    lastMillis = thisMillis;
    timerDMIPoll -= deltaMillis;
    timerDMITimeout -= deltaMillis;
    timerModeTimeout -= deltaMillis;
    timerPosRep -= deltaMillis;
    timerDyn -= deltaMillis;
  }
}

void ind(byte color, byte value) {
  switch (value) {
    case OFF:
      MAindication &= ~(1 << color);
      MAindFlash &= ~(1 << color);
      break;
    case ON:
      MAindication |= (1 << color);
      MAindFlash &= ~(1 << color);
      break;
    case FLASH:
      MAindication |= (1 << color);
      MAindFlash |= (1 << color);
  }
}
