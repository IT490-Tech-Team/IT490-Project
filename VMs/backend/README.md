# Backend VM

Welcome to the Backend VM folder! This directory contains scripts and files related to setting up the backend services for the project.

## Folder Structure:

- **services**: Contains RabbitMQ consumers responsible for handling messages for different services.
  - `authentication-receiver`: Consumer for handling authentication-related messages.
  - `discussion-receiver`: Consumer for handling discussion-related messages.
  - `email-signup-receiver`: Consumer for handling email signup-related messages.
  - `reviews-receiver`: Consumer for handling reviews-related messages.
  - `search-db-receiver`: Consumer for handling search database-related messages.
- **sql**: Houses SQL scripts for database setup and credentials.
  - `credentials.sql`: SQL script for user authentication into the database.
  - `database.sql`: SQL script containing database schema and initial data.
- **dependencies.sh**: Script to install dependencies required for the backend services.
- **firewall.sh**: Script to configure the firewall using UFW.
- **phpmyadmin.sh**: Script to install and configure phpMyAdmin for database management.
- **rabbitmq.sh**: Script to set up RabbitMQ exchanges and queues.
- **sql.sh**: Script to set up the database using the provided SQL scripts.

## Services:

### authentication-receiver

The `authentication-receiver` consumer handles messages related to user authentication, including login and registration requests.

### discussion-receiver

The `discussion-receiver` consumer manages messages related to discussions, such as posting, commenting, and replying to discussions.

### email-signup-receiver

The `email-signup-receiver` consumer processes messages for email signup functionality, including sending verification emails and handling email confirmation requests.

### reviews-receiver

The `reviews-receiver` consumer deals with messages concerning book reviews, including submitting reviews, rating books, and retrieving review data.

### search-db-receiver

The `search-db-receiver` consumer handles messages for searching the database, including searching for books, authors, genres, and other relevant data.

## Scripts and Files:

### dependencies.sh

The `dependencies.sh` script installs necessary dependencies required for the backend services to function properly.

### firewall.sh

The `firewall.sh` script uses UFW (Uncomplicated Firewall) to create a firewall configuration, ensuring secure communication for the backend services.

### phpmyadmin.sh

The `phpmyadmin.sh` script automates the installation and configuration of phpMyAdmin, providing a user-friendly interface for managing the MySQL database.

### rabbitmq.sh

The `rabbitmq.sh` script sets up RabbitMQ exchanges and queues, facilitating message communication between different components of the backend services.

### sql.sh

The `sql.sh` script automates the setup of the MySQL database using the SQL scripts provided in the `sql` folder. It creates the necessary tables and populates them with initial data.

Feel free to explore each script and folder to understand their functionalities and configurations better.
