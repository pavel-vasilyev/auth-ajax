<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes, shrink-to-fit=no">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="description" content="{{ $description }}">
    <meta name="author" content="{{ $author }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="icon" href="../../../../public/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="../../../../public/favicon.ico" type="image/x-icon" />
    <link rel="canonical" href="{{ config('app.url') }}" />
    <x-in-head />
</head>
<body>
{{ $slot }}
<x-mod :modal="$modal" />
<x-auth />
<x-js-connect />
</body>
</html>
