<x-app-layout>
    <h3>客户端</h3>

    <a href="{{ route('admin.clients.create') }}">新建客户端</a>

    <div class="alert alert-primary mt-3" role="alert">
        总计: {{ $count }}
    </div>

    <table class="mt-3 table table-hover text-center table-bordered" style="vertical-align: middle">
        <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>架构</th>
            <th>下载链接</th>
            <th>操作</th>
        </tr>
        </thead>


        <tbody>
        @foreach ($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td>{{ $client->name }}</td>
                <td>{{ $client->arch }}</td>
                <td>
                    <a target="_blank" href="{{ $client->url }}">{{ $client->url }}</a>
                </td>
                <td>
                    <a href="{{ route('admin.clients.edit', ['client' => $client]) }}"
                       class="btn btn-sm btn-primary mb-2">编辑</a>
                    <form action="{{ route('admin.clients.destroy', ['client' => $client]) }}" method="POST"
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
