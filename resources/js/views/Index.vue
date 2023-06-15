<template>
    <div>
        <h3>欢迎</h3>
    </div>

    <div class="mt-3">
        <p>用户名: {{ user.name }}</p>
        <p>剩余流量: {{ user.traffic }} GB</p>
    </div>

    <div class="mt-3" v-if="!user.realnamed">
        <h3>实名认证</h3>
        <p>
            注意，您没有完成实名认证，请点击下方按钮完成实名认证，否则您只能使用中国大陆以外的隧道。
        </p>
        <a
            class="btn btn-primary"
            target="_blank"
            href="https://oauth.laecloud.com/real_name"
            >实名认证</a
        >
        <p>在实名认证后，请重新登录 {{ sitename }}。</p>
    </div>

    <h3>访问密钥</h3>
    <div class="mt-3">
        <p>
            访问密钥是用于访问 {{ sitename }} API
            的密钥，您可以使用它来开发自己的客户端。
        </p>
        <p v-if="newToken" class="text-success">获取成功，请妥善保管您的 Token: {{ newToken }}</p>
        <button class="btn btn-primary" @click="getNewToken">
            获取新密钥
        </button>
        <button class="btn btn-danger" style="margin-left: 5px;" @click="deleteAllToken">
            删除所有密钥
        </button>
    </div>
</template>

<script setup>
import { ref } from "vue";
import http from "../plugins/http";

const sitename = window.Base.SiteName;

const user = ref({
    name: "loading...",
    traffic: "",
});

const newToken = ref("");

http.get("user").then((res) => {
    user.value = res.data;
});

function getNewToken() {
    http.post("tokens").then((res) => {
        newToken.value = res.data.token;
    });
}

function deleteAllToken() {
    http.delete("tokens").then((res) => {
        alert("所有 Token 删除成功。");
    });
}
</script>
