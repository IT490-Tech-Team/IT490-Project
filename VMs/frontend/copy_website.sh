#!/bin/bash

# Check if script is run with sudo
if [ "$(id -u)" != "0" ]; then
    echo "Please run this script with sudo."
    exit 1
fi

# Default restart Apache option
default_restart_apache=N

# Parse command line options
while [[ "$#" -gt 0 ]]; do
    case $1 in
        -r|--restart) default_restart_apache="$2"; shift ;;
        *) echo "Unknown parameter passed: $1"; exit 1 ;;
    esac
    shift
done

# Target directory
target_dir="/var/www/html"

# Check if the target directory exists, if not, create it
if [ ! -d "$target_dir" ]; then
    mkdir -p "$target_dir"
fi

# Delete the contents of /var/www/html
rm -rf "$target_dir"/*

# Copy the contents of ./website to /var/www/html
cp -r ./website/* "$target_dir"

# Check if the copy operation was successful
if [ $? -eq 0 ]; then
    echo "Files copied successfully."

    # Copy file ./bookshelf.conf to /etc/apache2/sites-available
    cp ./bookshelf.conf /etc/apache2/sites-available

    # Check if the symbolic link exists
    if [ ! -e "/etc/apache2/sites-enabled/bookshelf.conf" ]; then
        # Create symbolic link to enable the site
        ln -s /etc/apache2/sites-available/bookshelf.conf /etc/apache2/sites-enabled/
        
        echo "Symbolic link created."
    else
        echo "Symbolic link already exists."
    fi

    # Check if restart Apache option was specified
    if [[ $default_restart_apache =~ ^[Yy]$ ]]; then
        systemctl restart apache2
        echo "Apache restarted."
    else
        echo "Changes applied. Apache was not restarted."
    fi
else
    echo "Error: Failed to copy files."
fi
