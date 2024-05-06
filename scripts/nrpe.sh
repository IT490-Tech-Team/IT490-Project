#!/bin/bash

# Function to install NRPE and NRPE plugins
install_nrpe() {
    echo "Installing NRPE and NRPE plugins..."
    sudo apt update
    sudo apt install -y nagios-nrpe-server nagios-plugins
}

# Function to add monitoring server to allowed_hosts
add_monitoring_server() {
    local monitor_hostname="$1"
    echo "Adding monitoring server to allowed_hosts..."
    sudo sed -i "/^allowed_hosts=/ s/$/,$monitor_hostname/" /etc/nagios/nrpe.cfg
    sudo systemctl restart nagios-nrpe-server
}

# Main function
main() {
    # Check if NRPE is already installed
    if ! dpkg -l | grep -q nagios-nrpe-server; then
        install_nrpe
    else
        echo "NRPE is already installed."
    fi

    # Add monitoring server (website) to allowed_hosts
    add_monitoring_server "website"

    echo "NRPE installation and configuration completed."
}

# Execute main function
main
