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

# Install PHP CLI, PHP MySQL extension, and PHP AMQP extension
apt install -y php-cli php-mysql php-amqp

# Log in to MySQL as root
sudo mysql -u root -p <<EOF
    # Execute SQL commands from user-database.sql
    source ./user-database.sql;
EOF

echo "MySQL setup completed successfully."
