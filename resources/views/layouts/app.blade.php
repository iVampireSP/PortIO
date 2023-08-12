<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <title>{{ config('app.display_name', 'LAE') }} 后台</title>

    <link rel="stylesheet" href="/bs/bootstrap.min.css">
    <script src="/bs/bootstrap.bundle.min.js"></script>
</head>

<body>
<div id="top">
    @auth('admin')
        <nav class="navbar navbar-expand-lg bg-body-tertiary shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ route('admin.index') }}">{{ config('app.display_name') }} 后台</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.index') }}">首页</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.users.index') }}">用户</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.tunnels.index') }}">隧道</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.servers.index') }}">服务器</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.clients.index') }}">客户端</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:location.reload()">重新加载</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                               aria-expanded="false">
                                {{ \Illuminate\Support\Facades\Auth::user()->email }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('index') }}">
                                        客户首页
                                    </a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.logout') }}">
                                        退出登录
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endauth
</div>
{{-- display error --}}

<div class="container mt-4 mb-3">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    {{-- Page Content --}}
    <main @if ($errors->any() || session('success' || session('error')))class="mt-3"@endif>
        {{ $slot }}
    </main>
</div>
</body>

</html>
