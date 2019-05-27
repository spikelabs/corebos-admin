FROM ubuntu:16.04

RUN apt-get update && apt-get install -y nginx locales && locale-gen en_US.UTF-8

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

RUN apt-get update && apt-get install -y curl supervisor zip unzip git software-properties-common zlib1g-dev cron

RUN add-apt-repository -y ppa:ondrej/php

RUN apt-get update && apt-get install -y php7.1 php7.1-dev php7.1-cli php7.1-common php7.1-json php7.1-opcache php7.1-mysql php7.1-mbstring \
       php7.1-mcrypt php7.1-fpm php7.1-xml php7.1-curl composer php-pear phpunit \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN pecl install grpc
RUN pecl install protobuf

RUN echo "daemon off;" >> /etc/nginx/nginx.conf

RUN sed -i -e 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/g' /etc/php/7.1/fpm/php.ini
RUN echo "extension=grpc.so" >> /etc/php/7.1/fpm/php.ini
RUN echo "extension=protobuf.so" >> /etc/php/7.1/fpm/php.ini
RUN echo "extension=grpc.so" >> /etc/php/7.1/cli/php.ini
RUN echo "extension=protobuf.so" >> /etc/php/7.1/cli/php.ini

RUN sed -i "s|upload_max_filesize\s*=\s*2M|upload_max_filesize = 100M|g" /etc/php/7.1/fpm/php.ini

RUN composer global require hirak/prestissimo
RUN mkdir -p /tmp/composer /run/php

WORKDIR /tmp/composer
COPY composer.json ./
RUN composer install --no-scripts --no-autoloader

WORKDIR /var/www/html

RUN cp -a /tmp/composer/* ./

RUN rm -Rf /tmp/composer

COPY . ./
COPY ./deployment/deployment-env ./.env

RUN rm -Rf deployment

COPY deployment/nginx/default /etc/nginx/sites-available/

COPY deployment/php-fpm.conf /etc/php/7.1/fpm/php-fpm.conf

RUN composer install

RUN touch storage/logs/cron.log
RUN touch storage/logs/queue.log

RUN chown -R www-data:www-data /var/www/html /run/php
RUN chgrp -R www-data storage bootstrap/cache
RUN chmod -R ug+rwx storage bootstrap/cache
RUN chmod -R 0777 ./storage

COPY deployment/laravel-cron /etc/cron.d/laravel-cron
RUN chmod 0644 /etc/cron.d/laravel-cron
RUN crontab /etc/cron.d/laravel-cron

COPY deployment/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN php artisan cache:clear

COPY deployment/run.sh .
RUN chmod +x run.sh

CMD ["./run.sh"]

EXPOSE 80
