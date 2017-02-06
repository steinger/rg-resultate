# Rhythmic Gymnastics Live Results

**The script was developed in 2013 and is currently not planned to be further developed.** On Github it is to be available to other users.

![screenshots](https://github.com/steinger/rg-resultate/blob/master/screenshot/rg_resultate.png)

This script is intended for all those who want to create a ranking list at a rhythmic sports gymnastics competition. The notes can be entered. A ranking list is displayed on the web server. The script is optimized for mobile devices. Note D and Note E as optional and only as additional information.

**The script written in German**

## Installation

* Linux WebServer with PHP 5.4 or higher and MySQL
* JQuery 1.8.3
* JQuery Mobile 1.3.2

## Requirements

- Copy script on webserver directory
- config.sample.php copy to config.php and edit.
- database.sql impot to MySQL.

## Preparation

- First input the data on table ```rg\resultate``` for fieds startnr name and kat. Manually or with the help of ```config/rg_resultate_input.csv``` and inport this on MySQL.
- Set on ```config/rg_location.txt``` organizer and location. 

## Usage
- The results are entered over ```input/index.php```