#!/bin/bash

# Target directory
target_dir="/var/www/html"

# Check if the target directory exists, if not, create it
if [ ! -d "$target_dir" ]; then
    mkdir -p "$target_dir"
fi

# Copy the contents of ./website to /var/www/html
cp -r ./website/* "$target_dir"

# Check if the copy operation was successful
if [ $? -eq 0 ]; then
    echo "Files copied successfully."

    # Copy file ./bookshelf.conf to /etc/apache2/sites-available
    cp ./bookshelf.conf /etc/apache2/sites-available

    # Create symbolic link to enable the site
    ln -s /etc/apache2/sites-available/bookshelf.conf /etc/apache2/sites-enabled/

    # Restart Apache to apply changes
    systemctl restart apache2
else
    echo "Error: Failed to copy files."
fi
