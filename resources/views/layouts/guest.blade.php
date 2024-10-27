<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="semi-dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - @yield('title')</title>
    <meta content="" name="description" />
    <meta content="" name="keywords" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons -->
    <link href="{{ URL::asset('build/images/favicon.png') }}" rel="icon" />
    <link href="{{ URL::asset('build/images/apple-touch-icon.png') }}" rel="apple-touch-icon" />

    @include('layouts.head-css')
</head>

<body class="{{ isset($bodyClass) ? $bodyClass : '' }}">

    @yield('content')

    @include('layouts.common-scripts')
</body>

</html>
