// Train specific configuration
// ------------------------------------------------- Cargo train (new)

// define either OVERRIDE_SR, OVERRIDE_SH or none to allow OBU switch to override RBC mode authority
// OBU_PIN_OVERRIDE must be defined as well
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

#define N_SABALISES 35 // balise list for "stop if in shunting"
// Distances engineered in "wheel turns". OBU will start braking at that distance, allow for 80 cm braking distance
#define SA_BALISES { \
    /* Holmen, End station, braking point at 80 cm in rear of buffer stop */\
    \
{{0x1E, 0x00, 0xEC, 0xF9, 0xE1},  -24},    /* BG1 / -121 cm */\
{{0x1E, 0x00, 0x8E, 0x57, 0xF6},  -27},    /* BG2 / -136 cm */\
{{0x1E, 0x00, 0x56, 0xDE, 0x2A},  -49},    /* BG3 / -246 cm */\
{{0x1E, 0x00, 0x8E, 0xDF, 0x1F},  -72},    /* BG4 / -361 cm */\
{{0x1E, 0x00, 0x57, 0x35, 0xF9},  -96},    /* BG5 / -481 cm */\
    \
  /* Station Christiania */\
{{0x1F, 0x00, 0x69, 0xF3, 0xBB},  -2},    /* BG6 / -11 cm */\
{{0x1F, 0x00, 0x50, 0x3C, 0x80},  -6},    /* BG7 / -29 cm */\
{{0x1E, 0x00, 0xAC, 0x75, 0x9B},  -25},    /* BG10 / -125 cm */\
{{0x1E, 0x00, 0xB0, 0x50, 0x33},  -48},    /* BG11 / -242 cm */\
{{0x1E, 0x00, 0xAC, 0x98, 0x25},  -70},    /* BG12 / -352 cm */\
\
  /* Station Langtbortistan */\
{{0x1F, 0x00, 0x4B, 0x83, 0x72},  2},    /* BG42 / 12 cm */\
{{0x1E, 0x00, 0x8E, 0x70, 0x11},  6},    /* BG40 / 28 cm */\
{{0x1E, 0x00, 0x90, 0x73, 0x4B},  19},    /* BG38 / 95 cm */\
{{0x1E, 0x00, 0x57, 0xDC, 0x9F},  59},    /* BG35 / 295 cm */\
{{0x1F, 0x00, 0x6A, 0x63, 0xA2},  99},    /* BG34 / 495 cm */\
    \
  /* Station Højbanen */\
{{0x73, 0x00, 0x56, 0x9F, 0xA1},  -9},    /* BG44 / -44 cm */\
{{0x74, 0x00, 0x15, 0x50, 0xC0},  -6},    /* BG43 / -29 cm */\
{{0x76, 0x00, 0x0C, 0xE3, 0xA8},  2},    /* BG41 / 11 cm */\
{{0x74, 0x00, 0x11, 0x07, 0x0B},  42},    /* BG39 / 211 cm */\
{{0x76, 0x00, 0x0D, 0x19, 0x5B},  82},    /* BG37 / 411 cm */\
    \
  /* Signal S6 */\
{{0x1F, 0x00, 0x4D, 0x6A, 0x29},  12},    /* BG13 / 61 cm */\
{{0x1E, 0x00, 0xAC, 0xCA, 0x72},  -28},    /* BG14 / -139 cm */\
{{0x1E, 0x00, 0x57, 0x4C, 0xC8},  -68},    /* BG15 / -339 cm */\
{{0x1E, 0x00, 0x57, 0x07, 0x51},  -109},    /* BG17 / -543 cm */\
{{0x1F, 0x00, 0x4B, 0x02, 0x3D},  -115},    /* BG21 / -576 cm */\
    \
  /* Signal S19 */\
{{0x1F, 0x00, 0x4A, 0xFE, 0x5F},  -16},    /* BG30 / -78 cm */\
{{0x1F, 0x00, 0x61, 0xEF, 0x11},  -0},    /* BG29 / -1 cm */\
{{0x1F, 0x00, 0x69, 0xB5, 0x36},  19},    /* BG28 / 97 cm */\
{{0x1F, 0x00, 0x62, 0x75, 0x10},  35},    /* BG26 / 177 cm */\
{{0x1F, 0x00, 0x4D, 0x4B, 0xFE},  43},    /* BG22 / 215 cm */\
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
