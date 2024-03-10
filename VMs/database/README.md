# Database

* **`RabbitMQServer/`** ➜ Folder containing everything necessary for the databse to handle RabbitMQ messages (login, register, validate_session).
  * *`rabbitMQ.ini`* ➜ configuration file with variables for the RabbitMQ connection
  * *`rabbitMQServer.php`* ➜ RAbbitMQ reciever which currently does login, registration, and validate_session
* *`users.sql`* ➜ SQL file for initializing the usersdb, users table.
* *`books.sql`* ➜ SQL file for initializing the booksdb, and all tables related.
* *`credentials.sql`* ➜ SQL file for initializing the mysql user, and permissions.
  * this user will be used by the RabbitMQServer.php to log into and query the database.  

## Guide

1. run *`setup.sh`*
2. **(Optional)** run *`phpmyadmin.sh`*

## Running the RabbitMQ reciever

1. Go into the RabbitMQServer folder from your terminal.
2. run command `sudo ./RabbitMQServer.php`