<template>
    <div>
        <h3>节点状态</h3>
        <table class="table table-hover table-bordered table-responsive align-middle mt-3">
            <thead class="text-center">
            <tr>
                <th scope="col">节点名称</th>
                <th scope="col">节点状态</th>
                <th scope="col">上行流量</th>
                <th scope="col">下行流量</th>
                <th scope="col">当前连接数</th>
            </tr>
            </thead>
            <tbody class="text-center">
            <tr v-for="server in servers">
                <td>
                    {{ server.name }}
                </td>
                <td>
                    <ServerStatus :status="server.status"/>
                </td>
                <td>
                    {{ server.traffic_in ? (server.traffic_in/1024/1024).toFixed(2) + " MB" : "暂无数据" }}
                </td>
                <td>
                    {{ server.traffic_out ? (server.traffic_out/1024/1024).toFixed(2) + " MB" : "暂无数据" }}
                </td>
                <td>
                    {{ server.connections ?? "暂无数据" }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import ServerStatus from "../components/ServerStatus.vue";
import http from "../plugins/http"
import {ref} from "vue";

const servers = ref([])

if (localStorage.getItem("status") !== null) {
    servers.value = JSON.parse(localStorage.getItem("status"))
} else {
    http.get("servers").then(res => {
        servers.value = res.data
        localStorage.setItem("status", servers.value.toString())
    })
}

</script>

<style scoped>

</style>
