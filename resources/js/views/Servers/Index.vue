<template>
    <div>
        <h3>服务器列表</h3>

        <div class="list-group">

            <template v-for="server in servers">
                <router-link :to="{
                    name: isAdmin() ? 'servers.edit' : 'tunnels.create',
                    params: {
                    id: server.id
                },
                query: {
                    server_id: server.id
                }
            }" aria-current="true" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{ server.name }}</h5>
                        <small>
                            <server-status :status="server.status"/>
                        </small>
                    </div>
                    <p class="mb-1">
                        支持的协议:
                        <span v-show="server.allow_http">HTTP &nbsp;</span>
                        <span v-show="server.allow_tcp">TCP &nbsp;</span>
                        <span v-show="server.allow_https">HTTPS &nbsp;</span>
                        <span v-show="server.allow_udp">UDP &nbsp;</span>
                        <span v-show="server.allow_stcp">STCP &nbsp;</span>
                        <span v-show="server.allow_sudp">SUDP &nbsp;</span>
                        <span v-show="server.allow_xtcp">XTCP</span>
                    </p>
                    <small>{{ server.server_address }}:{{ server.server_port }}</small>
                </router-link>
            </template>
        </div>

    </div>

</template>

<script setup>
import {ref} from 'vue'
import http from '../../plugins/http'
import ServerStatus from "../../components/ServerStatus.vue";


const servers = ref([{
    "id": "1",
    "name": "",
    "server_address": "",
    "server_port": "",
    "token": "",
    "allow_http": 1,
    "allow_https": 0,
    "allow_tcp": 0,
    "allow_udp": 0,
    "allow_stcp": 0,
    "allow_sudp": 0,
    "allow_xtcp": 0,
    "bandwidth_limit": 0,
    "min_port": 0,
    "max_port": 1024,
    "max_tunnels": 100,
    "tunnels": 0,
    "status": "maintenance",
    "is_china_mainland": 0,
    "created_at": "2023-03-15T11:57:47.000000Z",
    "updated_at": "2023-03-15T11:57:47.000000Z"
}])

http.get('servers').then((res) => {
    servers.value = res.data
})


const isAdmin = () => {
    return window['Base']['User']['is_admin']
}

</script>
