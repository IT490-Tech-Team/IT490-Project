#!/bin/bash

# Update package index
sudo apt update

sudo apt install -y zip

# Install PHP CLI, PHP MySQL extension, and PHP AMQP extension
sudo apt install -y php-cli php-amqp php-curl php-ssh2
