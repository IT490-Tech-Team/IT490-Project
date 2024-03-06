#!/bin/bash

# Check if script is run with sudo
if [ "$(id -u)" != "0" ]; then
    echo "Please run this script with sudo."
    exit 1
fi

# Install RabbitMQ
echo "Installing RabbitMQ..."
apt-get update
apt-get install -y rabbitmq-server

# Enable RabbitMQ management plugin
rabbitmq-plugins enable rabbitmq_management

# Install RabbitMQ management tool
echo "Installing RabbitMQ management tool..."
wget http://localhost:15672/cli/rabbitmqadmin -O /usr/local/bin/rabbitmqadmin
chmod +x /usr/local/bin/rabbitmqadmin

# Copy rabbitmq.config to the correct system folder
echo "Copying rabbitmq.config file..."
cp ./rabbitmq.config /etc/rabbitmq/

echo "Setup completed successfully."
