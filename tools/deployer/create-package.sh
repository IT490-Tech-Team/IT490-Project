#!/bin/bash

# Check if the first argument is set
if [ -z "$1" ]; then
    echo "Error: Command argument missing."
    exit 1
fi

# Get the absolute path of the script directory
SCRIPT_DIR=$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)

# Get the directory three levels up from the script directory
TARGET_DIR=$(dirname $(dirname $(dirname "$SCRIPT_DIR")))

# Create a timestamp for the zip file
TIMESTAMP=$(date +"%Y-%m-%d_%H-%M-%S")

# Create a backup directory if it doesn't exist
PACKAGE_DIR="$TARGET_DIR/IT490-Project/packages/backup"

# Change directory to the target directory
cd "$TARGET_DIR" || { echo "Error: Unable to change directory to $TARGET_DIR"; exit 1; }

# Create the zip file in the backup directory, skipping the 'packages' directory
ZIP_FILE="$PACKAGE_DIR/backup_$TIMESTAMP.zip"
zip -r "$ZIP_FILE" IT490-Project -x "IT490-Project/packages/*" -x "IT490-Project/.git/*"

echo "Zip file created in $ZIP_FILE"

# Run create-package.php with arguments
php "$SCRIPT_DIR/create-package.php" "$1" "$ZIP_FILE"
