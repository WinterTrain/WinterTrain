
// Train specific configuration
// ------------------------------------------------- Rail Bus, motorcar and trailer

#define DEFAULT_WHITE_LIGHT_NORMAL 180
#define DEFAULT_WHITE_LIGHT_BRIGHT 255
#define DEFAULT_RED_LIGHT 255
#define DEFAULT_CABIN_LIGHT_RED     0xff 
#define DEFAULT_CABIN_LIGHT_GREEN   0x70
#define DEFAULT_CABIN_LIGHT_BLUE    0x0c

#define PIN_INTERNAL_LED 13

#ifdef COMPILE_MOTOR_CAR // ----------------------------- Motor car

#define VERSION 0x0102
#define I2C_ADDR 0x30
// OBU HW assignment, motorcar
#define PIN_MOTOR 5         // PWM signal to moter control
                                // Note: PWM frequency is set for pin 5 in setup()
                                
#define PIN_DIR_CONTROL 7         // Direction control.
#define DIR_CONTROL_FORWARD LOW   // Define output level for direction forward
#define DIR_CONTROL_REVERSE HIGH  // Define output level for direction reverse
#define PIN_WHITE_LIGHT 6         // White
#define PIN_RED_LIGHT 3           // Red
#define PIN_CABIN_LIGHT 9         // Cabin

#define MAX_MOTOR_PWM 100


#else // ------------------------------------------------ Trailer

#define I2C_ADDR 0x31
#define VERSION 0x0202

// OBU HW assignment, trailer
#define PIN_WHEEL 2           // Wheel sensor, monitored via intyerrupt, hence pin 2 or 3
#define PIN_WHITE_LIGHT 3     // White
#define PIN_RED_LIGHT 4       // Red
#define PIN_CABIN_LIGHT 6     // 
#define PIN_RIGHT_WHEEL 8    // Polarity sense for right wheel 
#define PIN_LEFT_WHEEL 11      // 
#define PIN_UPS_ENABLE  7     // 
#define PIN_CAP_VOLTAGE A1
#define PIN_OBU_SHUTDOWN 10

#define TAG_READER_7941E        // Balise reader type 7941E
//#define TAG_READER_RDM6300      // Balise reader type RDM6300

#define T_ON_MIN 2000
#define T_OFF_MIN 2000
#define UPS_SHUTDOWN_TIME 15000
#define UPS_WAIT_TIME 5000

#define VCAP_80 820
#define VCAP_MIN 760
#endif
