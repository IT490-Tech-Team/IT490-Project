#!/bin/bash

# Check if two arguments are provided
if [ $# -ne 2 ]; then
    echo "Usage: $0 <arg1> <arg2>"
    exit 1
fi

# Concatenate the arguments with a hyphen
new_hostname="$1-$2"

# Set the hostname
hostnamectl set-hostname "$new_hostname"

# Display the new hostname
echo "Hostname set to: $new_hostname"
