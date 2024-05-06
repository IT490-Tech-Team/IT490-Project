# System Startup Script

This bash script is designed to streamline the setup process for essential and optional software on your system. It provides interactive prompts to allow you to choose which actions to take. Below are the functionalities provided by this script:

## Functionalities

### 1. Update Package Lists and Upgrade Installed Packages
This option updates the package lists and upgrades installed packages using `apt-get`.

### 2. Install Tailscale (VPN for Project Machines)
This option installs Tailscale, a secure network tool used as a VPN to connect all project machines together. Tailscale facilitates secure and private communication between devices across networks.

### 3. Install Zsh (Optional)
This option installs Zsh, an alternative shell to Bash, using `apt-get`. Some team members prefer Zsh over Bash for its additional features and customization options.

### 4. Install Oh-My-Zsh (Optional)
This option installs Oh-My-Zsh, a community-driven framework for managing Zsh configurations, using its installation script retrieved via curl. Oh-My-Zsh enhances the Zsh experience with plugins, themes, and additional features.

### 5. Install Powerlevel10k Theme (Optional)
This option installs the Powerlevel10k theme for Zsh, a highly customizable and feature-rich theme. Powerlevel10k enhances the visual appearance and functionality of the terminal prompt.

### 6. Set Zsh Theme to Powerlevel10k (Optional)
This option sets the Zsh theme to Powerlevel10k by modifying the `~/.zshrc` configuration file. This step is particularly relevant for users who prefer a visually appealing and customizable terminal prompt.

### 7. Change Default Shell to Zsh (Optional)
This option changes the default shell to Zsh using `chsh`. Users who prefer Zsh can make it their default shell for a consistent experience across sessions.

## Note

- Make sure to review the actions carefully before confirming, as some changes may affect your system configuration.
- Tailscale is primarily used as a VPN solution for connecting project machines securely. Ensure proper configuration for your specific use case.
- After executing the script, it's recommended to restart your terminal to apply the changes.

