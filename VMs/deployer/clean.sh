#!/bin/bash

# Uninstall RabbitMQ and its dependencies
sudo apt remove --purge -y rabbitmq-server

# Remove RabbitMQ config files
sudo rm -rf /etc/rabbitmq

# Uninstall MySQL and its dependencies
sudo apt remove --purge -y mysql-server mysql-client

# Remove MySQL config files
sudo rm -rf /etc/mysql

# Perform autoremove to remove any dependencies that are no longer needed
sudo apt autoremove --purge -y

# Optionally, clean up any residual configuration files
sudo apt autoclean -y
