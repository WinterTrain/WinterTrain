
// Train specific configuration
// ------------------------------------------------- Rail Bus, motorcar and trailer



#ifdef COMPILE_MOTOR_CAR

#define I2C_ADDR 0x30
// OBU HW assignment, motorcar
#define OBU_PIN_MOTOR 5         // PWM signal to moter control
                                // Note: PWM frequency is set for pin 5 in setup()
                                
#define OBU_PIN_DIR_CONTROL 7     // Direction control.
#define OBU_DIR_CONTROL_FORWARD   // Define if direction control HIGH means forward
#define OBU_PIN_WHITE_LIGHT 6    // White
#define OBU_PIN_RED_LIGHT 3     // Red
#define OBU_PIN_CABIN_LIGHT 9     // 


#else // Trailer

#define I2C_ADDR 0x31

// OBU HW assignment, trailer
#define OBU_PIN_WHEEL A2        // Wheel sensor  JeeNode port 3A
#define OBU_PIN_WHITE_LIGHT 7    // White
#define OBU_PIN_RED_LIGHT 6     // Red
#define OBU_PIN_CABIN_LIGHT 6   // 
#define OBU_PIN_TRACK_UP A0     // 
#define OBU_PIN_TRACK_DOWN 4    // 

#define TAG_READER_7941E        // Balise reader type 7941E
//#define TAG_READER_RDM6300      // Balise reader type RDM6300


#endif
