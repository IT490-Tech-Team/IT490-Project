# VMs Folder

This folder serves to organize all the files necessary to run the project into separate folders, each dedicated to a different Virtual Machine (VM). Below is an outline of the folder structure and the purpose of each VM:

## VMs

### 1. Database
This folder contains files related to the database VM. It includes setup scripts, background service scripts, and an SQL folder containing SQL files for database initialization and maintenance.

### 2. DMZ
The DMZ (Demilitarized Zone) folder houses files specific to the DMZ VM. It includes setup scripts, a service script, and an emails job script for managing email communications.

### 3. Frontend
The Frontend folder holds everything related to the website frontend. This includes setup scripts, web application files, frontend frameworks, assets, and configurations.

### 4. RabbitMQ
The RabbitMQ folder contains files related to the RabbitMQ VM. It includes setup scripts for proper communication setup, background service scripts, and configurations.

## VM-Agnostic Folders

### 1. Services
The Services folder, present in some VMs, houses background services necessary for running the website. Each service within this folder follows a specific setup structure:
- **RabbitMQServer.php**: Script executed for each service.
- ***.service**: File necessary for the service to be recognized by the Linux machine.
- **service_manipulate.sh**: Script managing the service.
- **service_setup.sh**: Script installing the service on the machine.
- **/functions**: Contains all functions used in the PHP file of the service.

Feel free to provide additional explanations or details for each section as needed. This README provides a basic overview of the folder structure and the purpose of each VM and VM-agnostic folder within the project.
