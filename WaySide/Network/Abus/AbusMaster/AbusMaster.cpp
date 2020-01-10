#include "Arduino.h"
#include "AbusMaster.h"

AbusMaster::AbusMaster(byte TXenablePin) {
  _enablePin = TXenablePin;
  pinMode(_enablePin,OUTPUT);
  digitalWrite(_enablePin,LOW);
// Serial.begin() has to be called in setup()
}

// JB 2015-05-26: Do not call Send or Broadcast from a wire receiver function (ref. Wire.onReceive),
// as the send buffer empty condition will not work.


int AbusMaster::Send(byte slaveAddress,unsigned long receiveTimeout) {
  toS[0] = slaveAddress; // receiver
  toS[1] = _MASTER_ADDRESS; // sender
  digitalWrite (_enablePin, HIGH);  // enable sending
  sendMsg (toS, sizeof toS);
  while (!(UCSR0A & (1 << UDRE0)))  // Wait for empty transmit fromSfer
    UCSR0A |= 1 << TXC0;  // mark transmission not complete
  while (!(UCSR0A & (1 << TXC0)));   // Wait for the transmission to complete
  digitalWrite (_enablePin, LOW);  // disable sending
  received = recvMsg (fromS, sizeof fromS,receiveTimeout);
  if (received > 0) {
    if (fromS[0] == _MASTER_ADDRESS && fromS[1] == slaveAddress) {
      return received; // OK
    } 
    else {
      return -1; // Error
    } 
  } 
  else {
    return received; //Timeout eller fejl;
  }
}

void AbusMaster::Broadcast() {
  toS[0] = _BROADCAST;
  toS[1] = _MASTER_ADDRESS; 
  digitalWrite (_enablePin, HIGH);  // enable sending
  sendMsg (toS, sizeof toS);
  while (!(UCSR0A & (1 << UDRE0)))  // Wait for empty transmit buffer
    UCSR0A |= 1 << TXC0;  // mark transmission not complete
  while (!(UCSR0A & (1 << TXC0)));   // Wait for the transmission to complete
  digitalWrite (_enablePin, LOW);  // disable sending
}

// calculate 8-bit CRC
static byte crc8 (const byte *addr, byte len)
{
  byte crc = 0;
  while (len--) 
    {
    byte inbyte = *addr++;
    for (byte i = 8; i; i--)
      {
      byte mix = (crc ^ inbyte) & 0x01;
      crc >>= 1;
      if (mix) 
        crc ^= 0x8C;
      inbyte >>= 1;
      }  // end of for
    }  // end of while
  return crc;
}  // end of crc8

// send a byte complemented, repeated
// only values sent would be (in hex): 
//   0F, 1E, 2D, 3C, 4B, 5A, 69, 78, 87, 96, A5, B4, C3, D2, E1, F0
void sendComplemented (const byte what)
{
byte c;

  // first nibble
  c = what >> 4;
  Serial.write ((c << 4) | (c ^ 0x0F)); 

  // second nibble
  c = what & 0x0F;
  Serial.write ((c << 4) | (c ^ 0x0F)); 
  
}  // end of sendComplemented

// send a message of "length" bytes (max 255) to other end
// put STX at start, ETX at end, and add CRC
void AbusMaster::sendMsg (const byte * data, const byte length)
{
  Serial.write (STX);  // STX
  for (byte i = 0; i < length; i++)
    sendComplemented (data [i]);
  Serial.write (ETX);  // ETX
  sendComplemented (crc8 (data, length));
}  // end of sendMsg

// receive a message, maximum "length" bytes, timeout after "timeout" milliseconds
// if nothing received, or an error (eg. bad CRC, bad data) return 0
// otherwise, returns length of received data
int AbusMaster::recvMsg (byte * data,                    // buffer to receive into
              const byte length,              // maximum buffer size
              unsigned long timeout)          // milliseconds before timing out
  {              
    
  unsigned long start_time = millis ();
  
  bool have_stx = false;

  // variables below are set when we get an STX
  bool have_etx;
  byte input_pos;
  bool first_nibble;
  byte current_byte;

  while (millis () - start_time < timeout)
    {
    if (Serial.available () > 0) 
      {
      byte inByte = Serial.read ();
  
      switch (inByte)
        {
  
        case STX:   // start of text
          have_stx = true;
          have_etx = false;
          input_pos = 0;
          first_nibble = true;
          start_time = millis ();  // reset timeout period
          break;
          
        case ETX:   // end of text
          have_etx = true;   
          break;
    
        default:
          // wait until packet officially starts
          if (!have_stx)
            break;   
            
          // check byte is in valid form (4 bits followed by 4 bits complemented)
          if ((inByte >> 4) != ((inByte & 0x0F) ^ 0x0F) )
            return -4;  // bad character
            
          // convert back 
          inByte >>= 4;
             
          // high-order nibble?
          if (first_nibble)
            {
            current_byte = inByte;
            first_nibble = false;
            break;
            }  // end of first nibble
            
          // low-order nibble
          current_byte <<= 4;
          current_byte |= inByte;
          first_nibble = true;
          
          // if we have the ETX this must be the CRC
          if (have_etx)
            {
            if (crc8 (data, input_pos) != current_byte)
              return -5;  // bad crc  
            return input_pos;  // return received length
            }  // end if have ETX already
            
          // keep adding if not full
          if (input_pos < length)
            data [input_pos++] = current_byte;
          else
            return -6;  // overflow
          break;
  
        }  // end of switch
      }  // end of incoming data
    } // end of while not timed out
    
  return -7;  // timeout
} // end of recvMsg

