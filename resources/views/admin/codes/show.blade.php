<x-app-layout>
    <h3 class="mb-3">流量激活码生成详情</h3>
    <textarea class="form-control" id="code" rows="10" readonly>
@foreach($codes as $code)
{{ $code }}
@endforeach
    </textarea>
</x-app-layout>
