#!/bin/bash

# Define the target directory
target_directory="/opt/$(basename "$(pwd)")"
cron_command="0 0 1 * * php /opt/email-sender/main.php"

# Function to display and log errors
log_error() {
    echo "Error: $1" >&2  # Redirect error message to stderr
    exit 1
}

# Check if the script is run with sudo
if [ "$(id -u)" != "0" ]; then
    log_error "This script must be run with sudo"
fi

# If the directory exists, delete it
if [ -d "$target_directory" ]; then
    rm -rf "$target_directory" || log_error "Failed to delete existing target directory"
fi

# Copy Service files to /opt/
cp -r "$(pwd)" "$target_directory" || log_error "Failed to copy service files to target directory"

# Step 1: List current crontab and append new cron job entries
crontab -l > /tmp/crontab.tmp || touch /tmp/crontab.tmp
echo "$cron_command" >> /tmp/crontab.tmp || log_error "Failed to append cron job to temporary file"

# Step 2: Install the updated crontab
crontab /tmp/crontab.tmp || log_error "Failed to install updated crontab"

# Step 3: Remove temporary file
rm /tmp/crontab.tmp || log_error "Failed to remove temporary file"

echo "Service setup and cron job added successfully."
