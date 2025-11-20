#!/bin/bash

cd /app

if [ -f .env ]; then
    export $(cat .env | grep -v '#' | awk '/=/ {print $1}')
fi

echo 'artisan migrate --force'
php artisan migrate --force
echo 'artisan db:seed'
php artisan db:seed
echo 'artisan storage:link'
php artisan storage:link
#echo 'artisan config:cache'
#php artisan config:cache
#echo 'artisan route:cache'
#php artisan route:cache
#echo 'artisan optimize'
#php artisan optimize
echo 'dumpautoload --optimize'
composer dumpautoload --optimize
echo "chown -R application:application storage"
chown -R application:application storage/logs/
echo "chown DONE"
