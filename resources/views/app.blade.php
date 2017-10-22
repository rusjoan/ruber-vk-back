<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AR-галерея товаров</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="/css/app.css" crossorigin="anonymous">

    @yield('head')
</head>
<body>
    @yield('wrapper')
    @yield('js')
</body>
</html>
