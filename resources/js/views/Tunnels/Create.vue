<template>
    <h3>创建隧道</h3>


    <h5>好的名称是好的开始。</h5>
    <div class="form-floating mb-3">
        <input id="tunnelName" v-model="data.name" class="form-control" placeholder="起一个易于辨别的名字" type="text">
        <label for="tunnelName">隧道名称</label>
    </div>

    <div class="form-floating mb-3">
        <select id="serverSelect" v-model="data.server_id" class="form-select">
            <option v-for="server in servers" :value="server.id">{{ server.name }}</option>
        </select>
        <label for="serverSelect">服务器</label>
    </div>
    <div class="text-muted mb-3">端口范围 {{ server.min_port }}-{{ server.max_port }}</div>

    <div v-if="server">
        <div class="form-check form-check-inline">
            <input id="protocolHTTP" v-model="data.protocol" :disabled="!server.allow_http" class="form-check-input" type="radio"
                   value="http">
            <label class="form-check-label" for="protocolHTTP">HTTP</label>
        </div>
        <div class="form-check form-check-inline">
            <input id="protocolHTTPS" v-model="data.protocol" :disabled="!server.allow_https" class="form-check-input"
                   type="radio"
                   value="https">
            <label class="form-check-label" for="protocolHTTPS">HTTPS</label>
        </div>


        <div class="form-check form-check-inline">
            <input id="protocolTCP" v-model="data.protocol" :disabled="!server.allow_tcp" class="form-check-input" type="radio"
                   value="tcp">
            <label class="form-check-label" for="protocolTCP">TCP</label>
        </div>
        <div class="form-check form-check-inline">
            <input id="protocolUDP" v-model="data.protocol" :disabled="!server.allow_udp" class="form-check-input" type="radio"
                   value="udp">
            <label class="form-check-label" for="protocolUDP">UDP</label>
        </div>

        <div class="form-check form-check-inline">
            <input id="protocolSTCP" v-model="data.protocol" :disabled="!server.allow_stcp" class="form-check-input" type="radio"
                   value="stcp">
            <label class="form-check-label" for="protocolSTCP">STCP</label>
        </div>

        <div class="form-check form-check-inline">
            <input id="protocolSUDP" v-model="data.protocol" :disabled="!server.allow_sudp" class="form-check-input" type="radio"
                   value="sudp">
            <label class="form-check-label" for="protocolSUDP">SUDP</label>
        </div>

        <div class="form-check form-check-inline">
            <input id="protocolXTCP" v-model="data.protocol" :disabled="!server.allow_xtcp" class="form-check-input" type="radio"
                   value="xtcp">
            <label class="form-check-label" for="protocolXTCP">XTCP</label>
        </div>

    </div>

    <h5 class="mt-3">本地服务的地址</h5>
    <div class="form-floating mb-3">
        <input id="localAddress" v-model="data.local_address" class="form-control" placeholder="比如 127.0.0.1:80"
               type="text">
        <label for="localAddress">本地地址</label>
    </div>


    <div v-if="data.protocol === 'http'|| data.protocol === 'https'">
        <h5>自定义域名</h5>
        <div class="form-floating mb-3">
            <input id="customDomain" v-model="data.custom_domain" class="form-control" placeholder="比如 example.com"
                   type="text">
            <label for="customDomain">自定义域名</label>
        </div>
    </div>

    <div v-if="data.protocol === 'tcp' || data.protocol === 'udp'">
        <h5>外部端口</h5>
        <div class="input-group mb-3">
            <div class="form-floating">
                <input id="remotePort" v-model="data.remote_port" class="form-control" placeholder="比如 25565"
                       type="text">
                <label for="remotePort">外部端口</label>
            </div>
            <button class="btn btn-outline-primary" type="button" @click="randomPort">随机端口</button>
        </div>
    </div>

    <div v-if="data.protocol === 'stcp' || data.protocol === 'sudp' || data.protocol === 'xtcp' ">
        <h5>访问密钥</h5>
        <div class="form-floating mb-3">
            <input id="sk" v-model="data.sk" class="form-control" placeholder="比如 25565"
                   type="text">
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
    min_port: 10000,
    max_port: 65535,
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

function randomPort() {
    const minPort = server.value.min_port
    const maxPort = server.value.max_port
    data.value.remote_port = Math.floor(Math.random() * (maxPort - minPort + 1)) + minPort
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
