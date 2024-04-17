# Frontend VM

Welcome to the Frontend VM folder! This directory contains scripts and files related to setting up the frontend services for the project.

## Folder Structure:

- **website**: Houses all files for the website.
- **bookquest.conf**: Apache configuration file for the website.
- **clean.sh**: Script for cleaning up temporary files or resources.
- **dependencies.sh**: Script to install dependencies required for the frontend services.
- **jsconfig.json**: Configuration file to assist in proper importing within VSCode.
- **copy_website.sh**: Script to copy the website folder into the proper `/var/www` directory.
- **monitor_website**: Script to monitor the website folder and automatically copy changes to the website when detected.

## Website Structure:

The `website` folder is organized into the following subfolders:

- **api**: Contains functions that link directly to the project's consumers' designed functionality. For example, it houses an `authentication.js` file that matches the `authentication-receiver` functionality with additional features.
- **client**: Houses information that connects the frontend to RabbitMQ.
- **javascript**: Contains shared JavaScript files.
- **pages**: Houses all the website pages. For example, `/pages/registration/index.html` corresponds to `[url]/registration`, with the exception of the homepage, which links directly to the first page of the website.
- **styles**: Contains shared CSS files.

## Scripts and Files:

### clean.sh

The `clean.sh` script is responsible for cleaning up temporary files or resources generated during the operation of the frontend services.

### dependencies.sh

The `dependencies.sh` script installs necessary dependencies required for the frontend services to function properly.

### copy_website.sh

The `copy_website.sh` script copies the website folder into the proper `/var/www` directory, ensuring that the website is served correctly.

### monitor_website

The `monitor_website` script monitors the website folder for changes and automatically copies them to the website when detected. This script is essential for seamless development and deployment of the website.

Feel free to explore each script and folder to understand their functionalities and configurations better.
