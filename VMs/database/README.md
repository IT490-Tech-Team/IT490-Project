# Database

* **`services/`** ➜ folder containing php scripts that are meant to be run as services.
  * **`authentication-receiver/`** ➜ Folder containing everything necessary for the database to handle RabbitMQ messages (login, register, validate_session).
    *  **`functions/`** ➜ folder containing files that the *`rabbitMQServer.php`* uses
    * *`rabbitMQ.ini`* ➜ configuration file with variables for the RabbitMQ connection
    * *`rabbitMQServer.php`* ➜ RabbitMQ receiver which currently handles login, registration, and session validation.
    * *`authentication-receiver.service`* ➜ service file
    * *`service_setup.sh`* ➜ script file to set up the service
    * *`rabbitMQServer.php`* ➜ script file to manipulate the service
  * **`search-db-receiver/`** ➜ Folder containing everything necessary for the database to handle RabbitMQ messages (add books to database, add book covers to database).
    *  **`functions/`** ➜ folder containing files that the *`rabbitMQServer.php`* uses
    * *`rabbitMQ.ini`* ➜ configuration file with variables for the RabbitMQ connection
    * *`rabbitMQServer.php`* ➜ RabbitMQ receiver which currently handles `add` and `add_covers`.
    * *`search-db-receiver.service`* ➜ service file
    * *`service_setup.sh`* ➜ script file to set up the service
    * *`service_manipulate.sh`* ➜ script file to manipulate the service
* **`sql/`** ➜ Folder containing SQL files for initializing the databases and tables:
  * *`users.sql`* ➜ SQL file for initializing the `usersdb` and `users` table.
  * *`books.sql`* ➜ SQL file for initializing the `booksdb` and related tables.
  * *`credentials.sql`* ➜ SQL file for initializing the MySQL user and permissions. This user will be used by `RabbitMQServer.php` to log in and query the database.

## Guide

1. Run *`setup.sh`*.
2. **(Optional)** Run *`phpmyadmin.sh`*.

### Setting up the Services

To set up the services, navigate to the corresponding folder for the service you want to set up (e.g., `authentication-receiver` or `search-db-receiver`) and run the `service_setup.sh` script with sudo privileges. This script will configure the service and enable it to run on system startup.

example:
```bash
cd services/authentication-receiver
sudo ./service_setup.sh
```