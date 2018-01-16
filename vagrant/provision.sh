#!/usr/bin/env bash
set -o errexit
set -o nounset

# This strategy from https://www.tecmint.com/install-different-php-versions-in-ubuntu/
apt-get install --yes python-software-properties
add-apt-repository --yes ppa:ondrej/php
apt-get update || true

apt-get install --yes php5.6
apt-get install --yes php5.6-curl php5.6-xml php5.6-mbstring php5.6-mysql
apt-get install --yes php7.0
apt-get install --yes php7.0-curl php7.0-xml php7.0-mbstring php7.0-mysql
apt-get install --yes php7.1
apt-get install --yes php7.1-curl php7.1-xml php7.1-mbstring php7.1-mysql
apt-get install --yes php7.2
apt-get install --yes php7.2-curl php7.2-xml php7.2-mbstring php7.2-mysql

# Switch to latest PHP version.
update-alternatives --set php /usr/bin/php7.2

composer self-update
(cd /var/www && composer install --ignore-platform-reqs --no-progress --no-interaction)
