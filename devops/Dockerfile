FROM php:8.2-fpm-alpine

# && echo "log_level = debug" >> /usr/local/etc/php-fpm.conf \
#echo "chdir = /public/" >> /usr/local/etc/php-fpm.d/www.conf \
# && echo "chroot = /app/" >> /usr/local/etc/php-fpm.d/www.conf \
#echo "chroot = /app/" >> /usr/local/etc/php-fpm.d/www.conf \


RUN apk add git bash \
    nano net-tools mc \
 && docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN ln -s /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

ADD devops/docker-entrypoint.sh /docker-entrypoint.sh

COPY --chown=www-data:www-data . /app/

WORKDIR /app/

RUN ln -s .env.example .env \
 && composer install --optimize-autoloader \
 && php artisan key:generate \
 && php artisan storage:link

EXPOSE 9000

CMD ["docker-entrypoint.sh"]
