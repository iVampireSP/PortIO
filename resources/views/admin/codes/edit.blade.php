<x-app-layout>
    <h3 class="mb-3">编辑客户端</h3>
    <form action="{{ route('admin.clients.update', $client->id) }}" method="post">
        @csrf
        @method('PUT')
        <label for="name" class="form-label">名称</label>
        <input type="text" name="name" id="name" placeholder="名称" class="form-control mb-3" required
               value="{{ $client->name }}">

        <label for="name" class="form-label">架构</label>
        <input type="text" name="arch" id="arch" placeholder="架构" class="form-control mb-3" required
               value="{{ $client->arch }}">

        <label for="name" class="form-label">下载链接</label>
        <input type="text" name="url" id="url" placeholder="下载链接" class="form-control mb-3" required
               value="{{ $client->url }}">

        <button class="btn btn-primary" type="submit">保存更改</button>
    </form>
</x-app-layout>
