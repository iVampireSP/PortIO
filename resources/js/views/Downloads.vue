<template>
    <h3>客户端下载</h3>

    <table class="table table-bordered mt-3">
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
        name: "Windows Frpc",
        arch: "amd64",
        url: "https://download.muhanfrp.cn/frp_0.46.1_windows_amd64.zip",
    },
    {
        name: "Windows Frpc",
        arch: "i386",
        url: "https://download.muhanfrp.cn/frp_0.46.1_windows_386.zip",
    },
    {
        name: "Linux Frpc i386",
        arch: "i386",
        url: "https://download.muhanfrp.cn/frp_0.46.1_linux_386.tar.gz",
    },
    {
        name: "Linux Frpc amd64",
        arch: "amd64",
        url: "https://download.muhanfrp.cn/frp_0.46.1_linux_amd64.tar.gz",
    },
    {
        name: "Linux Frpc arm32",
        arch: "arm32",
        url: "https://download.muhanfrp.cn/frp_0.46.1_linux_arm32.tar.gz",
    },
    {
        name: "Linux Frpc arm64",
        arch: "arm64",
        url: "https://download.muhanfrp.cn/frp_0.46.1_linux_arm64.tar.gz",
    },
    {
        name: "Darwin Frpc amd64",
        arch: "amd64",
        url: "https://download.muhanfrp.cn/frp_0.46.1_darwin_amd64.tar.gz",
    },
    {
        name: "Darwin Frpc arm64",
        arch: "arm64",
        url: "https://download.muhanfrp.cn/frp_0.46.1_darwin_arm64.tar.gz",
    },
    {
        name: "Freebsd Frpc i386",
        arch: "i386",
        url: "https://download.muhanfrp.cn/frp_0.46.1_freebsd_386.tar.gz",
    },
    {
        name: "Freebsd Frpc arm64",
        arch: "arm64",
        url: "https://download.muhanfrp.cn/frp_0.46.1_freebsd_amd64.tar.gz",
    },
    {
        name: "Mips Frpc i386",
        arch: "i386",
        url: "https://download.muhanfrp.cn/frp_0.46.1_linux_mips.tar.gz",
    },
    {
        name: "Mips Frpc amd64",
        arch: "amd64",
        url: "https://download.muhanfrp.cn/frp_0.46.1_linux_mips64.tar.gz",
    },
    {
        name: "Mipsle Frpc i386",
        arch: "i386",
        url: "https://download.muhanfrp.cn/frp_0.46.1_linux_mipsle.tar.gz",
    },
    {
        name: "Mipsle Frpc amd64",
        arch: "amd64",
        url: "https://download.muhanfrp.cn/frp_0.46.1_linux_mips64le.tar.gz",
    },
    {
        name: "Riscv Frpc amd64",
        arch: "amd64",
        url: "https://download.muhanfrp.cn/frp_0.46.1_linux_riscv64.tar.gz",
    },
    {
        name: "android-v0.39.1.1 Frpc 图形客户端",
        arch: "android",
        url: "https://download.muhanfrp.cn/frpc_android-v0.39.1.1.apk",
    },
    {
        name: "android-v0.31.2 Frpc 图形客户端",
        arch: "android",
        url: "https://download.muhanfrp.cn/FRP.apk",
    },
    {
        name: "Termux 客户端 ",
        arch: "android",
        url: "https://download.muhanfrp.cn/com.termux_118.apk",
    },
    {
        name: "Windows PortIO_Python 图形客户端",
        arch: "86-64",
        url: "https://download.muhanfrp.cn/PortIO_Client.zip"
    },
    {
        name: "Windows PortIO_Client_CLI_Python 命令行图形客户端",
        arch: "86-64",
        url: "https://download.muhanfrp.cn/PortIO_Client_CLI.exe",
    },
    {
        name: "Windows PortIO_Pascal 图形客户端",
        arch: "86-64",
        url: "https://download.muhanfrp.cn/pioc_windows_x86_64.zip",
    },
    {
        name: "Linux PortIO_Pascal 图形客户端",
        arch: "86-64",
        url: "https://download.muhanfrp.cn/pioc_linux_x86_64.tar.gz",
    },
];

const items = ref(version);
</script>
