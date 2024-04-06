#!/bin/bash

# Update package index
sudo apt update

sudo apt install -y apache2

sudo apt install -y php libapache2-mod-php php-amqp
sudo apt install php-amqp

sudo systemctl restart apache2