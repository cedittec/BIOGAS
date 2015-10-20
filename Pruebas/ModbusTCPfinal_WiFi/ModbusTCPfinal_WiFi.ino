#include <SPI.h>
#include <Ethernet.h>
#include <EscapedString.h>          
#include <IndentedPrint.h>
#include <JsonArray.h>
#include <JsonArrayBase.h>
#include <JsonObject.h>
#include <JsonObjectBase.h>
#include <JsonPrettyPrint.h>
#include <JsonPrintable.h>
#include <JsonValue.h>
#include <Print.h>
#include <Printable.h>
#include <StringBuilder.h>
#include <WiFi.h>

//Credenciales y estado del radio WiFi...
char ssid[] = "cedittec";     
char pass[] = "querty1234";  
int status = WL_IDLE_STATUS;
WiFiClient cliente;     

using namespace ArduinoJson::Generator; 
byte mac[] = {0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED};    
IPAddress ip(192,168,250,22);                             
IPAddress myDns(1,1,1,1);                              
IPAddress myGateway(192,168,250,1);                      
EthernetClient client;                                 
char server[] = "192.168.250.20";   
int datos[62];
int data[100];
byte message[]={
0x00, 0x01, 0x00, 0x00, 0x00, 0x06, 0x01, 0x03, 0x00, 0x00,0x00, 0x1A};

float g = (5.0/1023);                   
int Cc = 12;                            
float Cv1 = 131.1;                      
float Cv2 = 130.81;                     
float Cv3 = 129.34;
float Sensores[11];
unsigned long E = 0;

void setup()
{
  Serial.begin(9600);
  delay(1000);
  Ethernet.begin(mac, ip, myDns, myGateway);
  delay(1000);
  E = millis();
  //Luego de conectarse a la Ethernet, se conecta a la WiFi
  while ( status != WL_CONNECTED) {
    Serial.print("Attempting to connect to WPA SSID: ");
    Serial.println(ssid);
    // Connect to WPA/WPA2 network:
    status = WiFi.begin(ssid, pass);

    // wait 10 seconds for connection:
    delay(10000);
  }
}

void loop()
{
  msjModbus();
  delay(500);
  int a = 0;
  
  while (client.available())
  {
    int c = client.read();
    datos[a] = c;
    a++;
  }
  client.stop();
  
  int carry = 9;
  for(int i = 0; i<26; i++)
  {
    int High = datos[carry];
    carry ++;
    High = High << 8;
    //Serial.println(High);
    byte Low = datos[carry];
    carry ++;
    //Serial.println(Low);
    data[i] = float(High + Low);
    //Serial.println(data[i]);  
  }
  
   for (int i =0; i<6 ; i++)
   {
     Sensores[i] = Prom(i);
   }
   
   Sensores[6] = Prom(7);
     
   Sensores[0] = ((Sensores[0]) * Cv1) - 124.65;
   Sensores[1] = ((Sensores[1]) * Cv2) - 125.47;
   Sensores[2] = ((Sensores[2])* Cv3) - 125.07;
   Sensores[3] = Sensores[3] * Cc;
   Sensores[4] = Sensores[4] * Cc;
   Sensores[5] = Sensores[5] * Cc;
   Sensores[6] = Sensores[6] * 1;
   
 
   for (int i =0; i<6 ; i++)
   {
     if(Sensores[i] < 0 )
     {
       Sensores[i] = 0;
     }
   }
   
   Sensores[7] = Sensores[0]* Sensores[3];
   Sensores[8] = Sensores[1]* Sensores[4];
   Sensores[9] = Sensores[2]* Sensores[5];
   Sensores[10] = Sensores[7] + Sensores[8] +Sensores[9];  
   
  int clock = millis()-E;
  //Serial.println("En espera");
  //Serial.println(clock);
  
  //if (millis() - E > 600000 || millis() - E < 0)
  //{
    
  JsonObject<22> controladorMicroturbina;

  controladorMicroturbina["Fecha"] = data[0]+":"+data[1]+":"data[2]+" "+data[3]+":"+data[4]+":"+data[5];
  controladorMicroturbina["H2S"] = data[6];
  controladorMicroturbina["CH4"] = data[7];
  controladorMicroturbina["CO2"] = data[8];
  controladorMicroturbina["O2"] = data[9];
  controladorMicroturbina["Celda op. 1"] = data[10];
  controladorMicroturbina["Celda op. 2"] = data[11];
  controladorMicroturbina["PhaseACurrentRMS"] = data[15];
  controladorMicroturbina["PhaseBCurrent RMS"] = data[16];
  controladorMicroturbina["PhaseCCurrent RMS"] = data[17];
  controladorMicroturbina["Neutral Current RMS"] = data[18];
  controladorMicroturbina["Phase AN Voltaje RMS"] = data[19];
  controladorMicroturbina["Phase BN Voltaje RMS"] = data[20];
  controladorMicroturbina["Phase CN Voltaje RMS"] = data[21];
  controladorMicroturbina["Phase A Power Average"] = data[22];
  controladorMicroturbina["Phase B Power Average"] = data[23];
  controladorMicroturbina["Phase C Power Average"] = data[24];
  controladorMicroturbina["Total Average Power"] = data[25];
      
 
 JsonObject<33> controladorBiofiltro;

  controladorMicroturbina["Fecha"] = data[26]+":"+data[27]+":"data[28]+" "+data[29]+":"+data[30]+":"+data[31];
  controladorBiofiltro["Year"] = data[26];
  controladorBiofiltro["Mes"] = data[27];
  controladorBiofiltro["Dia"] = data[27];
  controladorBiofiltro["Hora"] = data[29];
  controladorBiofiltro["Min"] = data[30];
  controladorBiofiltro["Seg"] = data[31];
  controladorBiofiltro["H2S"] = data[32];
  controladorBiofiltro["CH4"] = data[33];
  controladorBiofiltro["CO2"] = data[34];
  controladorBiofiltro["O2"] = data[35];
  controladorBiofiltro["Temperatura"] = data[36];
  controladorBiofiltro["Presion"] = data[37];
  controladorBiofiltro["Flujo"] = data[38];
 
 
  //armar objeto de Analogicas
  JsonObject<11> analogicas;
  
  analogicas["V1"] = Sensores[0];
  analogicas["V2"] = Sensores[1];
  analogicas["V3"] = Sensores[2];
  analogicas["I1"] = Sensores[3];
  analogicas["I2"] = Sensores[4];
  analogicas["I3"] = Sensores[5];
  analogicas["Flujo"] = Sensores[6];
  analogicas["P1"] = Sensores[7];
  analogicas["P2"] = Sensores[8];
  analogicas["P3"] = Sensores[9];
  analogicas["PT"] = Sensores[10];
  
  JsonObject<2> mTurbina;    
  mTurbina["controladorBiofiltro"] = controladorBiofiltro;
  mTurbina["controladorMicroturbina"] = controladorMicroturbina;
  mTurbina["Analogicas"] = analogicas;

  //Serial.println(mTurbina);
  cliente.print("http://132.254.39.213/biogas/biogas.php?");cliente.print("controladorMicroturbina['Fecha']");
  cliente.print("&&data[0]+":"+data[1]+":"data[2]+" "+data[3]+":"+data[4]+":"+data[5]");
  cliente.print("&&controladorMicroturbina['H2S']");
  cliente.print("&&data[6]");
  cliente.print("&&controladorMicroturbina['CH4']");
  cliente.print("&&data[7]");
  cliente.print("&&controladorMicroturbina['CO2']");
  cliente.print("&&data[8]");
  cliente.print("&&controladorMicroturbina['O2']");
  cliente.print("&&data[9]");
  cliente.print("&&controladorMicroturbina['Celda op. 1']");
  cliente.print("&&data[10]");
  cliente.print("&&controladorMicroturbina['Celda op. 2']");
  cliente.print("&&data[11]");
  cliente.print("&&controladorMicroturbina['PhaseACurrentRMS']");
  cliente.print("&&data[15]");
  cliente.print("&&controladorMicroturbina['PhaseBCurrent RMS']");
  cliente.print("&&data[16]");
  cliente.print("&&controladorMicroturbina['PhaseCCurrent RMS']");
  cliente.print("&&data[17]");
  cliente.print("&&controladorMicroturbina['Neutral Current RMS']");
  cliente.print("&&data[18]");
  cliente.print("&&controladorMicroturbina['Phase AN Voltaje RMS']");
  cliente.print("&&data[19]");
  cliente.print("&&controladorMicroturbina['Phase BN Voltaje RMS']");
  cliente.print("&&data[20]");
  cliente.print("&&controladorMicroturbina['Phase CN Voltaje RMS']");
  cliente.print("&&data[21]");
  cliente.print("&&controladorMicroturbina['Phase A Power Average']");
  cliente.print("&&data[22]");
  cliente.print("&&controladorMicroturbina['Phase B Power Average']");
  cliente.print("&&data[23]");
  cliente.print("&&controladorMicroturbina['Phase C Power Average']");
  cliente.print("&&data[24]");
  cliente.print("&&controladorMicroturbina['Total Average Power']");
  cliente.print("&&data[25]");
  cliente.print("&&controladorMicroturbina['Fecha']");
  cliente.print("&&data[26]+":"+data[27]+":"data[28]+" "+data[29]+":"+data[30]+":"+data[31]");
  cliente.print("&&controladorBiofiltro['Year']");
  cliente.print("&&data[26]");
  cliente.print("&&controladorBiofiltro['Mes']");
  cliente.print("&&data[27]");
  cliente.print("&&controladorBiofiltro['Dia']");
  cliente.print("&&data[27]");
  cliente.print("&&controladorBiofiltro['Hora']");
  cliente.print("&&data[29]");
  cliente.print("&&controladorBiofiltro['Min']");
  cliente.print("&&data[30]");
  cliente.print("&&controladorBiofiltro['Seg']");
  cliente.print("&&data[31]");
  cliente.print("&&controladorBiofiltro['H2S']");
  cliente.print("&&data[32]");
  cliente.print("&&controladorBiofiltro['CH4']");
  cliente.print("&&data[33]");
  cliente.print("&&controladorBiofiltro['CO2']");
  cliente.print("&&data[34]");
  cliente.print("&&controladorBiofiltro['O2']");
  cliente.print("&&data[35]");
  cliente.print("&&controladorBiofiltro['Temperatura']");
  cliente.print("&&data[36]");
  cliente.print("&&controladorBiofiltro['Presion']");
  cliente.print("&&data[37]");
  cliente.print("&&controladorBiofiltro['Flujo']");
  cliente.print("&&data[38]");
  cliente.print("&&analogicas['V1']");
  cliente.print("&&Sensores[0]");
  cliente.print("&&analogicas['V2']");
  cliente.print("&&Sensores[1]");
  cliente.print("&&analogicas['V3']");
  cliente.print("&&Sensores[2]");
  cliente.print("&&analogicas['I1']");
  cliente.print("&&Sensores[3]");
  cliente.print("&&analogicas['I2']");
  cliente.print("&&Sensores[4]");
  cliente.print("&&analogicas['I3']");
  cliente.print("&&Sensores[5]");
  cliente.print("&&analogicas['Flujo']");
  cliente.print("&&Sensores[6]");
  cliente.print("&&analogicas['P1']");
  cliente.print("&&Sensores[7]");
  cliente.print("&&analogicas['P2']");
  cliente.print("&&Sensores[8]");
  cliente.print("&&analogicas['P3']");
  cliente.print("&&Sensores[9]");
  cliente.print("&&analogicas['PT']");
  cliente.print("&&Sensores[10]");
  
  
  E = millis();
  //}

  delay(600000);
}

void msjModbus() 
{
  if (client.connect(server, 502)) 
  {
    client.write(message, sizeof(message));
    //Serial.println("mensaje enviado");
  } 
  else 
  {
    client.stop();
    for(int j = 0; j<62; j++)
    {
      datos[j]=0;
    }
  }
}

float Prom(int x)
 {
   float A = 0;
   for (int j =0; j<10 ; j++)
     {
      int a = analogRead(x);
      float h = a * g;
      A = A + h;
      delay (1);
     }
     A = A/10;
     return A;
 }
