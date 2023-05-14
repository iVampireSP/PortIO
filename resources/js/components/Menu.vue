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
        name: "签到",
        route: "sign",
    },
    {
        name: "隧道",
        route: "tunnels",
    },
    {
        name: "创建隧道",
        route: "tunnels.create",
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
