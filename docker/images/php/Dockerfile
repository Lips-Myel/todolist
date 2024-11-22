# Utiliser une image officielle PHP avec Composer
FROM php:8.3-fpm-alpine

# Variables d'environnement pour PHP
ENV PHP_MEMORY_LIMIT=1G
ENV PHP_UPLOAD_MAX_FILESIZE=1G
ENV PHP_POST_MAX_SIZE=1G

# Installer les dépendances système
RUN apk --no-cache add bash git libpq icu-dev $PHPIZE_DEPS autoconf linux-headers

# Configurer PHP et installer les extensions
RUN docker-php-ext-install pdo pdo_mysql intl opcache

# Installer APCu
RUN pecl install apcu && docker-php-ext-enable apcu

# Installer Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Configuration PHP personnalisée
RUN echo "short_open_tag=off" > /usr/local/etc/php/conf.d/custom.ini
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/custom.ini
RUN echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/custom.ini
RUN echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/custom.ini
RUN echo "opcache.max_accelerated_files=4000" >> /usr/local/etc/php/conf.d/custom.ini
RUN echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/custom.ini
RUN echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/custom.ini

# Configuration Xdebug pour le développement
RUN echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.log_level=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier le projet dans le conteneur
COPY . .

# Fixer les permissions
RUN chown -R www-data:www-data /var/www

# Installer les dépendances PHP
RUN composer install || true

EXPOSE 9000

# Commande pour lancer PHPI
CMD ["php-fpm"]