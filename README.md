# how to run project
copy .env.example to .env

php artisan migrate

php artisan serve

### to run queue job
php artisan queue:listen