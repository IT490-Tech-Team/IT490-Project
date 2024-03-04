# authentication

March 04, 5:50 PM

This is the latest progress we've made with the authentication. So far we've gotten RabbitMQ to work locally and remotely through my current rabbitmq server (OSCAR).

## website folder

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

- get_host_info.inc → *dependencies*
- host.ini → *dependencies*
- path.inc → *dependencies*
- RabbitMQ.ini → **config**
- RabbitMQLib.inc → *dependencies*
- RabbitMQServer.php →