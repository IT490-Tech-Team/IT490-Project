# Development Environment

This is a step by step guide with the objective of setting up everything necessary to run all parts of the project (Database, DMZ, Frontend, and RabbitMQ) under a single machine. The goal is to provide contributors with a simple way to edit the seperate parts of the project.



## Setting Up Your Development Environment

> **Important**: While this guide aims to cover various scenarios, it may not address all potential errors. If you encounter issues, pay attention to your terminal's output and utilize online resources, such as search engines, to resolve them promptly.
>
> Additionally, please refer to the section on common errors for further assistance.

To get started with setting up your project, please follow the steps below:

1. **Ensure Git is Installed**
   
    The only package necessary beforehand is git. To ensure that git installed in your system try running the following command in your terminal:

    ```bash
    git
    ```

    If you get anything similar to the command below, please proceed to step 1.1

    ```bash
    bash: git: command not found
    ```

    1. **Install Git**

        To install git, please run the command below:

       ```bash
        sudo apt-get install git -y
       ```

2. **Clone the Repository**
   
   Begin by cloning the project repository to your local machine. You can do this by executing the following command in your terminal:
   
    ```bash
    git clone https://github.com/IT490-Tech-Team/IT490-Project
    ```

3. **Run Startup Script**
   
   Navigate to `/IT490-Project/scripts` and run the `startup.sh` script. 
   
   This script guides you through running essential and optional commands to set up the environment, the most crucial step is installing Tailscale.

    ```bash
    cd IT490-Project/scripts
    ```

    ```bash
    ./startup.sh
    ```

4.  **Initialize Tailscale**

    This step logs you into the tailscale network to interact with all the computers that are part of this project. 
    
    Please replace `[your_name]` to use the correct script. For example: `./tailscale_login_[your_name].sh` → `./tailscale_login_callie.sh`

    ```bash
    ./tailscale_login_[your_name].sh
    ```
   
    If you're interested in how we use tailscale please refer to: [Tailscale.md](/docs/tailscale.md)

5. **Install VM Requirements**: Navigate to the `/VMs` directory within the project repository. Here, you'll find four folders corresponding to each VM: `/database`, `/frontend`, `/rabbitmq`, and `/dmz`. In each folder, there's a `setup.sh` script. Execute these scripts to install all required programs for the respective VM.
    ```bash
    cd ../VMs/database
    ./setup.sh
    
    cd ../frontend
    ./setup.sh
    ./copy_website.sh
    
    cd ../rabbitmq
    ./setup.sh
    ./rabbitmq.sh
    
    cd ../dmz
    ./setup.sh
    ```

6. **Setup Services**: Once all VM requirements are installed, return to the `/scripts` folder and run `setup_services.sh`. This script configures the services required for the project.
    ```bash
    cd ../../scripts
    ./setup_services.sh
    ```

7. **Test Your Setup**: After completing the setup steps, your development environment should be ready. You can test if everything is functioning correctly by accessing `localhost` in your web browser. This will allow you to verify if you can access the website.

1 **Development Workflow**: To work on the frontend website, run the `monitor_website.sh` script in the background. This script detects any changes made to the website files within the project and automatically copies them to the Apache server. Remember to refresh your browser to see the changes reflected in the website.

With these steps completed, you're all set to start developing for the Online Bookshelf service. Happy developing!

## Possible Errors

* `Error: unable to fetch some archives...`

    If you run into this error it means that you need to run 

    ```
    sudo apt-get update
    ```