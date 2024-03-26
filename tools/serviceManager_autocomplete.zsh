_serviceManager_complete() {
    local -a options
    local -a folders
    local user_service
    local action

    # Retrieve current word, previous word, and entire command line
    cur=${words[CURRENT]}
    prev=${words[PREV]}
    action=${words[2]}  # Third argument is the action
    user_service=${words[3]}  # Fourth argument is the service name

    # Define options dynamically
    options=("install" "start" "stop" "restart" "status" "log")

    # Dynamically retrieve folders
   PARENT_DIR="$HOME/IT490-Project"

    # Iterate over each "services" folder
    for SERVICES_FOLDER in "$PARENT_DIR"/VMs/*/services; do    
        # Iterate over each subfolder in the "services" folder
        for SUBFOLDER in "$SERVICES_FOLDER"/*/; do
            folder_name=$(basename "$SUBFOLDER")
            
            # Check if both scripts exist in the current subfolder
            if [ -e "$SUBFOLDER/service_manipulate.sh" ] && [ -e "$SUBFOLDER/service_setup.sh" ]; then
                folders+=("$folder_name")
            fi
        done
    done

    folders+=("all")

    case "$action" in
        install)
            _describe -t actions 'services' folders
            ;;
        start|stop|restart|status|log)
            _describe -t actions 'services' folders
            ;;
        *)
            _describe -t actions 'actions' options
            ;;
    esac
}

# Register completion function
compdef _serviceManager_complete serviceManager
