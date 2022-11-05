// HardWare Configuration for Element Controller
//
// Name: EC02
// HW: 8 U-devies, 4 P-devices, 16 L-devices


// Abus
#define TXENABLE_PIN 8
#define SLAVE_ADDRESS 202
#define RECEIVE_TIMEOUT 10

// General hardware
#define CLK 11
#define DATA 9
#define STROBE 10
#define BLINK 13

// EC hardware
#define N_ELEMENT 12 // max number of element allowed. Abus packet type 1 allows max 32 elements (due to max packet length of 20 byte) 
#define N_UDEVICE 8
#define N_LDEVICE 16
#define N_LREG 2
#define N_PDEVICE 4
#define N_PREG 1
#define UDEVICE_PIN {A1, A0, A2, 6, 5, 7, 4, 3}; // 2 pol molex, 4 pol molex
#define PDEVICE_ON_MASK {0x10, 0x20, 0x01, 0x80}; // Mask to set device on, per device
#define PDEVICE_POL_MASK {0x08, 0x04, 0x02, 0x40}; // Mask to set device reversed polarity, per device
#define PDEVICE_REG {0, 0, 0, 0}; // Assignment of device to shift register
#define LDEVICE_ON_MASK {0x80, 0x40, 0x20, 0x01, 0x04, 0x10, 0x02, 0x01, 0x04, 0x02, 0x08, 0x10, 0x08, 0x20, 0x40, 0x80}; // Mask to set device on, per device
#define LDEVICE_REG {2, 2, 2, 2, 2, 2, 2, 1, 1, 1, 1, 1, 2, 1, 1, 1}; // Assignment of device to shift register

// Capacity
#define N_TRAIN 0 // Max number of train. Radio Link only, otherwise 0


