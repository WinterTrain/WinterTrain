// WinterTrain DMI next generation - DMI configuration

// DMI identity
#define DMI_ID "DMIng01"

// OBU identity
// #define OBU_ADDR 10.0.0.206 // FIXME to be dependent on wifi AP
#define OBU_PORT 9910

// Timers
#define TIMER_CHECK_AVAIABILITY 3000  // How often to check availability of connected wifi
#define TIMER_WIFI_RETRY 10000        // Timer for reconnecting to wifi
#define TIMER_OBU_CONNECT 5000        // Timer for reconnecting to OBU
#define TIMER_OBU_LOGIN 2000          // Timer for OBU login
#define TIMER_UPDATE 1000             // Timer for update of meter in ST_DMI_STDBY
#define FLASH1TIME 500
#define FLASH5TIME 100

// Counters
#define MAX_WIFI_RETRY 5
#define MAX_DMI_RETRY 5

// HW assignment
#define PIN_IND {4,5,3, 1, 0, 2}
#define PIN_OPERATION_SEL A2  // Operation selector
#define PIN_MODE_SEL A1       // Mode selector
#define PIN_DIR_SEL A3        // Direction selector
#define PIN_DRIVE_SEL A4      // Drive selector
#define PIN_METER 6           // Meter

#define SERIAL_SPEED 115200
#define CMDBUFFER_SIZE 20

// LiPo management
// Voltage divider at ADC input: R1 =  330000, R2 = 1200000

#define BAT_ADC_FACTOR 0.001027222// = 3.3 / 4096 * (R1 + R2)) / R2
#define BAT_C_PERCENT 111 // = 100 / (VBAT_FULL - VBAT_EMPTY)
#define VBAT_FULL 4.2
#define VBAT_EMPTY 3.3
#define C_BAT 2


// Enummerations

// LoginState
#define LS_UDEF 0
#define LS_ACCEPTED 1
#define LS_REJECTED 2

// Indications
#define IND_WHITE 0
#define IND_BLUE 1
#define IND_RED_OPR 2
#define IND_RED 3
#define IND_YELLOW 4
#define IND_GREEN 5
#define OFF 0
#define ON 1
#define FLASH1 2
#define FLASH5 3

#define OPR_OPR 3
#define OPR_STDBY 2
#define OPR_OFF 1

#define DIR_BACKWARD 1
#define DIR_NEUTRAL 2
#define DIR_FORWARD 3

#define MODE_N    1
#define MODE_SR   2
#define MODE_SH   3
#define MODE_FS   4
#define MODE_ATO  5

// WiFi state machine
#define ST_WIFI_BOOT 0
#define ST_WIFI_START 1
#define ST_WIFI_WAIT 5
#define ST_WIFI_FAILED  3
#define ST_WIFI_AVAILABLE 4
#define ST_WIFI_GOTO_SLEEP 254
#define ST_WIFI_UDEF 255

// DMI state machine
#define ST_DMI_BOOT     0
#define ST_DMI_STDBY    1
#define ST_DMI_AWAIT_WIFI     2
#define ST_DMI_TCP      8
#define ST_DMI_TCP_WAIT   3
#define ST_DMI_LOGIN    4
#define ST_DMI_LOGIN_WAIT    5
#define ST_DMI_RUN     9
#define ST_DMI_REJECTED     10
#define ST_DMI_FAILED       7
#define ST_DMI_UDEF 255
