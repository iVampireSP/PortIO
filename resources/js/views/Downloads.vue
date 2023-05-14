<template>
    <h1>客户端下载</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>名称</th>
                <th>架构</th>
                <th>下载</th>
            </tr>
        </thead>

        <tbody>
            <tr v-for="i in items">
                <td>{{ i.name }}</td>
                <td>{{ i.arch }}</td>
                <td>
                    <a :href="i.url">下载</a>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script setup>
import { ref } from "vue";

const version = [
    {
        name: "Windows Frpc amd64",
        arch: "amd64",
        url: "https://r2.laecloud.com/MEFrpRelease/MirrorEdgeFrp_0.46.1_beta_windows_amd64.zip",
    },
    {
        name: "Windows Frpc i386",
        arch: "i386",
        url: "https://r2.laecloud.com/MEFrpRelease/MirrorEdgeFrp_0.46.1_beta_windows_386.zip",
    },
    {
        name: "Windows Python 图形化启动器",
        arch: "amd64",
        url: "https://r2.laecloud.com/MEFrpRelease/Mirror_Edge_Frp_Python_Win.zip",
    },
    {
        name: "Linux Frpc amd64",
        arch: "amd64",
        url: "https://r2.laecloud.com/MEFrpRelease/frp_MirrorEdgeFrp_0.46.1_beta_linux_amd64.tar.gz",
    },
    {
        name: "Linux Frpc arm64",
        arch: "arm64",
        url: "https://r2.laecloud.com/MEFrpRelease/frp_MirrorEdgeFrp_0.46.1_beta_linux_arm64.tar.gz",
    },
    {
        name: "Darwin Frpc amd64",
        arch: "amd64",
        url: "https://r2.laecloud.com/MEFrpRelease/frp_MirrorEdgeFrp_0.46.1_beta_darwin_amd64.tar.gz",
    },
    {
        name: "Darwin Frpc arm64",
        arch: "arm64",
        url: "https://r2.laecloud.com/MEFrpRelease/frp_MirrorEdgeFrp_0.46.1_beta_darwin_arm64.tar.gz",
    },
];

const items = ref(version);
</script>
