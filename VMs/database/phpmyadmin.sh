#!/bin/bash

# Check if script is run with sudo
if [ "$(id -u)" != "0" ]; then
    echo "Please run this script with sudo."
    exit 1
fi

# Function to uninstall phpMyAdmin
uninstall_phpmyadmin() {
    apt purge -y phpmyadmin
    apt autoremove -y
    apt autoclean
}

# Check if phpMyAdmin is already installed
if [ -x "$(command -v phpmyadmin)" ]; then
    # phpMyAdmin is installed, so uninstall it first
    echo "phpMyAdmin is already installed. Uninstalling..."
    uninstall_phpmyadmin
fi

# Update package index
apt update

# Install phpMyAdmin and PHP for Apache
apt install -y phpmyadmin libapache2-mod-php

# Enable PHP mcrypt and mbstring extensions
phpenmod mcrypt
phpenmod mbstring

# Restart Apache
systemctl restart apache2

echo "phpMyAdmin setup completed successfully."
