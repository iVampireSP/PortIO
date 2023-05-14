<!DOCTYPE html>
<html data-bs-theme="auto">
<head>
    <title>{{ config('app.display_name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta chrset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>


<body>
   <span>欢迎使用: {{ config('app.display_name') }}</span>

   <a href="{{ route('login') }}">登录</a>
</body>
</html>
