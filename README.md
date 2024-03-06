# Systems Integration: Online Bookshelf Service

This repo holds the source code for **Tech Team's** System Integration project. The goal is to create a service with 4 VMs (Frontend, Database, DMZ, and RabbitMQ), which each VM being hosted by a seperate student. We, the **Tech Team**, have chosen to host an Online Bookshelf service.

## Project Overview

The Online Bookshelf service allows users to easily find and save books tailored to their interests. Users can search for books, read reviews, leave their own reviews, and access personal bookshelves. Recommendations are provided based on user interests and reading history.

For more details, refer to [proposal.md](proposal.md).

## Project Structure

The repository is organized into folders corresponding to each VM:

| Component    | Description                                                                | README                         |
| ------------ | -------------------------------------------------------------------------- | ------------------------------ |
| **Database** | Contains setup instructions and scripts for setting up the MySQL database. | [README](./database/README.md) |
| **DMZ**      | Contains files related to the DMZ (Demilitarized Zone) service.            | [README](./DMZ/README.md)      |
| **Frontend** | Contains files and folders related to the frontend service.                | [README](./frontend/README.md) |
| **RabbitMQ** | Contains files and scripts related to the RabbitMQ service.                | [README](./RabbitMQ/README.md) |


## Guides

| Title                          | Overview                                            | Link                                   |
| ------------------------------ | --------------------------------------------------- | -------------------------------------- |
| Tailscale Installation Guide   | Install and set up Tailscale for secure networking. | [README](/tailscale.md)                |
| Tailscale Machine Rename Guide | Guide for renaming machines in Tailscale.           | [README](/tailscale-machine-rename.md) |


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

### Example Tailscale Domains

| Machine name | IP             | Domain name                    |
| ------------ | -------------- | ------------------------------ |
| apache       | 100.64.125.94  | apache.beetal-pancake.ts.net   |
| database     | 100.109.171.19 | database.beetal-pancake.ts.net |
