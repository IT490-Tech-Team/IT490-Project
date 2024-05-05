#!/bin/bash

# Function to copy config file to Nagios directory
copy_config_file() {
    local config_file="config.cfg"
    local nagios_config_dir="/etc/nagios"

    echo "Copying $config_file to $nagios_config_dir..."
    sudo cp "$config_file" "$nagios_config_dir"
}

# Function to add include line to nrpe.cfg if it doesn't already exist
add_include_line() {
    local nrpe_config="/etc/nagios/nrpe.cfg"
    local include_line="include=/etc/nagios/config.cfg"

    if ! grep -q "$include_line" "$nrpe_config"; then
        echo "Adding include line to $nrpe_config..."
        sudo bash -c "echo '$include_line' >> $nrpe_config"
    else
        echo "Include line already exists in $nrpe_config. Skipping..."
    fi
}

copy_commands_folder() {
    local commands_folder="commands"
    local dest_folder="/opt/commands"

    echo "Copying $commands_folder to $dest_folder..."
    rm -rf "$dest_folder"
    sudo cp -r "$commands_folder" "$dest_folder"
}

# Function to add sudoers entry for NRPE commands
add_sudoers_entry() {
    local sudoers_entry="nrpe ALL=(ALL) NOPASSWD: /opt/commands/*"
    local sudoers_file="/etc/sudoers.d/nrpe"

    echo "Adding sudoers entry for NRPE commands..."
    echo "$sudoers_entry" | sudo tee "$sudoers_file" > /dev/null
}

# Main function
main() {
    copy_config_file
    add_include_line
    copy_commands_folder
    add_sudoers_entry
    sudo usermod -aG systemd-journal nagios
    sudo systemctl restart nagios-nrpe-server
}

# Execute main function
main
