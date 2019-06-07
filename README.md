# alder
Alder CMS

## Installing

1. ```composer require webcosmonauts/alder```
2. remove standart laravel migrations (users and password resets)
3. run ```php artisan migrate:fresh --seed```
4. add ```Alder::routes()``` to standart laravel routes
5. add to ```config/app.php``` in section 'aliases' following code:

  ```'AlderLeaf' => Webcosmonauts\Alder\Http\Controllers\LeavesController\LeafEntityController::class ```
