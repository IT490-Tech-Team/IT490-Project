#!/bin/bash

# Flush existing rules
sudo ufw reset

# Set default policies
sudo ufw default deny incoming
sudo ufw default allow outgoing

# Allow RabbitMQ communication
sudo ufw allow 5672/tcp

# Allow SSH connections for VSCode
sudo ufw allow 22/tcp

# Allow Tailscale traffic
sudo ufw allow 41641/udp
sudo ufw allow 41642/udp

# Enable UFW
sudo ufw enable

# Display status
sudo ufw status verbose