#!/bin/bash

# Get the directory of the current script
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"

# Define the path to the zshrc file
ZSHRC_FILE="$HOME/.zshrc"
# Define the path to the completion script
COMPLETION_SCRIPT="$SCRIPT_DIR/serviceManager_autocomplete.zsh"
echo $SCRIPT_DIR

# Check if the completion script exists
if [ ! -f "$COMPLETION_SCRIPT" ]; then
    echo "Error: Completion script '$COMPLETION_SCRIPT' not found."
    exit 1
fi

# Check if the source line already exists in the zshrc file
if grep -q "_serviceManager_complete" "$ZSHRC_FILE"; then
    echo "Completion already configured in $ZSHRC_FILE."
else
    # Append the source line to the zshrc file
    echo "" >> "$ZSHRC_FILE"
    echo "# Enable serviceManager completion" >> "$ZSHRC_FILE"
    echo "source $COMPLETION_SCRIPT" >> "$ZSHRC_FILE"
    echo "Completion configured in $ZSHRC_FILE."
fi
