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

#define N_SABALISES 12 // balise list for "stop if in shunting"
// Distances are stated in "wheel turns".Train will start braking at that distance from the balise
#define SA_BALISES { \
  /* End station */\
  {{0x74, 0x00, 0x11, 0x07, 0x0B}, -1},   /* BG01 (0 cm) */\
  {{0x76, 0x00, 0x0C, 0xB7, 0x36}, -11},   /* BG03 (0 cm) */\
  {{0x75, 0x00, 0x14, 0xD2, 0x51}, -26},   /* BG02 (0 cm) */\
  {{0x74, 0x00, 0x15, 0x65, 0x29}, -34},   /* BG04 (0 cm) */\
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


