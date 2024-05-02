#!/bin/bash

# Get the directory of the script
BASE_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Log in to MySQL as root and execute SQL commands
echo "Setting up MySQL database..."
sudo mysql -u root -p <<EOF
    source $BASE_DIR/sql/database.sql;
    source $BASE_DIR/sql/credentials.sql;
EOF
