#!/bin/bash
CURRENT_DIR=$(pwd)
MYSQL_PASSWD="erp"
mysql -u root -p$MYSQL_PASSWD -h mini_erp_database -e "" 
mysql -u root -p$MYSQL_PASSWD -h mini_erp_database < $CURRENT_DIR/mini_erp.sql
mysql -u root -p$MYSQL_PASSWD -h mini_erp_database < $CURRENT_DIR/mini_erp_credentials.sql
