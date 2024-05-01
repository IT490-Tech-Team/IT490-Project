# Project Replication Guide

## VM Specifications

Each virtual machine (VM) in the project requires the following specifications:

- Storage: At least 8 GB of storage space.
- RAM: Preferably 2 GB of RAM. However, if 2 GB is not available, a minimum of 1 GB of RAM with 1-2 GB of SWAP is acceptable.
- CPU: Access to a minimum of 2 CPU cores.
- Operating System: Linux-based operating system. The preferred OS is Ubuntu Server 22.

It's important to note that there's no need to specify specific users for the VMs. As long as the user performing the installation is able to execute sudo commands, it will suffice.

Each VM in the `/VMs` folder contains a dependencies script that automatically installs all necessary software for that machine. This dependencies script is triggered by the installer script, simplifying the setup process.

## Deployer

Set up the deployer machine to manage package deployment and orchestrate setup across the project environment.

### Setting Up the Deployer Machine

1.  Run `./startup.sh` in the `/scripts` folder and follow the directions.
2. execute `./set_hostname deployer` to set the hostname to "deployer".
3. Join a Tailscale network. 
   - Note: The "tech team" Tailscale network already has the environment set up, so you can't join that one. However, if you were to use the "tech team" Tailscale network, you can use `./tailscale_login.sh` also located in `/scripts`.
4. Navigate to the `/VMs/deployer` folder.
5. Run the following scripts in order to set up dependencies, RabbitMQ, and SQL:
   - `dependencies.sh`: This script installs necessary dependencies for the project.
   - `rabbitmq.sh`: This script sets up RabbitMQ, a message broker used in the project.
   - `sql.sh`: This script configures SQL-related components required for the project.
6. After setting up the deployer machine, navigate back to the `/scripts` folder.
7. Run the `service_manager.sh` script to manage services on the deployer machine.
8. Specifically, install the "manage-packages" service by running:
   1. ```./service_manager.sh install manage-packages```

Once you've completed the above steps, the deployer machine should be set up with all the necessary dependencies and services configured. You can proceed with replicating other components of the project as needed.

## Dev Cluster

Set up the development cluster consisting of backend, DMZ, and frontend machines for testing and development purposes.

### Backend Machine

1. Run `./startup.sh` in the `/scripts` folder and follow the directions.
2. Execute `./set_hostname.sh dev backend` to set the hostname to "dev-backend".
3. Join the Tailscale network used by the deployer machine.
   - Note: The "tech team" Tailscale network already has the environment set up, so you can't join that one. However, if you were to use the "tech team" Tailscale network, you can use `./tailscale_login.sh` also located in `/scripts`.
4. Once joined to the network, navigate to the root of the project.
5. Execute the installer script with the following command:
   1. ```./installer.sh -backend -a -b -c -d -e -f -g -h -i -j```
   2. The script may prompt for your password during installation.

### DMZ Machine

1. Run `./startup.sh` in the `/scripts` folder and follow the directions.
2. Execute `./set_hostname.sh dev dmz` to set the hostname to "dev-dmz".
3. Join the Tailscale network used by the deployer machine.
   - Note: The "tech team" Tailscale network already has the environment set up, so you can't join that one. However, if you were to use the "tech team" Tailscale network, you can use `./tailscale_login.sh` also located in `/scripts`.
4. Once joined to the network, navigate to the root of the project.
5. Execute the installer script with the following command:
   1. ```./installer.sh -dmz -a -b -c -d -e -f -g -h -i -j```
   2. The script may prompt for your password during installation.

### Frontend Machine

1. Run `./startup.sh` in the `/scripts` folder and follow the directions.
2. Execute `./set_hostname.sh dev frontend` to set the hostname to "dev-frontend".
3. Join the Tailscale network used by the deployer machine.
   - Note: The "tech team" Tailscale network already has the environment set up, so you can't join that one. However, if you were to use the "tech team" Tailscale network, you can use `./tailscale_login.sh` also located in `/scripts`.
4. Once joined to the network, navigate to the root of the project.
5. Execute the installer script with the following command:
   1. ```./installer.sh -frontend -a -b -c -d -e -f -g -h -i -j```
   2. The script may prompt for your password during installation.

By this time, you should have a working dev environment where you can access the website using the Tailscale domain name of the dev frontend machine, provided you're in the same Tailscale network as all the machines.

## Test Cluster

You have two options for setting up the test cluster machines:

1. **Replicate Steps from Dev Environment:** You can replicate the steps used for setting up the machines in the dev environment. Simply change `./set_hostname.sh dev` argument to `./set_hostname.sh test` for each machine.
2. **Using the Deployer (Optional):** Alternatively, you can utilize the deployer to streamline the setup process.

### Using the Deployer (Optional)

1. Go to one of the machines in the dev environment.
2. Execute `./create-package.sh` script located in `/scripts/deployer` with the following arguments:
   - `[ENVIRONMENT]`: Specify the environment (e.g., "dev").
   - `[NAME OF PACKAGE]`: Name of the package to create.
   - `[INSTALLATION FLAGS]`: Installation flags (-a through -j map to different parts of the installer. Full install is denoted by using all flags from a to j).
   - i.e. `./create-package.sh dev main -a -b -c -d -e -f -g -h -i -j`
   - This script will send a message to the deployer, which then sends the message to all machines in the test environment.

### Replicate These Steps For Backend, DMZ, and Frontend
1. Run `./startup.sh` in the `/scripts` folder and follow the directions.
2. Execute `./set_hostname.sh test` with the second argument being `backend` `dmz`, or `frontend` depending on the machine.
3. Join the Tailscale network used by the deployer machine.
   - Note: The "tech team" Tailscale network already has the environment set up, so you can't join that one. However, if you were to use the "tech team" Tailscale network, you can use `./tailscale_login.sh` also located in `/scripts`.
4. Once joined to the network, navigate to the root of the project.
5. Run `dependencies.sh` script located in `/scripts/deployer/` to install necessary dependencies on each machine.
6. Use `./get-latest-package.php` with the following arguments on each machine:
   - `arg1`: Environment (in this case, "test").
   - `arg2`: Machine type (`backend`, `dmz`, or `frontend`).
   - i.e. `./get-latest-package.php test backend`
   - The script will prompt for the user's admin password.

## Prod Cluster

You have two options for setting up the production cluster machines:

1. **Replicate Steps from Test Environment:** You can replicate the steps used for setting up the machines in the dev environment. Simply change `./set_hostname.sh dev` to `./set_hostname.sh prod` for each machine.

2. **Using the Deployer (Optional):** Alternatively, you can utilize the deployer to streamline the setup process.

### Pushing Package to Production (Optional)

To push a package from the test environment to production:

1. Access one of the test machines.
2. Run the script `manage-package.php` located in `/scripts/deployer` with the following arguments:
   - `[ENVIRONMENT]`: Specify the environment (e.g., "test").
   - `[ACTION]`: Action to perform (e.g., "accept").
   - `[PACKAGE NAME]`: Name of the package to push to production.
   - `[PACKAGE VERSION]`: Version of the package to push to production.
   - Example: `test accept main 1`

### Replicate These Steps For Backend, DMZ, and Frontend

1. Run `./startup.sh` in the `/scripts` folder and follow the directions.
2. Execute `./set_hostname.sh prod` with the second argument being `backend`, `dmz`, or `frontend` depending on the machine.
3. Join the Tailscale network used by the deployer machine.
   - Note: The "tech team" Tailscale network already has the environment set up, so you can't join that one. However, if you were to use the "tech team" Tailscale network, you can use `./tailscale_login.sh` also located in `/scripts`.
4. Once joined to the network, navigate to the root of the project.
5. Run `dependencies.sh` script located in `/scripts/deployer/` to install necessary dependencies on each machine.
6. Use `./get-latest-package.php` with the following arguments on each machine:
   - `arg1`: Environment (in this case, "test").
   - `arg2`: Machine type (`backend`, `dmz`, or `frontend`).
   - Example: `./get-latest-package.php test backend`
   - The script will prompt for the user's admin password.

## Prod-Backup Cluster

Set up the production backup cluster to ensure redundancy and data integrity in the production environment.

To set up the production backup environment, follow the steps used for the dev environment, but with the following adjustments for hostname:

1.  execute `./set_hostname.sh prod [backend|dmz|frontend] backup` for each machine.
   - i.e. `./set_hostname prod backend backup` 

## Database Replication Setup

Ensure database replication is properly configured to maintain data integrity and redundancy. This will set up a "master-master" relationship between both backends.

### Setting Up Replication

1. **Primary Database (Prod-Backend):**
   - Go to the `prod-backend` machine.
   - Navigate to the `/VMs/backend` folder.
   - Execute the `replication.sh` script with the following command:
     ```
     ./replication.sh main parent
     ```
   
2. **Backup Database (Prod-Backend-Backup):**
   - Go to the `prod-backend-backup` machine.
   - Navigate to the `/VMs/backend` folder.
   - Execute the `replication.sh` script with the following command:
     ```
     ./replication.sh backup child
     ```
   - After executing the above command, run:
     ```
     ./replication.sh backup parent
     ```
   
3. **Completing Replication Setup:**
   - Return to the `prod-backend` machine.
   - Execute the `replication.sh` script with the following command:
     ```
     ./replication.sh main child
     ```
   
4. **Restart MySQL Server:**
   - To ensure changes take effect, restart MySQL Server on both systems.
   - On each system, run the following command:
     ```
     sudo service mysql-server restart
     ```

After completing these steps, database replication should be properly configured between the primary and backup databases, ensuring data consistency and redundancy.

## Load Balancer

Set up the load balancer to distribute traffic across multiple servers for improved performance and reliability.

1. Run `./startup.sh` in the `/scripts` folder and follow the directions.
2. Execute `./set_hostname.sh <preferred_hostname>` to set the hostname to your preferred domain name for the website.
3. Join the Tailscale network used by the deployer machine. If needed, use `./tailscale_login.sh` in the `/scripts` folder to log in to Tailscale.
4. Once joined to the network, navigate to the `/VMs/load-balancer/` folder.
5. Run the `docker.sh` script located inside the folder to install Docker. After running the script, exit or logout and log back in to apply the changes.
6. After logging back in, navigate to the `/containers/traefik/` folder.
7. Run `docker-compose up -d` to start the Traefik container in detached mode.

## Accessing the Production Website

After completing all the setup steps sequentially, you should be able to access the production website from the load balancer domain as long as you're connected to the Tailscale network.

### Server Switching Functionality

To switch between servers, follow these steps:

1. Open your browser's developer tools and navigate to the "Storage" or "Application" tab.
2. Add a cookie named "server" with one of the following values: "dev", "test", or "prod", depending on the desired server.
3. Once the cookie is set, refresh the page.
4. A link will be added to the navbar that allows you to move from website to website, depending on the value of the "server" cookie.

By following these steps, you can easily switch between servers and access the corresponding website from the load balancer domain.
