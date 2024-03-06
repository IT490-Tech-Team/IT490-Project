#!/bin/bash

# Check if script is run with sudo
if [ "$(id -u)" != "0" ]; then
    echo "Please run this script with sudo."
    exit 1
fi

# Update package index
apt update

# Install MySQL Server
apt install -y mysql-server

# Run MySQL security script
mysql_secure_installation

# Install phpMyAdmin
apt install -y phpmyadmin

# Enable PHP mcrypt and mbstring extensions
phpenmod mcrypt
phpenmod mbstring

# Restart Apache
systemctl restart apache2

echo "Setup completed successfully."
