#!/bin/bash

# Check if script is run with sudo
if [ "$(id -u)" != "0" ]; then
    echo "Please run this script with sudo."
    exit 1
fi

# Function to install and set up services
setup_services() {
    cd ./search-dmz-receiver
    ./service_setup.sh
    cd ../
}

# Update package index
apt update

# Install PHP CLI, PHP MySQL extension, and PHP AMQP extension
apt install -y php-cli php-amqp
