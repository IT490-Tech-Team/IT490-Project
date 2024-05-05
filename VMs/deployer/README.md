# Deployer VM

Welcome to the Deployer VM folder! This directory contains scripts and files related to managing package deployment for the project.

## Folder Structure:

- **sql**: Houses SQL scripts for database setup and credentials.
  - `credentials.sql`: SQL script for user authentication into the database.
  - `database.sql`: SQL script containing database schema and initial data.
- **clean.sh**: Script for cleaning up temporary files or resources.
- **dependencies.sh**: Script to install dependencies required for the deployer services.
- **phpmyadmin.sh**: Script to install and configure phpMyAdmin for database management.
- **rabbitmq.sh**: Script to set up RabbitMQ exchanges and queues.
- **sql.sh**: Script to set up the database using the provided SQL scripts.
- **services**: Houses the `manage_packages` service, responsible for managing the deployment of packages across different environments.

## Services:

### manage_packages

The `manage_packages` service handles the creation of packages and facilitates pushing a package up or down the environment stacks (dev, test, prod). It plays a crucial role in managing the deployment process for the project.

## Scripts and Files:

### clean.sh

The `clean.sh` script is responsible for cleaning up temporary files or resources generated during the operation of the deployer services.

### dependencies.sh

The `dependencies.sh` script installs necessary dependencies required for the deployer services to function properly.

### phpmyadmin.sh

The `phpmyadmin.sh` script automates the installation and configuration of phpMyAdmin, providing a user-friendly interface for managing the MySQL database.

### rabbitmq.sh

The `rabbitmq.sh` script sets up RabbitMQ exchanges and queues, facilitating message communication between different components of the deployer services.

### sql.sh

The `sql.sh` script automates the setup of the MySQL database using the SQL scripts provided in the `sql` folder. It creates the necessary tables and populates them with initial data.

Feel free to explore each script and folder to understand their functionalities and configurations better.
