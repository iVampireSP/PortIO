<template>
    <h3>客户端下载</h3>

    <table class="table table-bordered mt-3">
        <thead>
        <tr>
            <th>名称</th>
            <th>架构</th>
            <th>作者</th>
            <th>下载</th>
        </tr>
        </thead>

        <tbody>
        <tr v-for="i in items">
            <td>{{ i.name }}</td>
            <td>{{ i.arch }}</td>
            <td>{{ i.author }}</td>
            <td>
                <a :href="i.url">下载</a>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script setup>
import { ref } from "vue";
import http from '../plugins/http'

const items = ref([])

http.get('clients').then((res) => {
    items.value = res.data
})
</script>
