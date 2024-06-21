#include <LiquidCrystal.h>
#include <DHT.h>

#include "DHT.h"

#define DHTPIN 6     // what pin we're connected to

#define DHTTYPE DHT11   // DHT 11


LiquidCrystal lcd(12, 11, 5, 4, 3, 2);

DHT dht(DHTPIN, DHTTYPE);

void setup()
{
  Serial.begin(9600);
//   for (int DigitalPin = 7; DigitalPin <= 9; DigitalPin++) 
//  {
//   pinMode(DigitalPin, OUTPUT);
//  }
  lcd.begin(16,2); //16 by 2 character display
  
dht.begin();
}
 
void loop()
{
  delay(1000);
  // Reading temperature or humidity takes about 250 milliseconds!
  // Sensor readings may also be up to 2 seconds 'old' (its a very slow sensor)
  float h = dht.readHumidity();
  // Read temperature as Celsius (the default)
  float t = dht.readTemperature();
  lcd.clear();
  if (isnan(h) || isnan(t))
  {
    lcd.setCursor(0,0);
    lcd.print("Failed");
  }
  else{
  lcd.setCursor(0,0);
  lcd.print("Temp: ");
  lcd.print(t);
  lcd.print("'C");
  
  lcd.setCursor(0,1);
  lcd.print("Humid: ");
  lcd.print(h);
  lcd.print("%");
  
  }
  
}

 
