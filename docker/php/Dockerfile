FROM php:8.2-fpm-alpine

# Installer les dépendances pour GD et PDO MySQL
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libwebp-dev \
    msmtp

# Configurer et installer l'extension GD et PDO MySQL
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp && \
    docker-php-ext-install pdo pdo_mysql gd

# Configuration de msmtp comme sendmail
COPY msmtprc /etc/msmtprc
RUN echo "sendmail_path = /usr/bin/msmtp -t" > /usr/local/etc/php/conf.d/mail.ini

# Create uploads directories with correct permissions
RUN mkdir -p /var/www/html/uploads/avatars && \
    chown -R www-data:www-data /var/www/html/uploads && \
    chmod -R 755 /var/www/html/uploads

WORKDIR /var/www/html
