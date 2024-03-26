#!/bin/bash

# Define color codes
RED='\033[0;31m' # Red color
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Define a function to display the help message
function display_help {
    echo -e "${GREEN}Usage: $(basename "$0") [action] [service]${NC}\n"
    echo -e "${CYAN}Actions:${NC}"
	echo -e "${YELLOW}  install${NC}  - Install a/all service(s)."
    echo -e "${YELLOW}  start${NC}   - Start a/all service(s)."
    echo -e "${YELLOW}  stop${NC}    - Stop a/all service(s)."
    echo -e "${YELLOW}  restart${NC} - Restart a/all service(s)."
    echo -e "${YELLOW}  status${NC}  - Show the status of a service."
    echo -e "${YELLOW}  log${NC}     - Show the log for a service."
    echo
    echo -e "${CYAN}Services:${NC}"
    echo -e "${YELLOW}  all${NC} - Perform action on all services"
    for folder in "${@}"; do
        echo -e "${YELLOW}  ${folder}"
    done
}

# Define a function to perform the specified action for a service folder
function perform_action {
    local service_folder
    local action_script="$1"
    local action_name="$2"
    if [ "$action_name" = "status" ] || [ "$action_name" = "log" ]; then
        if [ -n "${folders[$user_service]}" ]; then
            "${folders[$user_service]}$action_script"
        else
            echo "Error: Folder '$user_service' not found."
            display_help "${!folders[@]}"
            exit 1
        fi
    elif [ "$user_service" = "all" ]; then
        for service_folder in "${!folders[@]}"; do
            if [[ "$action_name" =~ ^(start|stop|restart)$ ]]; then
                "${folders[$service_folder]}$action_script" "$action_name"
            else
                "${folders[$service_folder]}$action_script"
            fi
        done
    elif [ -n "${folders[$user_service]}" ]; then
        if [[ "$action_name" =~ ^(start|stop|restart)$ ]]; then
            "${folders[$user_service]}$action_script" "$action_name"
        else
            "${folders[$user_service]}$action_script"
        fi
    else
        echo "Error: Folder '$user_service' not found."
        display_help "${!folders[@]}"
        exit 1
    fi
}

# Declare an associative array
declare -A folders

# Define the valid actions
valid_actions=("install" "start" "stop" "restart" "status" "log")

PARENT_DIR=$(dirname "$(dirname "$(realpath "$0")")")

# Iterate over each "services" folder
for SERVICES_FOLDER in "$PARENT_DIR"/VMs/*/services; do    
    # Iterate over each subfolder in the "services" folder
    for SUBFOLDER in "$SERVICES_FOLDER"/*/; do
        folder_name=$(basename "$SUBFOLDER")
        
        # Check if both scripts exist in the current subfolder
        if [ -e "$SUBFOLDER/service_manipulate.sh" ] && [ -e "$SUBFOLDER/service_setup.sh" ]; then
            folders["$folder_name"]="$SUBFOLDER"
        fi
    done
done

user_service="$2"
action="$1"

# Check if the provided action is valid
if [[ ! " ${valid_actions[@]} " =~ " $action " ]]; then
	echo -e "${RED}Error: Invalid action. Supported actions are: ${valid_actions[*]}"
    display_help "${!folders[@]}"  # Pass the keys of the associative array
    exit 1
fi

# If no action is provided, display a help message
if [ -z "$action" ]; then
	echo -e "${RED}Error: No action provided."
    display_help "${!folders[@]}"  # Pass the keys of the associative array
    exit 1
fi

# If no folder name is provided, display a help message
if [ -z "$user_service" ]; then
	echo -e "${RED}Error: No folder name provided."
    display_help "${!folders[@]}"  # Pass the keys of the associative array
    exit 1
fi

# Check if the specified folder name exists in the associative array or is "all"
if [ -z "${folders[$user_service]}" ] && [ "$user_service" != "all" ]; then
    echo -e "${RED}Error: Folder '$user_service' not found."
    display_help "${!folders[@]}"
    exit 1
fi

# Perform action based on user input
case "$action" in
    start|stop|restart|status|log)
        perform_action "service_manipulate.sh" "$action"
        ;;
    install)
        perform_action "service_setup.sh" "install"
        ;;
    *)
        echo "Error: Invalid action. Supported actions are: ${valid_actions[*]}"
        display_help "${folders[@]}"
        exit 1
        ;;
esac