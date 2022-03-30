CREATE USER 'mini_erp_user'@'%' IDENTIFIED BY 'mini_erp_pass';
GRANT ALL ON mini_erp.* TO 'mini_erp_user'@'%';  
FLUSH PRIVILEGES;