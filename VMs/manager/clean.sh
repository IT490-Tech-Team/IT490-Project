#!/bin/bash

# Uninstall RabbitMQ and its dependencies
sudo apt remove --purge -y rabbitmq-server

# Uninstall MySQL and its dependencies
sudo apt remove --purge -y mysql-server mysql-client

# Perform autoremove to remove any dependencies that are no longer needed
sudo apt autoremove --purge -y

# Optionally, clean up any residual configuration files
sudo apt autoclean -y
