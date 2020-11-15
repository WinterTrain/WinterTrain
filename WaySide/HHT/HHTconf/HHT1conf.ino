// HHT specific configuration

// RF12
#define HHT_ID 50  // RF12 node id of HHT1


#define TAG_READER_7941E        // Balise reader type 7941E
//#define TAG_READER_RDM6300      // Balise reader type RDM6300

// HW assignment
#define HHT_PIN_RED2 5
#define HHT_PIN_BLUE 6
#define HHT_PIN_RED A3
#define HHT_PIN_YELLOW A0 
#define HHT_PIN_GREEN A2

#define HHT_PIN_K1 3
#define HHT_PIN_K2 4
#define HHT_PIN_K3 9
#define HHT_PIN_K4 A1


// DMI
const int OBU_TIMEOUT = 1000; // Timeout for lost connection to OBU

const byte obuIDlist[] = {20, 22, 24};
const byte dmiIDlist[] = {21, 23, 25};
char trainTxt[3][9] = {"Gods    ", "Cirkus  ", "Passager"};
