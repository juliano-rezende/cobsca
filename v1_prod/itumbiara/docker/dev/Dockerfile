# Download base image php:5.6-apache
FROM php:5.6-apache

# Manually update the sources list to point to the archive
RUN echo "deb http://archive.debian.org/debian stretch main" > /etc/apt/sources.list && \
    echo "deb-src http://archive.debian.org/debian stretch main" >> /etc/apt/sources.list && \
    echo "deb http://archive.debian.org/debian-security stretch/updates main" >> /etc/apt/sources.list && \
    echo "deb-src http://archive.debian.org/debian-security stretch/updates main" >> /etc/apt/sources.list && \
    apt-get update -o Acquire::Check-Valid-Until=false -o Acquire::Check-Date=false

# Installing and enabling extensions
RUN apt-get install -y \
    libpq-dev \
    libpng-dev \
    libxml2-dev \
    git \
    uuid-runtime \
    libzip-dev \
    libonig-dev && \
    docker-php-ext-install gd xml zip mbstring sockets pdo pdo_mysql exif

RUN apt-get upgrade -y

# Define working directory
WORKDIR /var/www/painel

# Copy the Apache configuration file into the container
COPY panel.conf /etc/apache2/sites-enabled
RUN rm /etc/apache2/sites-enabled/000-default.conf

# Enable URL rewriting
RUN a2enmod rewrite

# Define volume
VOLUME /var/www/painel
