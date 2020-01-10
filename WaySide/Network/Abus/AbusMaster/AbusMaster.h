/* AbusMaaster

Version 0.1
JB
*/
#ifndef AbusMaster_h
#define AbusMaster_h
#include "Arduino.h"

#define _BROADCAST 0
#define _MASTER_ADDRESS 255

const byte STX = '\2';
const byte ETX = '\3';


class AbusMaster
{
public:
  AbusMaster(byte TXenablePin);
  void Broadcast();
  int Send(byte slaveAddress, unsigned long receiveTimeout);

  byte fromS [20];
  byte toS [20];


private:
  byte _enablePin;
  int received;

  void sendMsg (const byte * data, const byte length);
  int recvMsg (byte * data,                    // buffer to receive into
                const byte length,              // maximum buffer size
                unsigned long timeout);          // milliseconds before timing out
 
};

#endif

