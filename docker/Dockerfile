FROM ubuntu:focal

RUN mkdir /data
COPY ./script /data/script
COPY ./vhosts /data/vhosts
COPY ./database /data/database

RUN apt-get update

RUN apt-get update && DEBIAN_FRONTEND=noninteractive && apt-get install -y --no-install-recommends tzdata

RUN apt-get install -y vim apache2 libapache2-mod-php net-tools sudo gettext curl git libpcre3-dev mariadb-client-10.3 libcurl4-openssl-dev libzip-dev libicu-dev libxslt1-dev libxml++2.6-dev libpng-dev libjpeg-dev

RUN apt-get install -y php7.4 php7.4-cli php7.4-bcmath php7.4-mbstring php7.4-curl php7.4-json php7.4-intl php7.4-common php7.4-dom php7.4-mysqlnd php-psr 

RUN /data/script/composer_install.sh
RUN /data/script/phalcon_install.sh

# configure vhost
RUN /data/script/apache_configure.sh
