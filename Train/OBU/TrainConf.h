// Train specific configuration
// --------------------------------------------------- Cargo train

// RF12
#define OBU_ID 20 // RF12 node ID of train
#define DMI_ID 21 // RF12 node ID of assigned DMI

// Capacity
#define MAX_BALISES 8

// Specific engineering ------------------------ Stop if in shunting
// Balises commanding "stop if in shunting"
// Distance measured in tacometer pulses 

#define N_SABALISES 13 // balise list for "stop if in shunting"
// Distances engineered in "wheel turns". OBU will start braking at that distance, allow for 80 cm braking distance
#define SA_BALISES { \
  /* End station, braking point at 100 cm in rear of buffer stop */\
  {{0x76, 0x00, 0x0C, 0xFA, 0x7D}, -1},    /* BG01 (-5 cm) */\
  {{0x73, 0x00, 0x6E, 0xC8, 0x15}, -13},   /* BG03 (-65 cm) */\
  {{0x73, 0x00, 0x70, 0x3D, 0x8D}, -1},    /* BG02 (-5 cm) */\
  {{0x75, 0x00, 0x14, 0xFB, 0x94}, -13},   /* BG04 (-65 cm) */\
  {{0x76, 0x00, 0x0C, 0xE3, 0xA8}, -26},   /* BG05 (-130 cm) */\
  \
  /* Bar track */\
  {{0x73, 0x00, 0x56, 0xD6, 0xF2}, 1},    /* BG29 (5 cm) */\
  {{0x73, 0x00, 0x56, 0xD9, 0xB2}, 4},    /* BG28 (20 cm) */\
  {{0x73, 0x00, 0x70, 0x98, 0x69}, 44},   /* BG25 (220 cm) */\
  {{0x76, 0x00, 0x0C, 0xFC, 0xCB}, 94},   /* BG24 (470 cm) */\
\
  /* Outside Bar */\
  {{0x74, 0x00, 0x15, 0x65, 0x29}, 108},  /* BG30 (544 cm) */\
  {{0x73, 0x00, 0x56, 0xC0, 0x72}, 68},   /* BG32 (343 cm) */\
  {{0x74, 0x00, 0x10, 0xF3, 0x3E}, 29},   /* BG33 147 cm) */\
  {{0x73, 0x00, 0x56, 0x93, 0xAA}, 1},    /* BG34 */\
};

// OBU HW assignment
#define OBU_PIN_MOTOR 5         //  PWM signal to moter control. JeeNode port 2
                                // Note: PWM frequency is set for port 5 in setup()
#define OBU_PIN_REVERSE_DIR A1  // Direction control. JeeNode port 2
#define OBU_PIN_TRACK_UP A0     //  JeeNode port 1
#define OBU_PIN_TRACK_DOWN 4    // JeeNode port 1
#define OBU_PIN_BLUE A3
#define OBU_PIN_WHEEL 7         // Wheel sensor  JeeNode port 4
#define OBU_PIN_FLIGHT 6        // Front light
#define OBU_PIN_RLIGHT A2       // Rear light

// DMI HW assignment
#define DMI_PIN_BLUE 4, 
#define DMI_PIN_RED 5
#define DMI_PIN_YELLOW A3
#define DMI_PIN_GREEN 7
#define DMI_PIN_MODE_SEL A0
#define DMI_PIN_DIR_SEL A1
#define DMI_PIN_DRIVE_SEL A2
#define DMI_PIN_METER 6


