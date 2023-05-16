<template>
    <div>
        <h3>欢迎</h3>

    </div>

    <div class="mt-3">
         <p>用户名: {{ user.name }}</p>
        <p>剩余流量: {{ user.traffic }}GB</p>
    </div>

    <div class="mt-3" v-if="!user.realnamed">
        <p>注意，您没有完成实名认证，请点击下方按钮完成实名认证，否则您只能使用中国大陆以外的隧道。</p>
        <a class="btn btn-primary" target="_blank" href="https://oauth.laecloud.com/real_name">实名认证</a>
        <p>在实名认证后，请重新登录 {{ sitename }}。</p>
    </div>

</template>

<script setup>
import { ref } from "vue";
import http from '../plugins/http'

const sitename = window.Base.SiteName

const user = ref({
    name: 'loading...',
    traffic: ''
})

http.get('user').then((res) => {
    user.value = res.data
})


</script>
