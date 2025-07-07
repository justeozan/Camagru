FROM php:8.2-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

RUN apk add --no-cache msmtp

COPY msmtprc /etc/msmtprc
RUN echo "sendmail_path = /usr/bin/msmtp -t" > /usr/local/etc/php/conf.d/mail.ini

WORKDIR /var/www/html