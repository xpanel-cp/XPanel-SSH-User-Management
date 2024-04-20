#!/bin/bash
#XPanel Alireza

if [ "$#" -ne 3 ]; then
    echo "Usage: $0 <add/del> <username> <max_logins>"
    exit 1
fi

action="$1"
username="$2"
max_logins="$3"

if [ ! -f "/etc/security/limits.conf" ]; then
    echo "Error: /etc/security/limits.conf not found."
    exit 1
fi

if [ "$action" = "add" ]; then
    if grep -q "^$username " /etc/security/limits.conf; then
        awk -v username="$username" -v max_logins="$max_logins" '$1 == username {$4 = max_logins} 1' /etc/security/limits.conf > /tmp/limit>
    else
        echo "$username hard maxlogins $max_logins" >> /etc/security/limits.conf
    fi
    echo "User $username limit set to $max_logins"
elif [ "$action" = "del" ]; then
    sed -i "/^$username /d" /etc/security/limits.conf
    echo "User $username limit removed"
else
    echo "Unknown action: $action. Please use 'add' or 'del'."
    exit 1
fi
