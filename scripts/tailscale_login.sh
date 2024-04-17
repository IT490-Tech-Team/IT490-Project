#!/bin/bash

if [ $# -ne 2 ]; then
    echo "Usage: $0 [prod|test|dev] [frontend|backend|dmz]"
    exit 1
fi

environment=$1
component=$2

case $environment in
    prod|test|dev)
        ;;
    *)
        echo "Invalid environment. Choose either prod, test, or dev."
        exit 1
        ;;
esac

case $component in
    frontend|backend|dmz)
        ;;
    *)
        echo "Invalid component. Choose either frontend, backend, or dmz."
        exit 1
        ;;
esac

echo "Setting up Tailscale for $environment $component with hostname: $hostname..."
sudo tailscale up --authkey tskey-auth-kZjPafknXz11CNTRL-STwZyBK5K3YN9irtSgic2YzqN9Z9JPwE --hostname="$hostname"
