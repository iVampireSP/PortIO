<template>
    <h3>隧道列表</h3>

    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">名称</th>
            <th scope="col">协议</th>
            <th scope="col">本地地址</th>
            <th scope="col">远程端口/域名</th>
            <th scope="col">连接数</th>
            <th scope="col">下载流量</th>
            <th scope="col">上载流量</th>
            <th scope="col">服务器</th>
            <th scope="col">状态</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="tunnel in tunnels">
            <th>1</th>
            <td>
                <router-link :to="{name: 'tunnels.show', params: {id: tunnel.id}}">
                    {{ tunnel.name }}
                </router-link>

            </td>
            <td>STCP</td>
            <td>127.0.0.1:80</td>

            <td>127.0.01:</td>

            <td>0</td>
            <td>0.000 Bytes</td>
            <td>0.000 Bytes</td>

            <td><a href=" http:
                //portio.test/servers/1">Test</a></td>

            <td>
                <span class="text-danger">离线</span>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script setup>
import {ref} from "vue";
import http from "../../plugins/http";


const tunnels = ref([])

http.get('tunnels').then((res) => {
    tunnels.value = res.data

    console.log(tunnels.value)
})

</script>
