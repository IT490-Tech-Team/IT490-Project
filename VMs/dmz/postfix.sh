#!/bin/bash

# Install Postfix
sudo apt-get update
sudo DEBIAN_FRONTEND=noninteractive apt-get install -y postfix

# Set parent folder
parent_folder=$(dirname "$0")

# Copy configuration files
sudo cp "postfix/main.cf" /etc/postfix/main.cf
sudo cp "postfix/aliases" /etc/aliases
sudo cp "postfix/mailname" /etc/mailname
sudo cp "postfix/sasl_passwd" /etc/postfix/sasl/sasl_passwd
sudo cp "postfix/sasl_passwd.db" /etc/postfix/sasl/sasl_passwd.db
sudo cp "postfix/ssl-cert-snakeoil.key" /etc/ssl/private/ssl-cert-snakeoil.key
sudo cp "postfix/ssl-cert-snakeoil.pem" /etc/ssl/certs/ssl-cert-snakeoil.pem

# Set permissions and ownership
sudo chown root:root /etc/postfix/sasl/sasl_passwd
sudo chown root:root /etc/postfix/sasl/sasl_passwd.db
sudo chmod 600 /etc/postfix/sasl/sasl_passwd
sudo chmod 600 /etc/postfix/sasl/sasl_passwd.db

# Update Postfix lookup tables
sudo postmap /etc/postfix/sasl/sasl_passwd

# Restart Postfix service
sudo systemctl restart postfix

echo "Postfix installed and configured successfully."
