# alder
Alder CMS

## Installing

1. ```composer require webcosmonauts/alder```
2. remove standart laravel migrations (users and password resets)
3. run ```php artisan migrate:fresh --seed```
4. add ```Alder::routes()``` to standart laravel routes
