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

php artisan cache:clear
php artisan config:cache
php artisan config:clear

/usr/bin/supervisord