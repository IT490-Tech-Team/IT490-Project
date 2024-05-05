# Monitoring Folder

Welcome to the Monitoring folder! This directory contains all the necessary files to set up NRPE (Nagios Remote Plugin Executor) on a machine and prepare it to be monitored by Nagios.

## Folder Structure:

- **commands**: Contains scripts used by NRPE for monitoring various aspects of the machine's performance.
- **config.cfg**: NRPE configuration file specifying all the NRPE commands to be executed on the monitored machine.
- **copy_commands.sh**: Script to copy the commands folder to the appropriate location for NRPE.
- **monitor_commands.sh**: Script to monitor changes in the commands folder and automatically run copy_commands.sh when changes are detected.
- **setup.sh**: Script to set up NRPE on the machine, including adding the machine as a host to be monitored by Nagios and configuring NRPE with config.cfg.

## Setup Instructions:

1. **NRPE Setup:**
   - Execute the `setup.sh` script to set up NRPE on the machine, including adding the machine as a host to be monitored by Nagios and configuring NRPE with the specified commands in `config.cfg`.
     ```bash
     bash setup.sh
     ```
2. **Copying Commands:**
   - After running `setup.sh`, execute the `copy_commands.sh` script to copy the `commands` folder to the appropriate location where NRPE can execute them.
     ```bash
     bash copy_commands.sh
     ```

By following these setup instructions, you'll have NRPE configured on the machine and ready to be monitored by Nagios. Feel free to explore each file and script for further customization and configuration.
