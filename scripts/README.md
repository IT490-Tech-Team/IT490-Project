# Scripts

This folder contains general-purpose scripts that are not specific to any particular virtual machine (VM) or project.

## Contents:

- **startup.sh**: File containing common commands after initializing a new VM and cloning the repository.
- **gh_cli.sh**: Script for setting up GitHub and Git quickly.
- **tailscale_login.sh**: Script for logging into the Tailscale network.
- **set_hostname.sh**: Script for changing the machine's hostname.
- **service_manager.sh**: Script for managing all services related to the project.


### startup.sh

The `startup.sh` script is designed to automate the initial setup process when setting up a new virtual machine (VM) for the project. It performs essential and optional tasks such as updating package lists, installing Tailscale, setting up Zsh with Oh-My-Zsh and Powerlevel10k theme.

### gh_cli.sh

The `gh_cli.sh` script automates the installation and configuration of GitHub CLI (gh) and Git. It streamlines the setup process, making it easier for users to integrate GitHub and Git into their development workflow.

### tailscale_login.sh

The `tailscale_login.sh` script simplifies the setup process for connecting to Tailscale VPN for all the VMs to interconnect. 

### set_hostname.sh

The `set_hostname.sh` script simplifies the process of setting the hostname of a machine. It takes two arguments and concatenates them with a hyphen to form the new hostname. Then, it sets the hostname using `hostnamectl` and displays the new hostname.

#### How to Use:
1. Run the script by executing the following command:
    ```bash
    bash set_hostname.sh <arg1> [<arg2>] [<arg3>]
    ```
2. Replace `<arg1>` with the first part of the desired hostname.
3. Optionally, replace `<arg2>` with the second part of the desired hostname.
4. Optionally, replace `<arg3>` with the third part of the desired hostname.
5. After execution, the script will set the hostname to the concatenated value of `<arg1>`, `<arg2>`, and `<arg3>`.


### service_manager.sh

The `service_manager.sh` script provides a convenient way to manage services related to the project. It allows users to perform various actions such as installing, starting, stopping, restarting, checking status, and viewing logs for individual services or all services at once.

### How to Use:
1. Run the script by executing the following command:
    ```bash
    bash service_manager.sh [action] [service]
    ```
2. Replace `[action]` with one of the following:
    - `install`: Install a/all service(s).
    - `start`: Start a/all service(s).
    - `stop`: Stop a/all service(s).
    - `restart`: Restart a/all service(s).
    - `status`: Show the status of a service.
    - `log`: Show the log for a service.
3. Replace `[service]` with the name of the service you want to perform the action on. You can specify `all` to perform the action on all services.
4. After execution, the script will perform the specified action on the specified service(s).

## Running Scripts

To run the provided scripts, follow these steps:

1. **Open a Terminal**: Open a terminal window on your system. You can usually find a Terminal application in the Applications menu on Linux or macOS, or you can use the search feature to find it.

2. **Navigate to the Script Directory**: Use the `cd` command to navigate to the directory where the script is located. For example, if the script is located in the `scripts` directory within your home directory, you can navigate to it with the following command:

   ```bash
   cd ~/scripts
   ```

3. **Run the Script**: Once the script has executable permissions, you can run it by typing `./` followed by the script name and pressing Enter. For example, to run the `startup.sh` script, you would run:

   ```bash
   ./startup.sh
   ```

   If prompted, enter your password to allow the script to make system changes (such as installing packages).

4. **Follow On-Screen Instructions**: Some scripts may prompt you for input or display information as they run. Follow any on-screen instructions to complete the process.

That's it! You've successfully run a script on your system. Repeat these steps for any other scripts you want to run.
