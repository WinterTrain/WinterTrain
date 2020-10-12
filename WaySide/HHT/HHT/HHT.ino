#include <Bounce2.h>
#include <JeeLib.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include "HHTconf.h"

//--------------------------------------------------- HHT

// RF12
const byte RBC_ID = 10;
const byte BROADCAST = 0; // RF12 header for broadcast
const byte ID_MASK = 0x1F;
const byte GROUP = 101;


// TAG reader, type 7941E
#define TR_START 1
#define TR_LENGTH 2
#define TR_TYPE 3
#define TR_TAGNO 4
#define TR_CHECK_SUM 5
#define TR_END 6

// state ---------------- Terminal State
#define S_BOOT    0     // Terminal booting
#define S_SELECT  1     // Function selector
#define S_HHT     2     // HHT mode
#define S_BALRD   3     // Balise reader
#define S_DMI     4     // DMI

// fMode ---------------- Terminal mode selector
#define F_DMI_T1  0
#define F_DMI_T2  1
#define F_DMI_T3  2
#define F_BALRD   3
#define F_HHT     4

// modeSel -------------- DMI mode selector
#define M_OFF 0
#define M_SR  1
#define M_SH  2
#define M_FS  3
#define M_ATO 4

// dirSel
#define D_REVERSE 1
#define D_NEUTRAL 2
#define D_FORWARD 3

// driveSel
#define V_STOP 1
#define V_LOW 2
#define V_MED1 3
#define V_MED2 4
#define V_HIGH 5

// -------- text
char modeSelTxt[5][4] = {"OFF", "SR ", "SH ", "FS ", "ATO"};
char dirSelTxt[3][6] = {"Bak  ", "Neut ", "Frem "};


byte dirSelState[4] = {D_NEUTRAL, D_FORWARD, D_NEUTRAL, D_REVERSE};

LiquidCrystal_I2C lcd(0x27, 20, 4); // set the LCD address to 0x27 for a 16 chars and 2 line display

// ----------------------------------------- Variables

// Timing
unsigned long lastMillis, deltaMillis, thisMillis;
long tagIndication, timerOBUtimeout;
byte RBCmsg[10], baliseBuf[5], txQ[2];

byte state, fMode = F_BALRD, modeSel = M_OFF, dirSel = D_NEUTRAL, driveSel = V_STOP, dirSelSeq, train;
boolean txRBC, txOBU, tagRead;

byte obuID, dmiID, vReq;

Button k1 = Button(); Button k2 = Button(); Button k3 = Button(); Button k4 = Button();


void setup() {
  rf12_initialize(HHT_ID, RF12_868MHZ, GROUP);
  Serial.begin(9600); // for TAG reader

  pinMode(HHT_PIN_RED2, OUTPUT);
  pinMode(HHT_PIN_BLUE, OUTPUT);
  pinMode(HHT_PIN_RED, OUTPUT);
  pinMode(HHT_PIN_YELLOW, OUTPUT);
  pinMode(HHT_PIN_GREEN, OUTPUT);
  digitalWrite(HHT_PIN_RED2, HIGH);
  digitalWrite(HHT_PIN_BLUE, HIGH);
  digitalWrite(HHT_PIN_RED, HIGH);
  digitalWrite(HHT_PIN_YELLOW, HIGH);
  digitalWrite(HHT_PIN_GREEN, HIGH);

  k1.attach(HHT_PIN_K1, INPUT_PULLUP);  k2.attach(HHT_PIN_K2, INPUT_PULLUP); k3.attach(HHT_PIN_K3, INPUT_PULLUP); k4.attach(HHT_PIN_K4, INPUT_PULLUP);
  k1.interval(25); k2.interval(25); k3.interval(25); k4.interval(25);
  k1.setPressedState(LOW); k2.setPressedState(LOW); k3.setPressedState(LOW); k4.setPressedState(LOW);

  lcd.init();
  lcd.clear();
  lcd.backlight();
  lcd.print(F("HHT vers. 01P02"));
  delay(2000);
  digitalWrite(HHT_PIN_RED2, LOW);
  digitalWrite(HHT_PIN_BLUE, LOW);
  digitalWrite(HHT_PIN_RED, LOW);
  digitalWrite(HHT_PIN_YELLOW, LOW);
  digitalWrite(HHT_PIN_GREEN, LOW);
  lastMillis = millis();
  state = S_SELECT;
  startSelect();
}

void loop() {
  timing();
  processKeys();
  if (tagRead and tagIndication < 0) {
    tagRead = false;
    digitalWrite(HHT_PIN_RED2, LOW);
  }
  if (modeSel != M_OFF and timerOBUtimeout < 0) {
    digitalWrite(HHT_PIN_BLUE, HIGH);
  }
  checkBalise();
  rf12Transceive();
}

// -------------------------------------------- Menu processing
void processKeys() {
  k1.update();  k2.update();  k3.update();  k4.update();
  if (k1.pressed()) { // ---------------------------------------------- Mode selector
    switch (state) {
      case S_SELECT: // ------------------------------------ Key Mode
        fMode = (fMode == F_HHT ? F_DMI_T1 : fMode + 1);
        printFMode();
        break;
      case S_HHT:
      case S_BALRD:
        break;
      case S_DMI: // --------------------------------------- Key Mode
        modeSel = (modeSel == M_ATO ? M_OFF : modeSel + 1);
        printModeSel();
        switch (modeSel) {
          case M_OFF:
            printLuk();
            clearRetnSel();
            clearDriveSel();
            digitalWrite(HHT_PIN_BLUE, LOW);
            digitalWrite(HHT_PIN_RED, LOW);
            digitalWrite(HHT_PIN_RED2, LOW);
            digitalWrite(HHT_PIN_YELLOW, LOW);
            digitalWrite(HHT_PIN_GREEN, LOW);
            break;
          case M_ATO:
            clearKey2();
            clearKey3();
            clearKey4();
            clearRetnSel();
            clearDriveSel();
            break;
          default:
            printRetnSel();
            printDriveSel();
            lcd.setCursor(5, 3);
            lcd.print(F("Retn Brems Koer"));
            break;
        }
        break;
    }
  }
  if (k2.pressed()) { // -------------------------------------------------- direction etc.
    switch (state) {
      case S_SELECT: // -------------------------------------- Key Vaelg
        lcd.clear();
        switch (fMode) {
          case F_BALRD:             // Select Balise Reader
            rf12_initialize(HHT_ID, RF12_868MHZ, GROUP);
            state = S_BALRD;
            lcd.print(F("Balise Laeser"));
            clearKey1(); printLuk(); clearKey3(); printReset();
            for (byte b = 0; b < 5; b++) baliseBuf[b] = 0; // clearprevious read
            break;
          case F_HHT:             // Select HHT
            state = S_HHT;
            lcd.print("HHT");
            clearKey1(); printLuk(); clearKey3(); clearKey4();
            break;
          case F_DMI_T1:          // Select DMI
          case F_DMI_T2:
          case F_DMI_T3:
            state = S_DMI;
            train = fMode;
            obuID = obuIDlist[train];
            dmiID = dmiIDlist[train];
            rf12_initialize(dmiID, RF12_868MHZ, GROUP);
            dirSelSeq = 0;
            modeSel = M_OFF;
            dirSel = D_NEUTRAL;
            driveSel = V_STOP;
            lcd.print("DMI");
            lcd.setCursor(4, 0);
            lcd.print(trainTxt[train]);
            printModeSel();
            printMode(); printLuk(); clearKey3(); clearKey4();
            break;
        }
        break;
      case S_HHT: // -------------------------------------------- Key Luk
      case S_BALRD: // ------------------------------------------ Key Luk
        state = S_SELECT;
        startSelect();
        break;
      case S_DMI:
        switch (modeSel) {
          case M_OFF: // ------------------------------------- Key Luk
            state = S_SELECT;
            startSelect();
            modeSel = M_OFF;
            dirSel = D_NEUTRAL;
            driveSel = V_STOP;
            break;
          case M_SR:
          case M_SH:
          case M_FS:
            dirSelSeq = dirSelSeq == 3 ? 0 : dirSelSeq + 1;
            dirSel = dirSelState[dirSelSeq];
            printRetnSel();
            break;
        }
        break;
    }
  }
  if (k3.pressed()) {
    switch (state) {
      case S_DMI: // --------------------------------------- Key Brems
        switch (modeSel) {
          case M_SR:
          case M_SH:
          case M_FS:
            if (driveSel > 1) {
              driveSel--;
              printDriveSel();
            }
        }
        break;
    }
  }
  if (k4.pressed()) {
    switch (state) {
      case S_DMI: // ----------------------------------------- Key Koer
        switch (modeSel) {
          case M_SR:
          case M_SH:
          case M_FS:
            if (driveSel < 5) {
              driveSel++;
              printDriveSel();
            }
        }
        break;
      case S_BALRD: // -------------------------------------- Key Reset
        for (byte b = 0; b < 5; b++) baliseBuf[b] = 0; // clearprevious read balise
        lcd.setCursor(3, 1);
        lcd.print(F("              "));
        break;
    }
  }
}

void startSelect() {
  lcd.clear();
  lcd.print(F("Vaelg funktion"));
  printFMode();
  lcd.setCursor(0, 3);
  lcd.print(F("Mode Vaelg"));
  clearKey3();
  clearKey4();
}

void printFMode() {
  lcd.setCursor(0, 2);
  switch (fMode) {
    case F_HHT:
      lcd.print(F("HHT          "));
      break;
    case F_BALRD:
      lcd.print(F("Balise Laeser"));
      break;
    case F_DMI_T1:
    case F_DMI_T2:
    case F_DMI_T3:
      lcd.print("DMI ");
      lcd.print(trainTxt[fMode]);
      break;
  }
}

void clearKey() {
  lcd.print("  -  ");
}

void clearKey1() {
  lcd.setCursor(0, 3);
  clearKey();
}

void clearKey2() {
  lcd.setCursor(5, 3);
  clearKey();
}

void clearKey3() {
  lcd.setCursor(10, 3);
  clearKey();
}

void clearKey4() {
  lcd.setCursor(15, 3);
  clearKey();
}

void printMode() {
  lcd.setCursor(0, 3);
  lcd.print("Mode ");
}

void printLuk() {
  lcd.setCursor(5, 3);
  lcd.print("Luk  ");
}

void printReset() {
  lcd.setCursor(15, 3);
  lcd.print("Reset");
}


void clearRetnSel() {
  lcd.setCursor(5, 2);
  lcd.print(F("     "));
}

void printRetnSel() {
  lcd.setCursor(5, 2);
  lcd.print(dirSelTxt[dirSel - 1]);
}

void printModeSel() {
  lcd.setCursor(0, 2);
  lcd.print(modeSelTxt[modeSel]);
}

void clearDriveSel() {
  lcd.setCursor(15, 2);
  lcd.print(" ");
}

void printDriveSel() {
  lcd.setCursor(15, 2);
  lcd.print(driveSel - 1);
}

// --------------------------------------- DMI functions


// --------------------------------------------------------------------------------- Radio

void rf12Transceive() {

  if (rf12_recvDone() and rf12_crc == 0) {
    switch (state) {
      case S_DMI:
        if  (modeSel != M_OFF and (rf12_hdr & ID_MASK) == obuID and rf12_data[0] == dmiID) {
          vReq = rf12_data[2];
          lcd.setCursor(15, 1);
          lcd.print(vReq, DEC);
          digitalWrite(HHT_PIN_RED2, rf12_data[1] & 0x10);
          digitalWrite(HHT_PIN_RED, rf12_data[1] & 0x04);
          digitalWrite(HHT_PIN_YELLOW, rf12_data[1] & 0x02);
          digitalWrite(HHT_PIN_GREEN, rf12_data[1] & 0x01);
          txQ[0] = obuID;
          txQ[1] = modeSel | dirSel << 3 | driveSel << 5;
          txOBU = true;
          timerOBUtimeout = OBU_TIMEOUT;
          digitalWrite(HHT_PIN_BLUE, LOW);
        }
        break;
      case S_BALRD:
        break;
    }
  }
  if (txOBU and rf12_canSend()) {
    rf12_sendStart(BROADCAST, &txQ, 2);
    rf12_sendWait(0);
    txOBU = false;
  }
  if (txRBC and rf12_canSend()) {
    rf12_sendStart(BROADCAST, &RBCmsg, sizeof(RBCmsg));
    rf12_sendWait(0);
    txRBC = false;
  }
}

// ------------------------------------ Balise reader

void checkBalise() {
  if (state == S_BALRD) {
    if (readBalise()) {
      digitalWrite(HHT_PIN_RED2, HIGH);
      tagIndication = 100;
      tagRead = true;
      lcd.setCursor(0, 1);
      lcd.print("ID:                 ");
      lcd.setCursor(4, 1);
      for (byte x = 0; x < 5; x++) {
        lcd.print(baliseBuf[x], HEX);
        lcd.print(" ");
      }
    }
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
          newBalise = not csError and (data[0] != 0) and diff;
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
  return newBalise;
}


// ----------------------------------------------- utility
void timing() {
  deltaMillis = 0; // clear last result
  thisMillis = millis();
  if (thisMillis != lastMillis) {
    deltaMillis = thisMillis - lastMillis; // note this works even if millis() has rolled over back to 0
    lastMillis = thisMillis;
    tagIndication -= deltaMillis;
    timerOBUtimeout -= deltaMillis;

  }
}
