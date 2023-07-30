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
            <!-- <th scope="col">连接数</th>
            <th scope="col">下载流量</th>
            <th scope="col">上载流量</th> -->
            <th scope="col">服务器</th>
            <th scope="col">状态</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="tunnel in tunnels">
            <th>{{ tunnel.id }}</th>
            <td>
                <router-link
                    :to="{
                            name: 'tunnels.show',
                            params: { id: tunnel.id },
                        }"
                >
                    {{ tunnel.name }}
                </router-link>
            </td>
            <td>
                {{ tunnel.protocol.toString().toUpperCase() }}
            </td>
            <td>
                {{ tunnel.local_address }}
            </td>

            <td>
                    <span
                        v-if="
                            tunnel.protocol === 'http' ||
                            tunnel.protocol === 'https'
                        "
                    >
                        {{ tunnel.custom_domain }}
                    </span>
                <span v-else>
                        {{ tunnel.server.server_address }}:{{
                        tunnel.remote_port
                    }}
                    </span>
            </td>

            <!-- <td>0</td>
            <td>0.000 Bytes</td>
            <td>0.000 Bytes</td> -->

            <td>{{ tunnel.server.name }}</td>

            <td>
                <span v-if="tunnel.run_id" class="text-success">在线</span>
                <span v-else="tunnel.run_id" class="text-danger">离线</span>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script setup>
import {ref} from "vue";
import http from "../../plugins/http";

const tunnels = ref([
    {
        id: "0",
        protocol: "",
        server: {
            server_address: "",
            server_port: "",
            name: "",
        },
        run_id: ""
    },
]);

http.get("tunnels").then((res) => {
    tunnels.value = res.data;

    console.log(tunnels.value);
});
</script>
