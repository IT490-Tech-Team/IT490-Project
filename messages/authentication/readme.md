# authentication

March 04, 5:50 PM

This is the latest progress we've made with the authentication. So far we've gotten RabbitMQ to work locally and remotely through my current rabbitmq server (OSCAR).

## website folder

This folder is the equivalent of apache /var/www/html

- index.html → place holder for the homepage.
- _register_ folder → http://localhost/register
  - index.html → registration page
  - main.js → sends request to register.php
  - register.php → sends a rabbitmq 
  - _RabbitMQClient_ folder → rabbitmq client dependencies
    - get_host_info.inc
    - host.ini
    - path.inc
    - RabbitMQ.ini 
    - RabbitMQClient.php → **possibly does nothing but don't want to test**
    - rabbitMQLib.inc


## server folder
This folder should run inside of the database machine

- get_host_info.inc → *dependencies*
- host.ini → *dependencies*
- path.inc → *dependencies*
- RabbitMQ.ini → **config**
- RabbitMQLib.inc → *dependencies*
- RabbitMQServer.php → what recieves the client request from the website

## RabbitMQServer.php

- Currently it's set up with 3 message types
  - login, register, and validate_session, each has their own function