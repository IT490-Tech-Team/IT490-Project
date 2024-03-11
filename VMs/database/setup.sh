#!/bin/bash

# Function to uninstall MySQL Server
uninstall_mysql() {
    apt purge -y mysql-server mysql-client mysql-common mysql-server-core-* mysql-client-core-*
    apt autoremove -y
    apt autoclean
}

# Function to install and set up services
setup_services() {
    cd ./services/authentication-receiver
    ./service_setup.sh
    cd ../../
    cd ./services/search-db-receiver
    ./service_setup.sh
    cd ../../
}

# Check if script is run with sudo
if [ "$(id -u)" != "0" ]; then
    echo "Please run this script with sudo."
    exit 1
fi

# Check if MySQL Server is already installed
if [ -x "$(command -v mysql)" ]; then
    # MySQL Server is installed, so uninstall it first
    echo "MySQL Server is already installed. Uninstalling..."
    uninstall_mysql
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
    source ./sql/users.sql;
    source ./sql/books.sql;
    source ./sql/credentials.sql;
EOF

echo "MySQL setup completed successfully."

# Install and set up services
setup_services