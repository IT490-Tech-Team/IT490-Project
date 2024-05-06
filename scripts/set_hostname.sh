#!/bin/bash

# Check if at least one argument is provided
if [ $# -lt 1 ]; then
    echo "Usage: $0 <arg1> [arg2] [arg3]"
    exit 1
fi

# If only one argument is provided, set the hostname directly
if [ $# -eq 1 ]; then
    new_hostname="$1"
elif [ $# -eq 2 ]; then
    # If two arguments are provided, concatenate them with a hyphen
    new_hostname="$1-$2"
elif [ $# -eq 3 ]; then
    # If three arguments are provided, concatenate them with hyphens
    new_hostname="$1-$2-$3"
else
    echo "Error: Too many arguments provided."
    echo "Usage: $0 <arg1> [arg2] [arg3]"
    exit 1
fi

# Set the hostname
hostnamectl set-hostname "$new_hostname"

# Display the new hostname
echo "Hostname set to: $new_hostname"
