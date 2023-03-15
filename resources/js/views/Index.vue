<template>
    <div>
        <h1>Index</h1>
        <p>Index page</p>
        <p>Current time: {{ time }}</p>
    </div>

    <div>
        <p>用户名: {{ user.name }}</p>
    </div>
</template>

<script setup>
import { ref } from "vue";
import http from '../plugins/http'
const time = ref(new Date().toLocaleTimeString());


const user = ref({
    name: 'loading...'

})

http.get('user').then((res) => {
    user.value = res.data
})


http.get('tunnels').then((res) => {
    console.log(res.data)
})


</script>
