-- Prerequisites

PHP Version 7.0.30

-- Deployment

All the PHP files need to go on any folder web accessible in a web server that has PHP installed. Then you need to create a folder "JSON" to place the JSON file (madrid.json)

The directory tree should look like this:

/getMOstSuitableByLocation.php
/getMOstSuitableByTime.php
/JSON/madrid.json

-- Testing

/********************************************/
getMOstSuitableByLocation.php
/********************************************/

It can make use of three OPTIONAL different variables as arguments:
location
	String
district
	String
category
	String

It can use all of them, just a few of them, or none of them.

Examples:
getMOstSuitableByLocation.php?location=indoors&district=Retiro&category=cultural
getMOstSuitableByLocation.php?location=indoors&category=cultural
getMOstSuitableByLocation.php?location=indoors&district=Retiro
getMOstSuitableByLocation.php?district=Retiro&category=cultural
getMOstSuitableByLocation.php?location=indoors
getMOstSuitableByLocation.php?category=cultural
getMOstSuitableByLocation.php?district=Retiro
getMOstSuitableByLocation.php

In case no variables are sent as arguments in the PHP call the script will return everything inside the JSON file.

/********************************************/
getMOstSuitableByTime.php
/********************************************/

It can make use of three MANDATORY different variables as arguments:
time 
	Expressed as [start_time-end_time] formatted as follows "HH:MM-HHMM" in format 00:00-23:59
day
	Expressed as [weekday] formatted as a 2-letter string (mo, tu, we, th, fr, sa, su)
category
	String

Examples:

getMOstSuitableByTime.php?time=10:30-16:00&day=mo&category=shopping
getMOstSuitableByTime.php?time=13:30-16:00&day=tu&category=cultural
getMOstSuitableByTime.php?time=13:30-16:00&day=we&category=shopping
