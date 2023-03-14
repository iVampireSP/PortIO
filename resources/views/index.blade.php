<!DOCTYPE html>
<html data-bs-theme="auto">
<head>
    <title>{{ config('app.display_name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta chrset="utf-8">

</head>


<body>
    Index

    @auth
        <a href="{{ route('logout') }}">Logout</a>
    @else
        <a href="{{ route('login') }}">Login</a>
    @endauth
</body>
</html>
