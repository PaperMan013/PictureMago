#!/usr/bin/env bash
export DEBIAN_FRONTEND=noninteractive

source /var/www/vagrant/provision/common.sh

#== Import script args ==

timezone=$(echo "$1")

#== Provision script ==
apt-get install -y unzip info > /dev/null

info "Provision-script user: `whoami`"

info "Configuring timezone"
timedatectl set-timezone ${timezone} --no-ask-password

info "Updating OS software"
apt-get update > /dev/null
apt-get upgrade -y > /dev/null
echo "Done!"

info "Installing and setting up Apache server"
apt-get install apache2 -y > /dev/null
a2enmod rewrite
chmod 0777 /etc/apache2 -R
chmod 0777 /var/log -R
rm -r /var/www/html
cp /var/www/vagrant/apache/*.conf /etc/apache2/sites-enabled/
service apache2 restart
echo "Done!"

info "Installing PHP 7.4 and required extensions"
apt-get install -y php php-mbstring php-imagick > /dev/null
echo "Done!"

info "Installing and setting xDebug"
apt-get install -y php-xdebug > /dev/null
cp /var/www/vagrant/xdebug/xdebug.ini /etc/php/7.4/mods-available/
phpenmod xdebug
service apache2 restart
echo "Done!"

info "Installing Composer"
apt-get install -y curl unzip > /dev/null
curl -sS https://getcomposer.org/installer -o composer-setup.php > /dev/null
php composer-setup.php --install-dir=/usr/local/bin --filename=composer > /dev/null
echo "Done!"
