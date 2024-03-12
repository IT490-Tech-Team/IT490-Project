# Systems Integration: Online Bookshelf Service

This repo holds the source code for **Tech Team's** System Integration project. The goal is to create a service with 4 VMs (Frontend, Database, DMZ, and RabbitMQ), which each VM being hosted by a seperate student. We, the **Tech Team**, have chosen to host an Online Bookshelf service.

## Want to Contribute?

**Interested in contributing to the project? Please check out the guides section below, with a special emphasis on the [Development Environment guide](/docs/vm-environment.md)!**


## Table of Contents
1. [Project Overview](#project-overview)
2. [Project Structure](#project-structure)
3. [Guides](#guides)

## Project Overview

The Online Bookshelf service allows users to easily find and save books tailored to their interests. Users can search for books, read reviews, leave their own reviews, and access personal bookshelves. Recommendations are provided based on user interests and reading history.

For more details, refer to [proposal.md](./.docs/proposal.md).

## Project Structure

The repository is organized into folders corresponding to each VM. Please make sure to read each machine's README to fully understand the contents of each.

| Component    | Description                                                                | README                             |
| ------------ | -------------------------------------------------------------------------- | ---------------------------------- |
| **Database** | Contains setup instructions and scripts for setting up the MySQL database. | [README](./VMs/database/README.md) |
| **DMZ**      | Contains files related to the DMZ (Demilitarized Zone) service.            | [README](./VMs/DMZ/README.md)      |
| **Frontend** | Contains files and folders related to the frontend service.                | [README](./VMs/frontend/README.md) |
| **RabbitMQ** | Contains files and scripts related to the RabbitMQ service.                | [README](./VMs/RabbitMQ/README.md) |


## Guides

Ready to configure your VM? Follow these guides to set up Tailscale and perform machine renaming. Remember to customize the machine name according to your needs!

| Title                          | Overview                                             | Link                                        |
| ------------------------------ | ---------------------------------------------------- | ------------------------------------------- |
| Tailscale Installation Guide   | Install and set up Tailscale for secure networking.  | [README](/docs/tailscale.md)                |
| Tailscale Machine Rename Guide | Guide for renaming machines in Tailscale.            | [README](/docs/tailscale-machine-rename.md) |
| Development Environment        | Guide for setting up your VM to work on the project. | [README](/docs/vm-environment.md)           |

## Tailscale Setup

The project is not intended for public access, so the **Tech Team** has opted to use Tailscale, a personal VPN built on top of WireGuard.

Benefits of using Tailscale:
1. Automatically makes all team members Tailscale admins by setting up a GitHub organization.
2. Provides a Tailscale-generated domain name for the network or "tailnet".
3. Generates HTTPS certificates for each machine on the tailnet using the tailnet domain name.
   - While the domain name will be added to a public ledger, none of the machines will be exposed to the internet through the VPN.

Planned uses:
* Use automatic domain certificates to ensure HTTPS connections.
* Enforce VPN-wide ACL as a firewall.
* Connect all VMs and change their machine names to create static domain names