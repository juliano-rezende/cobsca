# Download base image ubuntu 22.04
FROM php:7.4-apache

#instalando e habilitando extencoes
RUN apt update && apt install -y libpq-dev libpng-dev libxml2-dev git uuid-runtime libzip-dev libonig-dev
RUN docker-php-ext-install gd xml zip mbstring sockets pdo pdo_mysql exif

RUN apt-get update && apt-get upgrade -y


#definindo workdirå
WORKDIR /var/www/painel

# Instalando o Composer
RUN php -r "copy('http://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer



# Instalando NodeJs
RUN php -r "copy('https://nodejs.org/dist/v14.17.4/node-v14.17.4-linux-x64.tar.xz', '/usr/node-v14.17.4-linux-x64.tar.xz');"
RUN cd /usr && tar -xf node-v14.17.4-linux-x64.tar.xz
RUN cp -rf /usr/node-v14.17.4-linux-x64/bin /usr
RUN cp -rf /usr/node-v14.17.4-linux-x64/lib /usr
RUN cp -rf /usr/node-v14.17.4-linux-x64/share /usr
RUN cp -rf /usr/node-v14.17.4-linux-x64/include /usr


#copiando o arquivo de configuracao pra dentro do container
COPY panel.conf /etc/apache2/sites-enabled
RUN rm /etc/apache2/sites-enabled/000-default.conf

#permissao para escrita de imagens
#RUN chmod 777 -R /var/www/painel/public

#habilitando reescrita de url
RUN a2enmod rewrite

#definindo volume
VOLUME /var/www/painel