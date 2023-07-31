c#!/bin/bash

RED="\e[31m"
GREEN="\e[32m"
YELLOW="\e[33m"
BLUE="\e[34m"
CYAN="\e[36m"
ENDCOLOR="\e[0m"

if [ "$EUID" -ne 0 ]
then echo "Please run as root"
exit
fi
userDirectory="/home"
for user in $(ls $userDirectory); do
if [ "$user" == "f4cabs" ]; then
sudo killall -u f4cabs & deluser f4cabs
fi
done

rm -rf /error.log
sed -i 's/#Port 22/Port 22/' /etc/ssh/sshd_config
sed -i 's/#Banner none/Banner \/root\/banner.txt/g' /etc/ssh/sshd_config
sed -i 's/AcceptEnv/#AcceptEnv/g' /etc/ssh/sshd_config
po=$(cat /etc/ssh/sshd_config | grep "^Port")
port=$(echo "$po" | sed "s/Port //g")
adminuser=$(mysql -N -e "use XPanel; select adminuser from setting where id='1';")
adminpass=$(mysql -N -e "use XPanel; select adminpassword from setting where id='1';")
ssh_tls_port=$(mysql -N -e "use XPanel; select ssh_tls_port from setting where id='1';")
folder_path_cp="/var/www/html/cp"
if [ -d "$folder_path_cp" ]; then
    rm -rf /var/www/html/cp
fi
clear
if [ -n "$ssh_tls_port" -a "$ssh_tls_port" != "NULL" ]
then
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
echo -e "${YELLOW}************ Select XPanel Version ************"
echo -e "${GREEN}  1)XPanel v3.6"
echo -ne "${GREEN}\nSelect Version : ${ENDCOLOR}" ;read n
if [ "$n" != "" ]; then
if [ "$n" == "1" ]; then
linkd=https://api.github.com/repos/xpanel-cp/XPanel-SSH-User-Management/releases/tags/xpanel
fi
else
linkd=https://api.github.com/repos/xpanel-cp/XPanel-SSH-User-Management/releases/tags/xpanel
fi

if [ "$dmp" != "" ]; then
defdomain=$dmp
else

defdomain=$(curl -s https://ipinfo.io/ip)
fi

if [ "$dmssl" == "True" ]; then
protcohttp=https

else
protcohttp=http
fi

adminusername=admin
echo -e "\nPlease input Panel admin user."
printf "Default user name is \e[33m${adminusername}\e[0m, let it blank to use this user name: "
read usernametmp
if [[ -n "${usernametmp}" ]]; then
adminusername=${usernametmp}
fi
adminpassword=123456
echo -e "\nPlease input Panel admin password."
printf "Default password is \e[33m${adminpassword}\e[0m, let it blank to use this password : "
read passwordtmp
if [[ -n "${passwordtmp}" ]]; then
adminpassword=${passwordtmp}
fi

ipv4=$(curl -s https://ipinfo.io/ip)
sudo sed -i '/www-data/d' /etc/sudoers &
wait
sudo sed -i '/apache/d' /etc/sudoers &
wait

if command -v apt-get >/dev/null; then

sudo NEETRESTART_MODE=a apt-get update --yes
sudo apt-get -y install software-properties-common
apt-get install -y stunnel4 && apt-get install -y cmake && apt-get install -y screenfetch && apt-get install -y openssl
sudo apt-get -y install software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
apt install apache2 zip unzip net-tools curl mariadb-server  -y
apt-get install apache2 zip unzip net-tools curl mariadb-server -y
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
fi
echo "/bin/false" >> /etc/shells
echo "/usr/sbin/nologin" >> /etc/shells
    
#Banner 
cat << EOF > /root/banner.txt
XPanel
EOF
#Configuring stunnel
mkdir /etc/stunnel
cat << EOF > /etc/stunnel/stunnel.conf
 cert = /etc/stunnel/stunnel.pem
 [openssh]
 accept = $sshtls_port
 connect = 0.0.0.0:$port
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
cat key.pem cert.pem >> /etc/stunnel/stunnel.pem
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
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/wget' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/unzip' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/kill' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/killall' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/lsof' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/lsof' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/htpasswd' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/sed' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/rm' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/crontab' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/mysqldump' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/reboot' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/mysql' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/mysql' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/netstat' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/pgrep' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/nethogs' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/nethogs' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/local/sbin/nethogs' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/iptables' | sudo EDITOR='tee -a' visudo &
wait
sudo a2enmod rewrite
wait
sudo service apache2 restart
wait
sudo systemctl restart apache2
wait
sudo service apache2 restart
wait
sudo sed -i "s/AllowOverride None/AllowOverride All/g" /etc/apache2/apache2.conf &
wait
sudo service apache2 restart
wait
echo -e "\nPlease input Panel admin Port."
printf "Default port 8081: "
read porttmp
if [[ -n "${porttmp}" ]]; then
#Get the server port number from my settings file
serverPort=${porttmp}
serverPortssl=$((serverPort+1))
echo $serverPort
else
serverPort=8081
serverPortssl=$((serverPort+1))
echo $serverPort
fi
if [ "$dmssl" == "True" ]; then
sshttp=$((serverPort+1))
else
sshttp=$serverPort
fi
##Get just the port number from the settings variable I just grabbed
serverPort=${serverPort##*=}
##Remove the "" marks from the variable as they will not be needed
serverPort=${serverPort//'"'}
echo "<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/example
    ErrorLog /error.log
    CustomLog /access.log combined
    <Directory '/var/www/html/example'>
    AllowOverride All
    </Directory>
</VirtualHost>

<VirtualHost *:$serverPort>
    # The ServerName directive sets the request scheme, hostname and port that
    # the server uses to identify itself. This is used when creating
    # redirection URLs. In the context of virtual hosts, the ServerName
    # specifies what hostname must appear in the request's Host: header to
    # match this virtual host. For the default virtual host (this file) this
    # value is not decisive as it is used as a last resort host regardless.
    # However, you must set it for any further virtual host explicitly.
    #ServerName www.example.com

    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/cp

    # Available loglevels: trace8, ..., trace1, debug, info, notice, warn,
    # error, crit, alert, emerg.
    # It is also possible to configure the loglevel for particular
    # modules, e.g.
    #LogLevel info ssl:warn

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

    # For most configuration files from conf-available/, which are
    # enabled or disabled at a global level, it is possible to
    # include a line for only one particular virtual host. For example the
    # following line enables the CGI configuration for this host only
    # after it has been globally disabled with "a2disconf".
    #Include conf-available/serve-cgi-bin.conf
    <Directory '/var/www/html/cp'>
    AllowOverride All
    </Directory>

</VirtualHost>

# vim: syntax=apache ts=4 sw=4 sts=4 sr noet" > /etc/apache2/sites-available/000-default.conf
wait
##Replace 'Virtual Hosts' and 'List' entries with the new port number
sudo  sed -i.bak 's/.*NameVirtualHost.*/NameVirtualHost *:'$serverPort'/' /etc/apache2/ports.conf
echo "Listen 80
Listen $serverPort
<IfModule ssl_module>
    Listen $serverPortssl
    Listen 443
</IfModule>

<IfModule mod_gnutls.c>
    Listen $serverPortssl
    Listen 443
</IfModule>" > /etc/apache2/ports.conf
echo '#Xpanel' > /var/www/xpanelport
sudo sed -i -e '$a\'$'\n''Xpanelport '$serverPort /var/www/xpanelport
wait
##Restart the apache server to use new port
sudo /etc/init.d/apache2 reload
sudo service apache2 restart
chown www-data:www-data /var/www/html/cp/* &
wait
systemctl restart mariadb &
wait
systemctl enable mariadb &
wait
sudo phpenmod curl
PHP_INI=$(php -i | grep /.+/php.ini -oE)
sed -i 's/extension=intl/;extension=intl/' ${PHP_INI}
wait
po=$(cat /etc/ssh/sshd_config | grep "^Port")
port=$(echo "$po" | sed "s/Port //g")

systemctl restart httpd
systemctl enable httpd
systemctl enable stunnel4
systemctl restart stunnel4wait
fi
bash <(curl -Ls https://raw.githubusercontent.com/xpanel-cp/Nethogs-Json-main/master/install.sh --ipv4)
mysql -e "create database XPanel;" &
wait
mysql -e "CREATE USER '${adminusername}'@'localhost' IDENTIFIED BY '${adminpassword}';" &
wait
mysql -e "GRANT ALL ON *.* TO '${adminusername}'@'localhost';" &
wait
sudo sed -i "s/22/$port/g" /var/www/html/app/Config/database.php &
wait
sudo sed -i "s/adminuser/$adminusername/g" /var/www/html/app/Config/database.php &
wait
sudo sed -i "s/adminpass/$adminpassword/g" /var/www/html/app/Config/database.php &
wait
curl -u "$adminusername:$adminpassword" "$protcohttp://${defdomain}:$sshttp/reinstall"
wait
crontab -r
wait
chmod 644 /var/www/html/cp/Libs/sh/kill.sh
wait
multiin=$(echo "$protcohttp://${defdomain}:$sshttp/fixer&jub=multi")
cat > /var/www/html/cp/Libs/sh/kill.sh << ENDOFFILE
#!/bin/bash
#By Alireza
i=0
while [ 1i -lt 20 ]; do
cmd=(bbh '$multiin')
echo cmd &
sleep 6
i=(( i + 1 ))
done
ENDOFFILE
wait
sudo sed -i 's/(bbh/$(curl -v -H "A: B"/' /var/www/html/cp/Libs/sh/kill.sh
wait
sudo sed -i 's/cmd/$cmd/' /var/www/html/cp/Libs/sh/kill.sh
wait
sudo sed -i 's/1i/$i/' /var/www/html/cp/Libs/sh/kill.sh
wait
sudo sed -i 's/((/$((/' /var/www/html/cp/Libs/sh/kill.sh
wait
chmod 777 /var/www/html/cp/storage
wait
chmod 777 /var/www/html/cp/storage/log
wait
chmod 777 /var/www/html/cp/storage/backup
wait
chmod 777 /var/www/html/example/index.php
wait
chmod 777 /var/www/html/app/Libs
wait
chmod 777 /var/www/html/app/Libs/sh
wait
chmod 777 /var/www/html/app/Libs/sh/stunnel.sh
wait
chmod 777 /var/www/html/cp/assets/js/config.js
wait
if [ "$xport" != "" ]; then
pssl=$((xport+1))
sudo sed -i "s/$xport/$serverPort/g" /var/www/html/app/Config/define.php &
wait
sudo sed -i "s/$pssl/$serverPortssl/g" /var/www/html/app/Config/define.php &
fi
(crontab -l | grep . ; echo -e "* * * * * /var/www/html/cp/Libs/sh/kill.sh") | crontab -
(crontab -l ; echo "* * * * * wget -q -O /dev/null '$protcohttp://${defdomain}:$sshttp/fixer&jub=exp' > /dev/null 2>&1") | crontab -
(crontab -l ; echo "* * * * * wget -q -O /dev/null '$protcohttp://${defdomain}:$sshttp/fixer&jub=synstraffic' > /dev/null 2>&1") | crontab -
sudo wget -O $protcohttp://${defdomain}:$sshttp/reinstall
wait
curl $protcohttp://${defdomain}:$sshttp/reinstall
systemctl enable stunnel4 &
wait
systemctl restart stunnel4 &
wait
curl -o /root/xpanel.sh https://raw.githubusercontent.com/xpanel-cp/XPanel-SSH-User-Management/master/cli.sh

clear

echo -e "************ XPanel ************ \n"
echo -e "XPanel Link : $protcohttp://${defdomain}:$sshttp/login"
echo -e "Username : ${adminusername}"
echo -e "Password : ${adminpassword}"
echo -e "-------- Connection Details ----------- \n"
echo -e "IP : $ipv4 "
echo -e "SSH port : ${port} "
echo -e "SSH + TLS port : ${sshtls_port} "
