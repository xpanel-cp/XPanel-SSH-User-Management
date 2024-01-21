wget -4 https://downloads.ioncube.com/loader_downloads/ioncube_loaders_lin_x86-64.tar.gz
sudo tar xzf ioncube_loaders_lin_x86-64.tar.gz -C /usr/local
sudo rm -rf ioncube_loaders_lin_x86-64.tar.gz

PHPVERSION=$(php -i | grep /.+/php.ini -oE | sed 's/[^0-9.]*//g')

echo "zend_extension = /usr/local/ioncube/ioncube_loader_lin_${PHPVERSION}.so" > /etc/php/${PHPVERSION::-1}/fpm/conf.d/00-ioncube.ini
echo "zend_extension = /usr/local/ioncube/ioncube_loader_lin_${PHPVERSION}.so" > /etc/php/${PHPVERSION::-1}/cli/conf.d/00-ioncube.ini

systemctl restart nginx
