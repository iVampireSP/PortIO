<template>
    <h3>创建隧道</h3>


    <h5>好的名称是好的开始。</h5>
    <div class="form-floating mb-3">
        <input v-model="data.name" type="text" class="form-control" id="tunnelName" placeholder="起一个易于辨别的名字">
        <label for="tunnelName">隧道名称</label>
    </div>

    <div class="form-floating mb-3">
        <select v-model="data.server_id" class="form-select" id="serverSelect">
            <option v-for="server in servers" :value="server.id">{{ server.name }}</option>
        </select>
        <label for="serverSelect">服务器</label>
    </div>

    <div v-if="server">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" id="protocolHTTP" value="http" :disabled="!server.allow_http"
                   v-model="data.protocol">
            <label class="form-check-label" for="protocolHTTP">HTTP</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" id="protocolHTTPS" value="https" :disabled="!server.allow_https"
                   v-model="data.protocol">
            <label class="form-check-label" for="protocolHTTPS">HTTPS</label>
        </div>


        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" id="protocolTCP" value="tcp" :disabled="!server.allow_tcp"
                   v-model="data.protocol">
            <label class="form-check-label" for="protocolTCP">TCP</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" id="protocolUDP" value="udp" :disabled="!server.allow_udp"
                   v-model="data.protocol">
            <label class="form-check-label" for="protocolUDP">UDP</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" id="protocolSTCP" value="stcp" :disabled="!server.allow_stcp"
                   v-model="data.protocol">
            <label class="form-check-label" for="protocolSTCP">STCP</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" id="protocolSUDP" value="sudp" :disabled="!server.allow_sudp"
                   v-model="data.protocol">
            <label class="form-check-label" for="protocolSUDP">SUDP</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" id="protocolXTCP" value="xtcp" :disabled="!server.allow_xtcp"
                   v-model="data.protocol">
            <label class="form-check-label" for="protocolXTCP">XTCP</label>
        </div>

    </div>

    <h5 class="mt-3">本地服务的地址</h5>
    <div class="form-floating mb-3">
        <input v-model="data.local_address" type="text" class="form-control" id="localAddress"
               placeholder="比如 127.0.0.1:80">
        <label for="localAddress">本地地址</label>
    </div>


    <div v-if="data.protocol === 'http'|| data.protocol === 'https'">
        <h5>自定义域名</h5>
        <div class="form-floating mb-3">
            <input v-model="data.custom_domain" type="text" class="form-control" id="customDomain"
                   placeholder="比如 example.com">
            <label for="customDomain">自定义域名</label>
        </div>
    </div>

    <div v-if="data.protocol === 'tcp' || data.protocol === 'udp'">
        <h5>外部端口</h5>
        <div class="form-floating mb-3">
            <input v-model="data.remote_port" type="text" class="form-control" id="remotePort"
                   placeholder="比如 25565">
            <label for="remotePort">外部端口</label>
        </div>
    </div>

    <div v-if="data.protocol === 'stcp' || data.protocol === 'sudp' || data.protocol === 'xtcp' ">
        <h5>访问密钥</h5>
        <div class="form-floating mb-3">
            <input v-model="data.sk" type="text" class="form-control" id="sk"
                   placeholder="比如 25565">
            <label for="sk">访问密钥</label>
        </div>


    </div>

    <button class="btn btn-primary" v-on:click="create">创建</button>

</template>

<script setup>

import {onMounted, ref, watch} from 'vue'
import http from '../../plugins/http'

const server = ref({
    id: "",
    name: "",
    allow_http: true,
    allow_https: true,
    allow_tcp: true,
    allow_udp: true,
    allow_stcp: true,
    allow_sudp: true,
    allow_xtcp: true,
})


const servers = ref([])
const data = ref({
    name: "",
    protocol: "http",
    server_id: "",
    local_address: "",
    custom_domain: "",
    remote_port: "",
    sk: "",
})


http.get('/servers').then(res => {
    servers.value = res.data

    if (data.value.server_id) {
        return
    }

    if (servers.value.length > 0) {
        data.value.server_id = servers.value[0].id
    }
})


function handleServerUpdate() {
    // update selected server
    server.value = servers.value.find(s => s['id'] === data.value.server_id)
    // update server_id in query
    const urlParams = new URLSearchParams(window.location.search)
    urlParams.set('server_id', data.value.server_id)
    window.history.replaceState({}, '', `${window.location.pathname}?${urlParams.toString()}`)
}


watch(() => data.value.server_id, handleServerUpdate)

const getServer = async () => {
    const urlParams = new URLSearchParams(window.location.search)
    data.value.server_id = urlParams.get('server_id')
}

onMounted(() => {
    getServer()
})

const create = () => {
    http.post('/tunnels', data.value).then(res => {
        if (res.status === 200 || res.status === 201) {
            alert("创建成功")
        }
    })
}


</script>
