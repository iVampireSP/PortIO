<template>
    <ul class="navbar-nav me-auto">
        <template v-for="item in items">
            <li class="nav-item">
                <router-link
                    class="nav-link text-auto"
                    :to="{ name: item.route }"
                    active-class="active"
                    >{{ item.name }}</router-link
                >
            </li>
        </template>
    </ul>
</template>

<script setup>
import { ref } from "vue";

const items = ref([
    {
        name: "首页",
        route: "index",
    },
    {
        name: "隧道",
        route: "tunnels",
    },
    // {
    //     name: "服务器列表",
    //     route: "servers",
    // },
    {
        name: "创建隧道",
        route: "tunnels.create",
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
