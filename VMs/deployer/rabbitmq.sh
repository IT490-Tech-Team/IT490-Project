#!/bin/bash

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

# Function to create RabbitMQ resources for an exchange and queue
create_exchange_and_queue() {
    local exchange_name=$1
    local exchange_type=$2
    local queue_name=$3

    # Create virtual host
    output=$(sudo rabbitmqadmin --host=$RABBITMQ_ADMIN_HOST --port=$RABBITMQ_ADMIN_PORT --username=$RABBITMQ_ADMIN_USER --password=$RABBITMQ_ADMIN_PASS declare vhost name="$VHOST" 2>&1)
    check_access_refused "$output"

    # Create exchange
    output=$(sudo rabbitmqadmin --host=$RABBITMQ_ADMIN_HOST --port=$RABBITMQ_ADMIN_PORT --username=$RABBITMQ_ADMIN_USER --password=$RABBITMQ_ADMIN_PASS declare exchange --vhost="$VHOST" name="$exchange_name" type="$exchange_type" 2>&1)
    check_access_refused "$output"

    # Create queue
    output=$(sudo rabbitmqadmin --host=$RABBITMQ_ADMIN_HOST --port=$RABBITMQ_ADMIN_PORT --username=$RABBITMQ_ADMIN_USER --password=$RABBITMQ_ADMIN_PASS declare queue --vhost="$VHOST" name="$queue_name" 2>&1)
    check_access_refused "$output"

    # Bind exchange to queue
    output=$(sudo rabbitmqadmin --host=$RABBITMQ_ADMIN_HOST --port=$RABBITMQ_ADMIN_PORT --username=$RABBITMQ_ADMIN_USER --password=$RABBITMQ_ADMIN_PASS declare binding --vhost="$VHOST" source="$exchange_name" destination="$queue_name" 2>&1)
    check_access_refused "$output"

    echo "RabbitMQ setup for exchange '$exchange_name' and queue '$queue_name' completed successfully."
}

# Check if rabbitmqadmin is installed
check_rabbitmqadmin

# RabbitMQ admin authentication
echo "Enter RabbitMQ admin username (default: 'guest', press Enter if unchanged): "
read RABBITMQ_ADMIN_USER
if [ -z "$RABBITMQ_ADMIN_USER" ]; then
    RABBITMQ_ADMIN_USER="guest"
fi

echo "Enter RabbitMQ admin password (default: 'guest', press Enter if unchanged): "
read -s RABBITMQ_ADMIN_PASS
if [ -z "$RABBITMQ_ADMIN_PASS" ]; then
    RABBITMQ_ADMIN_PASS="guest"
fi

# RabbitMQ admin host and port
RABBITMQ_ADMIN_HOST="localhost"
RABBITMQ_ADMIN_PORT="15672"

# RabbitMQ user authentication
RABBITMQ_USER="bookQuest"
RABBITMQ_PASS="8bkJ3r4dWSU1lkL6HQT7"

# Virtual host
VHOST="bookQuest"

# Original exchange and queue
create_exchange_and_queue "createPackageExchange" "topic" "createPackageQueue"

create_exchange_and_queue "dev-backend-Exchange" "topic" "dev-backend-Queue"
create_exchange_and_queue "dev-frontend-Exchange" "topic" "dev-frontend-Queue"
create_exchange_and_queue "dev-dmz-Exchange" "topic" "dev-dmz-Queue"

create_exchange_and_queue "test-backend-Exchange" "topic" "test-backend-Queue"
create_exchange_and_queue "test-frontend-Exchange" "topic" "test-frontend-Queue"
create_exchange_and_queue "test-dmz-Exchange" "topic" "test-dmz-Queue"

create_exchange_and_queue "prod-backend-Exchange" "topic" "prod-backend-Queue"
create_exchange_and_queue "prod-frontend-Exchange" "topic" "prod-frontend-Queue"
create_exchange_and_queue "prod-dmz-Exchange" "topic" "prod-dmz-Queue"


# Create user
output=$(sudo rabbitmqadmin --host=$RABBITMQ_ADMIN_HOST --port=$RABBITMQ_ADMIN_PORT --username=$RABBITMQ_ADMIN_USER --password=$RABBITMQ_ADMIN_PASS declare user name="$RABBITMQ_USER" password="$RABBITMQ_PASS" tags="" 2>&1)
check_access_refused "$output"

# Set permissions for guest user on Bookshelf virtual host
output=$(sudo rabbitmqctl set_permissions -p $VHOST $RABBITMQ_USER ".*" ".*" ".*" 2>&1)
check_access_refused "$output"

sudo systemctl restart rabbitmq-server
