<?php
/*
 * 1) Регистрация провайдера пакета через "extra" в composer.json пакета не работает. Поэтому при установке пакета пользователю придётся вручную регистрировать
 * провайдер пакета в config/app/php проекта в массиве 'providers':
 *
 * PavelVasilyev\AuthAjax\Providers\PackageServiceProvider::class,
 *
 * 2) Опубликовать активы css и js в папку resources/vendor, обновить модель app/Models/User.php, опубликовать переводы в lang/en.json:
 * php artisan vendor:publish --tag=sass --tag=js --tag=user --tag=fonts --tag=middleware --force
 *
 * 3) Далее пользователю необходимо применить миграцию пакета для внесения изменений в таблицу 'users':
 *
 * php artisan migrate
 *
 * 4) Подключить компоненты пакета в файлах представлений приложения. Конечно лучше это сделать в одном месте - в макете:
 *  - в блоке <head>:
 *      <x-auth-ajax::Layouts.head />
 * - в конце блока <body>:
 *      <x-auth-ajax::Layouts.modal />
 *      <x-auth-ajax::Layouts.head-auth />
 *      <x-auth-ajax::Layouts.js-connect />
 *
 * 5) В webpack.js определить команды компиляции активов:
 *
 *  .sass('resources/sass/app.scss',
 *      'public/css'
 *  )
 *  .sass('resources/vendor/auth-ajax/sass/app.scss',
 *      'public/css'
 *  )
 *  .js([
 *      'resources/js/app.js',
 *      'resources/vendor/auth-ajax/js/app.js',
 *      'resources/vendor/auth-ajax/js/auth.js',
 *  ], `public/js/app.js`)
 *
 *  6) Выполнить: npm run dev
 */
