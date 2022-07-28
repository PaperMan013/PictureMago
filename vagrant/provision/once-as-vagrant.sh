#!/usr/bin/env bash

source /var/www/vagrant/provision/common.sh

#== Provision script ==

info "Provision-script user: `whoami`"

info "Installing PHP dependencies"
cd /var/www
composer --no-progress --prefer-dist install > /dev/null
echo "Done!"
