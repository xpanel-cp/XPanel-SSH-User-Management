#!/bin/bash

#By setting DEBIAN_FRONTEND to noninteractive, any prompts or interactive dialogs from the package manager will proceed with the installation without user intervention.
export DEBIAN_FRONTEND=noninteractive
config_file="/etc/needrestart/needrestart.conf"
# Check if the configuration file exists
if [ -f "$config_file" ]; then
    # Disable "Pending kernel upgrade" popup during install
    sed -i "s/#\$nrconf{kernelhints} = -1;/\$nrconf{kernelhints} = -1;/g" "$config_file"

    # Disable "Daemons using outdated libraries" popup during install
    sudo sed -i 's/#$nrconf{restart} = '"'"'i'"'"';/$nrconf{restart} = '"'"'a'"'"';/g' "$config_file"
fi
RED="\e[31m"
GREEN="\e[32m"
YELLOW="\e[33m"
BLUE="\e[34m"
CYAN="\e[36m"
ENDCOLOR="\e[0m"

if [ "$EUID" -ne 0 ]; then
  echo "Please run as root"
  exit
fi
ENV_FILE="/var/www/html/app/.env"
COPY_FILE="/var/www/html/.env_copy"

if [ -f "$ENV_FILE" ]; then
  cp "$ENV_FILE" "$COPY_FILE"
  chmod 644 /var/www/html/.env_copy
fi
checkOS() {
  # List of supported distributions
  #supported_distros=("Ubuntu" "Debian" "Fedora" "CentOS" "Arch")
  supported_distros=("Ubuntu")
  # Get the distribution name and version
  if [[ -f "/etc/os-release" ]]; then
    source "/etc/os-release"
    distro_name=$NAME
    distro_version=$VERSION_ID
  else
    echo "Unable to determine distribution."
    exit 1
  fi
  # Check if the distribution is supported
  if [[ " ${supported_distros[@]} " =~ " ${distro_name} " ]]; then
    echo "Your Linux distribution is ${distro_name} ${distro_version}"
    : #no-op command
  else
    # Print error message in red
    echo -e "\e[31mYour Linux distribution (${distro_name} ${distro_version}) is not currently supported.\e[0m"
    exit 1
  fi

  # This script only works on Ubuntu 20 and above
  if [ "$(uname)" == "Linux" ]; then
    version_info=$(lsb_release -rs | cut -d '.' -f 1)
    # Check if it's Ubuntu and version is below 20
    if [ "$(lsb_release -is)" == "Ubuntu" ] && [ "$version_info" -lt 20 ]; then
      echo "This script only works on Ubuntu 20 and above"
      exit
    fi
  fi
}
configSSH() {
  sed -i 's/#Port 22/Port 22/' /etc/ssh/sshd_config
  sed -i 's/Banner \/root\/banner.txt/#Banner none/g' /etc/ssh/sshd_config
  sed -i 's/AcceptEnv/#AcceptEnv/g' /etc/ssh/sshd_config
  port=$(grep -oE 'Port [0-9]+' /etc/ssh/sshd_config | cut -d' ' -f2)
}
setCONFIG() {

source_file="/var/www/html/example/index.php"
destination_directory="/var/www/"
[ -f "$source_file" ] && cp "$source_file" "$destination_directory" && echo "index.php copied successfully."

  # Check if MySQL is installed
  if dpkg-query -W -f='${Status}' mariadb-server 2>/dev/null | grep -q "install ok installed"; then
    adminuser=$(mysql -N -e "use XPanel_plus; select username from admins where permission='admin';")
    adminpass=$(mysql -N -e "use XPanel_plus; select username from admins where permission='admin';")
    ssh_tls_port=$(mysql -N -e "use XPanel_plus; select tls_port from settings where id='1';")
  fi

  folder_path_cp="/var/www/html/cp"
  if [ -d "$folder_path_cp" ]; then
    rm -rf /var/www/html/cp
  fi
  folder_path_app="/var/www/html/app"
  if [ -d "$folder_path_app" ]; then
    rm -rf /var/www/html/app
  fi

  if [ -n "$ssh_tls_port" -a "$ssh_tls_port" != "NULL" ]; then
    sshtls_port=$ssh_tls_port
  else
    sshtls_port=444
  fi
  if test -f "/var/www/xpanelport"; then
    domainp=$(cat /var/www/xpanelport | grep "^DomainPanel")
    sslp=$(cat /var/www/xpanelport | grep "^SSLPanel")
    xpo=$(cat /var/www/xpanelport | grep "^Xpanelport")
    xport=$(echo "$xpo" | sed "s/Xpanelport //g")
    dmp=$(echo "$domainp" | sed "s/DomainPanel //g")
    dmssl=$(echo "$sslp" | sed "s/SSLPanel //g")
  else
    xport=""
    dmp=""
    dmssl=""
  fi
}
wellcomeINSTALL() {
  echo -e "${YELLOW}************ Select XPanel Version Nginx Web Server************"
  echo -e "${GREEN}  1)XPanel v4.0 full free"
  echo -e "${GREEN}  2)XPanel v3.9.9"
  echo -e "${GREEN}  3)XPanel v3.9.7"
  echo -e "${GREEN}  4)XPanel v3.9.6"
  echo -e "${GREEN}  5)XPanel v3.9.4"
  echo -e "${GREEN}  6)XPanel v3.9.1"
  echo -e "${GREEN}  7)XPanel v3.8.7"
  echo -e "${GREEN}  8)XPanel v3.8.6"
  echo -e "${GREEN}  9)XPanel v3.8.5"
  echo -e "${GREEN}  10)XPanel v3.7.9"
  echo -ne "${GREEN}\nSelect Version : ${ENDCOLOR}"
  read n
  if [ "$n" != "" ]; then
    if [ "$n" == "1" ]; then
      linkd=https://api.github.com/repos/xpanel-cp/XPanel-SSH-User-Management/releases/tags/v4-0
    fi
    if [ "$n" == "2" ]; then
      linkd=https://api.github.com/repos/xpanel-cp/XPanel-SSH-User-Management/releases/tags/v3-9-9
    fi
    if [ "$n" == "3" ]; then
      linkd=https://api.github.com/repos/xpanel-cp/XPanel-SSH-User-Management/releases/tags/v3-9-7
    fi
    if [ "$n" == "4" ]; then
      linkd=https://api.github.com/repos/xpanel-cp/XPanel-SSH-User-Management/releases/tags/v3-9-6
    fi
    if [ "$n" == "5" ]; then
      linkd=https://api.github.com/repos/xpanel-cp/XPanel-SSH-User-Management/releases/tags/v3-9-4
    fi
    if [ "$n" == "6" ]; then
      linkd=https://api.github.com/repos/xpanel-cp/XPanel-SSH-User-Management/releases/tags/v3-9-1
    fi
    if [ "$n" == "7" ]; then
      linkd=https://api.github.com/repos/xpanel-cp/XPanel-SSH-User-Management/releases/tags/v3-8-7
    fi
    if [ "$n" == "8" ]; then
      linkd=https://api.github.com/repos/xpanel-cp/XPanel-SSH-User-Management/releases/tags/v3-8-6
    fi
    if [ "$n" == "9" ]; then
      linkd=https://api.github.com/repos/xpanel-cp/XPanel-SSH-User-Management/releases/tags/v3-8-5
    fi
    if [ "$n" == "10" ]; then
      linkd=https://api.github.com/repos/xpanel-cp/XPanel-SSH-User-Management/releases/tags/v3-7-9
    fi
    
  else
    linkd=https://api.github.com/repos/xpanel-cp/XPanel-SSH-User-Management/releases/tags/v4-0
  fi
}
userINPU() {
  echo -e "\nPlease input IP Server"
  printf "IP: "
  read ip
  if [ -n "$ip" -a "$ip" == " " ]; then
    echo -e "\nPlease input IP Server"
    printf "IP: "
    read ip
  fi
  clear
  adminusername=admin
  echo -e "\nPlease input Panel admin user."
  printf "Default user name is \e[33m${adminusername}\e[0m, leave it blank to use this user name: "
  read usernametmp
  if [[ -n "${usernametmp}" ]]; then
    adminusername=${usernametmp}
  fi

  # Function to generate random uppercase character
  function random_uppercase {
    echo $((RANDOM % 26 + 65)) | awk '{printf("%c",$1)}'
  }

  # Function to generate random lowercase character
  function random_lowercase {
    echo $((RANDOM % 26 + 97)) | awk '{printf("%c",$1)}'
  }

  # Function to generate random digit
  function random_digit {
    echo $((RANDOM % 10))
  }

  # Generate a complex password
  password=""
  password="${password}$(random_uppercase)"
  password="${password}$(random_uppercase)"
  password="${password}$(random_uppercase)"
  password="${password}$(random_uppercase)"
  password="${password}$(random_digit)"
  password="${password}$(random_digit)"
  password="${password}$(random_digit)"
  password="${password}$(random_digit)"
  password="${password}$(random_lowercase)"
  password="${password}$(random_lowercase)"
  password="${password}$(random_lowercase)"

  adminpassword=${password}

  echo -e "\nPlease input Panel admin password."
  printf "Randomly generated password is \e[33m${adminpassword}\e[0m, leave it blank to use this random password : "
  read passwordtmp
  if [[ -n "${passwordtmp}" ]]; then
    adminpassword=${passwordtmp}
  fi
}
startINSTALL() {
  if [ "$dmp" != "" ]; then
    defdomain=$dmp
  else

    defdomain=$ip
  fi

  if [ "$dmssl" == "True" ]; then
    protcohttp=https

  else
    protcohttp=http
  fi
  ipv4=$ip
  sudo sed -i '/www-data/d' /etc/sudoers &
  wait

  if command -v apt-get >/dev/null; then
    sudo systemctl stop apache2
    sudo systemctl disable apache2
    sudo apt-get remove apache2 -y
    sudo apt autoremove -y

    sudo NEETRESTART_MODE=a apt-get update --yes
    sudo apt update -y
    sudo apt upgrade -y
    sudo apt -y -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" upgrade
    apt-get install -y stunnel4 && apt-get install -y cmake && apt-get install -y screenfetch && apt-get install -y openssl
    sudo apt-get -y install software-properties-common
    sudo add-apt-repository ppa:ondrej/php -y
    sudo apt-get install nginx zip unzip net-tools mariadb-server -y
    sudo apt-get install php php-cli php-mbstring php-dom php-pdo php-mysql -y
    sudo apt-get install npm -y
    sudo apt install python -y
    sudo apt install python3 -y
    sudo apt install iftop -y
    sudo apt install apt-transport-https -y
    sudo apt-get install coreutils
    apt install curl -y
    apt install git cmake -y
    apt install php8.1 php8.1-mysql php8.1-xml php8.1-curl cron -y
    sudo apt install php8.1-fpm
    sudo apt install php8.1 php8.1-cli php8.1-common php8.1-opcache php8.1-mysql php8.1-mbstring php8.1-zip php8.1-intl php8.1-simplexml -y
    wait

    phpv=$(php -v)
    if [[ $phpv == *"8.1"* ]]; then

      apt autoremove -y
      echo "PHP Is Installed :)"
    else
      rm -fr /etc/php/7.4/apache2/conf.d/00-ioncube.ini
      sudo apt-get purge '^php7.*' -y
      apt remove php* -y
      apt remove php -y
      apt autoremove -y
      apt install php8.1 php8.1-mysql php8.1-xml php8.1-curl cron -y
      sudo apt install php8.1-fpm
      sudo apt install php8.1 php8.1-cli php8.1-common  php8.1-opcache php8.1-mysql php8.1-mbstring php8.1-zip php8.1-intl php8.1-simplexml -y

    fi
    curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
    echo "/bin/false" >>/etc/shells
    echo "/usr/sbin/nologin" >>/etc/shells

    #Banner
    cat <<EOF >/root/banner.txt
Connect To Server
EOF
    #Configuring stunnel
    sudo mkdir /etc/stunnel
    cat <<EOF >/etc/stunnel/stunnel.conf
 cert = /etc/stunnel/stunnel.pem
 [openssh]
 accept = $sshtls_port
 connect = 0.0.0.0:$port
 sslVersion = TLSv1.2
 options = NO_SSLv2
 options = NO_SSLv3
 options = SINGLE_DH_USE
 options = SINGLE_ECDH_USE
 ciphers = ECDH+AESGCM:DH+AESGCM:ECDH+AES256:DH+AES256:ECDH+AES128:DH+AES:ECDH+3DES:DH+3DES:RSA+AESGCM:RSA+AES:RSA+3DES:!aNULL:!MD5:!DSS
EOF

    echo "=================  XPanel OpenSSL ======================"
    country=ID
    state=Semarang
    locality=XPanel
    organization=hidessh
    organizationalunit=HideSSH
    commonname=hidessh.com
    email=admin@hidessh.com
    openssl genrsa -out key.pem 2048
    openssl req -new -x509 -key key.pem -out cert.pem -days 1095 -subj "/C=$country/ST=$state/L=$locality/O=$organization/OU=$organizationalunit/CN=$commonname/emailAddress=$email"
    cat key.pem cert.pem >>/etc/stunnel/stunnel.pem
    sed -i 's/ENABLED=0/ENABLED=1/g' /etc/default/stunnel4
    service stunnel4 restart

    if test -f "/var/www/xpanelport"; then
      echo "File exists xpanelport"
    else
      touch /var/www/xpanelport
    fi
    link=$(sudo curl -Ls "$linkd" | grep '"browser_download_url":' | sed -E 's/.*"([^"]+)".*/\1/')
    sudo wget -O /var/www/html/update.zip $link
    sudo unzip -o /var/www/html/update.zip -d /var/www/html/ &
    wait
    sudo wget -4 -O /usr/local/bin/cronx https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/cronx
    chmod +x /usr/local/bin/cronx
    sudo wget -4 -O /usr/local/bin/cronxfixed https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/cronxfixed
    chmod +x /usr/local/bin/cronxfixed
    sed -i 's@zend_extension = /usr/local/ioncube/ioncube_loader_lin_8.1.so@@' /etc/php/8.1/cli/php.ini
    bash <(curl -Ls https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/ioncube.sh --ipv4)
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/local/bin/cronx' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/local/bin/cronxfixed' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/adduser' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/userdel' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/sed' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/passwd' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/curl' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/kill' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/killall' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/lsof' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/lsof' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/sed' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/rm' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/crontab' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/mysqldump' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/pgrep' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/nethogs' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/nethogs' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/local/sbin/nethogs' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/netstat' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/service' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/reboot' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/cp' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/rm' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/zip' | sudo EDITOR='tee -a' visudo &
    wait
    echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/zip -r' | sudo EDITOR='tee -a' visudo &
    wait
    clear

    # Random port number generator to prevent xpanel detection by potential attackers
    randomPort=""
    # Check if $RANDOM is available in the shell
    if [ -z "$RANDOM" ]; then
      # If $RANDOM is not available, use a different random number generation method
      random_number=$(od -A n -t d -N 2 /dev/urandom | tr -d ' ')
    else
      # Generate a random number between 0 and 63000 using $RANDOM
      random_number=$((RANDOM % 63001))
    fi

    # Add 2000 to the random number to get a range between 2000 and 65000
    randomPort=$((random_number + 2000))

    # Use port 8081 if the random_number is zero (in case $RANDOM was not available and port 8081 was chosen)
    if [ "$random_number" -eq 0 ]; then
      randomPort=8081
    fi

    echo -e "\nPlease input Panel admin Port, or leave blank to use randomly generated port"
    printf "Random port \033[33m$randomPort:\033[0m "
    read porttmp
    if [[ -n "${porttmp}" ]]; then
      #Get the server port number from my settings file
      serverPort=${porttmp}
      serverPortssl=$((serverPort + 1))
      echo $serverPort
    else
      serverPort=$randomPort
      serverPortssl=$((serverPort + 1))
      echo $serverPort
    fi
    if [ "$dmssl" == "True" ]; then
      sshttp=$((serverPort + 1))
    else
      sshttp=$serverPort
    fi
    udpport=7300
    echo -e "\nPlease input UDPGW Port ."
    printf "Default Port is \e[33m${udpport}\e[0m, leave it blank to use this Port: "
    read udpport
    sudo bash -c "$(curl -Ls https://raw.githubusercontent.com/xpanel-cp/Nethogs-Json-main/master/install.sh --ipv4)"
    git clone https://github.com/ambrop72/badvpn.git /root/badvpn
    mkdir /root/badvpn/badvpn-build
    cd /root/badvpn/badvpn-build
    cmake .. -DBUILD_NOTHING_BY_DEFAULT=1 -DBUILD_UDPGW=1 &
    wait
    make &
    wait
    cp udpgw/badvpn-udpgw /usr/local/bin
    cat >/etc/systemd/system/videocall.service <<ENDOFFILE
[Unit]
Description=UDP forwarding for badvpn-tun2socks
After=nss-lookup.target

[Service]
ExecStart=/usr/local/bin/badvpn-udpgw --loglevel none --listen-addr 127.0.0.1:$udpport --max-clients 999
User=videocall

[Install]
WantedBy=multi-user.target
ENDOFFILE
    useradd -m videocall
    systemctl enable videocall
    systemctl start videocall

    ##Get just the port number from the settings variable I just grabbed
    serverPort=${serverPort##*=}
    ##Remove the "" marks from the variable as they will not be needed
    serverPort=${serverPort//''/}
    sudo tee /etc/nginx/sites-available/default <<'EOF'
server {
    listen 80;
    server_name example.com;
    root /var/www/html/example;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param PHP_VALUE "memory_limit=4096M";
    }
    location ~ /\.ht {
        deny all;
    }
     location /ws
    {
    proxy_pass http://127.0.0.1:8880/;
    proxy_redirect off;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_read_timeout 52w;
    }
    location /drp
    {
    proxy_pass http://127.0.0.1:9990/;
    proxy_redirect off;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_read_timeout 52w;
    }
}
server {
    listen 443 ssl;
    server_name example.com;

    root /var/www/html/example;
    index index.php index.html;

    ssl_certificate /root/cert.pem;
    ssl_certificate_key /root/key.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param PHP_VALUE "memory_limit=4096M";
    }

    location ~ /\.ht {
        deny all;
    }

    location /ws {
        if ($http_upgrade != "websocket") {
                return 404;
        }
        proxy_pass http://127.0.0.1:8880;
        proxy_redirect off;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_read_timeout 52w;
    }
    location /drp {
        if ($http_upgrade != "websocket") {
                return 404;
        }
        proxy_pass http://127.0.0.1:9990;
        proxy_redirect off;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_read_timeout 52w;
    }
}
server {
    listen serverPort;
    server_name example.com;
    root /var/www/html/cp;
    index index.php index.html;
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param PHP_VALUE "memory_limit=4096M";
        fastcgi_param IONCUBE "/usr/local/ioncube/ioncube_loader_lin_8.1.so";
        fastcgi_param PHP_ADMIN_VALUE "zend_extension=/usr/local/ioncube/ioncube_loader_lin_8.1.so";
    }
    location ~ /\.ht {
        deny all;
    }
}
EOF
    sed -i "s/serverPort/$serverPort/g" /etc/nginx/sites-available/default
    sudo ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/
    echo '#Xpanel' >/var/www/xpanelport
    sudo sed -i -e '$a\'$'\n''Xpanelport '$serverPort /var/www/xpanelport
    wait
    ##Restart the webserver server to use new port
    sudo nginx -t
    sudo systemctl start nginx
    sudo systemctl enable nginx
    sudo systemctl reload nginx
    # Getting Proxy Template
    sudo wget -q -O /usr/local/bin/wss https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/wss
    sudo chmod +x /usr/local/bin/wss
    sudo wget -q -O /usr/local/bin/wssd https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/wssd
    sudo chmod +x /usr/local/bin/wssd

    # Installing Service
    cat >/etc/systemd/system/wss.service <<END
[Unit]
Description=Python Proxy XPanel
Documentation=https://t.me/Xpanelssh
After=network.target nss-lookup.target

[Service]
Type=simple
User=root
CapabilityBoundingSet=CAP_NET_ADMIN CAP_NET_BIND_SERVICE
AmbientCapabilities=CAP_NET_ADMIN CAP_NET_BIND_SERVICE
NoNewPrivileges=true
ExecStart=/usr/bin/python -O /usr/local/bin/wss 8880
Restart=on-failure

[Install]
WantedBy=multi-user.target
END

    systemctl daemon-reload
    systemctl enable wss
    systemctl restart wss

    cat >/etc/systemd/system/wssd.service <<END
[Unit]
Description=Python Proxy XPanel
Documentation=https://t.me/Xpanelssh
After=network.target nss-lookup.target

[Service]
Type=simple
User=root
CapabilityBoundingSet=CAP_NET_ADMIN CAP_NET_BIND_SERVICE
AmbientCapabilities=CAP_NET_ADMIN CAP_NET_BIND_SERVICE
NoNewPrivileges=true
ExecStart=/usr/bin/python -O /usr/local/bin/wssd 9990
Restart=on-failure

[Install]
WantedBy=multi-user.target
END

    systemctl daemon-reload
    systemctl enable wssd
    systemctl restart wssd

    chown www-data:www-data /var/www/html/cp/* &
    wait
    systemctl restart mariadb &
    wait
    systemctl enable mariadb &
    wait
    PHP_INI=$(php -i | grep /.+/php.ini -oE)
    sed -i 's/extension=intl/;extension=intl/' ${PHP_INI}
    wait
    po=$(cat /etc/ssh/sshd_config | grep "^Port")
    port=$(echo "$po" | sed "s/Port //g")
    sed -i "s/DEFAULT_HOST =.*/DEFAULT_HOST = '127.0.0.1:${port}'/g" /usr/local/bin/wss
    systemctl daemon-reload
    systemctl enable wss
    systemctl restart wss
    systemctl enable stunnel4
    systemctl restart stunnel4
    wait
  fi
  
}

checkDATABASE() {
  mysql -e "create database XPanel_plus;" &
  wait
  mysql -e "CREATE USER '${adminusername}'@'localhost' IDENTIFIED BY '${adminpassword}';" &
  wait
  mysql -e "GRANT ALL ON *.* TO '${adminusername}'@'localhost';" &
  wait
  mysql -e "ALTER USER '${adminusername}'@'localhost' IDENTIFIED BY '${adminpassword}';" &
  wait
  sed -i "s/DB_USERNAME=.*/DB_USERNAME=$adminusername/g" /var/www/html/app/.env
  sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$adminpassword/g" /var/www/html/app/.env
  cd /var/www/html/app
  php artisan migrate
  if [ -n "$adminuser" -a "$adminuser" != "NULL" ]; then
    mysql -e "USE XPanel_plus; UPDATE admins SET username = '${adminusername}' where permission='admin';"
    mysql -e "USE XPanel_plus; UPDATE admins SET password = '${adminpassword}' where permission='admin';"
    mysql -e "USE XPanel_plus; UPDATE settings SET ssh_port = '${port}' where id='1';"
    php artisan clear-compiled
    php artisan cache:clear
    php artisan config:clear
    php artisan view:clear

  else
    mysql -e "USE XPanel_plus; INSERT INTO admins (username, password, permission, credit, status) VALUES ('${adminusername}', '${adminpassword}', 'admin', '', 'active');"
    home_url=$protcohttp://${defdomain}:$sshttp
    mysql -e "USE XPanel_plus; INSERT INTO settings (ssh_port, tls_port, t_token, t_id, language, multiuser, ststus_multiuser, home_url) VALUES ('${port}', '444', '', '', '', 'active', '', '${home_url}');"
  fi
}
moreCONFIG() {
  sed -i "s/PORT_SSH=.*/PORT_SSH=$port/g" /var/www/html/app/.env
  sed -i "s/PORT_UDPGW=.*/PORT_UDPGW=$udpport/g" /var/www/html/app/.env
  sudo chown -R www-data:www-data /var/www/html/app
  crontab -r
  wait

  multiin=$(echo "$protcohttp://${defdomain}:$sshttp/fixer/multiuser")
  cat >/var/www/html/kill.sh <<ENDOFFILE
#!/bin/bash
#By Alireza
i=0
while [ 1i -lt 10 ]; do
cmd=(bbh '$multiin')
echo cmd &
sleep 6
i=(( i + 1 ))
done
ENDOFFILE
  wait
  sudo sed -i 's/(bbh/$(curl -v -H "A: B"/' /var/www/html/kill.sh
  wait
  sudo sed -i 's/cmd/$cmd/' /var/www/html/kill.sh
  wait
  sudo sed -i 's/1i/$i/' /var/www/html/kill.sh
  wait
  sudo sed -i 's/((/$((/' /var/www/html/kill.sh
  wait
  chmod +x /var/www/html/kill.sh

  othercron=$(echo "$protcohttp://${defdomain}:$sshttp/fixer/other")
  cat >/var/www/html/other.sh <<ENDOFFILE
#!/bin/bash
#By Alireza
i=0
while [ 1i -lt 3 ]; do
cmd=(bbh '$othercron')
echo cmd &
sleep 17
i=(( i + 1 ))
done
ENDOFFILE
  wait
  sudo sed -i 's/(bbh/$(curl -v -H "A: B"/' /var/www/html/other.sh
  wait
  sudo sed -i 's/cmd/$cmd/' /var/www/html/other.sh
  wait
  sudo sed -i 's/1i/$i/' /var/www/html/other.sh
  wait
  sudo sed -i 's/((/$((/' /var/www/html/other.sh
  wait
  chmod +x /var/www/html/other.sh
  
  mkdir /var/www/html/app/storage/banner
  chmod 777 /etc/ssh/sshd_config
  chmod 777 /var/www/html/app/storage/banner
  if ! grep -q -E "#?Match all" /etc/ssh/sshd_config; then
    echo "#Match all" | sudo tee -a /etc/ssh/sshd_config
    sudo systemctl restart ssh
  fi
  wait
  if [ "$xport" != "" ]; then
    pssl=$((xport + 1))
  fi
  (crontab -l | grep . ; echo -e "* * * * * /var/www/html/kill.sh") | crontab -
  (crontab -l | grep . ; echo -e "* * * * * /var/www/html/other.sh") | crontab -
  (crontab -l | grep . ; echo -e "0 */1 * * * /var/www/html/killlog.sh") | crontab -
  (crontab -l ; echo "* * * * * wget -q -O /dev/null '$protcohttp://${defdomain}:$sshttp/fixer/exp' > /dev/null 2>&1") | crontab -
  (crontab -l ; echo "0 * * * * wget -q -O /dev/null '$protcohttp://${defdomain}:$sshttp/fixer/checkhurly' > /dev/null 2>&1") | crontab -
  (crontab -l ; echo "*/10 * * * * wget -q -O /dev/null '$protcohttp://${defdomain}:$sshttp/fixer/checktraffic' > /dev/null 2>&1") | crontab -
  (crontab -l ; echo "*/15 * * * * wget -q -O /dev/null '$protcohttp://${defdomain}:$sshttp/fixer/checkfilter' > /dev/null 2>&1") | crontab -
  (crontab -l ; echo "0 0 * * * wget -q -O /dev/null '$protcohttp://${defdomain}:$sshttp/fixer/send/email/3day' > /dev/null 2>&1") | crontab -
  (crontab -l ; echo "0 0 * * * wget -q -O /dev/null '$protcohttp://${defdomain}:$sshttp/fixer/send/email/24h' > /dev/null 2>&1") | crontab -
  crontab -l | sed '/dropbear\.sh/d' | crontab -
  if [ -f "/var/www/html/dropbear.sh" ]; then
    rm -rf "/var/www/html/dropbear.sh"
  fi
  wait
  systemctl enable stunnel4 &
  wait
  systemctl restart stunnel4 &
  wait
  sudo mkdir -p /xpanel
  wait
  curl -sL -o /usr/local/bin/xp_user_limit https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/xp_user_limit.sh
  chmod +x /usr/local/bin/xp_user_limit
  echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/local/bin/xp_user_limit' | sudo EDITOR='tee -a' visudo
  wait
  curl -o /root/xpanel.sh https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/cli.sh
  sudo wget -4 -O /usr/local/bin/xpanel https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/cli.sh
  chmod +x /usr/local/bin/xpanel
  chown www-data:www-data /var/www/html/example/
  chown www-data:www-data /var/www/html/example/index.php
  sed -i "s/PORT_PANEL=.*/PORT_PANEL=$sshttp/g" /var/www/html/app/.env
  DEFAULT_APP_LOCALE=en
  DEFAULT_APP_MODE=light
  DEFAULT_PANEL_DIRECT=cp
  DEFAULT_CRON_TRAFFIC=active
  DEFAULT_DAY=active
  DEFAULT_PORT_DROPBEAR=2083
  DEFAULT_TRAFFIC_BASE=12
  DEFAULT_STATUS_LOG=deactive
  DEFAULT_BOT_TOKEN=
  DEFAULT_BOT_ID_ADMIN=
  DEFAULT_BOT_API_ACCESS= 
  DEFAULT_ANTI_USER= 
  DEFAULT_TRAFFIC_SERVER= 
  DEFAULT_BOT_LOG=
  DEFAULT_PORT_UDPGW=
  DEFAULT_MAIL_STATUS=
  DEFAULT_MAIL_HOST=
  DEFAULT_MAIL_PORT=
  DEFAULT_MAIL_USERNAME=
  DEFAULT_MAIL_PASSWORD=
  DEFAULT_MAIL_FROM_ADDRESS=
  DEFAULT_MAIL_FROM_NAME=
  DEFAULT_GB_CHANGE=

  if [ -f /var/www/html/.env_copy ]; then
    while IFS= read -r line; do
      key=$(echo "$line" | awk -F'=' '{print $1}')
      value=$(echo "$line" | awk -F'=' '{print $2}')

      if [ "$key" = "APP_LOCALE" ]; then
        APP_LOCALE="$value"
      elif [ "$key" = "APP_MODE" ]; then
        APP_MODE="$value"
      elif [ "$key" = "PANEL_DIRECT" ]; then
        PANEL_DIRECT="$value"
      elif [ "$key" = "CRON_TRAFFIC" ]; then
        CRON_TRAFFIC="$value"
      elif [ "$key" = "DAY" ]; then
        DAY="$value"
      elif [ "$key" = "PORT_DROPBEAR" ]; then
        PORT_DROPBEAR="$value"
      elif [ "$key" = "TRAFFIC_BASE" ]; then
        TRAFFIC_BASE="$value"
      elif [ "$key" = "STATUS_LOG" ]; then
        STATUS_LOG="$value"
      elif [ "$key" = "BOT_TOKEN" ]; then
        BOT_TOKEN="$value"
      elif [ "$key" = "BOT_ID_ADMIN" ]; then
        BOT_ID_ADMIN="$value"
      elif [ "$key" = "BOT_API_ACCESS" ]; then
        BOT_API_ACCESS="$value"
      elif [ "$key" = "ANTI_USER" ]; then
        ANTI_USER="$value"
      elif [ "$key" = "TRAFFIC_SERVER" ]; then
        TRAFFIC_SERVER="$value"
      elif [ "$key" = "BOT_LOG" ]; then
        BOT_LOG="$value"
      elif [ "$key" = "PORT_UDPGW" ]; then
        PORT_UDPGW="$value"
      elif [ "$key" = "MAIL_STATUS" ]; then
        MAIL_STATUS="$value"
      elif [ "$key" = "MAIL_HOST" ]; then
        MAIL_HOST="$value"  
      elif [ "$key" = "MAIL_PORT" ]; then
        MAIL_PORT="$value" 
      elif [ "$key" = "MAIL_USERNAME" ]; then
        MAIL_USERNAME="$value" 
      elif [ "$key" = "MAIL_PASSWORD" ]; then
        MAIL_PASSWORD="$value" 
      elif [ "$key" = "MAIL_FROM_ADDRESS" ]; then
        MAIL_FROM_ADDRESS="$value" 
      elif [ "$key" = "MAIL_FROM_NAME" ]; then
        MAIL_FROM_NAME="$value" 
      elif [ "$key" = "GB_CHANGE" ]; then
        GB_CHANGE="$value" 
      fi
    done </var/www/html/.env_copy
  fi

  APP_LOCALE="${APP_LOCALE:-$DEFAULT_APP_LOCALE}"
  APP_MODE="${APP_MODE:-$DEFAULT_APP_MODE}"
  PANEL_DIRECT="${PANEL_DIRECT:-$DEFAULT_PANEL_DIRECT}"
  CRON_TRAFFIC="${CRON_TRAFFIC:-$DEFAULT_CRON_TRAFFIC}"
  DAY="${DAY:-$DEFAULT_DAY}"
  PORT_DROPBEAR="${PORT_DROPBEAR:-$DEFAULT_PORT_DROPBEAR}"
  TRAFFIC_BASE="${TRAFFIC_BASE:-$DEFAULT_TRAFFIC_BASE}"
  STATUS_LOG="${STATUS_LOG:-$DEFAULT_STATUS_LOG}"
  BOT_TOKEN="${BOT_TOKEN:-$DEFAULT_BOT_TOKEN}"
  BOT_ID_ADMIN="${BOT_ID_ADMIN:-$DEFAULT_BOT_ID_ADMIN}"
  BOT_API_ACCESS="${BOT_API_ACCESS:-$DEFAULT_BOT_API_ACCESS}"
  ANTI_USER="${ANTI_USER:-$DEFAULT_ANTI_USER}"
  TRAFFIC_SERVER="${TRAFFIC_SERVER:-$DEFAULT_TRAFFIC_SERVER}"
  BOT_LOG="${BOT_LOG:-$DEFAULT_BOT_LOG}"
  PORT_UDPGW="${PORT_UDPGW:-$DEFAULT_PORT_UDPGW}"
  MAIL_STATUS="${MAIL_STATUS:-$DEFAULT_MAIL_STATUS}"
  MAIL_HOST="${BOT_MAIL_HOST:-$DEFAULT_MAIL_HOST}"
  MAIL_PORT="${BOT_MAIL_PORT:-$DEFAULT_MAIL_PORT}"
  MAIL_USERNAME="${BOT_MAIL_USERNAME:-$DEFAULT_MAIL_USERNAME}"
  MAIL_PASSWORD="${MAIL_PASSWORD:-$DEFAULT_MAIL_PASSWORD}"
  MAIL_FROM_ADDRESS="${MAIL_FROM_ADDRESS:-$DEFAULT_MAIL_FROM_ADDRESS}"
  MAIL_FROM_NAME="${MAIL_FROM_NAME:-$DEFAULT_MAIL_FROM_NAME}"
  GB_CHANGE="${GB_CHANGE:-$DEFAULT_GB_CHANGE}"

  sed -i "s/APP_LOCALE=.*/APP_LOCALE=$APP_LOCALE/g" /var/www/html/app/.env
  sed -i "s/APP_MODE=.*/APP_MODE=$APP_MODE/g" /var/www/html/app/.env
  sed -i "s/PANEL_DIRECT=.*/PANEL_DIRECT=$PANEL_DIRECT/g" /var/www/html/app/.env
  sed -i "s/CRON_TRAFFIC=.*/CRON_TRAFFIC=$CRON_TRAFFIC/g" /var/www/html/app/.env
  sed -i "s/DAY=.*/DAY=$DAY/g" /var/www/html/app/.env
  sed -i "s/PORT_DROPBEAR=.*/PORT_DROPBEAR=$PORT_DROPBEAR/g" /var/www/html/app/.env
  sed -i "s/TRAFFIC_BASE=.*/TRAFFIC_BASE=$TRAFFIC_BASE/g" /var/www/html/app/.env
  sed -i "s/STATUS_LOG=.*/STATUS_LOG=$STATUS_LOG/g" /var/www/html/app/.env
  sed -i "s/BOT_TOKEN=.*/BOT_TOKEN=$BOT_TOKEN/g" /var/www/html/app/.env
  sed -i "s/BOT_ID_ADMIN=.*/BOT_ID_ADMIN=$BOT_ID_ADMIN/g" /var/www/html/app/.env
  sed -i "s/BOT_API_ACCESS=.*/BOT_API_ACCESS=$BOT_API_ACCESS/g" /var/www/html/app/.env
  sed -i "s/ANTI_USER=.*/ANTI_USER=$ANTI_USER/g" /var/www/html/app/.env
  sed -i "s/TRAFFIC_SERVER=.*/TRAFFIC_SERVER=$TRAFFIC_SERVER/g" /var/www/html/app/.env
  sed -i "s/BOT_LOG=.*/BOT_LOG=$BOT_LOG/g" /var/www/html/app/.env
  sed -i "s/PORT_UDPGW=.*/PORT_UDPGW=$PORT_UDPGW/g" /var/www/html/app/.env
  sed -i "s/MAIL_STATUS=.*/MAIL_STATUS=$MAIL_STATUS/g" /var/www/html/app/.env
  sed -i "s/MAIL_HOST=.*/MAIL_HOST=$MAIL_HOST/g" /var/www/html/app/.env
  sed -i "s/MAIL_PORT=.*/MAIL_PORT=$MAIL_PORT/g" /var/www/html/app/.env
  sed -i "s/MAIL_USERNAME=.*/MAIL_USERNAME=$MAIL_USERNAME/g" /var/www/html/app/.env
  sed -i "s/MAIL_PASSWORD=.*/MAIL_PASSWORD=$MAIL_PASSWORD/g" /var/www/html/app/.env
  sed -i "s/MAIL_FROM_ADDRESS=.*/MAIL_FROM_ADDRESS=$MAIL_FROM_ADDRESS/g" /var/www/html/app/.env
  sed -i "s/MAIL_FROM_NAME=.*/MAIL_FROM_NAME=$MAIL_FROM_NAME/g" /var/www/html/app/.env
  sed -i "s/GB_CHANGEE=.*/GB_CHANGE=$GB_CHANGE/g" /var/www/html/app/.env
  sudo systemctl stop apache2
  sudo systemctl disable apache2
  sudo apt-get remove apache2 -y
  sudo apt autoremove -y
  cp /var/www/index.php /var/www/html/example/
  clear
}
endINSTALL() {
  echo -e "************ XPanel ************ \n"
  echo -e "XPanel Link : $protcohttp://${defdomain}:$sshttp/login"
  echo -e "Username : ${adminusername}"
  echo -e "Password : ${adminpassword}"
  echo -e "-------- Connection Details ----------- \n"
  echo -e "IP : $ipv4 "
  echo -e "SSH port : ${port} "
  echo -e "SSH + TLS port : ${sshtls_port} \n"
  echo -e "************ Check Install Packag and Moudels ************ \n"
}
check_install() {
  if dpkg-query -W -f='${Status}' "$1" 2>/dev/null | grep -q "install ok installed"; then
    echo -e "$1 \e[34m is installed \e[0m"
  else
    if which $1 &>/dev/null; then
      echo -e "$1 \e[34m is installed \e[0m"
    else
      echo -e "$1 \e[31m is not installed \e[0m"
    fi
  fi
}

checkOS
configSSH
setCONFIG
wellcomeINSTALL
userINPU
startINSTALL
checkDATABASE
moreCONFIG
endINSTALL
# Check and display status for each package
check_install software-properties-common
check_install stunnel4
check_install cmake
check_install screenfetch
check_install openssl
check_install nginx
check_install zip
check_install unzip
check_install net-tools
check_install curl
check_install mariadb-server
check_install php
check_install npm
check_install coreutils
check_install php8.1
check_install php8.1-mysql
check_install php8.1-xml
check_install php8.1-curl
check_install cron
check_install nethogs
