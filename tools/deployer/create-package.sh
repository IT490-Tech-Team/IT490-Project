#!/bin/bash

# Get the absolute path of the script
TARGET_DIR="./*"

# Get the parent directory of the parent directory of the script

# Create a timestamp for the zip file
TIMESTAMP=$(date +"%Y-%m-%d_%H-%M-%S")

# Create a backup directory if it doesn't exist
PACKAGE_DIR="./packages/backup"

# Create the zip file in the backup directory
zip -r "$PACKAGE_DIR/backup_$TIMESTAMP.zip" "$TARGET_DIR"
echo $TARGET_DIR

echo "Zip file created in $PACKAGE_DIR"
