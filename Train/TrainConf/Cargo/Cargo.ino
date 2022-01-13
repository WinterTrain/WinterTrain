// Train specific configuration
// ------------------------------------------------- Cargo train (new)

// define either OVERRIDE_SR, OVERRIDE_SH or none to allow OBU switch to override RBC mode authority
#define OVERRIDE_SR

// RF12
#define OBU_ID 20 // RF12 node ID of train
#define DMI_ID 21 // RF12 node ID of assigned DMI

// Capacity
#define MAX_BALISES 8

// Configuration
#define DETECT_NOM_DIR AUTO_DETECT // UP, DOWN or AUTO_DETECT
#define BRAKING_DISTANCE 18
#define SH_BREAKE_DIST 0
#define MAX_DRIVE 100
#define VMAX_SH 60
#define SHORT_MA 30
#define STOP_MA 3 // Distance (wheel turn) below this will show red on DMI
#define V_OFFSET 30
#define MIN_DIST 2 // Distance (wheel turn) below this will be ignored
#define DRIVE_CORRECTION_LIMIT 25 // Drive order below this limt will be corrected over time

// Specific engineering ------------------------ Stop if in shunting
// Balises commanding "stop if in shunting"
// Distance measured in tacometer pulses

#define N_SABALISES 12 // balise list for "stop if in shunting"
// Distances engineered in "wheel turns". OBU will start braking at that distance, allow for 80 cm braking distance
#define SA_BALISES { \
    /* Christianshavn, end station, braking point at 80 cm in rear of buffer stop */\
    \
    {{0x1E, 0x00, 0x57, 0x5B, 0x28},  -1},    /* BG5 / 3 cm */\
    {{0x76, 0x00, 0x0C, 0xE3, 0xA8},  -1},    /* BG7 / 7 cm */\
    {{0x1E, 0x00, 0xEC, 0xF9, 0xE1},  -9},    /* BG10 / -46 cm */\
    {{0x1F, 0x00, 0x78, 0xEF, 0xFD},  -9},    /* BG9 / -46 cm */\
    {{0x74, 0x00, 0x11, 0x07, 0x0B},  -19},    /* BG12 / -97 cm */\
    {{0x1F, 0x00, 0x68, 0xD5, 0xC8},  -43},    /* BG13 / -214 cm */\
    \
  /* Station Langtbortistan */\
    {{0x1F, 0x00, 0x69, 0xF3, 0xBB},  -5},    /* BG50 / -26 cm */\
    {{0x1F, 0x00, 0x62, 0x74, 0x36},  9},    /* BG48 / 43 cm */\
    {{0x1F, 0x00, 0x62, 0xA3, 0xD5},  29},    /* BG44b / 143 cm */\
    {{0x1E, 0x00, 0x90, 0x0C, 0xA5},  61},    /* BG44a / 307 cm */\
    {{0x1F, 0x00, 0x4D, 0xFF, 0xCC},  91},    /* BG44 / 455 cm */\
    {{0x73, 0x00, 0x56, 0xC0, 0x72},  116},    /* BG43 / 580 cm */\
    \
  }

// OBU HW assignment
#define OBU_PIN_MOTOR 5         // PWM signal to moter control. JeeNode port 2
// Note: PWM frequency is set for pin 5 in setup()
#define OBU_PIN_DIR_CONTROL 7   // Direction control.
#define OBU_DIR_CONTROL_FORWARD // Define if direction control HIGH means forward
#define OBU_PIN_TRACK_UP A0     // JeeNode port 1
#define OBU_PIN_TRACK_DOWN 4    // JeeNode port 1
#define OBU_PIN_RED A1          // For indication (outout)
//#define OBU_PIN_BLUE 3        // For indication (outout)
#define OBU_PIN_OVERRIDE 3      // For mode override (input)
#define OBU_PIN_WHEEL A2        // Wheel sensor  JeeNode port 4
#define OBU_PIN_FLIGHT A3       // Front light
#define OBU_PIN_RLIGHT 6        // Rear light

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
