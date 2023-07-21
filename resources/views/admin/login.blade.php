<x-app-layout>
    <div class="container">
        <h3>登录</h3>

        <form action="{{ route('admin.login') }}" method="POST" class="mt-3">
            @csrf
            <div class="card">
                <div class="card-header">
                    登录
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <input type="text" name="email" placeholder="邮箱" class="form-control">
                        </div>
                        <div class="col">
                            <input type="password" name="password" placeholder="密码" class="form-control">
                        </div>
                    </div>
                    <div class="mt-3">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label for="remember" class="form-check-label">记住我</label>
                    </div>
                    <button class="mt-3 btn btn-primary" type="submit">登录</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
