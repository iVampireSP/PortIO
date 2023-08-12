<x-app-layout>
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            max-width: 330px;
            padding: 15px;
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
    <div class="text-center">
        <form action="{{ route('admin.login') }}" method="POST" class="mt-3 form-signin w-100 m-auto">
            @csrf
            <h1 class="h3 mb-3 fw-normal">登录到 {{ config('app.display_name') }}</h1>

            <div class="form-floating">
                <input type="email" class="form-control" id="email" name="email" placeholder="邮箱">
                <label for="email">邮箱</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" placeholder="密码">
                <label for="password">密码</label>
            </div>

            <div class="checkbox mb-3">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label for="remember" class="form-check-label">记住我</label>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">登录</button>
        </form>
    </div>
</x-app-layout>
