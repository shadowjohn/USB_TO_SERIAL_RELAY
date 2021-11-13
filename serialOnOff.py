# pip install pyserial
import serial
import sys
import time
argc = len(sys.argv)

message = '''
  Serial Trigger Relay Program.
  Use serial DTR (PIN 4) and GND (PIN 5) trigger relay.
                                            
  Author : Feather Mountain ( https://3wa.tw/ )
  Version : V0.01
                                                
  Usage :
    python serialOnOff.py [Com Port] [on/off] [millisecond]
    # DTR ON
    python serialOnOff.py com7 on 15000
    
    # DTR OFF
    python serialOnOff.py com7 off 5000
'''    

if argc!=4:
    print(message)
    sys.exit()

COM_PORT = sys.argv[1]
ON_OFF = sys.argv[2].upper()
MLS = (int)(sys.argv[3])    
ser = serial.Serial(COM_PORT,9600,timeout=1) 
if ON_OFF == "ON":
    ser.setDTR(True)
    ser.close()
    ser.open()
    time.sleep(MLS/1000.0)
    ser.close()

else:
    ser.setDTR(False)
    ser.close()
    ser.open()
    time.usleep(MLS/1000.0)
    ser.close()