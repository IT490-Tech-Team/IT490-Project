#!/bin/bash

# Check if script is run with sudo
if [ "$(id -u)" != "0" ]; then
    echo "Please run this script with sudo."
    exit 1
fi

# Update package index
apt update

sudo apt install -y apache2

sudo apt install -y php libapache2-mod-php php-amqp

sudo systemctl restart apache2
