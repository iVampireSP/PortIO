<x-app-layout>
    <h3>流量激活码</h3>

    <a href="{{ route('admin.codes.create') }}">新建激活码</a>

    <div class="alert alert-primary mt-3" role="alert">
        总计: {{ $count }}
    </div>

    <table class="mt-3 table table-hover text-center table-bordered" style="vertical-align: middle">
        <thead>
        <tr>
            <th>ID</th>
            <th>激活码</th>
            <th>流量</th>
            <th>使用用户</th>
            <th>操作</th>
        </tr>
        </thead>


        <tbody>
        @foreach ($codes as $code)
            <tr>
                <td>{{ $code->id }}</td>
                <td>{{ $code->code }}</td>
                <td>{{ $code->traffic }}</td>
                <td>
                    @isset($code->user_id)
                       {{ $code->user->name }} # {{ $code->user_id }}
                    @endisset
                </td>
                <td>
                    <form action="{{ route('admin.codes.destroy', ['code' => $code]) }}" method="POST"
                          onsubmit="return confirm('真的要删除吗？')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">删除</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</x-app-layout>
