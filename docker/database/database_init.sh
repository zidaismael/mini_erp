#!/bin/bash
SCRIPT_BASE_FOLDER="$(dirname "$(readlink -e "$0")")"
MYSQL_PASSWD="erp"
mysql -u root -p$MYSQL_PASSWD -h mini_erp_database -e "" 
mysql -u root -p$MYSQL_PASSWD -h mini_erp_database < $SCRIPT_BASE_FOLDER/mini_erp.sql
mysql -u root -p$MYSQL_PASSWD -h mini_erp_database < $SCRIPT_BASE_FOLDER/mini_erp_data.sql
mysql -u root -p$MYSQL_PASSWD -h mini_erp_database < $SCRIPT_BASE_FOLDER/mini_erp_credentials.sql