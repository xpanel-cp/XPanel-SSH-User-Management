#!/bin/bash
crontab -r
xpo=$(cat /var/www/xpanelport | grep "^Xpanelport")
xpd=$(cat /var/www/xpanelport | grep "^DomainPanel")
xport=$(echo "$xpo" | sed "s/Xpanelport //g")
portssl=$((xport+1))
xpdomain=$(echo "$xpd" | sed "s/DomainPanel //g")
read -rp "Please input IP Server: " ip
read -rp "Please enter the pointed domain / sub-domain name: " domain

systemctl stop apache2

RED="\033[31m"
GREEN="\033[32m"
YELLOW="\033[33m"
PLAIN='\033[0m'

red(){
    echo -e "\033[31m\033[01m$1\033[0m"
}

green(){
    echo -e "\033[32m\033[01m$1\033[0m"
}

yellow(){
    echo -e "\033[33m\033[01m$1\033[0m"
}

REGEX=("debian" "ubuntu" "centos|red hat|kernel|oracle linux|alma|rocky" "'amazon linux'" "fedora")
RELEASE=("Debian" "Ubuntu" "CentOS" "CentOS" "Fedora")
PACKAGE_UPDATE=("apt-get update" "apt-get update" "yum -y update" "yum -y update" "yum -y update")
PACKAGE_INSTALL=("apt -y install" "apt -y install" "yum -y install" "yum -y install" "yum -y install")
PACKAGE_REMOVE=("apt -y remove" "apt -y remove" "yum -y remove" "yum -y remove" "yum -y remove")
PACKAGE_UNINSTALL=("apt -y autoremove" "apt -y autoremove" "yum -y autoremove" "yum -y autoremove" "yum -y autoremove")

[[ $EUID -ne 0 ]] && red "Note: Please run the script as the root user" && exit 1

CMD=("$(grep -i pretty_name /etc/os-release 2>/dev/null | cut -d \" -f2)" "$(hostnamectl 2>/dev/null | grep -i system | cut -d : -f2)" "$(lsb_release -sd 2>/dev/null)" "$(grep -i description /etc/lsb-release 2>/dev/null | cut -d \" -f2)" "$(grep . /etc/redhat-release 2>/dev/null)" "$(grep . /etc/issue 2>/dev/null | cut -d \\ -f1 | sed '/^[ ]*$/d')")

for i in "${CMD[@]}"; do
    SYS="$i"
    if [[ -n $SYS ]]; then
        break
    fi
done

for ((int = 0; int < ${#REGEX[@]}; int++)); do
    if [[ $(echo "$SYS" | tr '[:upper:]' '[:lower:]') =~ ${REGEX[int]} ]]; then
        SYSTEM="${RELEASE[int]}"
        if [[ -n $SYSTEM ]]; then
            break
        fi
    fi
done

[[ -z $SYSTEM ]] && red "Does not support the current OS, please use a supported one" && exit 1



    if [[ ! $SYSTEM == "CentOS" ]]; then
        ${PACKAGE_UPDATE[int]}
    fi
    ${PACKAGE_INSTALL[int]} curl wget sudo socat
    if [[ $SYSTEM == "CentOS" ]]; then
        ${PACKAGE_INSTALL[int]} cronie
        systemctl start crond
        systemctl enable crond
    else
        ${PACKAGE_INSTALL[int]} cron
        systemctl start cron
        systemctl enable cron
    fi

    
        autoEmail=$(date +%s%N | md5sum | cut -c 1-16)
        acmeEmail=$autoEmail@gmail.com
        yellow "Skipped entering email, using a fake email address: $acmeEmail"
    
    curl https://get.acme.sh | sh -s email=$acmeEmail
    source ~/.bashrc
    bash ~/.acme.sh/acme.sh --upgrade --auto-upgrade
    bash ~/.acme.sh/acme.sh --set-default-ca --server letsencrypt
    if [[ -n $(~/.acme.sh/acme.sh -v 2>/dev/null) ]]; then
        green "ACME.SH certificate application script installed successfully!"
    else
        red "Sorry, the ACME.SH certificate application script installation failed"
        green "Suggestions:"
        yellow "Check the server network connection"
        
    fi
   

        if [[ -z $(type -P lsof) ]]; then
        if [[ ! $SYSTEM == "CentOS" ]]; then
            ${PACKAGE_UPDATE[int]}
        fi
        ${PACKAGE_INSTALL[int]} lsof
    fi
    
    yellow "Checking if the port 80 is in use..."
    sleep 1
    
    if [[  $(lsof -i:"80" | grep -i -c "listen") -eq 0 ]]; then
        green "Good! Port 80 is not in use"
        sleep 1
    else
        red "Port 80 is currently in use, please close the service this service, which is using port 80:"
        lsof -i:"80"
            lsof -i:"80" | awk '{print $2}' | grep -v "PID" | xargs kill -9
            sleep 1
        
    fi


    [[ -z $(~/.acme.sh/acme.sh -v 2>/dev/null) ]] && red "Unpacking ACME.SH, Getting ready..." && exit 1
  
    WARPv4Status=$(curl -s4m8 https://www.cloudflare.com/cdn-cgi/trace -k | grep warp | cut -d= -f2)
    WARPv6Status=$(curl -s6m8 https://www.cloudflare.com/cdn-cgi/trace -k | grep warp | cut -d= -f2)
    if [[ $WARPv4Status =~ on|plus ]] || [[ $WARPv6Status =~ on|plus ]]; then
        wg-quick down wgcf >/dev/null 2>&1
    fi
    ipv4=$ip
    ipv6=''
    
    echo ""
    yellow "When using port 80 application mode, first point your domain name to your server's public IP address. Otherwise the certificate application will be failed!"
    echo ""
    if [[ -n $ipv4 && -n $ipv6 ]]; then
        echo -e "The public IPv4 address of server is: ${GREEN} $ipv4 ${PLAIN}"
        echo -e "The public IPv6 address of server is: ${GREEN} $ipv6 ${PLAIN}"
    elif [[ -n $ipv4 && -z $ipv6 ]]; then
        echo -e "The public IPv4 address of server is: ${GREEN} $ipv4 ${PLAIN}"
    elif [[ -z $ipv4 && -n $ipv6 ]]; then
        echo -e "The public IPv6 address of server is: ${GREEN} $ipv6 ${PLAIN}"
    fi
    echo ""
    
    [[ -z $domain ]] && red "Given domain is invalid. Please use example.com / sub.example.com" && exit 1
    green "The given domain name：$domain" && sleep 1
    domainIP=$(curl -sm8 ipget.net/?ip="${domain}")
    
    if [[ $domainIP == $ipv6 ]]; then
        bash ~/.acme.sh/acme.sh --issue -d ${domain} --standalone -k ec-256 --listen-v6 --insecure --force
    fi
    if [[ $domainIP == $ipv4 ]]; then
        bash ~/.acme.sh/acme.sh --issue -d ${domain} --standalone -k ec-256 --insecure --force
    fi
    
    if [[ -n $(echo $domainIP | grep nginx) ]]; then
        yellow "The domain name analysis failed, please check whether the domain name is correctly entered, and whether the domain name has been pointed to the server's public IP address"
        exit 1
    elif [[ -n $(echo $domainIP | grep ":") || -n $(echo $domainIP | grep ".") ]]; then
        if [[ $domainIP != $ipv4 ]] && [[ $domainIP != $ipv6 ]]; then
            if [[ -n $(type -P wg-quick) && -n $(type -P wgcf) ]]; then
                wg-quick up wgcf >/dev/null 2>&1
            fi
            green "Domain name ${domain} Currently pointed IP: ($domainIP)"
            red "The current domain name's resolved IP does not match the public IP used of the server"
            green "Suggestions:"
            yellow "1. Please check whether domain is correctly pointed to the server's current public IP"
            yellow "2. Please make sure that Cloudflare Proxy is closed (only DNS)"
            exit 1
        fi
    fi
    



if command -v apt-get >/dev/null; then
mkdir /etc/apache2/ssl/
bash ~/.acme.sh/acme.sh --force --install-cert -d ${domain} --key-file /etc/apache2/ssl/${domain}.key --fullchain-file /etc/apache2/ssl/${domain}.crt --ecc 

cat > /etc/apache2/conf-available/ssl-params.conf << ENDOFFILE
SSLCipherSuite EECDH+AESGCM:EDH+AESGCM
# Requires Apache 2.4.36 & OpenSSL 1.1.1
SSLProtocol -all +TLSv1.3 +TLSv1.2
SSLOpenSSLConfCmd Curves X25519:secp521r1:secp384r1:prime256v1
# Older versions
# SSLProtocol All -SSLv2 -SSLv3 -TLSv1 -TLSv1.1
SSLHonorCipherOrder On
# Disable preloading HSTS for now.  You can use the commented out header line that includes 
# the "preload" directive if you understand the implications.
# Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"
Header always set X-Frame-Options DENY
Header always set X-Content-Type-Options nosniff
# Requires Apache >= 2.4
SSLCompression off
SSLUseStapling on
SSLStaplingCache "shmcb:logs/stapling-cache(150000)"
# Requires Apache >= 2.4.11
SSLSessionTickets Off
ENDOFFILE
sudo cp /etc/apache2/sites-available/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf.bak
echo "<IfModule mod_ssl.c>
<VirtualHost *:443>
                ServerAdmin XPanel@${domain}
                ServerName ${domain}
                DocumentRoot /var/www/html/example
                ErrorLog ${APACHE_LOG_DIR}/error.log
                CustomLog ${APACHE_LOG_DIR}/access.log combined
                SSLEngine on
                SSLCertificateFile      /etc/apache2/ssl/${domain}.crt
                SSLCertificateKeyFile /etc/apache2/ssl/${domain}.key
                <FilesMatch '\.(cgi|shtml|phtml|php)$'>
                                SSLOptions +StdEnvVars
                </FilesMatch>
                <Directory /usr/lib/cgi-bin>
                                SSLOptions +StdEnvVars
                </Directory>
                
                <Directory "/var/www/html/example">
                AllowOverride All
                </Directory>
        </VirtualHost>
        
        <VirtualHost *:$portssl>
                ServerAdmin XPanel@${domain}
                ServerName ${domain}
                DocumentRoot /var/www/html/cp
                ErrorLog ${APACHE_LOG_DIR}/error.log
                CustomLog ${APACHE_LOG_DIR}/access.log combined
                SSLEngine on
                SSLCertificateFile      /etc/apache2/ssl/${domain}.crt
                SSLCertificateKeyFile /etc/apache2/ssl/${domain}.key
                <FilesMatch '\.(cgi|shtml|phtml|php)$'>
                                SSLOptions +StdEnvVars
                </FilesMatch>
                <Directory /usr/lib/cgi-bin>
                                SSLOptions +StdEnvVars
                </Directory>
                
                <Directory "/var/www/html/cp">
                AllowOverride All
                </Directory>
        </VirtualHost>
</IfModule>" > /etc/apache2/sites-available/default-ssl.conf
sudo a2enmod ssl
sudo a2enmod headers
sudo a2ensite default-ssl
sudo a2enconf ssl-params
sudo apache2ctl configtest
sudo systemctl restart apache2

elif command -v yum >/dev/null; then
mkdir /etc/ssl/
bash ~/.acme.sh/acme.sh --install-cert -d ${domain} --key-file /etc/ssl/${domain}.key --fullchain-file /etc/ssl/${domain}.crt --ecc 

cat > /etc/httpd/conf.d/${domain}.conf << ENDOFFILE
<VirtualHost *:443>
   ServerName ${domain}
   DocumentRoot /var/www/html/example
   SSLEngine on
   SSLCertificateFile /etc/ssl/${domain}.crt
   SSLCertificateKeyFile /etc/ssl/${domain}.key
   <Directory "/var/www/html/example">
   AllowOverride All
   </Directory>
</VirtualHost>

<VirtualHost *:$portssl>
   ServerName ${domain}
   DocumentRoot /var/www/html/cp
   SSLEngine on
   SSLCertificateFile /etc/ssl/${domain}.crt
   SSLCertificateKeyFile /etc/ssl/${domain}.key
   <Directory "/var/www/html/cp">
   AllowOverride All
   </Directory>
</VirtualHost>
SSLCipherSuite EECDH+AESGCM:EDH+AESGCM
# Requires Apache 2.4.36 & OpenSSL 1.1.1
SSLProtocol -all +TLSv1.2
# SSLOpenSSLConfCmd Curves X25519:secp521r1:secp384r1:prime256v1
# Older versions
# SSLProtocol All -SSLv2 -SSLv3 -TLSv1 -TLSv1.1
SSLHonorCipherOrder On
# Disable preloading HSTS for now.  You can use the commented out header line that includes
# the "preload" directive if you understand the implications.
#Header always set Strict-Transport-Security "max-age=63072000; includeSubdomains; preload"
Header always set Strict-Transport-Security "max-age=63072000; includeSubdomains"
# Requires Apache >= 2.4
SSLCompression off
SSLUseStapling on
SSLStaplingCache "shmcb:logs/stapling-cache(150000)"
# Requires Apache >= 2.4.11
# SSLSessionTickets Off
ENDOFFILE
sudo apachectl configtest
systemctl restart httpd
wait
sudo systemctl restart apache2
wait
sudo service apache2 restart
fi

if [ "$xpdomain" != "" ]; then
sed -i 's/$xpdomain/$domain/' /var/www/xpanelport
else
sudo sed -i -e '$a\'$'\n''DomainPanel '$domain /var/www/xpanelport
sudo sed -i -e '$a\'$'\n''SSLPanel True' /var/www/xpanelport
fi
wait
multiin=$(echo "https://${domain}:$portssl/fixer/multiuser")
cat > /var/www/html/kill.sh << ENDOFFILE
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

(crontab -l | grep . ; echo -e "* * * * * /var/www/html/kill.sh") | crontab -
(crontab -l ; echo "* * * * * wget -q -O /dev/null 'https://${domain}:$portssl/fixer/exp' > /dev/null 2>&1") | crontab -
(crontab -l | grep . ; echo -e "* * * * * /var/www/html/dropbear.sh") | crontab -
(crontab -l | grep . ; echo -e "30 4 * * * systemctl restart dropbear.service") | crontab -
clear
printf "\nHTTPS Address : https://${domain}:$portssl/login \n"
