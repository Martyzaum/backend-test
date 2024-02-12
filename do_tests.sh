php artisan migrate --force

php artisan db:seed --class=RedirectsSeeder
php artisan db:seed --class=RedirectsLogsSeeder

php artisan cache:clear

# wait 10s
sleep 10

php artisan test

php artisan cache:clear
php artisan migrate:reset
