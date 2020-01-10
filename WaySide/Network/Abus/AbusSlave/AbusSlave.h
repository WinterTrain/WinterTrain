/* AbusSlave

Version 0.1
JB
*/
#ifndef AbusSlave_h
#define AbusSlave_h
#include "Arduino.h"

#define _BROADCAST 0
#define _MASTER_ADDRESS 255

const byte STX = '\2';
const byte ETX = '\3';


class AbusSlave
{
public:
  typedef void (*RecBroadcastCallback)  ();
  typedef boolean (*RecThisCallback)  ();

  AbusSlave(byte slaveAddress, byte TXenablePin, unsigned long receiveTimeout, RecBroadcastCallback recBroadcast, RecThisCallback recThis);
  int poll();

  byte fromM [21];
  byte toM [20];


private:
  byte _enablePin;
  byte _slaveAddress;
  unsigned long _receiveTimeout;

  RecBroadcastCallback _recBroadcast;
  RecThisCallback _recThis;

  int received;
  void sendMsg (const byte * data, const byte length);
  int recvMsg (byte * data,                    // buffer to receive into
                const byte length,              // maximum buffer size
                unsigned long timeout);          // milliseconds before timing out
 
};

#endif

