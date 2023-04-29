## Laravel authentication via AJAX

This package complements the basic Laravel 9^ authentication classes. It allows you to perform authentication functions using AJAX. Accordingly, there is no need to use separate pages with forms. User interaction takes place through modal windows. Bootstrap and Jquery are used for this purpose. The [Doctrine DBAL](https://github.com/doctrine/dbal) package must also be connected during the development phase. Don't worry, this dependency is defined in the `composer.json` package file and loaded automatically.

Bootstrap and Jquery connect to the project using component files `in-head.blade.php` and `js-connect.blade.php` (vendor\pavel-vasilyev\auth-ajax\src\views\components\layouts).

Tested in Laravel 9 with PHP 8.0. Vite replaced with Webpack.

## Installation

First you need to add (update) a section of the repositories in the `composer.json` file of your project, specifying a link to the package repository:

```shell
composer config repositories.pavel-vasilyev/auth-ajax git https://github.com/pavel-vasilyev/auth-ajax
```
You can now install the package:

```shell
composer require pavel-vasilyev/auth-ajax:dev-master
```

Publish assets (js, css) to the `resources/vendor` directory. Also publish views, components, models, translations `en.json`. The --force flag is required to overwrite `user.php`:

```shell
php artisan vendor:publish --provider="PavelVasilyev\AuthAjax\Providers\PackageServiceProvider" --all --force
```

Use package migration to make changes to the `users` table and create `pages` table:

```shell
php artisan migrate
```

Execute the `db:seed` Artisan command to seed `pages` table:

```shell
php artisan db:seed --class=PageSeeder
```

Define asset compilation commands in `webpack.js`:

```shell
.sass('resources/sass/app.scss',
'public/css'
)
.sass('resources/vendor/auth-ajax/sass/app.scss',
'public/css'
)
.js([
'resources/js/app.js',
'resources/vendor/auth-ajax/js/app.js',
'resources/vendor/auth-ajax/js/auth.js',
], 'public/js/app.js')
```
This assumes that `resources/sass/app.scss` and `resources/js/app.js` are your own Laravel app files. They are first in line.

## Example route
Put in your `route/web.php` file as an example route `/example`:
```shell
Route::get('/example', [\App\Http\Controllers\ExampleController::class, 'show']);
```
Use the controller `app/Http/Controllers/ExampleController.php` and view-file `resource/views/example.blade.php` as an example for your pages.

Also use the layout class `app/View/Components/layouts/main.php` to get information about the page from the database (`'pages'` table).
Define in this class the fields you need in the corresponding database table.

## Hidden Page Routes

To create a routing group available only to authenticated users, add the following line to the `$routeMiddleware` property of the `app/Http/Kernel.php` file:
```shell
'auth.ajax' => \App\Http\Middleware\AuthenticateAjax::class,
```
Now you can add a group of hidden routes to your `routes/web.php`:
```shell
Route::middleware('auth.ajax')->group(function () {

    // ...

});
```
You can move your `'/example'` route to this closed group to make sure it works. 

## Finally
Compile Assets:
```shell
npm run dev
```
You can now open the project in your browser. 
Note: the buttons of the authentication block should be at the top right.
