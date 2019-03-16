#!/usr/bin/env bash

echo "DB_HOST=$DB_HOST" >> .env
echo "DB_PORT=$DB_PORT" >> .env
echo "DB_DATABASE=$DB_DATABASE" >> .env
echo "DB_USERNAME=$DB_USERNAME" >> .env
echo "DB_PASSWORD=$DB_PASSWORD" >> .env
echo "GRPC_CLIENT=$GRPC_CLIENT" >> .env

php artisan cache:clear
php artisan config:cache

/usr/bin/supervisord