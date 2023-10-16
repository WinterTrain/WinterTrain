// -------------------------------------------
// WinterTrain Element Controller (EC)


#include <AbusSlave.h>
#include <JeeLib.h>
#include <avr/wdt.h>

#include "HW_Conf.h"

// -------------------------------------------------------------------------------------- Type
struct elementType {
  byte type;
  byte deviceMajor;
  byte deviceMinor;
  byte order;
  byte state;
  byte cStatus;
  long timer;
};

// -------------------------------------------------------------------------------------- Ennumeration

// Orders
#define S_STOP 31     // Signal
#define S_PROCEED 32
#define S_PROCEEDPROCEED 33
#define R_PASS 41     // Road signal
#define R_STOP 42
#define P_RIGHT 11    // Point machine
#define P_LEFT 12
#define P_RIGHT_HOLD 13
#define P_LEFT_HOLD 14
#define P_RELEASE 19
#define L_ACTIVATE 21 // LX
#define L_DEACTIVATE 22

// Status
#define UNSUPERVISED 0
#define I_STOP 1
#define I_PROCEED 2
#define I_PROCEEDPROCEED 3
#define I_PASS 2
#define I_CLOSED 1
#define I_OPEN 2
#define I_MOVING_UP 3
#define I_MOVING_DOWN 4
#define I_RIGHT 1
#define I_LEFT 2
#define I_RIGHT_HOLDING 3
#define I_LEFT_HOLDING 4
#define I_U_RIGHT 5
#define I_U_LEFT 6
#define I_U_RIGHT_HOLDING 7
#define I_U_LEFT_HOLDING 8

// State machine
//   Point machine
#define PM_IDLE 0
#define PM_POL_RIGHT 1
#define PM_THROW_RIGHT 2
#define PM_POL_RIGHT_DONE 3
#define PM_THROW_LEFT 4
//   Semafore signal
#define SE_STOP 0
#define SE_CHANGE_PROCEED 1
#define SE_PROCEED 2
#define SE_POL_STOP 3
#define SE_CHANGE_STOP 4
#define SE_POL_STOP_DONE 5

// Radio packet type
#define POSREP 10
#define MA_REQ
// Abus packet type

// -------------------------------------------------------------------------------------- Timer constant
#define CLOSE_TIMER 10000
#define FLASH_TIMER 500
#define OBU_TIMEOUT 5000
#define LX_MOVING 12000
#define PM_THROW_TIME 600
#define PM_THROW_HOLD_TIME 30000
#define PM_POL_TIME 200

// -------------------------------------------------------------------------------------- Variable
const byte UdevicePin[N_UDEVICE] = UDEVICE_PIN;
const byte PdeviceOnMask[N_PDEVICE] = PDEVICE_ON_MASK;
const byte PdevicePolMask[N_PDEVICE] = PDEVICE_POL_MASK;
const byte PdeviceReg[N_PDEVICE] = PDEVICE_REG;
const byte LdeviceOnMask[N_LDEVICE] = LDEVICE_ON_MASK;
const byte LdeviceReg[N_LDEVICE] LDEVICE_REG;

unsigned long lastMillis, deltaMillis, thisMillis; // Timing
void AbusRecBroadcast();
boolean AbusRecThis();
boolean blink, flash;
boolean txMAWaiting, txModeWaiting, posRepValid[N_TRAIN];
byte lc;

byte MAPack[12], posPack[N_TRAIN][12]; // RF12 packets
byte nTrain; // number of known trains

long flashTimer, timerOBUTimeout[N_TRAIN];

AbusSlave Abus(SLAVE_ADDRESS, TXENABLE_PIN, RECEIVE_TIMEOUT, AbusRecBroadcast, AbusRecThis);

byte devReg[N_PREG + N_LREG]; // mirror for shift registers for L and P devices
byte nextElement = 0; // Index of next unused element descriptor; equals number of configured elements
elementType element[N_ELEMENT];

void setup() {
  Serial.begin(115200);
  pinMode(CLK, OUTPUT);
  pinMode(DATA, OUTPUT);
  pinMode(STROBE, OUTPUT);
#ifdef BLINK
  pinMode(BLINK, OUTPUT);
#endif
#ifdef RADIO_LINK_ID
  rf12_initialize(RADIO_LINK_ID, RF12_868MHZ, GROUP);
#endif
  wdt_enable(WDTO_2S);
  initEC();
  lastMillis = millis();
}

void loop() {
  timing();
  checkTimer();
  rf12Transceive();
  Abus.poll();
  updateLPdevice();
  lc++;
#ifdef BLINK
  if (lc == 5) {
    lc = 0;
    blink = !blink;
    digitalWrite(BLINK, blink);
  }
#endif
  wdt_reset();
}


void initEC() { // Initialize EC
  // Reset all device
  for (byte b = 0; b < N_LREG + N_PREG; b++) {
    devReg[b] = 0;
    shiftOut(DATA, CLK, MSBFIRST, 0);
  }
  pulse(STROBE);
  for (byte b = 0; b < sizeof(UdevicePin); b++) {
    pinMode(UdevicePin[b], INPUT);
  }
  // Data
  nextElement = 0;
}

void checkTimer() {
#ifdef RADIO_LINK_ID
  for (byte i = 0; i < nTrain; i++) {
    timerOBUTimeout[i] -= deltaMillis;
    if (timerOBUTimeout[i] <= 0) {// OBU lost
      timerOBUTimeout[i] = OBU_TIMEOUT;
      posRepValid[i] = false;
    }
  }
#endif
  // ------------------------------------------- Flashing device
  flashTimer -= deltaMillis;
  if (flashTimer < 0) {
    flashTimer = FLASH_TIMER;
    flash = !flash;
    for (byte index = 0; index < nextElement; index++) {
      switch (element[index].type) {
        case 30: // Road signal
          if (element[index].order == R_STOP) {
            Ldevice(element[index].deviceMajor, flash);
          }
          break;
        case 31: // Road signal
          if (element[index].order == R_STOP) {
            Udevice(element[index].deviceMajor, flash);
          }
          break;
        case 42: // Signal, 3 aspect, 2 L-devices
          if (element[index].order == S_PROCEEDPROCEED) {
            Ldevice(element[index].deviceMajor + 1, flash);
          }
          break;
        case 43: // Signal, 3 aspect, 2 U-devices
          if (element[index].order == S_PROCEEDPROCEED) {
            Udevice(element[index].deviceMajor + 1, flash);
          }
          break;
      }
    }
  }

  // ------------------------------------------------------- Cancel signals due to missing order, control LX, control PM, control semaphore signal
  for (byte index = 0; index < nextElement; index++) {
    element[index].timer -= deltaMillis;
    if (element[index].timer < 0) {
      switch (element[index].type) {
        case 10: // point machine w/o position detector
          switch (element[index].state) {
            case PM_POL_RIGHT: // polarity relay have been activated shortly so activate throw relay
              Pdevice(element[index].deviceMajor, HIGH, HIGH);
              element[index].state = PM_THROW_RIGHT;
              element[index].timer = (element[index].order == P_RIGHT_HOLD ? PM_THROW_HOLD_TIME : PM_THROW_TIME);
              break;
            case PM_THROW_RIGHT:
              Pdevice(element[index].deviceMajor, HIGH, LOW);
              element[index].state = PM_POL_RIGHT_DONE;
              element[index].timer = PM_POL_TIME;
              element[index].cStatus = I_U_RIGHT;
              break;
            case PM_POL_RIGHT_DONE:
              Pdevice(element[index].deviceMajor, LOW, LOW);
              element[index].state = PM_IDLE;
              element[index].timer = 0;
              break;
            case PM_THROW_LEFT:
              Pdevice(element[index].deviceMajor, LOW, LOW);
              element[index].state = PM_IDLE;
              element[index].timer = 0;
              element[index].cStatus = I_U_LEFT;
              break;
          }
          break;
        case 21: // semaphore signal
          switch (element[index].state) {
            case SE_POL_STOP: // activate polarity relay shortly before throw relay
              Pdevice(element[index].deviceMajor, HIGH, HIGH);
              element[index].state = SE_CHANGE_STOP;
              element[index].timer = PM_THROW_TIME;
              element[index].cStatus = I_STOP;
              break;
            case SE_CHANGE_STOP:
              Pdevice(element[index].deviceMajor, HIGH, LOW);
              element[index].state = SE_POL_STOP_DONE;
              element[index].timer = PM_POL_TIME;
              break;
            case SE_POL_STOP_DONE:
              Pdevice(element[index].deviceMajor, LOW, LOW);
              element[index].state = SE_STOP;
              element[index].timer = 0;
              break;
            case SE_CHANGE_PROCEED:
              Pdevice(element[index].deviceMajor, LOW, LOW);
              element[index].state = SE_PROCEED;
              element[index].timer = CLOSE_TIMER;
              element[index].cStatus = I_PROCEED;
              break;
            case SE_PROCEED:
              Pdevice(element[index].deviceMajor, HIGH, LOW);
              element[index].state = SE_POL_STOP;
              element[index].timer = PM_POL_TIME;
              element[index].cStatus = I_STOP;
              break;
          }
          break;
        case 40: // signal 2 aspect, 2 L-device
        case 42: // signal 3 aspect, 2 L-device
          Ldevice(element[index].deviceMajor, HIGH);
          Ldevice(element[index].deviceMajor + 1, LOW);
          element[index].order = S_STOP;
          element[index].cStatus = I_STOP;
          break;
        case 44: // signal 3 aspect, 3 L-device
          Ldevice(element[index].deviceMajor, HIGH);
          Ldevice(element[index].deviceMajor + 1, LOW);
          Ldevice(element[index].deviceMajor + 2, LOW);
          element[index].order = S_STOP;
          element[index].cStatus = I_STOP;
          break;
        case 41: // signal 1 U-device
          Udevice(element[index].deviceMajor, LOW);
          element[index].order = S_STOP;
          element[index].cStatus = I_STOP;
          break;
        case 43: // signal 3 aspect, 2 U-device
          Udevice(element[index].deviceMajor, HIGH);
          Udevice(element[index].deviceMajor + 1, LOW);
          element[index].order = S_STOP;
          element[index].cStatus = I_STOP;
          break;
        case 45: // signal 3 aspect, 3 U-device
          Udevice(element[index].deviceMajor, HIGH);
          Udevice(element[index].deviceMajor + 1, LOW);
          Udevice(element[index].deviceMajor + 2, LOW);
          element[index].order = S_STOP;
          element[index].cStatus = I_STOP;
          break;
        case 32: // road barrier
          switch (element[index].cStatus) {
            case I_MOVING_DOWN:
              Pdevice(element[index].deviceMajor, LOW, LOW);
              element[index].cStatus = I_CLOSED;
              break;
            case I_MOVING_UP:
              Pdevice(element[index].deviceMajor, LOW, LOW);
              element[index].cStatus = I_OPEN;
              break;
          }
          break;
        default:
          ;
      }
    }
  }
}

// --------------------------------------------------------------------------------- RF12 radio
void rf12Transceive() {
#ifdef RADIO_LINK_ID
  byte index, trainID;
  static byte oldId;
  if (rf12_recvDone() and rf12_crc == 0) {
    blink = !blink;
    switch (rf12_data[0]) {
      case POSREP:
        index = 0;
        trainID = rf12_hdr & ID_MASK;
        if (nTrain > 0) {
          while (posPack[index][0] != trainID and index < nTrain) {
            index++;
          }
        }
        if (index < nTrain) { // found existing
          posRepValid[index] = true;
          posPack[index][0] = trainID;
          for (byte b = 1; b < 10; b++) posPack[index][b] = rf12_data[b];
          timerOBUTimeout[index] = OBU_TIMEOUT;
        } else if (nTrain < N_TRAIN) { // new train
          posRepValid[index] = true;
          posPack[index][0] = trainID;
          for (byte b = 1; b < 10; b++) posPack[index][b] = rf12_data[b];
          timerOBUTimeout[index] = OBU_TIMEOUT;
          nTrain++;
        } // else ignore train
    }
  }
  if (txMAWaiting and rf12_canSend()) {
    rf12_sendStart(BROADCAST, &MAPack, sizeof(MAPack));
    rf12_sendWait(0);
    txMAWaiting = false;
  }
#endif
}

// --------------------------------------------------------------------- Abus

void AbusRecBroadcast() {
}

boolean AbusRecThis() {
  unsigned long ms;
  byte index, trainID;

  Abus.toM[2] = Abus.fromM[2]; // include packet type in reply
  switch (Abus.fromM[2]) { // packet type
    case 1: // Type 1, Element status request
      elementStatus();
      break;
    case 2: // Type 2, Request for EC status
      ms = millis();
      for (byte b = 0; b < 4; b++)  Abus.toM[b + 3] = (byte) (ms >> 8 * b);
      Abus.toM[7] = nextElement; // Number of configured elements
      Abus.toM[8] = N_ELEMENT;
      Abus.toM[9] = N_UDEVICE;
      Abus.toM[10] = N_LDEVICE;
      Abus.toM[11] = N_PDEVICE;
      break;
#ifdef RADIO_LINK_ID
    case 3: // Type 3, Mode and Movement authorization. Position report request
      // MA to train
      MAPack[0] = 31; // MA
      for (byte b = 1; b < 11; b++) MAPack[b] = Abus.fromM[b + 2];
      txMAWaiting = true;
      // posRep to RBC
      trainID = Abus.fromM[3];
      index = 0;
      while (posPack[index][0] != trainID and index < nTrain) {
        index++;
      }
      if (index < nTrain) { // found
        blink = !blink;
        Abus.toM[3] = posRepValid[index];
        for (byte b = 0; b < 11; b++) Abus.toM[b + 4] = posPack[index][b];
      } else { // no posrep for this train
        Abus.toM[3] = false;
        Abus.toM[4] = trainID;
      }
      break;
#endif
    case 10: // Type 10, Element order and element status request
      elementOrder(Abus.fromM[3], Abus.fromM[4]);
      elementStatus();
      break;
    case 20: // Type 20, Configuration data
      Abus.toM[3] = 0; // Set reply to configuration accepted
      switch (Abus.fromM[3]) { // Configuration command
        case 0: // -------------------------------------------------------------- Clear configuration
          initEC();
          break;
        case 1: // -------------------------------------------------------------- Add configuration
          if (nextElement < N_ELEMENT ) {
            switch (Abus.fromM[4]) { // element type
              // ------------------------------------------------------------------------- U-devices
              case 31: // Level crossing road signal
                if (Abus.fromM[5] > 0 and Abus.fromM[5] <= N_UDEVICE) {
                  element[nextElement].type = Abus.fromM[4];
                  element[nextElement].deviceMajor = Abus.fromM[5];
                  pinMode(UdevicePin[Abus.fromM[5] - 1], OUTPUT);
                  digitalWrite(UdevicePin[Abus.fromM[5] - 1], LOW);
                  element[nextElement].cStatus = I_PASS;
                } else {
                  Abus.toM[3] = 1; // invalid device number 1
                }
              break;              case 41: // Light signal, 2 lantern, 2 aspect   ---------- single U-device
                if (Abus.fromM[5] > 0 and Abus.fromM[5] <= N_UDEVICE) {
                  element[nextElement].type = Abus.fromM[4];
                  element[nextElement].deviceMajor = Abus.fromM[5];
                  pinMode(UdevicePin[Abus.fromM[5] - 1], OUTPUT);
                  digitalWrite(UdevicePin[Abus.fromM[5] - 1], LOW);
                  element[nextElement].cStatus = I_STOP;
                } else {
                  Abus.toM[3] = 1; // invalid device number 1
                }
                break;
              case 43: // Light signal, 2 lantern, 3 aspect  ----------- 2 U-devies
                if (Abus.fromM[5] > 0 and Abus.fromM[5] + 1 <= N_UDEVICE) {
                  element[nextElement].type = Abus.fromM[4];
                  element[nextElement].deviceMajor = Abus.fromM[5];
                  pinMode(UdevicePin[Abus.fromM[5] - 1], OUTPUT);
                  digitalWrite(UdevicePin[Abus.fromM[5] - 1], HIGH);
                  pinMode(UdevicePin[Abus.fromM[5]], OUTPUT);
                  digitalWrite(UdevicePin[Abus.fromM[5]], LOW);
                  element[nextElement].cStatus = I_STOP;
                } else {
                  Abus.toM[3] = 1; // invalid device number 1
                }
                break;
              case 45: // Light signal, 3 lantern, 3 aspecs  --------- 3 U-devices
                if (Abus.fromM[5] > 0 and Abus.fromM[5] + 2 <= N_UDEVICE) {
                  element[nextElement].type = Abus.fromM[4];
                  element[nextElement].deviceMajor = Abus.fromM[5];
                  pinMode(UdevicePin[Abus.fromM[5] - 1], OUTPUT);
                  digitalWrite(UdevicePin[Abus.fromM[5] - 1], HIGH);
                  pinMode(UdevicePin[Abus.fromM[5]], OUTPUT);
                  digitalWrite(UdevicePin[Abus.fromM[5]], LOW);
                  pinMode(UdevicePin[Abus.fromM[5] + 1], OUTPUT);
                  digitalWrite(UdevicePin[Abus.fromM[5] + 1], LOW);
                  element[nextElement].cStatus = I_STOP;
                } else {
                  Abus.toM[3] = 1; // invalid device number 1
                }
                break;
              // ------------------------------------------------------------------------------------P-devices
              case 32: // Road barrier
                if (Abus.fromM[5] > 0 and Abus.fromM[5] <= N_PDEVICE) {
                  element[nextElement].type = Abus.fromM[4];
                  element[nextElement].deviceMajor = Abus.fromM[5];
                  element[nextElement].cStatus = UNSUPERVISED;
                } else {
                  Abus.toM[3] = 1; // invalid device number 1
                }
                break;
              case 21: // Semaphor Signal
                if (Abus.fromM[5] > 0 and Abus.fromM[5] <= N_PDEVICE) {
                  element[nextElement].type = Abus.fromM[4];
                  element[nextElement].deviceMajor = Abus.fromM[5];
                  Pdevice(element[nextElement].deviceMajor, HIGH, LOW);
                  element[nextElement].state = SE_POL_STOP;
                  element[nextElement].timer = PM_POL_TIME;
                  element[nextElement].cStatus = I_STOP;
                } else {
                  Abus.toM[3] = 1; // invalid device number 1
                }
                break;
              case 10: // Point without feedback, P-device
                if (Abus.fromM[5] > 0 and Abus.fromM[5] <= N_PDEVICE) {
                  element[nextElement].type = Abus.fromM[4];
                  element[nextElement].deviceMajor = Abus.fromM[5];
                  element[nextElement].cStatus = UNSUPERVISED;
                } else {
                  Abus.toM[3] = 1; // invalid device number 1
                }
                break;
              // -------------------------------------------------------------------------------- L-device
              case 30: // Road signal, 1 lantern, 2 aspect
                if (Abus.fromM[5] > 0 and Abus.fromM[5] <= N_LDEVICE) {
                  element[nextElement].type = Abus.fromM[4];
                  element[nextElement].deviceMajor = Abus.fromM[5];
                  Ldevice(element[nextElement].deviceMajor, LOW);
                  element[nextElement].cStatus = I_PASS;
                } else {
                  Abus.toM[3] = 1; // invalid device number 1
                }
                break;
              case 40: // Signal, 2 lantern, 2 aspects
              case 42: // Signal, 2 lantern, 3 aspects
                if (Abus.fromM[5] > 0 and Abus.fromM[5] + 1 <= N_LDEVICE) {
                  element[nextElement].type = Abus.fromM[4];
                  element[nextElement].deviceMajor = Abus.fromM[5];
                  Ldevice(element[nextElement].deviceMajor, HIGH);
                  Ldevice(element[nextElement].deviceMajor + 1, LOW);
                  element[nextElement].cStatus = I_STOP;
                } else {
                  Abus.toM[3] = 1; // invalid device number 1
                }
                break;
              case 44: // Signal, 3 lantern, 3 aspects
                if (Abus.fromM[5] > 0 and Abus.fromM[5] + 2 <= N_LDEVICE) {
                  element[nextElement].type = Abus.fromM[4];
                  element[nextElement].deviceMajor = Abus.fromM[5];
                  Ldevice(element[nextElement].deviceMajor, HIGH);
                  Ldevice(element[nextElement].deviceMajor + 1, LOW);
                  Ldevice(element[nextElement].deviceMajor + 2, LOW);
                  element[nextElement].cStatus = I_STOP;
                } else {
                  Abus.toM[3] = 1; // invalid device number 1
                }
                break;
              default:
                Abus.toM[3] = 12; // unknown element type
            }
            nextElement++;
          } else {
            Abus.toM[3] = 10; // no space for more elements
          }
          break;
        default:
          Abus.toM[3] = 11; // unknown configuration command
      }
      break;
    default:
      return false;
      break;
  }
  return true;
}

void elementOrder(byte index, byte order) {
  if (index < nextElement) {
    element[index].order = order;
    switch (element[index].type) {
      case 10: // point w/o position detection
        switch (order) {
          case P_RIGHT:
          case P_RIGHT_HOLD:
            Pdevice(element[index].deviceMajor, HIGH, LOW);
            element[index].state = PM_POL_RIGHT;
            element[index].timer = PM_POL_TIME;
            element[index].cStatus = I_U_RIGHT_HOLDING;
            break;
          case P_LEFT:
          case P_LEFT_HOLD:
            Pdevice(element[index].deviceMajor, LOW, HIGH);
            element[index].state = PM_THROW_LEFT;
            element[index].timer = (order == P_LEFT_HOLD ? PM_THROW_HOLD_TIME : PM_THROW_TIME);
            element[index].cStatus = I_U_LEFT_HOLDING;
            break;
          case P_RELEASE:
            if (element[index].state == PM_THROW_RIGHT) {
              Pdevice(element[index].deviceMajor, HIGH, LOW);
              element[index].state = PM_POL_RIGHT_DONE;
              element[index].timer = PM_POL_TIME;
              element[index].cStatus = I_U_RIGHT;
            } else if (element[index].state == PM_THROW_LEFT) {
              Pdevice(element[index].deviceMajor, LOW, LOW);
              element[index].state = PM_IDLE;
              element[index].cStatus = I_U_LEFT;
            }
            break;
        }
        break;
      case 21: // Semaphor Signal
        switch (order) {
          case S_STOP:
            Pdevice(element[index].deviceMajor, HIGH, LOW);
            element[index].state = SE_POL_STOP;
            element[index].timer = PM_POL_TIME;
            element[index].cStatus = I_STOP;
            break;
          case S_PROCEED:
            switch (element[index].state) {
              case SE_STOP:
                Pdevice(element[index].deviceMajor, LOW, HIGH);
                element[index].state = SE_CHANGE_PROCEED;
                element[index].timer = PM_THROW_TIME;
                element[index].cStatus = I_PROCEED;
                break;
              case SE_PROCEED:
                element[index].timer = CLOSE_TIMER;
                break;
            }
            break;
        }
        break;
      case 40: // Signal 2 aspect, 2 L-device
      case 42: // Signal 3 aspect, 2 L-device
        switch (order) {
          case S_STOP:
            Ldevice(element[index].deviceMajor, HIGH);
            Ldevice(element[index].deviceMajor + 1, LOW);
            element[index].cStatus = I_STOP;
            break;
          case S_PROCEED:
            Ldevice(element[index].deviceMajor, LOW);
            Ldevice(element[index].deviceMajor + 1, HIGH);
            element[index].timer = CLOSE_TIMER;
            element[index].cStatus = I_PROCEED;
            break;
          case S_PROCEEDPROCEED:
            Ldevice(element[index].deviceMajor, LOW);
            Ldevice(element[index].deviceMajor + 1, flash);
            element[index].timer = CLOSE_TIMER;
            element[index].cStatus = I_PROCEEDPROCEED;
            break;
        }
        break;
      case 44: // Signal 3 aspect, 3 L-device
        switch (order) {
          case S_STOP:
            Ldevice(element[index].deviceMajor, HIGH);
            Ldevice(element[index].deviceMajor + 1, LOW);
            Ldevice(element[index].deviceMajor + 2, LOW);
            element[index].cStatus = I_STOP;
            break;
          case S_PROCEED:
            Ldevice(element[index].deviceMajor, LOW);
            Ldevice(element[index].deviceMajor + 1, HIGH);
            Ldevice(element[index].deviceMajor + 2, LOW);
            element[index].timer = CLOSE_TIMER;
            element[index].cStatus = I_PROCEED;
            break;
          case S_PROCEEDPROCEED:
            Ldevice(element[index].deviceMajor, LOW);
            Ldevice(element[index].deviceMajor + 1, HIGH);
            Ldevice(element[index].deviceMajor + 2, HIGH);
            element[index].timer = CLOSE_TIMER;
            element[index].cStatus = I_PROCEEDPROCEED;
            break;
        }
        break;
      case 41: // Signal, 2 aspect, 1 U-device
        switch (order) {
          case S_STOP:
            Udevice(element[index].deviceMajor, LOW);
            element[index].cStatus = I_STOP;
            break;
          case S_PROCEED:
            Udevice(element[index].deviceMajor, HIGH);
            element[index].timer = CLOSE_TIMER;
            element[index].cStatus = I_PROCEED;
            break;
        }
        break;
      case 43: // Signal, 3 aspect, 2 U-device
        switch (order) {
          case S_STOP:
            Udevice(element[index].deviceMajor, HIGH);
            Udevice(element[index].deviceMajor + 1, LOW);
            element[index].cStatus = I_STOP;
            break;
          case S_PROCEED:
            Udevice(element[index].deviceMajor, LOW);
            Udevice(element[index].deviceMajor + 1, HIGH);
            element[index].timer = CLOSE_TIMER;
            element[index].cStatus = I_PROCEED;
            break;
          case S_PROCEEDPROCEED:
            Udevice(element[index].deviceMajor, LOW);
            Udevice(element[index].deviceMajor + 1, flash);
            element[index].timer = CLOSE_TIMER;
            element[index].cStatus = I_PROCEEDPROCEED;
            break;
        }
        break;
      case 45: // Signal, 3 aspect, 3 U-device
        switch (order) {
          case S_STOP:
            Udevice(element[index].deviceMajor, HIGH);
            Udevice(element[index].deviceMajor + 1, LOW);
            Udevice(element[index].deviceMajor + 2, LOW);
            element[index].cStatus = I_STOP;
            break;
          case S_PROCEED:
            Udevice(element[index].deviceMajor, LOW);
            Udevice(element[index].deviceMajor + 1, HIGH);
            Udevice(element[index].deviceMajor + 2, LOW);
            element[index].timer = CLOSE_TIMER;
            element[index].cStatus = I_PROCEED;
            break;
          case S_PROCEEDPROCEED:
            Udevice(element[index].deviceMajor, LOW);
            Udevice(element[index].deviceMajor + 1, HIGH);
            Udevice(element[index].deviceMajor + 2, HIGH);
            element[index].timer = CLOSE_TIMER;
            element[index].cStatus = I_PROCEEDPROCEED;
            break;
        }
        break;
      case 50: // Route indicator, 2 segments 	2 x L-device 
        order = order & 0x03;
        Ldevice(element[index].deviceMajor, order & 0x01);
        Ldevice(element[index].deviceMajor + 1, order & 0x02);
        element[index].cStatus = order;
      break;
      case 51: // Route indicator, 2 segments 	2 x U-device 
        order = order & 0x03;
        Udevice(element[index].deviceMajor, order & 0x01);
        Udevice(element[index].deviceMajor + 1, order & 0x02);
        element[index].cStatus = order;
      break;
      case 52: // Route indicator, 3 segments 	3 x L-device 
        order = order & 0x07;
        Ldevice(element[index].deviceMajor, order & 0x01);
        Ldevice(element[index].deviceMajor + 1, order & 0x02);
        Ldevice(element[index].deviceMajor + 2, order & 0x04);
        element[index].cStatus = order;
      break;
      case 53: // Route indicator, 3 segments 	3 x U-device 
        order = order & 0x07;
        Udevice(element[index].deviceMajor, order & 0x01);
        Udevice(element[index].deviceMajor + 1, order & 0x02);
        Udevice(element[index].deviceMajor + 2, order & 0x04);
        element[index].cStatus = order;
      break;
      case 30: // Road signal, L-device
        switch (order) {
          case R_PASS:
            Ldevice(element[index].deviceMajor, LOW);
            element[index].cStatus = I_PASS;
            break;
          case R_STOP:
            Ldevice(element[index].deviceMajor, flash);
            element[index].cStatus = I_STOP;
            break;
        }
        break;
      case 31: // Road signal, U-device
        switch (order) {
          case R_PASS:
            Udevice(element[index].deviceMajor, LOW);
            element[index].cStatus = I_PASS;
            break;
          case R_STOP:
            Udevice(element[index].deviceMajor, flash);
            element[index].cStatus = I_STOP;
            break;
        }
        break;
      case 32: // Road barrier, P-device
        switch (order) {
          case L_ACTIVATE:
            Pdevice(element[index].deviceMajor, LOW, HIGH);
            element[index].cStatus = I_MOVING_DOWN;
            element[index].timer = LX_MOVING;
            break;
          case L_DEACTIVATE:
            Pdevice(element[index].deviceMajor, HIGH, LOW);
            element[index].cStatus = I_MOVING_UP;
            element[index].timer = LX_MOVING;
            break;
        }
        break;
    }
  } // else invalid element index - ignore
}

void elementStatus() {
  Abus.toM[3] = nextElement;
  for (byte i = 0; i < nextElement; i += 2) {
    Abus.toM[i / 2 + 4] = element[i].cStatus << 4 | element[i + 1].cStatus;
  }
}

// ----------------------------------------------------------------------------------------- Device driver

void Udevice(byte device, boolean value) {
  digitalWrite(UdevicePin[device - 1], value);
}

void Ldevice(byte device, byte value) {
  if (value) {
    devReg[LdeviceReg[device - 1]] |= LdeviceOnMask[device - 1];
  } else {
    devReg[LdeviceReg[device - 1]] &= ~LdeviceOnMask[device - 1];
  }
}

void Pdevice(byte device, byte polarity, byte value) {
  if (polarity) {
    devReg[PdeviceReg[device - 1]] |= PdevicePolMask[device - 1];
  } else {
    devReg[PdeviceReg[device - 1]] &= ~PdevicePolMask[device - 1];
  }
  if (value) {
    devReg[PdeviceReg[device - 1]] |= PdeviceOnMask[device - 1];
  } else {
    devReg[PdeviceReg[device - 1]] &= ~PdeviceOnMask[device - 1];
  }
}


void updateLPdevice() { // write device registers for L and P devices
  for (int b = N_LREG + N_PREG - 1; b >= 0; b--)  shiftOut(DATA, CLK, MSBFIRST, devReg[b]);
  pulse(STROBE);
}

// ----------------------------------------------------------------------------------------- Utility
void pulse(byte pin) {
  digitalWrite(pin, HIGH);
  digitalWrite(pin, LOW);
}

void timing() {
  deltaMillis = 0; // clear last result
  thisMillis = millis();
  if (thisMillis != lastMillis) {
    deltaMillis = thisMillis - lastMillis; // note this works even if millis() has rolled over back to 0
    lastMillis = thisMillis;
  }
}
