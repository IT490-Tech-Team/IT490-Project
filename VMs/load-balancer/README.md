# Load Balancer

Welcome to the Load Balancer folder! This directory contains files and scripts to set up a load balancer for distributing traffic across multiple servers, providing improved performance and reliability for your project. 

It uses NagiOS for monitoring and Traefik for load balancing between all frontends.

> **Note:** The preferred hostname for this PC is "website," but you can choose any preferred hostname during the setup process.

## Folder Structure:

- **docker.sh**: Script to install Docker on the machine, enabling containerized deployment of services.
- **containers**: Directory housing Docker container configurations for running Traefik and Nagios.

### Containers:

- **nagios**: Folder containing configuration files for running the Nagios monitoring system using Docker Compose.
- **traefik**: Folder containing configuration files for running the Traefik load balancer using Docker Compose.

## Setup Instructions:

1. **Install Docker:**
   - Execute the `docker.sh` script to install Docker on the machine.
     ```bash
     bash docker.sh
     ```
2. **Restart Computer:**
   - After running `docker.sh`, restart the computer to apply the changes.

3. **Run Traefik Container:**
   - Navigate to the `./containers/traefik` directory.
   - Run the following command to start the Traefik container in detached mode:
     ```bash
     docker-compose up -d
     ```

4. **Run Nagios Container:**
   - Navigate to the `./containers/nagios` directory.
   - Run the following command to start the Nagios container in detached mode:
     ```bash
     docker-compose up -d
     ```

By following these setup instructions, you'll have a load balancer set up using Traefik and a monitoring system deployed with Nagios, all containerized for easy management and scalability.

Feel free to explore each file and directory for further customization and configuration as needed.
