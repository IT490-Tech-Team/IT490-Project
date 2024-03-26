#!/bin/bash

# Update package index
sudo apt-get update

# Install RabbitMQ
echo "Installing RabbitMQ..."
sudo apt-get install -y rabbitmq-server

# Enable RabbitMQ management plugin
sudo rabbitmq-plugins enable rabbitmq_management

# Install RabbitMQ management tool
echo "Installing RabbitMQ management tool..."
sudo wget http://localhost:15672/cli/rabbitmqadmin -O /usr/local/bin/rabbitmqadmin
sudo chmod +x /usr/local/bin/rabbitmqadmin

# Copy rabbitmq.config to the correct system folder
echo "Copying rabbitmq.config file..."
sudo cp ./rabbitmq.config /etc/rabbitmq/

# Restart RabbitMQ service
echo "Restarting RabbitMQ service..."
sudo service rabbitmq-server restart

# Install MySQL Server
echo "Installing MySQL Server..."
sudo apt-get install -y mysql-server

# Install PHP CLI, PHP MySQL extension, and PHP AMQP extension
echo "Installing PHP CLI, PHP MySQL, and PHP AMQP extensions..."
sudo apt-get install -y php-cli php-mysql php-amqp

# Log in to MySQL as root and execute SQL commands
echo "Setting up MySQL database..."
sudo mysql -u root -p <<EOF
    source ./sql/database.sql;
    source ./sql/credentials.sql;
EOF

echo "Setup completed successfully."
