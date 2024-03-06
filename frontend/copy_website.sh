#!/bin/bash

# Target directory
target_dir="/var/www/html"

# Check if the target directory exists, if not, create it
if [ ! -d "$target_dir" ]; then
    mkdir -p "$target_dir"
fi

# Copy the contents of ./frontend/website to /var/www/html
cp -r ./website/* "$target_dir"

# Check if the copy operation was successful
if [ $? -eq 0 ]; then
    echo "Files copied successfully."
else
    echo "Error: Failed to copy files."
fi
