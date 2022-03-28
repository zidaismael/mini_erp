CREATE USER 'mini_erp_user'@'localhost' IDENTIFIED BY 'mini_erp_pass';
CREATE USER 'mini_erp_user'@'mini_erp_database' IDENTIFIED BY 'mini_erp_pass';

GRANT ALL ON mini_erp.* TO 'mini_erp_user'@'localhost';
GRANT ALL ON mini_erp.* TO 'mini_erp_user'@'mini_erp_database';  
FLUSH PRIVILEGES;