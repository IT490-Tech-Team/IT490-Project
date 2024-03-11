#!/bin/bash

# Check if script is run with sudo
if [ "$(id -u)" != "0" ]; then
    echo "Please run this script with sudo."
    exit 1
fi

# Function to install and set up services
setup_services() {
    cd ./services/search-frontend-receiver
    ./service_setup.sh
    cd ../../
}

# Update package index
apt update

sudo apt install -y apache2

sudo apt install -y php libapache2-mod-php php-amqp

sudo systemctl restart apache2

# Install and set up services
setup_services