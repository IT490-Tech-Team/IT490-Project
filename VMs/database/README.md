# Database

* **`RabbitMQServer/`** ➜ Folder containing everything necessary for the database to handle RabbitMQ messages (login, register, validate_session).
  * *`rabbitMQ.ini`* ➜ configuration file with variables for the RabbitMQ connection
  * *`rabbitMQServer.php`* ➜ RabbitMQ receiver which currently handles login, registration, and session validation.
* **`sql/`** ➜ Folder containing SQL files for initializing the databases and tables:
  * *`users.sql`* ➜ SQL file for initializing the `usersdb` and `users` table.
  * *`books.sql`* ➜ SQL file for initializing the `booksdb` and related tables.
  * *`credentials.sql`* ➜ SQL file for initializing the MySQL user and permissions. This user will be used by `RabbitMQServer.php` to log in and query the database.

## Guide

1. Run *`setup.sh`*.
2. **(Optional)** Run *`phpmyadmin.sh`*.

## Running the RabbitMQ Receiver

1. Navigate to the RabbitMQServer folder from your terminal.
2. Run the command `sudo ./RabbitMQServer.php`.
