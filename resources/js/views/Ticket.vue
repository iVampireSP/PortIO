<template>
    <div>
        <h3>发工单</h3>
    </div>

    <div>
        <h5>有遇到什么问题吗？</h5>

        <div class="mb-3">
            您可以选择以下常见问题：
            <a class="link" @click="common_domain">域名白名单</a>
            &nbsp;
            <a class="link" @click="common_problem">映射问题</a>
        </div>
        <div class="input-group mb-3">
            <input
                v-model="title"
                autofocus
                class="form-control"
                placeholder="简要概述您遇到的问题"
                type="text"
            />
        </div>

        <div v-if="title" class="input-group">
            <textarea
                v-model="content"
                class="form-control"
                placeholder="详细说明您遇到的问题..."
            ></textarea>
        </div>

        <div v-if="title">
            <div v-if="providers">
                <h5 class="mt-3">选择发工单的平台</h5>
                <p>如果您在选中的平台没有账号，我们将会帮您自动创建一个。</p>
                <template v-for="p in providers">
                    <div class="form-group form-check">
                        <input
                            :id="'providers_' + p"
                            v-model.value="provider"
                            :value="p"
                            class="form-check-input"
                            name="provider"
                            type="radio"
                        />
                        <label
                            :for="'providers_' + p"
                            class="form-check-label"
                            v-text="p"
                        ></label>
                    </div>
                </template>
            </div>
            <div v-else>
                <h5 class="mt-3">暂时没有可用的提供商</h5>
            </div>

            <div v-if="content">
                <button
                    :disabled="loading"
                    class="btn btn-primary mt-3"
                    @click="submit"
                    v-text="loading ? '请稍后' : '创建工单'"
                ></button>
            </div>
        </div>
    </div>

    <p v-if="loading">正在打开工单...</p>

    <div v-if="link" class="mt-3">
        <h5>完成</h5>
        <p>如果您浏览器没有打开新的创建，请点击以下链接来打开。</p>
        <a :href="link" class="link" target="_blank">打开工单</a>
    </div>
</template>

<script setup>
import {ref} from "vue";

import http from "../plugins/http";

const providers = ref([]);
const provider = ref("");

const title = ref("");
const content = ref("");

const link = ref("");
const loading = ref(false);

http.get("providers").then((res) => {
    providers.value = res.data;

    // 选择第一个(如果有)
    if (providers.value.length > 0) {
        provider.value = providers.value[0];
    }
});

function submit() {
    loading.value = true;
    http.post("providers/" + provider.value + "/ticket", {
        title: title.value,
        content: content.value,
    })
        .then((res) => {
            link.value = res.data.redirect_url;

            setTimeout(() => {
                window.open(link.value, "_blank");
            });
        })
        .finally(() => {
            loading.value = false;
        });
}

function common_domain() {
    title.value = "域名 {你的域名} 过白。";
    content.value =
        "您好，我的域名已备案，请将我的域名 {你的域名} 加入白名单，谢谢。";
}

function common_problem() {
    title.value = "{节点} 的隧道无法连接。";
    content.value =
        "您好，这个节点无法连接，请检查。";
}
</script>

<style scoped>
.link {
    color: #007bff;
    text-decoration: none;
    cursor: pointer;
}
</style>
