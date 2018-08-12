#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <ESP8266WebServer.h>
#include <ESP8266mDNS.h>
#include <ESP8266HTTPUpdateServer.h>

ESP8266WebServer httpServer(80);
ESP8266HTTPUpdateServer httpUpdater;

const char* host = "esp8266-webupdate";
const char* ssid     = "Idan";
const char* password = "0506582131";

const char* server = "93.178.245.130";

#define CACHE_ITEMS 10
#define CACHE_SIZE 32

char cachedArray[CACHE_ITEMS][CACHE_SIZE];
unsigned int cachedTime[CACHE_ITEMS];

boolean stringComplete = false;  // whether the string is complete
char received[1000];

void sendHtmlMessage(char* strMsg) {
    HTTPClient http;

    char request[1024];
    sprintf(request,"http://93.178.245.130/rfid/scan.php?rfid=%s",strMsg);
    http.begin(request); //HTTP

    int httpCode = http.GET();
    http.end();
}

void setup() {
  Serial.begin(115200);
  delay(10);

  // We start by connecting to a WiFi network
  Serial.println();
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);
  
  WiFi.begin(ssid, password);
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected");  
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
  
  MDNS.begin(host);
  httpUpdater.setup(&httpServer);
  httpServer.begin();
  MDNS.addService("http", "tcp", 80);

  received[0] = '[';
  received[1] = 0;

  stringComplete = false;

 // sendHtmlMessage(WiFi.localIP());

 for (int i=0; i<CACHE_ITEMS; i++) {
    cachedArray[i][0] = 0;
    cachedTime[i] = 0;
 }

    Serial.println("start");
    sendHtmlMessage("start");

}

int timeToPing = 180*100+1;
int len = 1;

void loop() {
  
  httpServer.handleClient();
  
  delay(10);
  ++timeToPing;

   if (timeToPing>180*100) //every 180sec
   {
      sendHtmlMessage("ping");
      //Serial.println("ping");
      Serial.println(WiFi.localIP());
      timeToPing = 0;
   }
  
   for (int i=0; i<CACHE_ITEMS; i++) {
      if (cachedTime[i]>0) {
        cachedTime[i]--;
      }
   }
 
    while (Serial.available())
   {
    // get the new byte:
    char inChar = (char)Serial.read();
    
    //Serial.print(inChar);
    if ((inChar < 40)||(inChar > 94)) {  //drop unused symbols
      continue;
    }
    
    if (inChar == '>') {  // end line marker
      stringComplete = true;
    }else{
       received[len++] = inChar;
    }

     if (stringComplete) {
       received[len++] = ']';
       received[len] = 0;

      //Serial.println(len);
      if ((len>10)&&(len<39)) {
         bool inCache = 0;
         int freeCache = -1;
         for (int i=0; i<CACHE_ITEMS;i++) {
            if (cachedTime[i]>0) {
              if (inCache==0) {
                if (strcmp(cachedArray[i],received)==0) {
                  inCache = 1;
                  //break;
                }
              }
            } else {
              freeCache = i;
            }
         };
         if (inCache==0) {
           sendHtmlMessage(received);
           if (freeCache>=0) {
             strcpy(cachedArray[freeCache], received);
             cachedTime[freeCache] = 500; 
             Serial.println(received);
             Serial.println("cached");
           }
          }else{
           Serial.println(received);
           Serial.println("in cache");
         }
      }   
        //Serial.println(received);

      // clear the string:
      received[0] = '[';
      len = 1;
      stringComplete = false;
     }
    
   }
  //====================================================================================    
 // unsigned long timeout = millis();
 //   if (millis() - timeout > 5000) {
 //     Serial.println(">>> Client Timeout !");
 //   }
  
  // Read all the lines of the reply from server and print them to Serial
  //while(client.available()){
  //  String line = client.readStringUntil('\r');
  //  Serial.print(line);
  //}
  
//  Serial.println();
//  Serial.println("closing connection");
}
