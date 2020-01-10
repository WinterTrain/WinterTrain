// Train specific configuration
// ------------------------------------------- Circus Wagon

// define either OVERRIDE_SR or OVERRIDE_SH
#define OVERRIDE_SR

// RF12
#define OBU_ID 22 // RF12 node ID of train
#define DMI_ID 23 // RF12 node ID of assigned DMI

// Capacity
#define MAX_BALISES 8

#define N_SABALISES 18 // balise list for "stop if in shunting"
// Distances engineered in "wheel turns". OBU will start braking at that distance, allow for 80 cm braking distance
#define SA_BALISES { \
  /* Christianshavn, end station, braking point at 100 cm in rear of buffer stop */\
  \  
  {{0x1E, 0x00, 0xEC, 0xF9, 0xE1},  -10 },    /* BG16  47 cm */\
  {{0x1F, 0x00, 0x61, 0xE5, 0x87},  -13 },    /* BG17  67 cm */\
  {{0x1F, 0x00, 0x84, 0x7C, 0xA5},  -10 },    /* BG19  47 cm */\
  {{0x1E, 0x00, 0x90, 0x0C, 0xA5},  -11 },    /* BG20  57 cm */\
  {{0x1E, 0x00, 0x98, 0xB1, 0xC9},  -24 },    /* BG21 119 cm */\
  {{0x76, 0x00, 0x0C, 0xE3, 0xA8},  -60 },    /* BG22 300 cm */\
  \
  /* Station C */\
  {{0x1F, 0x00, 0x50, 0x3C, 0x80}, -13 },    /* BG45 66 cm */\
  {{0x1E, 0x00, 0xEA, 0xE8, 0xE9}, -37 },    /* BG46 183 cm */\
  {{0x73, 0x00, 0x56, 0x93, 0xAA},  -2 },    /* BG47 11 cm */\
  {{0x76, 0x00, 0x0D, 0x0B, 0xF7},  -7 },    /* BG48 33 cm */\
  {{0x1F, 0x00, 0x4D, 0x6A, 0x29},  -4 },    /* BG49 21 cm */\
  {{0x1F, 0x00, 0x13, 0xC9, 0x5E},  -9 },    /* BG50 43 cm */\
  \
  /* Station D, depot track */\
  {{0x1E, 0x00, 0x90, 0x0C, 0x8B},  22 },    /* BG01 110 cm */\
  {{0x73, 0x00, 0x70, 0x98, 0x69},  12 },    /* BG02  60 cm */\
  {{0x76, 0x00, 0x0C, 0xFC, 0xCB},  35 },    /* BG03 175 cm */\
  {{0x74, 0x00, 0x10, 0xF3, 0x3E},  35 },    /* BG04 175 cm */\
  {{0x76, 0x00, 0x0C, 0xA4, 0x29},  31 },    /* BG05 154 cm */\
  {{0x73, 0x00, 0x6E, 0xC8, 0x15},  31 },    /* BG06 154 cm */\
};


// OBU HW assignment
#define OBU_PIN_MOTOR 5         //  PWM signal to moter control. JeeNode port 2
                                // Note: PWM frequency is set for pin 5 in setup()
#define OBU_PIN_REVERSE_DIR A1  // Direction control. JeeNode port 2
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
