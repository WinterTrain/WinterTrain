#include <Bounce2.h>


// Fællesnøgler
#define CK_PIN 5 // Common key
#define ER_PIN 4 // Emergency release

// Signal
#define STR 3
#define STG 2
#define SLR 6
#define SLG 8
#define SN_PIN A0

// SPSK
#define PTRR 11
#define PTRG 10
#define PTLR 13
#define PTLG 12
#define PN_PIN 9

// Sporstopper

#define BTR A1
#define BTG A2
#define BF 7
#define BN_PIN A3

Bounce dbCK = Bounce();
Bounce dbER = Bounce();
Bounce dbSN = Bounce();
Bounce dbPN = Bounce();
Bounce dbBN = Bounce();

char c, element;
int state, routeState, trackState;;

void setup() {
  pinMode(STR, OUTPUT);
  pinMode(STG, OUTPUT);
  pinMode(SLR, OUTPUT);
  pinMode(SLG, OUTPUT);
  pinMode(PTRR, OUTPUT);
  pinMode(PTRG, OUTPUT);
  pinMode(PTLR, OUTPUT);
  pinMode(PTLG, OUTPUT);
  pinMode(BTR, OUTPUT);
  pinMode(BTG, OUTPUT);
  pinMode(BF, OUTPUT);

  pinMode(CK_PIN, INPUT_PULLUP);
  pinMode(ER_PIN, INPUT_PULLUP);
  pinMode(SN_PIN, INPUT_PULLUP);
  pinMode(PN_PIN, INPUT_PULLUP);
  pinMode(BN_PIN, INPUT_PULLUP);

  dbCK.attach(CK_PIN);
  dbCK.interval(5);
  dbER.attach(ER_PIN);
  dbER.interval(5);
  dbSN.attach(SN_PIN);
  dbSN.interval(5);
  dbPN.attach(PN_PIN);
  dbPN.interval(5);
  dbBN.attach(BN_PIN);
  dbBN.interval(5);

  Serial.begin(57600);
  Serial.println("Panel");
}

void loop() {
  dbCK.update();
  dbER.update();
  dbSN.update();
  dbPN.update();
  dbBN.update();

  if (!dbCK.read() and dbPN.rose() or dbCK.rose() and !dbPN.read()) { // Throw P4
    Serial.println("pt P4");
  }
  if (!dbSN.read() and dbBN.rose() or dbSN.rose() and !dbBN.read()) { // Set route S23 BS3
    Serial.println("tr S23 BS3");
  }
  if (!dbER.read() and dbBN.rose() or dbER.rose() and !dbBN.read()) { // Release BS3
    Serial.println("rr BS3");
  }
  if (!dbER.read() and dbSN.rose() or dbER.rose() and !dbSN.read()) { // Release S23
    Serial.println("rr S23");
  }

  if (Serial.available() >= 8) {
    c = Serial.read();
    if (c == 'I') {
      element = Serial.read();
      state = (Serial.read() - 48) * 10 + Serial.read() - 48;
      routeState = (Serial.read() - 48) * 10 + Serial.read() - 48;
      trackState = (Serial.read() - 48) * 10 + Serial.read() - 48;
      switch (element) {
        case 'P':
          switch (state) {
            case 20: // Left
              switch (trackState) {
                case 1: // occupied
                case 2:
                case 3:
                  digitalWrite(PTLG, LOW);
                  digitalWrite(PTLR, HIGH);
                  break;
                case 5: //
                  if (routeState == 2) {
                    digitalWrite(PTLG, HIGH);
                    digitalWrite(PTLR, LOW);
                  } else {
                    digitalWrite(PTLG, HIGH);
                    digitalWrite(PTLR, HIGH);
                  }
                  break;
              }
              digitalWrite(PTRG, LOW);
              digitalWrite(PTRR, LOW);
              break;
            case 21: // Right
              switch (trackState) {
                case 1: // occupied
                case 2:
                case 3:
                  digitalWrite(PTRG, LOW);
                  digitalWrite(PTRR, HIGH);
                  break;
                case 5: //
                  if (routeState == 2) {
                    digitalWrite(PTRG, HIGH);
                    digitalWrite(PTRR, LOW);
                  } else {
                    digitalWrite(PTRG, HIGH);
                    digitalWrite(PTRR, HIGH);
                  }
                  break;
              }
              digitalWrite(PTLG, LOW);
              digitalWrite(PTLR, LOW);
              break;
            default:
              digitalWrite(PTLG, LOW);
              digitalWrite(PTLR, LOW);
              digitalWrite(PTRG, LOW);
              digitalWrite(PTRR, LOW);
              break;
          }
          break;
        case 'S':
          switch (state) {
            case 13: // closed as destination
              digitalWrite(SLR, HIGH);
              digitalWrite(SLG, LOW);
              break;
            case 11: // proceed
            case 12: // proceedproceed
              digitalWrite(SLR, LOW);
              digitalWrite(SLG, HIGH);
              break;
            default:
              digitalWrite(SLR, LOW);
              digitalWrite(SLG, LOW);
              break;
          }
          switch (trackState) {
            case 5: //
              if (routeState == 2) {
                digitalWrite(STR, LOW);
                digitalWrite(STG, HIGH);
              } else {
                digitalWrite(STR, LOW);
                digitalWrite(STG, LOW);
              }
              break;
            case 1: // occupied
            case 2:
            case 3:
              digitalWrite(STR, HIGH);
              digitalWrite(STG, LOW);
              break;
            default:
              digitalWrite(STR, LOW);
              digitalWrite(STG, LOW);
              break;
          }
          break;
        case 'B':
          if (state == 13) {
            digitalWrite(BF, HIGH);
          } else {
            digitalWrite(BF, LOW);
          }
          switch (trackState) {
            case 5: // clear
              if (routeState == 2) {
                digitalWrite(BTG, HIGH);
                digitalWrite(BTR, LOW);
              } else {
                digitalWrite(BTG, LOW);
                digitalWrite(BTR, LOW);
              }
              break;
            case 1: // occupied
            case 2:
            case 3:
              digitalWrite(BTR, HIGH);
              digitalWrite(BTG, LOW);
              break;
            default:
              digitalWrite(BTR, LOW);
              digitalWrite(BTG, LOW);
              break;
          }
          break;
      }
    }
  }


}
