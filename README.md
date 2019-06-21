# alder
Alder CMS

## Installing

1. ```composer require webcosmonauts/alder```
2. remove standart laravel migrations (users and password resets)
3. switch Auth user model on ```\Webcosmonauts\Alder\Models\User::class``` in ```config/auth```
4. run ```php artisan migrate:fresh --seed```
5. add ```Alder::routes()``` to standart laravel routes
