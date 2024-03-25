#!/bin/bash

# Define the directory where the services are located
SERVICES_DIR="./services"

# Check if the services directory exists
if [ ! -d "$SERVICES_DIR" ]; then
    echo "Services directory not found."
    exit 1
fi

# Loop through each subdirectory in the services directory
for dir in "$SERVICES_DIR"/*/; do
    if [ -d "$dir" ]; then
        echo "Running service_setup.sh in $dir"
        # Check if service_setup.sh exists in the current subdirectory
        if [ -f "$dir/service_setup.sh" ]; then
            # Change directory to the current subdirectory and run service_setup.sh
            (cd "$dir" && ./service_setup.sh)
        else
            echo "service_setup.sh not found in $dir"
        fi
    fi
done
