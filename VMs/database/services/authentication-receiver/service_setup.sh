#!/bin/bash

# Define the target directory
target_directory="/opt/$(basename "$(pwd)")"

# Define the service name
service_name="authentication-receiver.service"

# If the directory exists, delete it
if [ -d "$target_directory" ]; then
    sudo rm -rf "$target_directory"
fi

# Copy Service files to /opt/
sudo cp -r "$(pwd)" "$target_directory"

# Copy Service
sudo cp "./$service_name" "/etc/systemd/system/$service_name"

# Start Service and enable for reboot
sudo systemctl daemon-reload
sudo systemctl start "$service_name"
sudo systemctl restart "$service_name"
sudo systemctl enable "$service_name"
