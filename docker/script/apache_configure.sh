#!/bin/bash
a2dissite 000-default
rm -rf /etc/apache2/sites-available/*
cp /data/vhosts/mini_erp.conf /etc/apache2/sites-available
a2ensite mini_erp
a2enmod rewrite
service apache2 reload
service apache2 stop
service apache2 start
