# Scripts

This folder contains general-purpose scripts that are not specific to any particular virtual machine (VM) or project.

## Contents:

- **startup.sh**: This script automatically sets up a new VM with Zsh, Oh-My-Zsh, Powerlevel10k, Nano, Git, and Tailscale, and also upgrades installed packages.
- **tailscale_login_[member_name].sh**: Scripts for each member of this group to log into the Tailscale network with their respective Tailscale key.

### startup.sh

`startup.sh` is a script that automates the setup process for a new virtual machine (VM). It performs the following tasks:

1. Updates package lists and upgrades installed packages using `apt-get update` and `apt-get upgrade`.
2. Installs Zsh: A powerful shell with additional features compared to the standard Bash shell.
3. Installs Oh-My-Zsh: A community-driven framework for managing Zsh configurations.
4. Installs Powerlevel10k theme: A highly customizable Zsh theme that provides useful information and customization options.
5. Sets Zsh theme to Powerlevel10k.
6. Changes default shell to Zsh.
7. Installs Nano: A lightweight text editor that is easy to use and configure.
8. Installs Git: A version control system for tracking changes in files and collaborating on projects.
9. Installs Tailscale: A secure network mesh tool for connecting devices, services, and networks securely.

To use `startup.sh`, simply run it on a new VM, and it will automate the installation and configuration process for the mentioned packages and settings.

### tailscale_login_[member_name].sh

There will also be a script for each member of this group to log into the Tailscale network with their respective Tailscale key. **Please ensure that you use your script, as each key has been preconfigured for each member.**

Replace `[member_name]` with the name or identifier of the group member before running the script.

Feel free to modify the scripts according to your specific requirements or preferences.

## Running Scripts

To run the provided scripts, follow these steps:

1. **Open a Terminal**: Open a terminal window on your system. You can usually find a Terminal application in the Applications menu on Linux or macOS, or you can use the search feature to find it.

2. **Navigate to the Script Directory**: Use the `cd` command to navigate to the directory where the script is located. For example, if the script is located in the `scripts` directory within your home directory, you can navigate to it with the following command:

   ```bash
   cd ~/scripts
   ```

3. **Make the Script Executable**: If the script does not already have executable permissions, you can use the `chmod` command to add them. For example, to make the `startup.sh` script executable, you can run:

   ```bash
   chmod +x startup.sh
   ```

4. **Run the Script**: Once the script has executable permissions, you can run it by typing `./` followed by the script name and pressing Enter. For example, to run the `startup.sh` script, you would run:

   ```bash
   ./startup.sh
   ```

   If prompted, enter your password to allow the script to make system changes (such as installing packages).

5. **Run Tailscale Login Script**: For Tailscale network login, use the corresponding `tailscale_login_[member_name].sh` script for each member. Replace `[member_name]` with the name or identifier of the group member. For example, to log in as Oscar, run:

   ```bash
   ./tailscale_login_oscar.sh
   ```

   Replace `oscar` with the name of the desired member.

6. **Follow On-Screen Instructions**: Some scripts may prompt you for input or display information as they run. Follow any on-screen instructions to complete the process.

That's it! You've successfully run a script on your system. Repeat these steps for any other scripts you want to run.
