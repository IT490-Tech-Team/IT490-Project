#!/bin/bash

# Set recipient email address
recipient="oscarf200106@gmail.com"

# Set sender email address
sender="bookquestit490@gmail.com"

# Set email subject
subject="Test mail"

# Set email body
body="This is a test email"

# Use sendmail command to send the email
{
    echo "From: $sender"
    echo "To: $recipient"
    echo "Subject: $subject"
    echo ""
    echo "$body"
} | sendmail -t

echo "Test email sent to $recipient."
