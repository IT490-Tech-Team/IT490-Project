#!/bin/bash

# Define the target directory
target_directory="/opt/$(basename "$(pwd)")"

# Check if the target directory exists
if [ ! -d "$target_directory" ]; then
    # If the directory doesn't exist, create it
    sudo rm -rf "$target_directory"

fi

sudo cp -r "$(pwd)" "$target_directory"

sudo cp ./authentication-receiver.service "/etc/systemd/system/authentication-receiver.service"

sudo systemctl daemon-reload

sudo systemctl start authentication-receiver.service

sudo systemctl enable authentication-receiver.service

# sudo systemctl status authentication-receiver.service
# sudo systemctl restart authentication-receiver.service
# sudo systemctl stop authentication-receiver.service