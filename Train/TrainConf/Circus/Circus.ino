// Train specific configuration
// ------------------------------------------- Circus Wagon

// define either OVERRIDE_SR or OVERRIDE_SH
#define OVERRIDE_SR

// RF12
#define OBU_ID 22 // RF12 node ID of train
#define DMI_ID 23 // RF12 node ID of assigned DMI

// Capacity
#define MAX_BALISES 8

// Configuration
#define DETECT_NOM_DIR DOWN // UP, DOWN or AUTO_DETECT
#define BRAKING_DISTANCE 18
#define SH_BREAKE_DIST 0
#define MAX_DRIVE 100
#define VMAX_SH 60
#define SHORT_MA 30
#define STOP_MA 3 // Distance (wheel turn) below this will show red on DMI
#define V_OFFSET 30
#define MIN_DIST 2 // Distance (wheel turn) below this will be ignored

#define N_SABALISES 12 // balise list for "stop if in shunting"
// Distances engineered in "wheel turns". OBU will start braking at that distance, allow for 80 cm braking distance
#define SA_BALISES { \
  /* Christianshavn, end station, braking point at 80 cm in rear of buffer stop */\
  \  
  {{0x1F, 0x00, 0x69, 0xC9, 0x3C},  -20 },    /* BG03  -97 cm*/\
  {{0x1E, 0x00, 0x8E, 0x70, 0x11},  -15 },    /* BG04  -73 cm*/\
  {{0x1F, 0x00, 0x69, 0xA9, 0x3C},  -24 },    /* BG05 -120 cm*/\
  \
  /* Station Langtbortistan */\
  {{0x1E, 0x00, 0xEA, 0xE8, 0xE9},  21 },    /* BG27 104 cm */\
  {{0x1E, 0x00, 0xB0, 0x50, 0x33},  15 },    /* BG28  74 cm */\
  {{0x1E, 0x00, 0x90, 0x0C, 0x8B},  14 },    /* BG29  69 cm */\
  {{0x76, 0x00, 0x0C, 0xFC, 0xCB},  3  },    /* BG30  15 cm */\
  {{0x73, 0x00, 0x56, 0xD9, 0xB2},  2  },    /* BG31  10 cm */\
  \
  /* Station Christiania, Signal S9 */\
  {{0x76, 0x00, 0x0C, 0xFA, 0x7D},   -1 },    /* BG20   -5 cm */\
  {{0x74, 0x00, 0x15, 0x55, 0x26},  -24 },    /* BG22 -120 cm */\
  \
  /* Station Christiania, Signal S10 */\
  {{0x73, 0x00, 0x70, 0x98, 0x69},  31 },    /* BG12 155 cm*/\
  {{0x73, 0x00, 0x56, 0x9F, 0xA1},   6 },    /* BG13  30 cm */\
};


// OBU HW assignment
#define OBU_PIN_MOTOR 5         //  PWM signal to moter control. JeeNode port 2
                                // Note: PWM frequency is set for pin 5 in setup()
#define OBU_PIN_DIR_CONTROL A1  // Direction control. JeeNode port 2
#define OBU_PIN_TRACK_UP 4      // JeeNode port 1
#define OBU_PIN_TRACK_DOWN A0   // JeeNode port 1
//#define OBU_PIN_BLUE  A2      // Blue LED, JeeNode port 3
#define OBU_PIN_OVERRIDE A2     // Override switch, JeeNode port 3
#define OBU_PIN_WHEEL  6        // Wheel sensor  JeeNode port 3
#define OBU_PIN_FLIGHT  A3      // Front light, JeeNode port 4
#define OBU_PIN_RLIGHT  7       // Rear light, JeeNode port 4

#define TAG_READER_7941E      // Balise reader type 7941E
//#define TAG_READER_RDM6300      // Balise reader type RDM6300

// DMI HW assignment
#define DMI_PIN_BLUE 4 
#define DMI_PIN_RED 5           // Movement Auth indicator
#define DMI_PIN_RED2 3          // Mode Auth indicator
#define DMI_PIN_YELLOW A3 
#define DMI_PIN_GREEN 7
#define DMI_PIN_MODE_SEL A0 
#define DMI_PIN_DIR_SEL A1 
#define DMI_PIN_DRIVE_SEL A2
#define DMI_PIN_METER 6
