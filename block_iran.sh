#!/bin/sh
#Alireza & Roozemain

sudo apt-get update -y
sudo apt-get -y upgrade
sudo apt-get install curl unzip perl xtables-addons-common libtext-csv-xs-perl libmoosex-types-netaddr-ip-perl iptables-persistent -y 
sudo mkdir /usr/share/xt_geoip
chmod 755 /usr/lib/xtables-addons/xt_geoip_build
MON=$(date +"%m")
YR=$(date +"%Y")
wget https://download.db-ip.com/free/dbip-country-lite-${YR}-${MON}.csv.gz -O /usr/share/xt_geoip/dbip-country-lite.csv.gz
gunzip /usr/share/xt_geoip/dbip-country-lite.csv.gz
/usr/lib/xtables-addons/xt_geoip_build -D /usr/share/xt_geoip/ -S /usr/share/xt_geoip/
rm /usr/share/xt_geoip/dbip-country-lite.csv

echo -e "\n Download Success GEOIP Library  \n"
