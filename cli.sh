export GREEN='\e[32m'
export RED='\033[0;31m'
export BGBLUE='\e[1;44m'
export ORANGE='\033[0;33m'
export BLUE='\033[0;34m'
export PURPLE='\033[0;35m'
export CYAN='\033[0;36m'
export BG='\E[44;1;39m'
export NC='\033[0;37m'
export WHITE='\033[0;37m'
export TRY="[${RED} * ${NC}]"

# Check if the app directory exists
if [ -d "/var/www/html/app" ]; then
    # Check if the .env file exists within the app directory and extract values if it does
    if [ -f "/var/www/html/app/.env" ]; then
        adminuser=$(grep "DB_USERNAME=" /var/www/html/app/.env | awk -F "=" '{print $2}')
        adminpass=$(grep "DB_PASSWORD=" /var/www/html/app/.env | awk -F "=" '{print $2}')
        def_port=$(grep "PORT_PANEL=" /var/www/html/app/.env | awk -F "=" '{print $2}')
        def_cp=$(grep "PANEL_DIRECT=" /var/www/html/app/.env | awk -F "=" '{print $2}')
        def_pw=$(grep "DB_PASSWORD=" /var/www/html/app/.env | awk -F "=" '{print $2}')
    fi
fi

# Check if the cp directory exists
if [ -d "/var/www/html/cp" ]; then
    # Check if the config.js file exists within the cp directory and extract value if it does
    if [ -f "/var/www/html/cp/assets/js/config.js" ]; then
        vx=$(awk -F\' '/var version/ {print $2}' /var/www/html/cp/assets/js/config.js)
    fi
fi

# Function to display the menu
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

BLUE=$(tput setaf 12)
RED=$(tput setaf 1)
RESET=$(tput sgr0)
function show_menu() {
    clear
	echo -e "${GREEN}██╗░░██╗██████╗░░█████╗░███╗░░██╗███████╗██╗░░░░░"
	echo -e "${GREEN}╚██╗██╔╝██╔══██╗██╔══██╗████╗░██║██╔════╝██║░░░░░"
	echo -e "${GREEN}░╚███╔╝░██████╔╝███████║██╔██╗██║█████╗░░██║░░░░░"
	echo -e "${GREEN}░██╔██╗░██╔═══╝░██╔══██║██║╚████║██╔══╝░░██║░░░░░"
	echo -e "${GREEN}██╔╝╚██╗██║░░░░░██║░░██║██║░╚███║███████╗███████╗"
	echo -e "${GREEN}╚═╝░░╚═╝╚═╝░░░░░╚═╝░░╚═╝╚═╝░░╚══╝╚══════╝╚══════╝"
	echo -e "$BLUE┌────────────────────────────────────────────────────────────────•${NC}"
	echo -e "$BLUE│$NC ${ORANGE}•${NC} Version: ${BLUE}$vx${NC}"
	echo -e "$BLUE│$NC ${ORANGE}•${NC} SSH PORT: $sshport"
	echo -e "$BLUE│$NC ${ORANGE}•${NC} Username: ${BLUE}$adminuser${NC}"
	echo -e "$BLUE│$NC ${ORANGE}•${NC} Password: ${BLUE}$def_pw${NC}"
	echo -e "$BLUE│$NC ${ORANGE}•${NC} XPanel Link: \"http://$domain:$def_port/$def_cp/login\""
	echo -e "$BLUE│$NC ${ORANGE}•${NC} UPTime: $(uptime -p | sed 's/up //')"
	echo -e "$BLUE└────────────────────────────────────────────────────────────────•${NC}"
	echo -e "$BLUE•────────────────────────────────────────────────────────────────┐$NC"
	echo -e " ${BLUE}[1]${NC} ${ORANGE}•${NC} ${WHITE}Change Username AND Password${NC}"
	echo -e " ${BLUE}[2]${NC} ${ORANGE}•${NC} ${WHITE}Change Port SSH${NC}"
	echo -e " ${BLUE}[3]${NC} ${ORANGE}•${NC} ${WHITE}Change Port SSH TLS${NC}"
	echo -e " ${BLUE}[4]${NC} ${ORANGE}•${NC} ${WHITE}Update XPanel Nginx Web Server${NC}"
	echo -e " ${BLUE}[5]${NC} ${ORANGE}•${NC} ${WHITE}Remove XPanel${NC}"
	echo -e " ${BLUE}[6]${NC} ${ORANGE}•${NC} ${WHITE}Remove All Admin XPanel${NC}"
	echo -e " ${BLUE}[7]${NC} ${ORANGE}•${NC} ${WHITE}Blocked Port 80 and 443 IRAN${NC}"
	echo -e " ${BLUE}[8]${NC} ${ORANGE}•${NC} ${WHITE}UnBlock Port 80 and 443 IRAN${NC}"
	echo -e " ${BLUE}[9]${NC} ${ORANGE}•${NC} ${WHITE}Install Dropbear${NC}"
	echo -e " ${BLUE}[10]${NC} ${ORANGE}•${NC} ${WHITE}Install WordPress${NC}"
	echo -e " ${BLUE}[11]${NC} ${ORANGE}•${NC} ${WHITE}Fix Call (UDPGW)${NC}"
	echo -e " ${BLUE}[12]${NC} ${ORANGE}•${NC} ${WHITE}Sing-box${NC}"
        echo -e " ${BLUE}[13]${NC} ${ORANGE}•${NC} ${WHITE}Install BBR${NC}"
	echo -e "$BLUE•────────────────────────────────────────────────────────────────┘${NC}"
	echo -e "$BLUE┌─────────────────────────┐${NC}"
	echo -e "$BLUE│$NC ${BLUE}[0]${NC} ${ORANGE}•${NC} ${RED}Exit${NC}$NC"
	echo -e "$BLUE└─────────────────────────┘${NC}"
	echo -e ""
}

# Function to select an option
function select_option() {
    read -p "Please enter the option number: " choice
    case $choice in
        1)
            echo "Please enter a username: "
            read username
            echo "Please enter a Password: "
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
            sed -i "s/Port.*/Port $port/" /etc/ssh/sshd_config
            sed -i "s/PORT_SSH=.*/PORT_SSH=$port/g" /var/www/html/app/.env
            sed -i "s/DEFAULT_HOST =.*/DEFAULT_HOST = '127.0.0.1:${port}'/g" /usr/local/bin/wss
            systemctl daemon-reload
            systemctl enable wss
            systemctl restart wss
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
            bash <(curl -Ls https://github.com/xpanel-cp/XPanel-SSH-User-Management/raw/master/install.sh --ipv4)
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
        sudo apt-get purge apache2 nginx php8.1 zip unzip net-tools mariadb-server php8.1-cli php8.1-fpm php8.1-mysql php8.1-curl php8.1-gd php8.1-mbstring php8.1-xml -y
        fi
        ;;
        
        6)
       mysql -e "USE XPanel_plus; TRUNCATE TABLE admins;"
       echo "Removed All Admin"
        ;;
        
        7)
        bash <(curl -Ls https://github.com/xpanel-cp/XPanel-SSH-User-Management/raw/master/block_iran.sh --ipv4)
        ;;
        8)
        sudo iptables -F
        ;;
        9)
        bash <(curl -Ls https://raw.githubusercontent.com/xpanel-cp/Dropbear-ssh/main/install.sh)
        ;;
        10)
        bash <(curl -Ls https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/wp-install.sh --ipv4)
        ;;
        11)
        bash <(curl -Ls https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/fix-call.sh --ipv4)
        ;; 
        12)
        bash <(curl -Ls https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/OT/singbox.sh --ipv4)
        ;;
	13)
        bash <(curl -Ls https://raw.githubusercontent.com/teddysun/across/master/bbr.sh --ipv4)
        ;;
        0)
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
