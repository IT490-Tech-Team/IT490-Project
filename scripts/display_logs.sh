#!/bin/bash

# Check if the file exists
if [ -f "/opt/log-dispatch/logs.txt" ]; then
    # Read and print the contents of the file
    cat "/opt/log-dispatch/logs.txt"
else
    echo "Error: File '/opt/log-dispatch/logs.txt' not found."
fi

