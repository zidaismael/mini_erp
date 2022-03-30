#!/bin/bash
/data/script/make_aliases.sh

#install 3rd PHP librairies
cd /data/www/mini_erp
composer update

#install 3rd JS librairies
#npm install

#apache2 start
service apache2 restart