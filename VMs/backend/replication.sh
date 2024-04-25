#!/bin/bash

# MySQL Configuration
REPLICATION_USER="replication_user"
REPLICATION_PASSWORD="A5mr0dla2gIl"

# Function to setup master
setup_master() {
  # Enable Binary Logging
  echo "[mysqld]" | sudo tee -a /etc/mysql/my.cnf
  echo "server-id = 1" | sudo tee -a /etc/mysql/my.cnf
  echo "log_bin = $log_bin_path" | sudo tee -a /etc/mysql/my.cnf

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
  # Enable Binary Logging
  echo "[mysqld]" | sudo tee -a /etc/mysql/my.cnf
  echo "server-id = 2" | sudo tee -a /etc/mysql/my.cnf
  echo "log_bin = $log_bin_path" | sudo tee -a /etc/mysql/my.cnf

  # Restart MySQL Service
  sudo service mysql restart

  # Connect Slave to Master
  sudo mysql -e "CHANGE MASTER TO MASTER_HOST='$master_domain', MASTER_USER='$REPLICATION_USER', MASTER_PASSWORD='$REPLICATION_PASSWORD';"

  # Start Replication
  sudo mysql -e "START SLAVE;"

  # Verify Replication Status
  sudo mysql -e "SHOW SLAVE STATUS\G;"
}

# Check if the required parameters are provided
if [ -z "$1" ] || [ -z "$2" ]; then
  echo "Usage: $0 <machinetype> <relationship>"
  echo "Example: $0 main parent"
  exit 1
fi

MACHINE_TYPE="$1"
RELATIONSHIP="$2"

if [ "$MACHINE_TYPE" == "main" ]; then
  log_bin_path="/var/log/mysql/mysql-bin-main.log"
  master_domain="prod-backend"
elif [ "$MACHINE_TYPE" == "backup" ]; then
  log_bin_path="/var/log/mysql/mysql-bin-backup.log"
  master_domain="prod-backend-backup"
else
  echo "Invalid machine type. Use 'main' or 'backup'."
  exit 1
fi

# Check the relationship parameter
if [ "$RELATIONSHIP" == "parent" ]; then
  setup_master
elif [ "$RELATIONSHIP" == "child" ]; then
  if [ -z "$3" ]; then
    echo "Usage: $0 child <parent_ip>"
    exit 1
  fi
  setup_slave "$3"
else
  echo "Invalid relationship parameter. It should be 'parent' or 'child'."
  exit 1
fi
