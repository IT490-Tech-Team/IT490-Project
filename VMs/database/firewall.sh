#!/bin/bash

# Flush existing rules
sudo ufw reset

# Set default policies
sudo ufw default deny incoming
sudo ufw default allow outgoing

# Allow RabbitMQ communication
sudo ufw allow 5672/tcp

# Enable UFW
sudo ufw enable

# Display status
sudo ufw status verbose