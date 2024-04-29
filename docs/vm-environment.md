# Development Environment

This guide is designed to help contributors set up all the necessary components of the project on their machines. It provides step-by-step instructions to set up the Backend, Frontend, and DMZ components of the project into a singular machine.

> **Important:** While we aim to cover various scenarios, this guide may not address all potential errors. If you encounter issues, pay attention to your terminal's output and utilize online resources to resolve them promptly.

## Before You Begin

Before diving into the setup process, ensure you have the necessary requirements to carry on. While one could set this project up directly on their machines it is recommended to use a VM (virtual machine) to keep your personal computer clean of project files. This project has been developed with Linux, with all contributors using Ubuntu Desktop or Ubuntu Server.

### Virtual Machine
 
Since we recommend hosting the project on a virtual machine, one must use virtualization software to install their VM on their system. If you're unsure of how to do this, please follow the guides below based on your system:

* [Windows / Linux Guide](/docs/vm-windows-linux.md)
* [MacOS Guide](/docs/vm-macos.md)

### Terminal
Before diving into the setup process, ensure that you have a terminal installed on your VM. If you're unsure, search how to open a terminal for the operating system of your VM. Ubuntu Desktop has a "terminal" application you can search for, but Ubuntu Server opens into the terminal by default.

Your terminal application should look something like this.

![Terminal example with an echo "terminal is ready"](./resources/vm-environment/00-terminal-example.png)

> **Important:** Still unsure about what a command even is? Don't fret and go to this [guide](/docs/terminal.md) to learn more!

## Step-by-Step Setup

1. **Update Package Lists**

   Begin by updating your package lists. Run the following command in your terminal:

    ```bash
    sudo apt-get update && sudo apt-get upgrade -y
    ```

    > **Important:** To execute a command you must type the command into your terminal and press enter.

    > **Note:** If you're asked "which services should be restarted?" just click enter.

    ![gif with step by step of doing the update and upgrade](./resources/vm-environment/01-update-and-upgrade.gif)


2. **Install Git**
    
    This project is set up to use Git and GitHub as a centralized location for all the project code. To install Git, run the following:

    ```bash
    sudo apt-get install git -y
    ```

    ![terminal with the command above](./resources/vm-environment/02-install-git.png)

3. **Clone the Repository**
   
   Now, copy the project files into your virtual machine:
   
    ```bash
    git clone https://github.com/IT490-Tech-Team/IT490-Project
    ```

    ![terminal with the command above](./resources/vm-environment/03-clone-repo.gif)

4. **Run Startup Script**
   
   This script guides you through running essential and optional commands to set up the environment. If you'd like to learn more about what each step in the startup script does, go to the [startup explanation](/docs/startup-script.md).
   
   Navigate to `/IT490-Project/scripts`.
   
    ```bash
    cd IT490-Project/scripts
    ```

    Then run the startup script and follow the directions on the terminal:

    ```bash
    ./startup.sh
    ```

    > **important:** If you decide to install ZSH, Oh My ZSH!, and powerlevel10k, you will need to logout and log back in for the changes to apply.

    ![terminal with the commands above](./resources/vm-environment/04-startup-script.gif)

5.  **(Optional) Initialize Tailscale**

    Tailscale helps us connect with other project computers. You must log into tailscale if you want to follow the recommended workflow later on.

    ```bash
    ./tailscale_login.sh
    ```

    ![terminal with the commands above](./resources/vm-environment/05-tailscale.png)

6. Using the Installer

    Now, let's set up the requirements for each VM. Navigate to the root of the project repository and execute the following:

   - **Set up Backend**
   
        ```bash
        ./installer.sh -backend -a -b -c -d -e -f -g -h -i -j -k
        ```

    ![terminal with the commands above](./resources/vm-environment/06-01-installer-backend.png)

   - **Set up DMZ**

        ```bash
        ./installer.sh -dmz -a -b -c -d -e -f -g -h -i -j -k
        ```

    ![terminal with the commands above](./resources/vm-environment/06-02-installer-dmz.png)


   - **Set up Frontend**
   
        ```bash
        ./installer.sh -frontend -a -b -c -d -e -f -g -h -i -j -k
        ```

    ![terminal with the commands above](./resources/vm-environment/06-03-installer-frontend.png)


7.  **Test Your Setup**

    After completing the setup steps, test your development environment by accessing `localhost` in your web browser. This will allow you to verify if you can access the website.

    ![image showcasing the website running on firefox inside of the VM](./resources/vm-environment/07-test.png)

Congratulations! You've successfully set up your development environment for the Online Bookshelf project. Happy developing!

## Recommended workflow

Now that you have the website set up on your VM, you are free to work on it however you'd like. If you don't have a particular way you prefer, we recommended you follow the following video:

[![Watch the video](https://drive.google.com/thumbnail?id=1yyaTkbtWKjgzzIPolAcR1FByjrJd9AAC)](https://drive.google.com/file/d/1yyaTkbtWKjgzzIPolAcR1FByjrJd9AAC/view?usp=share_link)