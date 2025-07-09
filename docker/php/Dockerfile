FROM php:8.2-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

RUN apk add --no-cache msmtp

COPY msmtprc /etc/msmtprc
RUN echo "sendmail_path = /usr/bin/msmtp -t" > /usr/local/etc/php/conf.d/mail.ini

# Create uploads directories with correct permissions
# RUN mkdir -p /var/www/html/uploads/avatars && \
#     chown -R www-data:www-data /var/www/html/uploads && \
#     chmod -R 755 /var/www/html/uploads

WORKDIR /var/www/html