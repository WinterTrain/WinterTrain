// Panel Module
// WinterTrain, Track Panel
#include <Wire.h>

#define MODULE_I2C_ADDR 0x3f // To be specific per module

#define MAXBUF 6
#define N_INPUT 8
#define DEFAULT_OUTPUT_MAP 0x33FFFFUL // Map of available I/O pins; pin 18 and 19 are used for I2C
#define DEBOUNCE_DELAY 200UL

//  Variables
byte i2cRxBuf[MAXBUF]; // Buffer for incoming message
byte inputList[N_INPUT]; // List of pin number of configured input pins
byte inputIndex; // Next available input index / number of configured input pins
byte order;
byte inputReg;
byte inputState;
unsigned long outputOrder;
unsigned long outputMap = DEFAULT_OUTPUT_MAP;
unsigned long debounceTimer[N_INPUT];
byte prevInputState[N_INPUT];

byte error;

void setup() {
  Wire.begin(MODULE_I2C_ADDR);
  Wire.onReceive(WireReceiver);
  Wire.onRequest(WireSender);
  pinModeAllOut();
//  Serial.begin(115200);
//  Serial.println("WinterTrain, Track Panel");
//  Serial.print("PanelModule, I2C addr: ");
//  Serial.println(MODULE_I2C_ADDR, HEX);
}

void loop() {
  for (byte b = 0; b < inputIndex; b++) {
    inputState = digitalRead(inputList[b]);
    if (inputState !=  prevInputState[b]) {
      debounceTimer[b] = millis();
    }
    if (millis() - debounceTimer[b] > DEBOUNCE_DELAY) { // Input is stable for more than DEBOUNCE_DELAY
      if (inputState) {
        bitSet(inputReg, b);
      } else {
        bitClear(inputReg, b);
      }
    }
    prevInputState[b] = inputState;
//    delay(100);
  }
}

void WireReceiver(int count) {
  byte b;
  b = 0;
//  Serial.print ("REC: ");
  while (Wire.available()) {
    i2cRxBuf[b] = Wire.read();
//    Serial.print (i2cRxBuf[b], HEX);
//    Serial.print (" ");
    if (b < MAXBUF) b++;
  }
//  Serial.println ();
  order = i2cRxBuf[0]; // First byte received via I2C is the command, remaining bytes are parameters
  switch (order) {
    case 8:
    break;
    case 10: // Reset configuration
      inputIndex = 0;
      inputReg = 0;
      outputMap = DEFAULT_OUTPUT_MAP;
      pinModeAllOut();
      error = 0;
    break;
    case 11: // Configure pin n as input 
      if (count == 2 and (i2cRxBuf[1] < 22 and i2cRxBuf[1] != 18 and i2cRxBuf[1] != 19) and inputIndex < N_INPUT) {
        pinMode(i2cRxBuf[1], INPUT_PULLUP);
        inputList[inputIndex] = i2cRxBuf[1];
        inputIndex++;
        bitClear(outputMap, i2cRxBuf[1]);
        error = 0;
      } else { // Incorrect parameter count, pin n cannot be used for input or no more inputs allowed
        error = 2;
      }
    break;
    case 20: // Set output, x1, x2, x3
      if (count == 4) {
        outputOrder = i2cRxBuf[1] | (unsigned long) i2cRxBuf[2] << 8 | (unsigned long) i2cRxBuf[3] << 16;
        for (byte b = 0; b < 20; b++) {
          if (bitRead(outputMap, b)) digitalWrite(b, bitRead(outputOrder, b));
        }
        error = 0;
      } else { // Incorrect paramater count
        error = 3;
      }
    break;
    case 21: // Read input, x
      error = 0;
    break;
    default: // Unknown command
      error = 1;
    break;
  }
}

void WireSender() {
//  Serial.print ("SEND ");
  switch (order) {
    case 8: // Get error code
      Wire.write(error);
    break;
    case 21: // Read input
//      Serial.print("Read ");
//      Serial.println(inputReg, HEX);
      Wire.write(inputReg);
    break; 
    default:
      Wire.write(0);
  }
}

void pinModeAllOut() {
  // FIXME use DEFAULT_OUTPUT_MAP
  for (byte b = 0; b < 18; b++) {
    pinMode(b, OUTPUT);
  }
  // Pin 18 and 19 used for I2C
  pinMode(20, OUTPUT);
  pinMode(21,OUTPUT);
}
