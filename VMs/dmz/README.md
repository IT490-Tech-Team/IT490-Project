# DMZ

* **`/search-dmz-receiver/`** ➜ folder containing everything necessary for the DMZ to handle rabbitMQ messages pertaining to the search function.
  * *`rabbitMQ.ini`* ➜ configuration file with variables for the RabbitMQ connection
  * *`rabbitMQServer.php`* ➜ Currently does nothing but planned to fetch from the google api
  * *`search-dmz-receiver.service`* ➜ service file
  * *`service_setup.sh`* ➜ script file to set up the service
  * *`service_manipulate.sh`* ➜ script file to manipulate the service
