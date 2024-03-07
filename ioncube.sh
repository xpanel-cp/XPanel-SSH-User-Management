uname=$(uname -i)
if [[ $uname == x86_64 ]]; then
wget -4 https://downloads.ioncube.com/loader_downloads/ioncube_loaders_lin_x86-64.tar.gz
sudo tar xzf ioncube_loaders_lin_x86-64.tar.gz -C /usr/local
sudo rm -rf ioncube_loaders_lin_x86-64.tar.gz
fi
if [[ $uname == aarch64 ]]; then
wget -4 https://downloads.ioncube.com/loader_downloads/ioncube_loaders_lin_aarch64.tar.gz
sudo tar xzf ioncube_loaders_lin_aarch64.tar.gz -C /usr/local
sudo rm -rf ioncube_loaders_lin_aarch64.tar.gz
fi
PHPVERSION=$(php -i | grep /.+/php.ini -oE | sed 's/[^0-9.]*//g')

echo "zend_extension = /usr/local/ioncube/ioncube_loader_lin_${PHPVERSION}.so" > /etc/php/${PHPVERSION::-1}/fpm/conf.d/00-ioncube.ini
echo "zend_extension = /usr/local/ioncube/ioncube_loader_lin_${PHPVERSION}.so" > /etc/php/${PHPVERSION::-1}/cli/conf.d/00-ioncube.ini

PHP_INI_PATH="/etc/php/8.1/fpm/php.ini"
ZEND_EXTENSION_PATH="/usr/local/ioncube/ioncube_loader_lin_8.1.so"
grep -q "^zend_extension" $PHP_INI_PATH && sed -i "s@^zend_extension.*@zend_extension = $ZEND_EXTENSION_PATH@" $PHP_INI_PATH || echo "zend_extension = $ZEND_EXTENSION_PATH" >> $PHP_INI_PATH
sudo systemctl restart php8.1-fpm
systemctl restart nginx
