# Setting Up Your Development Environment

To get started with the **Tech Team's** System Integration project, follow these steps to set up your development environment:

1. **Clone the Repository**: Begin by cloning the project repository to your local machine. You can do this by executing the following command in your terminal:
    ```bash
    git clone https://github.com/IT490-Tech-Team/IT490-Project
    ```

2. **Initialize Tailscale**: Navigate to the `/scripts` folder within the cloned repository. Run the `startup.sh` script, which includes essential setup commands. The most crucial step is installing Tailscale, which provides secure networking for the project.
    ```bash
    cd IT490-Project/scripts
    ./startup.sh
    ./tailscale_login_[your_name].sh
    ```

3. **Install VM Requirements**: Navigate to the `/VMs` directory within the project repository. Here, you'll find four folders corresponding to each VM: `/database`, `/frontend`, `/rabbitmq`, and `/dmz`. In each folder, there's a `setup.sh` script. Execute these scripts to install all required programs for the respective VM.
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

4. **Setup Services**: Once all VM requirements are installed, return to the `/scripts` folder and run `setup_services.sh`. This script configures the services required for the project.
    ```bash
    cd ../../scripts
    ./setup_services.sh
    ```

5. **Test Your Setup**: After completing the setup steps, your development environment should be ready. You can test if everything is functioning correctly by accessing `localhost` in your web browser. This will allow you to verify if you can access the website.

6. **Development Workflow**: To work on the frontend website, run the `monitor_website.sh` script in the background. This script detects any changes made to the website files within the project and automatically copies them to the Apache server. Remember to refresh your browser to see the changes reflected in the website.

With these steps completed, you're all set to start developing for the Online Bookshelf service. Happy developing!
