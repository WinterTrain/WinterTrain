// Train specific configuration
// ------------------------------------------------- Circus Wagon

// define either OVERRIDE_SR, OVERRIDE_SH or none to allow OBU switch to override RBC mode authority
#define OVERRIDE_SR

// RF12
#define OBU_ID 22 // RF12 node ID of train
#define DMI_ID 23 // RF12 node ID of assigned DMI

// Capacity
#define MAX_BALISES 8

// Configuration
#define DETECT_NOM_DIR AUTO_DETECT // UP, DOWN or AUTO_DETECT
#define BRAKING_DISTANCE 18
#define SH_BREAKE_DIST 0
#define MAX_DRIVE 100
#define VMAX_SH 70
#define SHORT_MA 30
#define STOP_MA 3 // Distance (wheel turn) below this will show red on DMI
#define V_OFFSET 30
#define MIN_DIST 2 // Distance (wheel turn) below this will be ignored
#define DRIVE_CORRECTION_LIMIT 35 // Drive order below this limt will be corrected over time
 
// Specific engineering ------------------------ Stop if in shunting
// Balises commanding "stop if in shunting"
// Distance measured in tacometer pulses 

#define N_SABALISES 17 // balise list for "stop if in shunting"
// Distances engineered in "wheel turns". OBU will start braking at that distance, allow for 80 cm braking distance
#define SA_BALISES { \
  /* Christianshavn, end station, braking point at 80 cm in rear of buffer stop */\
  \  
{{0x74, 0x00, 0x11, 0x07, 0x0B},  -2},    /* BG3 / -12 cm */\
{{0x74, 0x00, 0x15, 0x50, 0xC0},  -10},    /* BG5 / -52 cm */\
{{0x1E, 0x00, 0x57, 0x35, 0xF9},  -18},    /* BG7 / -91 cm */\
{{0x1E, 0x00, 0xEC, 0xF9, 0xE1},  -3},    /* BG4 / -13 cm */\
{{0x1F, 0x00, 0x69, 0xF3, 0xBB},  -9},    /* BG6 / -43 cm */\
{{0x1F, 0x00, 0x4D, 0x6D, 0x73},  -15},    /* BG8 / -77 cm */\
{{0x1E, 0x00, 0x90, 0x0C, 0xA5},  -36},    /* BG9 / -180 cm */\
    \
  /* Station Langtbortistan */\
{{0x1F, 0x00, 0x78, 0xD7, 0xB2},  7},    /* BG36 / 37 cm */\
{{0x1F, 0x00, 0x69, 0xA9, 0x3C},  25},    /* BG33 / 125 cm */\
{{0x1F, 0x00, 0x4D, 0x6A, 0x29},  95},    /* BG32 / 475 cm */\
{{0x1E, 0x00, 0x98, 0xB1, 0xC9},  128},    /* BG30 / 640 cm */\
{{0x1F, 0x00, 0x62, 0x25, 0xB2},  152},    /* BG27 / 759 cm */\
    \
  /* Station Højbanen */\
{{0x76, 0x00, 0x0C, 0xFC, 0xCB},  4},    /* BG38 / 22 cm */\
{{0x1E, 0x00, 0x57, 0x4C, 0xC8},  32},    /* BG37 / 162 cm */\
{{0x1F, 0x00, 0x78, 0xBF, 0xB4},  63},    /* BG35 / 316 cm */\
{{0x1F, 0x00, 0x78, 0xBA, 0xE8},  96},    /* BG34 / 481 cm */\
{{0x1F, 0x00, 0x4D, 0xFF, 0xCC},  132},    /* BG31 / 661 cm */\
    \
};

// OBU HW assignment
#define OBU_PIN_MOTOR 5         //  PWM signal to moter control. JeeNode port 2
                                // Note: PWM frequency is set for pin 5 in setup()
#define OBU_PIN_DIR_CONTROL A1  // Direction control. JeeNode port 2
//#define OBU_DIR_CONTROL_FORWARD // Define if direction control HIGH means forward
#define OBU_PIN_TRACK_UP 4      // JeeNode port 1
#define OBU_PIN_TRACK_DOWN A0   // JeeNode port 1
//#define OBU_PIN_RED A1          // For indication (outout) (Circus has no LED)
//#define OBU_PIN_BLUE  A2      // Blue LED, JeeNode port 3 (Circus has no LED)
#define OBU_PIN_OVERRIDE A2     // Override switch, JeeNode port 3
#define OBU_PIN_WHEEL  6        // Wheel sensor  JeeNode port 3
#define OBU_PIN_FLIGHT  A3      // Front light, JeeNode port 4
#define OBU_PIN_RLIGHT  7       // Rear light, JeeNode port 4

#define TAG_READER_7941E        // Balise reader type 7941E
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
