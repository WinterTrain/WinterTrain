// HardWare Configuration for Element Controller
//
// Name: EC_LINK
// Equipped HW: RF12 Radio module, 5 U-devies

// RF12 radio
#define RADIO_LINK_ID 10  // ID of this radio node. If defined, code for RF12 radio will be included
#define BROADCAST 0 // RF12 header for broadcast
#define ID_MASK 0x1F
#define GROUP 101

// Abus
#define TXENABLE_PIN 3
#define SLAVE_ADDRESS 150 // EC/LINK
#define RECEIVE_TIMEOUT 10

// General hardware
#define CLK 13
#define DATA 12
#define STROBE 11
#define BLINK 7

// EC hardware
#define N_ELEMENT 5 // max number of element allowed. Abus packet type 1 allows max 32 elements (due to max packet length of 20 byte) 
#define N_UDEVICE 5
#define N_LDEVICE 0
#define N_LREG 0
#define N_PDEVICE 0
#define N_PREG 0
#define UDEVICE_PIN {5, A1, 6, 9, 8}
#define PDEVICE_ON_MASK {} // Mask to set device on
#define PDEVICE_POL_MASK {} // Mask to set device reversed polarity
#define PDEVICE_REG {} // Assignment of device to shift register
#define LDEVICE_ON_MASK {};
#define LDEVICE_REG {} // Assignment of device to shift register

// Capacity
#define N_TRAIN 2 // Max number of train. Radio Link only, otherwise 0


