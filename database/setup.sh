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

# Log in to MySQL as root
sudo mysql -u root <<EOF
    # Execute SQL commands from user-database.sql
    source /path/to/user-database.sql;
EOF

echo "MySQL setup completed successfully."
