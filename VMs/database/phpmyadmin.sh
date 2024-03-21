#!/bin/bash

# Update package index
sudo apt update

# Install phpMyAdmin and PHP for Apache
sudo apt install -y phpmyadmin libapache2-mod-php

# Restart Apache
sudo systemctl restart apache2

echo "phpMyAdmin setup completed successfully."
