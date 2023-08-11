<template>
    <ul class="navbar-nav me-auto">
        <template v-for="item in items">
            <li class="nav-item">
                <router-link
                    :to="{ name: item.route }"
                    active-class="active"
                    class="nav-link text-auto"
                >{{ item.name }}
                </router-link
                >
            </li>
        </template>
    </ul>

    <!-- Right Side Of Navbar -->
    <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <a
                class="nav-link text-auto"
                href="https://wiki.laecloud.com/PortIO"
                target="_blank"
            >文档</a
            >
        </li>
        <li class="nav-item">
            <a class="nav-link text-auto" href="/auth/logout">退出登录</a>
        </li>
    </ul>
</template>

<script setup>
import {ref} from "vue";

const items = ref([
    {
        name: "首页",
        route: "index",
    },
    {
        name: "穿透隧道",
        route: "tunnels",
    },
    {
        name: "创建隧道",
        route: "tunnels.create",
    },
    {
        name: "签到",
        route: "sign",
    },
    {
        name: "充值",
        route: "charge",
    },
    {
        name: "节点状态",
        route: "status",
    },
    {
        name: "发工单",
        route: "ticket",
    },
    {
        name: "客户端下载",
        route: "downloads",
    },
]);

// 如果是管理员
// if (window.Base.User.is_admin) {
//     // items.value.push({
//     //     name: "服务器",
//     //     route: "servers",
//     // });
//     items.value.push({
//         name: "创建服务器",
//         route: "servers.create",
//     });
// }
</script>
