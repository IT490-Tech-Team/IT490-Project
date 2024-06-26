#!/bin/bash

project_dir="/home/ubuntu/IT490-Project"
backup_dir="$HOME/IT490-Project"

# Initialize flags
backend=false
frontend=false
dmz=false

# Backend flags
flag_a=false # backend services
flag_b=false # backend sql
flag_c=false # backend firewall.sh
flag_d=false # backend rabbitmq.sh
flag_e=false # backend dependencies.sh

# Frontend flags
flag_f=false # frontend /website
flag_g=false # frontend .conf
flag_h=false # frontend dependencies

# DMZ flags
flag_i=false # dmz services
flag_j=false # dmz jobs
flag_k=false # dmz dependencies

# Parse command-line options
while [[ $# -gt 0 ]]; do
    key="$1"
    case $key in
        -backend)
        backend=true
        ;;
        -frontend)
        frontend=true
        ;;
        -dmz)
        dmz=true
        ;;
        -a)
        flag_a=true ;;
        -b)
        flag_b=true ;;
        -c)
        flag_c=true ;;
        -d)
        flag_d=true ;;
        -e)
        flag_e=true ;;
        -f)
        flag_f=true ;;
        -g)
        flag_g=true ;;
        -h)
        flag_h=true ;;
        -i)
        flag_i=true ;;
        -j)
        flag_j=true ;;
        -k)
        flag_k=true ;;
        *)
        echo "Unknown option: $1"
        ;;
    esac
    shift
done

# Check if the project directory exists
if [ -d "$project_dir" ]; then
    cd "$project_dir"
    echo "Changed directory to $project_dir"
else
    # If the project directory doesn't exist, go to the backup directory
    if [ -d "$backup_dir" ]; then
        cd "$backup_dir"
        echo "Changed directory to $backup_dir"
    else
        # If neither directory exists, print an error message
        echo "Neither $project_dir nor $backup_dir exists."
        exit 1
    fi
fi

# Perform actions based on group flags
if [ "$backend" = true ]; then
    echo "Backend actions:"
    if [ "$flag_a" = true ]; then
        cd ./VMs/backend/
		sudo ./dependencies.sh
		cd ../..
    fi
    if [ "$flag_b" = true ]; then
        cd ./VMs/backend/
		sudo ./sql.sh
		cd ../..
    fi
    if [ "$flag_c" = true ]; then
        cd ./VMs/backend/
		sudo ./rabbitmq.sh guest guest
		cd ../..
    fi
    if [ "$flag_d" = true ]; then
        cd scripts
		./service_manager.sh install search-db-receiver
		./service_manager.sh install authentication-receiver
		./service_manager.sh install email-signup-receiver
		./service_manager.sh install reviews-receiver
		./service_manager.sh install discussion-receiver
		cd ..
    fi
    if [ "$flag_e" = true ]; then
        cd ./VMs/backend/
		sudo ./firewall.sh
		cd ../..
    fi
fi

if [ "$frontend" = true ]; then
    echo "Frontend actions:"
    if [ "$flag_f" = true ]; then
        cd ./VMs/frontend/
		sudo ./dependencies.sh
		cd ../..
    fi
    if [ "$flag_g" = true ]; then
        cd ./VMs/frontend/
		sudo ./copy_website.sh
		cd ../..
    fi
    if [ "$flag_h" = true ]; then
        cd ./VMs/frontend/
		sudo ./copy_website.sh -r y
		cd ../..
    fi

fi

if [ "$dmz" = true ]; then
    echo "DMZ actions:"
    if [ "$flag_i" = true ]; then
        cd ./VMs/dmz/
		./dependencies.sh
		cd ../..
    fi
    if [ "$flag_j" = true ]; then
        cd scripts
		./service_manager.sh install search-dmz-receiver
		cd ..
    fi
fi
