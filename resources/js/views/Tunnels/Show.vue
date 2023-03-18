<template>
    <div>
        <h2>隧道: {{ tunnel.name }}</h2>
    </div>
</template>

<script setup>

import {onMounted, ref} from "vue";
import http from "../../plugins/http";
import router from "../../plugins/router";

let tunnel_id = router.currentRoute.value.params.id

const tunnel = ref({
    name: '',
    protocol: '',
    local_address: '',
    remote_port: '',
})


function getTunnel() {
    http.get(`/tunnels/${tunnel_id}`).then(res => {
        tunnel.value = res.data
    })
}

function updateTunnel() {
    http.put(`/tunnels/${tunnel_id}`, tunnel.value).then(res => {
        tunnel.value = res.data
    })
}

onMounted(getTunnel)



</script>
