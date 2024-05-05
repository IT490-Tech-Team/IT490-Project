#!/bin/bash

# Function to prompt user for confirmation
confirm() {
    RED='\033[0;31m'  # ANSI escape code for red color
    NC='\033[0m'       # ANSI escape code to reset color
    
    echo -e "${RED}$1${NC}"
    read -p "[press Enter to confirm](y/n): " -r

    if [[ $REPLY =~ ^[Yy]$ ]]; then
        return 0  # Return true
    else
        return 1  # Return false
    fi
}


# Update package lists and upgrade installed packages
if confirm "[ESSENTIAL] Do you want to update package lists and upgrade installed packages?"; then
    sudo apt-get update
    sudo apt-get upgrade -y
    echo
fi

# Install PHP and Zip
if confirm "[ESSENTIAL] Do you want to install PHP, Zip, and other essential packages?"; then
    sudo apt-get install -y php-cli php-amqp php-ssh2 zip
    echo
fi


# Install Tailscale
if confirm "[ESSENTIAL] Do you want to install Tailscale?"; then
    curl -fsSL https://tailscale.com/install.sh | sh
    echo
fi

# Install Zsh
if confirm "[OPTIONAL] Do you want to install Zsh?"; then
    sudo apt-get install zsh -y
    echo
fi

# Install Oh-My-Zsh
if confirm "[OPTIONAL] Do you want to install Oh-My-Zsh?"; then
    sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)" "" --unattended
    echo
fi

# Install Powerlevel10k theme
if confirm "[OPTIONAL] Do you want to install the Powerlevel10k theme?"; then
    git clone --depth=1 https://github.com/romkatv/powerlevel10k.git ${ZSH_CUSTOM:-$HOME/.oh-my-zsh/custom}/themes/powerlevel10k
    echo
fi

# Set Zsh theme to Powerlevel10k
if confirm "[OPTIONAL] Do you want to set Zsh theme to Powerlevel10k?"; then
    sed -i 's/ZSH_THEME="robbyrussell"/ZSH_THEME="powerlevel10k\/powerlevel10k"/g' ~/.zshrc
    echo
fi

# Change default shell to Zsh
if confirm "[OPTIONAL] Do you want to change the default shell to Zsh?"; then
    chsh -s $(which zsh)
    echo
fi

echo "Please restart your terminal to apply changes."
