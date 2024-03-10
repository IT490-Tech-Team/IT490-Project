#!/bin/bash

# Check if script is run with sudo
if [ "$(id -u)" != "0" ]; then
    echo "Please run this script with sudo."
    exit 1
fi

# Update package index
apt update

# Install PHP CLI, PHP MySQL extension, and PHP AMQP extension
apt install -y php-cli php-amqp
