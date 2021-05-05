FROM php:8-apache

MAINTAINER p-Clerick
RUN apt-get update && apt-get install -y --fix-missing \
    apt-utils \
    gnupg

RUN echo "deb http://packages.dotdeb.org jessie all" >> /etc/apt/sources.list
RUN echo "deb-src http://packages.dotdeb.org jessie all" >> /etc/apt/sources.list
RUN curl -sS --insecure https://www.dotdeb.org/dotdeb.gpg | apt-key add -

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    mc \
    git \
    unzip \
    wget \
    libpng-dev \
    libpq-dev \
    cron

RUN docker-php-ext-install  zip sockets gd pcntl pdo pdo_pgsql pgsql

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mkdir -p /usr/local/bin
RUN mv composer.phar /usr/local/bin/composer

RUN systemctl enable cron

RUN a2enmod rewrite
RUN service apache2 restart

RUN groupadd --gid 1001 dev \
  && useradd --uid 1000 --gid dev --shell /bin/bash --create-home dev
RUN usermod -aG www-data dev
RUN echo "export TERM=xterm-256color" > /home/dev/.profile
RUN chown -R dev:www-data /home/dev
RUN chmod -R 775 /home/dev

USER dev





