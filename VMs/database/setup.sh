#!/bin/bash

# Update package index
sudo apt update

# Install MySQL Server
sudo apt install -y mysql-server

# Install PHP CLI, PHP MySQL extension, and PHP AMQP extension
sudo apt install -y php-cli php-mysql php-amqp

# Log in to MySQL as root and execute SQL commands
sudo mysql -u root -p <<EOF
    source ./sql/database.sql;
    source ./sql/credentials.sql;
EOF

echo "MySQL setup completed successfully."
