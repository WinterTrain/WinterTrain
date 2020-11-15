// HardWare Configuration for Element Controller
//
// Name: EC04
// HW: 0 L-devices, 15 U-devices, 0 P-devices
// Arduino HW: ProMini

// Abus
#define TXENABLE_PIN 2
#define SLAVE_ADDRESS 204
#define RECEIVE_TIMEOUT 10

// General hardware
#define CLK 0
#define DATA 0
#define STROBE 0
//#define BLINK 13 // Define if LED available

// EC hardware
#define N_ELEMENT 15 // max number of element allowed. Abus packet type 1 allows max 32 elements (due to max packet length of 20 byte) 
#define N_UDEVICE 15
#define N_LDEVICE 0
#define N_LREG 0
#define N_PDEVICE 0
#define N_PREG 0
#define UDEVICE_PIN {6, 7, 8, 9, 11, 13, A1, A3, 10, 12, A0, A2, 3, 4, 5}; // 
#define PDEVICE_ON_MASK {}; // Mask to set device on, per device
#define PDEVICE_POL_MASK {}; // Mask to set device reversed polarity, per device
#define PDEVICE_REG {}; // Assignment of device to shift register
#define LDEVICE_ON_MASK {}; // Mask to set device on, per device
#define LDEVICE_REG {}; // Assignment of device to shift register

// Capacity
#define N_TRAIN 0 // Max number of train. Radio Link only, otherwise 0
