#!/bin/bash

# Update package index
sudo apt-get update

echo "Installing Zip..."
sudo apt install -y zip

# Install PHP CLI, PHP MySQL extension, and PHP AMQP extension
echo "Installing PHP CLI, PHP AMQP, and PHP SSH extensions..."
sudo apt-get install -y php-cli php-ssh2 php-amqp

echo "Setup completed successfully."
