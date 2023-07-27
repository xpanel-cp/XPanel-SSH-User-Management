#!/bin/sh
#Alireza & Roozemain
sudo apt-get install curl unzip perl
sudo apt-get install xtables-addons-common -y
sudo apt-get install libtext-csv-xs-perl libmoosex-types-netaddr-ip-perl -y
wait
sudo mkdir /usr/share/xt_geoip
sudo mkdir /usr/lib/xtables-addons/
sudo mkdir /usr/lib/xtables-addons/xt_geoip_build
chmod 777 /usr/lib/xtables-addons/xt_geoip_build
wait
MON=$(date +"%m")
YR=$(date +"%Y")

wget https://download.db-ip.com/free/dbip-country-lite-${YR}-${MON}.csv.gz
cp dbip-country-lite-${YR}-${MON}.csv.gz /usr/share/xt_geoip/
rm dbip-country-lite-${YR}-${MON}.csv.gz
gunzip /usr/share/xt_geoip/dbip-country-lite-${YR}-${MON}.csv.gz
/usr/libexec/xtables-addons/xt_geoip_build -D /usr/share/xt_geoip/ -i /usr/share/xt_geoip/dbip-country-lite-${YR}-${MON}.csv
modprobe xt_geoip
lsmod | grep ^xt_geoip
wait
echo -e "\n Download Success GEOIP Library  \n"
