#!/bin/bash

# Update package lists and upgrade installed packages
sudo apt-get update
sudo apt-get upgrade -y

# Install Zsh
sudo apt-get install zsh -y

# Install Oh-My-Zsh
sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"

# Install Powerlevel10k theme
git clone --depth=1 https://github.com/romkatv/powerlevel10k.git ${ZSH_CUSTOM:-$HOME/.oh-my-zsh/custom}/themes/powerlevel10k

# Set Zsh theme to Powerlevel10k
sed -i 's/ZSH_THEME="robbyrussell"/ZSH_THEME="powerlevel10k\/powerlevel10k"/g' ~/.zshrc

# Change default shell to Zsh
chsh -s $(which zsh)

# Install Nano and Git
sudo apt-get install nano git -y

# Install Tailscale
curl -fsSL https://tailscale.com/install.sh | sh

echo "Please restart your terminal to apply changes."
