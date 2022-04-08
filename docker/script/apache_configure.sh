#!/bin/bash
a2dissite 000-default
rm -rf /etc/apache2/sites-available/*
cp /data/vhosts/mini_erp.conf /etc/apache2/sites-available
cp /data/vhosts/doc_mini_erp.conf /etc/apache2/sites-available
a2ensite mini_erp
a2ensite doc_mini_erp
a2enmod rewrite
