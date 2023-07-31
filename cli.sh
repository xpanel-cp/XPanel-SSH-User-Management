#!/bin/bash

# Function to display the menu
adminuser=$(mysql -N -e "use XPanel_plus; select username from admins where id='1';")
adminpass=$(mysql -N -e "use XPanel_plus; select password from admins where id='1';")
sshport=$(mysql -N -e "use XPanel_plus; select ssh_port from settings where id='1';")
ssh_tls_port=$(mysql -N -e "use XPanel_plus; select tls_port from settings where id='1';")
if [ -f "/var/www/xpanelport" ]; then
domain=$(cat /var/www/xpanelport | grep "^DomainPanel")
ssl=$(cat /var/www/xpanelport | grep "^SSLPanel")
panelport=$(cat /var/www/xpanelport | grep "^Xpanelport")
panelport=$(echo "$panelport" | sed "s/Xpanelport //g")
domain=$(echo "$domain" | sed "s/DomainPanel //g")
ssl=$(echo "$ssl" | sed "s/SSLPanel //g")
else
panelport=""
domain=""
ssl=""
fi
if [ "$domain" != "" ]; then
domain=$domain
else
domain=$(curl -s https://ipinfo.io/ip)
fi
if [ "$ssl" == "True" ]; then
protcol=https
else
protcol=http
fi
if [ "$ssh_tls_port" == "" ]; then
ssh_tls_port=444
fi
if [ "$ssh_tls_port" == "NULL" ]; then
ssh_tls_port=444
fi
function show_menu() {
    clear
    echo "Detail XPanel"
    echo "------------------"
    echo "Username: $adminuser"
    echo "SSH PORT: $sshport"
    echo "SSH PORT TLS: $ssh_tls_port"
    echo ""
    echo "XPanel CLI Menu"
    echo "------------------"
    echo "1. Change Username AND Password"
    echo "2. Chnage Port SSH"
    echo "3. Chnage Port SSH TLS"
    echo "4. Update XPanel"
    echo "5. Remove XPanel"
    echo "6. Remove All Admin XPanel"
    echo "7. Change Banner Text"
    echo "8. Exit"
}

# Function to select an option
function select_option() {
    read -p "Please enter the option number: " choice
    case $choice in
        1)
            echo "Please enter a username:"
            read username
            echo "Please enter a Password : "
            read password
            if [[ -n "${username}" ]]; then
            username=${username}
            fi
            if [[ -n "${password}" ]]; then
            password=${password}
            fi
            mysql -e "CREATE USER '${username}'@'localhost' IDENTIFIED BY '${password}';" &
            wait
            mysql -e "GRANT ALL ON *.* TO '${username}'@'localhost';" &
            sed -i "s/DB_USERNAME=$adminuser/DB_USERNAME=$username/" /var/www/html/app/.env
            sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$password/g" /var/www/html/app/.env
            mysql -e "USE XPanel_plus; UPDATE admins SET username = '${username}' where id='1';"
            mysql -e "USE XPanel_plus; UPDATE admins SET password = '${password}' where id='1';"
            ;;
        2)
            echo "Please enter a SSH port:"
            read port
            sed -i "s/Port $sshport/Port $port/" /etc/ssh/sshd_config
            sed -i "s/PORT_SSH=$sshport/PORT_SSH=$port/" /var/www/html/app/.env
            mysql -e "USE XPanel_plus; UPDATE settings SET ssh_port = '${port}' where id='1';"
            reboot
            ;;
        3)
            echo "Please enter a SSH TLS port:"
            read tlsport
            echo "cert = /etc/stunnel/stunnel.pem
[openssh]
accept = $tlsport
connect = 0.0.0.0:$sshport
            " > /etc/stunnel/stunnel.conf
            systemctl enable stunnel4
            systemctl restart stunnel4
            mysql -e "USE XPanel_plus; UPDATE settings SET tls_port = '${tlsport}' where id='1';"
            reboot
            ;;
        4)
            bash <(curl -Ls https://raw.githubusercontent.com/Alirezad07/X-Panel-SSH-User-Management/main/install.sh --ipv4)
            ;;
        5)
        echo "You accept the risk of removing the panel (y/n)"
        read risk
        if [ "$risk" == "y" ]; then
        rm -rf /var/www/html/cp
        rm -rf /var/www/html/example
        rm -rf /var/www/html/app
        sudo apt-get purge '^php8.*' -y
        sudo apt purge stunnel4 -y
        sudo apt-get purge apache2 php8.1 zip unzip net-tools curl mariadb-server php8.1-cli php8.1-fpm php8.1-mysql php8.1-curl php8.1-gd php8.1-mbstring php8.1-xml -y
        fi
        ;;
        
        6)
       mysql -e "USE XPanel_plus; TRUNCATE TABLE admins;"
       echo "Removed All Admin"
        ;;
        7)
        echo "Please enter a Text Banner:"
        read banner
cat << EOF > /root/banner.txt
$banner
EOF
            ;;
        8)
            echo "Exiting the menu."
            exit 0
            ;;
        *)
            echo "Invalid option."
            ;;
    esac
}

# Main loop to display the menu and select an option
while true
do
    show_menu
    select_option
    read -p "Press Enter to continue..."
done
