<template>
    <div>
        <h3>节点状态</h3>
        <table class="table table-hover table-bordered table-responsive align-middle mt-3">
            <thead class="text-center">
            <tr>
                <th scope="col">节点名称</th>
                <th scope="col">节点状态</th>
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

http.get("servers").then(res => {
    servers.value = res.data
})

</script>

<style scoped>

</style>
