#!/bin/bash

# MySQL Configuration
REPLICATION_USER="replication_user"
REPLICATION_PASSWORD="A5mr0dla2gIl"

# Function to setup master
setup_master() {
  # Enable Binary Logging
  echo "[mysqld]" | sudo tee -a /etc/mysql/my.cnf
  echo "server-id = 1" | sudo tee -a /etc/mysql/my.cnf
  echo "log_bin = /var/log/mysql/mysql-bin.log" | sudo tee -a /etc/mysql/my.cnf

  # Restart MySQL Service
  sudo service mysql restart

  # Create Replication User
  sudo mysql -e "CREATE USER '$REPLICATION_USER'@'%' IDENTIFIED BY '$REPLICATION_PASSWORD';"
  sudo mysql -e "GRANT REPLICATION SLAVE ON *.* TO '$REPLICATION_USER'@'%';"

  # Display Master Status
  sudo mysql -e "SHOW MASTER STATUS;"
}

# Function to setup slave
setup_slave() {
  # Check if parent_ip parameter is provided
  if [ -z "$1" ]; then
    echo "Usage: $0 child <parent_ip>"
    exit 1
  fi

  # MySQL Configuration
  MASTER_IP="$1"

  # Enable Binary Logging
  echo "[mysqld]" | sudo tee -a /etc/mysql/my.cnf
  echo "server-id = 2" | sudo tee -a /etc/mysql/my.cnf

  # Restart MySQL Service
  sudo service mysql restart

  # Connect Slave to Master
  sudo mysql -e "CHANGE MASTER TO MASTER_HOST='$MASTER_IP', MASTER_USER='$REPLICATION_USER', MASTER_PASSWORD='$REPLICATION_PASSWORD';"

  # Start Replication
  sudo mysql -e "START SLAVE;"

  # Verify Replication Status
  sudo mysql -e "SHOW SLAVE STATUS\G;"
}

# Check if the required parameters are provided
if [ -z "$1" ]; then
  echo "Usage: $0 <relationship> [<parent_ip>]"
  echo "Relationship should be 'parent' or 'child'"
  exit 1
fi

# Check the relationship parameter
if [ "$1" == "parent" ]; then
  setup_master
elif [ "$1" == "child" ]; then
  if [ -z "$2" ]; then
    echo "Usage: $0 child <parent_ip>"
    exit 1
  fi
  setup_slave "$2"
else
  echo "Invalid relationship parameter. It should be 'parent' or 'child'."
  exit 1
fi
