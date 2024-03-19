#!/bin/bash

# Check if the required arguments are provided
if [ $# -lt 3 ]; then
    echo "Usage: $0 <recipient_email> <subject> <body>"
    exit 1
fi

# Set recipient email address
recipient="$1"

# Set email subject
subject="$2"

# Set email body
body="$3"

# Set sender email address (change this to your desired sender address)
sender="bookquestit490@gmail.com"

# Use sendmail command to send the email
{
    echo "From: $sender"
    echo "To: $recipient"
    echo "Subject: $subject"
    echo ""
    echo "$body"
} | sendmail -t

echo "Email sent to $recipient with subject '$subject'."
