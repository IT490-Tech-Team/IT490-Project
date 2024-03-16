#!/bin/bash

# Function to prompt user for confirmation
confirm() {
    read -p "$1 (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        return 0  # Return true
    else
        return 1  # Return false
    fi
}

# Update package lists and upgrade installed packages
if confirm "Do you want to update package lists and upgrade installed packages?"; then
    sudo apt-get update
    sudo apt-get upgrade -y
fi

# Install Nano and Git
if confirm "Do you want to install Nano and Git?"; then
    sudo apt-get install nano git -y
fi

# Install Tailscale
if confirm "Do you want to install Tailscale?"; then
    curl -fsSL https://tailscale.com/install.sh | sh
fi

# Install Zsh
if confirm "Do you want to install Zsh?"; then
    sudo apt-get install zsh -y
fi

# Install Oh-My-Zsh
if confirm "Do you want to install Oh-My-Zsh?"; then
    sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"
fi

# Install Powerlevel10k theme
if confirm "Do you want to install the Powerlevel10k theme?"; then
    git clone --depth=1 https://github.com/romkatv/powerlevel10k.git ${ZSH_CUSTOM:-$HOME/.oh-my-zsh/custom}/themes/powerlevel10k
fi

# Set Zsh theme to Powerlevel10k
if confirm "Do you want to set Zsh theme to Powerlevel10k?"; then
    sed -i 's/ZSH_THEME="robbyrussell"/ZSH_THEME="powerlevel10k\/powerlevel10k"/g' ~/.zshrc
fi

# Change default shell to Zsh
if confirm "Do you want to change the default shell to Zsh?"; then
    chsh -s $(which zsh)
fi

echo "Please restart your terminal to apply changes."