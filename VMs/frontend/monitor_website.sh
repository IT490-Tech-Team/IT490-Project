#!/bin/bash

# Check if script is run with sudo
if [ "$EUID" -ne 0 ]; then
    echo "Please run this script with sudo."
    exit 1
fi

# Folder to monitor
FOLDER_TO_MONITOR="./website"

# Script to run
SCRIPT_TO_RUN="copy_website.sh"

# Function to run the script
run_script() {
    echo "Changes detected. Copying"
    ./$SCRIPT_TO_RUN
}

# Check if inotifywait is installed
if ! command -v inotifywait &> /dev/null; then
    echo "inotifywait is not installed. Installing..."
    # Install inotify-tools package
    if [ -x "$(command -v apt-get)" ]; then
        apt-get update
        apt-get install -y inotify-tools
    fi
fi

# Monitor the folder for changes
while inotifywait -r -e modify,move,create,delete $FOLDER_TO_MONITOR; do
    run_script
done
