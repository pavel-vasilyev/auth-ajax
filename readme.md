## Laravel authentication via AJAX

This package complements the basic Laravel 9 authentication classes. It allows you to perform authentication functions using AJAX. Accordingly, there is no need to use separate pages with forms. User interaction takes place through modal windows. Bootstrap and Jquery are used for this purpose. The [Doctrine DBAL](https://github.com/doctrine/dbal) package must also be connected during the development phase. This defined in the "require" section of package's composer.json file.

Bootstrap and Jquery connect in package component files: `head.blade.php` and `js-connect.blade.php` in `pavel-vasilyev/auth-ajax/src/views/components/layouts` directory.

## Installation

You’ll have to make a slight adjustment to `composer.json` file of your project . Open the file and update include the following array somewhere in the object:

```php
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/pavel-vasilyev/auth-ajax"
    }
],
```

In the same composer.json file in section "autoload" > "psr-4" add:

```php
"PavelVasilyev\\AuthAjax\\": "packages/pavel-vasilyev/auth-ajax/src/"
```


Require this package with composer:

```shell
composer require pavel-vasilyev/auth-ajax:dev-main
```

Package doesn't Auto-Discovery, so require you to manually add the ServiceProvider to the 'providers' array in `config/app.php`:

```php
PavelVasilyev\AuthAjax\Providers\PackageServiceProvider::class,
```

Publish assets (js, css) to the `resources/vendor` directory, update the `app/models/user.php` model, publish translations `en.json` to the application's `lang` directory. The --force flag is required to overwrite `user.php`:

```php
php artisan vendor:publish --provider="PavelVasilyev\AuthAjax\Providers\PackageServiceProvider" --tag=sass --tag=js --tag=user --tag=fonts --tag=middleware --force
```

Use package migration to make changes to the `users` table:

```php
php artisan migrate
```

Include the package components in the application view files. Of course, it is better to do this in the general layout:
- in the `head` block:
```php
<x-auth-ajax::Layouts.head />
```
- at the end of the `body` block:
```php
<x-auth-ajax::Layouts.modal />
<x-auth-ajax::Layouts.head-auth />
<x-auth-ajax::Layouts.js-connect />
```

Define asset compilation commands in `webpack.js`:
```php
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
], `public/js/app.js`)
```
This assumes that `app.scss` and `app.js` are your own Laravel app files. They are first in line.

Compile Assets: 
```php
npm run dev
```
You can now open the project in your browser. Note: the buttons of the authentication block should be at the top right.
