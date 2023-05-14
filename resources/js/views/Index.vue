<template>
    <div>
        <h1>欢迎</h1>

    </div>

    <div class="mt-3">
         <p>用户名: {{ user.name }}</p>
        <p>剩余流量: {{ user.traffic }}GB</p>
    </div>

</template>

<script setup>
import { ref } from "vue";
import http from '../plugins/http'


const user = ref({
    name: 'loading...',
    traffic: ''
})

http.get('user').then((res) => {
    user.value = res.data
})


</script>
