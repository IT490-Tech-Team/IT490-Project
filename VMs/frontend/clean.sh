#!/bin/bash

# Uninstall PHP and related packages without manual confirmation
sudo apt remove --purge -y php libapache2-mod-php php-amqp php-ssh2

# Disable Apache modules without manual confirmation
sudo a2dismod -y rewrite

# Uninstall Apache without manual confirmation
sudo apt remove --purge -y apache2

# Uninstall zip without manual confirmation
sudo apt remove --purge -y zip

# Perform autoremove to remove any dependencies that are no longer needed without manual confirmation
sudo apt autoremove --purge -y

# Optionally, clean up any residual configuration files without manual confirmation
sudo apt autoclean -y
