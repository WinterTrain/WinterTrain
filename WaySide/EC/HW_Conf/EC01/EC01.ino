// HardWare Configuration for Element Controller
//
// Name: EC01
// HW: 16 L-devies, 4 P-devices


// Abus
#define TXENABLE_PIN 8
#define SLAVE_ADDRESS 201
#define RECEIVE_TIMEOUT 10

// General hardware
#define CLK 9
#define DATA 10
#define STROBE 11
#define BLINK 12

// EC hardware
#define N_ELEMENT 12 // max number of element allowed. Abus packet type 1 allows max 32 elements (due to max packet length of 20 byte) 
#define N_UDEVICE 0
#define N_LDEVICE 16
#define N_LREG 2
#define N_PDEVICE 4
#define N_PREG 1
#define UDEVICE_PIN {}; // 
#define PDEVICE_ON_MASK {0x10, 0x20, 0x01, 0x80}; // Mask to set device on, per device
#define PDEVICE_POL_MASK {0x08, 0x04, 0x02, 0x40}; // Mask to set device reversed polarity, per device
#define PDEVICE_REG {2, 2, 2, 2}; // Assignment of device to shift register
#define LDEVICE_ON_MASK {}; // Mask to set device on, per device
#define LDEVICE_REG {0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1}; // Assignment of device to shift register

// Capacity
#define N_TRAIN 0 // Max number of train. Radio Link only, otherwise 0


