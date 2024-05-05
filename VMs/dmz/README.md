# DMZ VM

Welcome to the DMZ VM folder! This directory contains scripts and files related to setting up the DMZ (Demilitarized Zone) services for the project.

The DMZ only uses PHP with some additional extensions.

## Folder Structure:

- **jobs**: Contains cronjobs that run periodically.
- **services**: Houses the `search-dmz-receiver` service, responsible for fetching books from an external API.
- **clean.sh**: Script for cleaning up temporary files or resources.
- **dependencies.sh**: Script to install dependencies required for the DMZ services.

## Services:

### search-dmz-receiver

The `search-dmz-receiver` service fetches books from an external API, providing search functionality to users.

## Scripts and Files:

### clean.sh

The `clean.sh` script is responsible for cleaning up temporary files or resources generated during the operation of the DMZ services.

### dependencies.sh

The `dependencies.sh` script installs necessary dependencies required for the DMZ services to function properly.

Feel free to explore each script and folder to understand their functionalities and configurations better.
