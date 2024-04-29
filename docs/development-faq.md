# FAQ

Welcome to the FAQ section of BookQuest! Below, we've compiled answers to some common questions you might encounter while working on the project.

## 1. Why Can't I See Changes I Made to the Website?

When working on the BookQuest website, it's important to understand its structure. While your project files can reside anywhere in your system, Apache2, the web server, typically uses `/var/www` as the default directory for websites. This means that changes made to project files won't automatically reflect on the website.

To address this, we provide two scripts in the `VMs/frontend/` directory: `./copy_website.sh` and `./monitor_website.sh`.

- **copy_website.sh**: Copies project files into the appropriate `/var/www` directory once. 
  - You can also use the command `./copy_website.sh -r y` to restart Apache2, although it's optional as most changes don't require a restart.
- **monitor_website.sh**: Automatically detects changes in your project files and copies them to `/var/www` whenever a change is detected.

## 2. How Can I Make a RabbitMQServer.php File Run Automatically?

To automate the execution of a `RabbitMQServer.php` file, you can create a service for it. Our project structure simplifies this process with the `./service_manager.sh` script.

- **Creating a Service**: Start by copying one of the original "services" from `/VMs/[VM FOLDER]/services`. This ensures you have all the necessary files for your service to be recognized.
- **Customizing the Service**: Rename and edit the `.service` file to identify your service correctly.
- **Configuring Scripts**: Modify `./service_manipulate.sh` and `./service_setup.sh` to ensure the `service_name` script variable reflects the correct `.service` filename.
- **Make changes to the ./RabbitMQServer.php**: ofcourse, the most important step is that you change the ./RabbitMQServer to do what you want it to do.
- **Run the `./service_manager.sh` script**: Now that your service is ready, you can use the ./service_manager.sh script to install ther service. While the script requires some parameters, using the script without the right parameters will invoke the help text to help you use the script.

## 3. How Can I Add RabbitMQ Exchanges and Queues?

If you're making changes to the backend, especially the RabbitMQ exchange and queues, follow these steps:

1. Edit the `./VMs/backend/rabbitmq.sh` script.
2. Copy one of the existing lines (e.g., from line 80 to line 98) and modify the strings to specify the names of your exchange and queue.
	
	```bash
	create_exchange_and_queue "exampleExchange" "topic" "exampleQueue"
	```
3. Run the `./rabbitmq.sh` script.