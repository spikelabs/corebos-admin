#!/usr/bin/env bash

echo "APP_ENV=$APP_ENV" >> .env
echo "DB_HOST=$DB_HOST" >> .env
echo "DB_PORT=$DB_PORT" >> .env
echo "DB_DATABASE=$DB_DATABASE" >> .env
echo "DB_USERNAME=$DB_USERNAME" >> .env
echo "DB_PASSWORD=$DB_PASSWORD" >> .env
echo "REDIS_HOST=$REDIS_HOST" >> .env
echo "GRPC_CLIENT=$GRPC_CLIENT" >> .env
echo "DIGITAL_OCEAN_TOKEN=$DIGITAL_OCEAN_TOKEN" >> .env
echo "API_TOKEN=$API_TOKEN" >> .env

chown -R www-data:www-data /var/www/html/storage/app
chgrp -R www-data /var/www/html/storage/app
chmod -R ug+rwx /var/www/html/storage/app
chmod -R 0777 /var/www/html/storage/app

php artisan cache:clear
php artisan config:cache
php artisan config:clear

/usr/bin/supervisord