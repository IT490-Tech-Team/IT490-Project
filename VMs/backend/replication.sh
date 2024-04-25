#!/bin/bash

# MySQL Configuration
REPLICATION_USER="replication_user"
REPLICATION_PASSWORD="A5mr0dla2gIl"

# Function to setup master
setup_master() {
  local log_bin_path=""
  local server_id=""

  if [ "$MACHINE_TYPE" == "main" ]; then
    log_bin_path="/var/log/mysql/mysql-bin-main.log"
    server_id="1"
  elif [ "$MACHINE_TYPE" == "backup" ]; then
    log_bin_path="/var/log/mysql/mysql-bin-backup.log"
    server_id="2"
  else
    echo "Invalid machine type. Use 'main' or 'backup'."
    exit 1
  fi

  # Enable Binary Logging
  echo "[mysqld]" | sudo tee -a /etc/mysql/my.cnf
  echo "server-id = $server_id" | sudo tee -a /etc/mysql/my.cnf
  echo "log_bin = $log_bin_path" | sudo tee -a /etc/mysql/my.cnf
  echo "bind-address = 0.0.0.0" | sudo tee -a /etc/mysql/my.cnf

  # Restart MySQL Service
  sudo service mysql restart

  # Create Replication User
  sudo mysql -e "CREATE USER '$REPLICATION_USER'@'%' IDENTIFIED WITH mysql_native_password BY '$REPLICATION_PASSWORD';"
  sudo mysql -e "GRANT REPLICATION SLAVE ON *.* TO '$REPLICATION_USER'@'%';"

  # Display Master Status
  sudo mysql -e "SHOW MASTER STATUS;"
}

# Function to setup slave
setup_slave() {
  if [ "$MACHINE_TYPE" == "backup" ]; then
    master_domain="prod-backend.tortoise-daggertooth.ts.net"
  elif [ "$MACHINE_TYPE" == "main" ]; then
    master_domain="prod-backend-backup.tortoise-daggertooth.ts.net"
  else
    echo "Invalid machine type. Use 'main' or 'backup'."
    exit 1
  fi

  # Restart MySQL Service
  sudo service mysql restart

  # Connect Slave to Master
  sudo mysql -e "CHANGE MASTER TO MASTER_HOST='$master_ip', MASTER_USER='$REPLICATION_USER', MASTER_PASSWORD='$REPLICATION_PASSWORD';"

  # Start Replication
  sudo mysql -e "START SLAVE;"

  # Verify Replication Status
  sudo mysql -e "SHOW SLAVE STATUS\G;"
}

# Function to undo slave setup
undo_slave() {
  # Stop replication
  sudo mysql -e "STOP SLAVE;"

  # Reset slave configuration
  sudo mysql -e "RESET SLAVE;"
}

# Optional: Undo slave setup if requested
if [ "$1" == "undo" ]; then
  undo_slave
  exit 1
fi

# Check if the required parameters are provided
if [ -z "$1" ] || [ -z "$2" ]; then
  echo "Usage: $0 <machinetype> <relationship>"
  echo "Usage: $0 undo"
  echo "Example: $0 main parent"
  exit 1
fi

MACHINE_TYPE="$1"
RELATIONSHIP="$2"

# Check the relationship parameter
if [ "$RELATIONSHIP" == "parent" ]; then
  setup_master
elif [ "$RELATIONSHIP" == "child" ]; then
  setup_slave
  setup_slave
else
  echo "Invalid relationship parameter. It should be 'parent' or 'child'."
  exit 1
fi


