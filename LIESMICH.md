# Rhythmische Gymnastik Live Resultate

**Das Skript wurde 2013 entwickelt und ist zur Zeit nicht vorgesehen es weiter zu entwickeln.** Auf Github soll es anderen Nutzern weiter zur Verfügung stehen.

![screenshots](https://github.com/steinger/rg-resultate/blob/master/screenshot/rg_resultate.png)

Dieses Skript ist für all diejenigen gedacht, die bei einem Rhythmische Sport Gymnastik Wettkampf eine Rangliste erstellen möchten. Es können laufen die Noten eingegeben werden. Daraufhin wird gleich eine Rangliste auf dem Webserver angezeigt. Das Skript ist für Mobile Devices Optimiert. Note D und Note E als Optional und nur als Zusatzinformation.

## Funktionen

* Startliste 
* Rangliste
* Rangliste nach Geräte
* Eingabe der Noten
* Optimiert für Mobilegeräten

## Voraussetzungen

* Linux WebServer mit PHP 5.4 oder höher und MySQL
* JQuery 1.8.3
* JQuery Mobile 1.3.2

## Installation

- Das Script in ein Webserver Verzeichnis kopieren.
- config.sample.php kopieren nach config.php und anpassen.
- database.sql auf MySQL importieren.

## Vorbereitung

- In der Tabelle ```rg_resultate``` müssen vor dem Wettkampf die Felder startnr name und kat der Teilnehmenden eingetragen werden. Als Hilfe kann dieser über das ```config/rg\_resultate\_input.csv``` erfasst werden und dann in die SQL einlesen werden. 
- Unter ```config/rg_location.txt``` ist der Veranstalter und Ort einzugeben.

## Ablauf
- Die Eingaben der Noten erfolgt über ```input/index.php```