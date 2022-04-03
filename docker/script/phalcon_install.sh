curl -s https://packagecloud.io/install/repositories/phalcon/stable/script.deb.sh | sudo bash

# Workaround because of bug on phalcon 4.1.* with php7.4 and ubuntu focal => must downgrade and use phalcon 4.0.*
@see https://github.com/phalcon/cphalcon/discussions/15259
sed -i 's/focal/bionic/g' /etc/apt/sources.list.d/phalcon_stable.list
sudo apt-get update
sudo apt-get install php7.4-phalcon=4.0.6-975+php7.4;

alias phalcon=/data/www/vendor/phalcon/devtools/phalcon
