// Train specific configuration
// ------------------------------------------- Circus Wagon

// RF12
#define OBU_ID 22 // RF12 node ID of train
#define DMI_ID 23 // RF12 node ID of assigned DMI

// Capacity
#define MAX_BALISES 8

#define N_SABALISES 14 // balise list for "stop if in shunting"
#define SA_BALISES { \
  /* End station */\
  {{0x76, 0x00, 0x0C, 0x75, 0xE5}, -1},   /* BG01 (0 cm) */\
  {{0x75, 0x00, 0x14, 0xD1, 0x25}, -1},   /* BG03 (0 cm) */\
  {{0x73, 0x00, 0x56, 0xD9, 0xB2}, -1},   /* BG02 (0 cm) */\
  {{0x76, 0x00, 0x0C, 0xFA, 0x7D}, -1},   /* BG04 (0 cm) */\
  {{0x73, 0x00, 0x70, 0x3D, 0x8D}, -15},  /* BG05 (75 cm) */\
  {{0x74, 0x00, 0x11, 0x07, 0x0B}, -19},  /* BG06 (95 cm) */\
  \
  /* Bar track */\
  {{0x74, 0x00, 0x11, 0x09, 0x64}, 120},  /* BG22 (601 cm) */\
  {{0x74, 0x00, 0x10, 0xF3, 0x3E}, 76},   /* BG25 (380 cm) */\
  {{0x76, 0x00, 0x0C, 0xFC, 0xCB}, 25},   /* BG27 (123 cm) */\
  {{0x76, 0x00, 0x0D, 0x12, 0xCB}, 1},    /* BG28 (0 cm) */\
\
  /* Outside Bar */\
  {{0x74, 0x00, 0x10, 0xE1, 0x92}, 124},  /* BG31 (619 cm) */\
  {{0x74, 0x00, 0x11, 0x04, 0x76}, 90},   /* BG32 (449 cm) */\
  {{0x73, 0x00, 0x56, 0xDC, 0x08}, 38},   /* BG33 (192 cm) */\
  {{0x73, 0x00, 0x56, 0x9B, 0xF4}, 1},    /* BG34 */\
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

// DMI HW assignment
#define DMI_PIN_BLUE 4 
#define DMI_PIN_RED 5 
#define DMI_PIN_YELLOW A3 
#define DMI_PIN_GREEN 7
#define DMI_PIN_MODE_SEL A0 
#define DMI_PIN_DIR_SEL A1 
#define DMI_PIN_DRIVE_SEL A2
#define DMI_PIN_METER 6


