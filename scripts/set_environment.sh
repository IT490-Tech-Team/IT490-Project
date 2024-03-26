#!/bin/bash

# Function to set the environment variable based on the provided argument
set_rabbitmq_host() {
    case "$1" in
        prod)
            export RABBITMQ_HOST="rabbitmq.tortoise-daggertooth.ts.net"
            ;;
        test)
            export RABBITMQ_HOST="test-host" # Replace 'test-host' with the actual test host
            ;;
        dev)
            export RABBITMQ_HOST="127.0.0.1"
            ;;
        *)
            echo "Invalid environment specified."
            exit 1
            ;;
    esac
}

# Check if an argument is provided
if [ $# -ne 1 ]; then
    echo "Usage: $0 <environment>"
    exit 1
fi

# Get the environment argument
environment="$1"

# Set the environment variable
set_rabbitmq_host "$environment"

# Display the set environment variable
echo "RABBITMQ_HOST set to: $RABBITMQ_HOST"
