#!/bin/bash

# Check if script is run with sudo
if [ "$(id -u)" != "0" ]; then
    echo "Please run this script with sudo."
    exit 1
fi

# Function to check if rabbitmqadmin is installed
check_rabbitmqadmin() {
    if ! command -v rabbitmqadmin &> /dev/null; then
        echo "ERROR: rabbitmqadmin is not installed. Please install it first."
        exit 1
    fi
}

# Function to check for "Access refused" message in output
check_access_refused() {
    if [[ $1 =~ ^\*\*\*\ Access\ refused: ]]; then
        echo "ERROR: Access refused. Wrong username or password."
        exit 1
    fi
}

# Check if rabbitmqadmin is installed
check_rabbitmqadmin

# RabbitMQ admin credentials
read -p "Enter RabbitMQ admin username (default: 'guest'): " RABBITMQ_ADMIN_USER
read -s -p "Enter RabbitMQ admin password (default: 'guest'): " RABBITMQ_ADMIN_PASS
echo

# RabbitMQ admin host and port
RABBITMQ_ADMIN_HOST="localhost"
RABBITMQ_ADMIN_PORT="15672"

# RabbitMQ user credentials
RABBITMQ_USER="bookQuest"
RABBITMQ_PASS="8bkJ3r4dWSU1lkL6HQT7"

# Virtual host
VHOST="BookQuest"

# Exchange names and type
SEARCH_EXCHANGE_NAME="searchExchange"
SEARCH_EXCHANGE_TYPE="topic"

# Queue names
SEARCH_DB_QUEUE_NAME="searchDatabaseQueue"
SEARCH_DMZ_QUEUE_NAME="searchDmzQueue"
SEARCH_FRONTEND_QUEUE_NAME="searchFrontendQueue"

# Create virtual host
output=$(rabbitmqadmin --host=$RABBITMQ_ADMIN_HOST --port=$RABBITMQ_ADMIN_PORT --username=$RABBITMQ_ADMIN_USER --password=$RABBITMQ_ADMIN_PASS declare vhost name="$VHOST" 2>&1)
check_access_refused "$output"

# Create exchange
output=$(rabbitmqadmin --host=$RABBITMQ_ADMIN_HOST --port=$RABBITMQ_ADMIN_PORT --username=$RABBITMQ_ADMIN_USER --password=$RABBITMQ_ADMIN_PASS declare exchange --vhost="$VHOST" name="$SEARCH_EXCHANGE_NAME" type="$SEARCH_EXCHANGE_TYPE" 2>&1)
check_access_refused "$output"

# Create queues
output=$(rabbitmqadmin --host=$RABBITMQ_ADMIN_HOST --port=$RABBITMQ_ADMIN_PORT --username=$RABBITMQ_ADMIN_USER --password=$RABBITMQ_ADMIN_PASS declare queue --vhost="$VHOST" name="$SEARCH_DB_QUEUE_NAME" 2>&1)
check_access_refused "$output"

output=$(rabbitmqadmin --host=$RABBITMQ_ADMIN_HOST --port=$RABBITMQ_ADMIN_PORT --username=$RABBITMQ_ADMIN_USER --password=$RABBITMQ_ADMIN_PASS declare queue --vhost="$VHOST" name="$SEARCH_DMZ_QUEUE_NAME" 2>&1)
check_access_refused "$output"

output=$(rabbitmqadmin --host=$RABBITMQ_ADMIN_HOST --port=$RABBITMQ_ADMIN_PORT --username=$RABBITMQ_ADMIN_USER --password=$RABBITMQ_ADMIN_PASS declare queue --vhost="$VHOST" name="$SEARCH_FRONTEND_QUEUE_NAME" 2>&1)
check_access_refused "$output"

# Bind exchanges to queues
output=$(rabbitmqadmin --host=$RABBITMQ_ADMIN_HOST --port=$RABBITMQ_ADMIN_PORT --username=$RABBITMQ_ADMIN_USER --password=$RABBITMQ_ADMIN_PASS declare binding --vhost="$VHOST" source="$SEARCH_EXCHANGE_NAME" destination="$SEARCH_DB_QUEUE_NAME" 2>&1)
check_access_refused "$output"

output=$(rabbitmqadmin --host=$RABBITMQ_ADMIN_HOST --port=$RABBITMQ_ADMIN_PORT --username=$RABBITMQ_ADMIN_USER --password=$RABBITMQ_ADMIN_PASS declare binding --vhost="$VHOST" source="$SEARCH_EXCHANGE_NAME" destination="$SEARCH_DMZ_QUEUE_NAME" 2>&1)
check_access_refused "$output"

output=$(rabbitmqadmin --host=$RABBITMQ_ADMIN_HOST --port=$RABBITMQ_ADMIN_PORT --username=$RABBITMQ_ADMIN_USER --password=$RABBITMQ_ADMIN_PASS declare binding --vhost="$VHOST" source="$SEARCH_EXCHANGE_NAME" destination="$SEARCH_FRONTEND_QUEUE_NAME" 2>&1)
check_access_refused "$output"

echo "RabbitMQ setup completed successfully."