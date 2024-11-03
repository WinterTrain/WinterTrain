// HardWare Configuration for Element Controller
//
// Name: EC03
// HW: 8 U-devices
// Processor: Uno


// Abus
#define TXENABLE_PIN 6
#define SLAVE_ADDRESS 203
#define RECEIVE_TIMEOUT 10

// General hardware
#define CLK 9
#define DATA 10
#define STROBE 11
#define BLINK 12

// EC hardware
#define N_ELEMENT 8 // max number of element allowed. Abus packet type 1 allows max 32 elements (due to max packet length of 20 byte) 
#define N_UDEVICE 8
#define N_LDEVICE 0
#define N_LREG 0
#define N_PDEVICE 0
#define N_PREG 0
#define UDEVICE_PIN {5,A0,4,A1,6,9,A3,8}; // 
#define PDEVICE_ON_MASK {}; // Mask to set device on, per device
#define PDEVICE_POL_MASK {}; // Mask to set device reversed polarity, per device
#define PDEVICE_REG {}; // Assignment of device to shift register
#define LDEVICE_ON_MASK {}; // Mask to set device on, per device
#define LDEVICE_REG {}; // Assignment of device to shift register

// Capacity
#define N_TRAIN 0 // Max number of train. Radio Link only, otherwise 0
