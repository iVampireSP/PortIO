<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta name="format-detection" content="telephone=no" />

    <title>{{ config('app.display_name') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <script>
        window.Base = {
            SiteName: '{{ config('app.display_name') }}',
            User: @json(auth()->user()),
        }
    </script>

</head>

<body>
    <div id="app"></div>
</body>

</html>

