# Development Environment

This guide is designed to help contributors, especially those new to working with terminals, set up all the necessary components of the project on their machines.

## Introduction

This guide provides step-by-step instructions to set up the Database, DMZ, Frontend, and RabbitMQ components of the project. We recommend performing these setup steps within a virtual machine to prevent any changes to your personal computer.

> **Important**: While we aim to cover various scenarios, this guide may not address all potential errors. If you encounter issues, pay attention to your terminal's output and utilize online resources, such as search engines, to resolve them promptly.

## Before you Begin

Before diving into the setup process, ensure that you have a terminal emulator installed on your system. If you're unsure, you can check by searching for a terminal application in your VM and running the following command:

```bash
echo "Terminal is ready"
```

## Step-by-Step Setup

1. **Update Package Lists**

   Begin by updating your package lists. Run the following command in your terminal:

    ```bash
    sudo apt-get update
    ```

2. **Install Git**
    
    This project is set up to use Git and GitHub as a centralized location for all the project code. To install Git, run the following:

    ```bash
    sudo apt-get install git -y
    ```

3. **Clone the Repository**
   
   Now, copy the project files into your virtual machine:
   
    ```bash
    git clone https://github.com/IT490-Tech-Team/IT490-Project
    ```

4. **Run Startup Script**
   
   This script guides you through running essential and optional commands to set up the environment, the most crucial step is installing Tailscale.
   
   Navigate to `/IT490-Project/scripts`.
   
    ```bash
    cd IT490-Project/scripts
    ```

    Then run the startup script:

    ```bash
    ./startup.sh
    ```

5.  **Initialize Tailscale**

    Tailscale helps us connect with other project computers. Replace [your_name] with your actual first name and run:

    ```bash
    ./tailscale_login_[your_name].sh
    ```

6. install VM Requirements

    Now, let's set up the requirements for each virtual machine (VM). Navigate to the /VMs directory within the project repository.

    ```bash
    cd VMs
    ```

   - **Set up RabbitMQ**
   
        ```bash
        cd rabbitmq
        ```
        ```bash
        ./setup.sh
        ```
        ```bash
        ./rabbitmq.sh
        ```

   - **Go back to the VMs Folder**

        ```bash
        cd ..
        ```

   - **Set up DMZ**

        ```bash
        cd dmz
        ```
        ```bash
        ./setup.sh
        ```
        ```bash
        ./setup_services.sh
        ```

   - **Go back to the VMs Folder**
   
        ```bash
        cd ..
        ```

   - **Set up Database**

        ```bash
        cd database
        ```
        ```bash
        ./setup.sh
        ```
        ```bash
        ./setup_services.sh
        ```

        This command is optional, but it enables you to look at the database as you make changes:

        ```bash
        ./phpmyadmin.sh
        ```
   
   - **Go back to the VMs Folder**

        ```bash
        cd ..
        ```

   - **Set up Frontend**
   
        ```bash
        cd frontend
        ```
        ```bash
        ./setup.sh
        ```
        ```bash
        ./copy_website.sh
        ```

7.  **Test Your Setup**

    After completing the setup steps, test your development environment by accessing localhost in your web browser. This will allow you to verify if you can access the website.

8.  **Development Workflow**

    To work on the frontend website, run the `monitor_website.sh` script in the background. This script detects any changes made to the website files within `IT490-Project/VMs/frontend/website` and automatically copies them to the Apache server. Remember to refresh your browser to see the changes reflected on the website.

Congratulations! You've successfully set up your development environment for the Online Bookshelf project. Happy developing!