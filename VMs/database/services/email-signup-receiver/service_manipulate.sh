#!/bin/bash

service_name="email-signup-receiver"

function start_service {
    sudo systemctl start "$service_name"
}

function stop_service {
    sudo systemctl stop "$service_name"
}

function restart_service {
    sudo systemctl restart "$service_name"
}

function status_service {
    sudo systemctl status "$service_name"
}

function log_service {
    sudo journalctl -e -u "$service_name"
}

function display_help {
    echo "Usage: $0 [action]"
    echo "Default action is 'status'."
    echo "Actions:"
    echo "  start   - Start the $service_name service."
    echo "  stop    - Stop the $service_name service."
    echo "  restart - Restart the $service_name service."
    echo "  status  - Show the status of the $service_name service."
    echo "  log     - Show the journalctl log for the $service_name service."
}

if [ $# -eq 0 ]; then
    status_service
    exit 0
fi

action="$1"

case "$action" in
    start)
        start_service
        ;;
    stop)
        stop_service
        ;;
    restart)
        restart_service
        ;;
    status)
        status_service
        ;;
    log)
        log_service
        exit 0
        ;;
    help)
        display_help
        exit 0
        ;;
    *)
        echo "Invalid action. Please use 'start', 'stop', 'restart', 'status', 'log', or 'help'."
        exit 1
        ;;
esac
