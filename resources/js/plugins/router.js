import {createRouter, createWebHistory} from "vue-router";
// import app from "../config/app";

const routes = [
    {
        path: "/",
        name: "index",
        component: () => import("../views/Index.vue"),
        meta: {
            title: "欢迎",
        },
    },
    {
        path: "/tunnels",
        name: "tunnels",
        component: () => import("../views/Tunnels/Index.vue"),
        meta: {
            title: "隧道",
        },
    },
    {
        path: "/tunnels/create",
        name: "tunnels.create",
        component: () => import("../views/Tunnels/Create.vue"),
        meta: {
            title: "创建隧道",
        },
    },
    {
        path: "/servers",
        name: "servers",
        component: () => import("../views/Servers/Index.vue"),
        meta: {
            title: "服务器列表",
        },
    },
    {
        path: "/servers/create",
        name: "servers.create",
        component: () => import("../views/Servers/Create.vue"),
        meta: {
            admin: true,
            title: "创建服务器",
        },
    },
    {
        path: "/servers/:id/edit",
        name: "servers.edit",
        component: () => import("../views/Servers/Edit.vue"),
        meta: {
            admin: true,
            title: "创建服务器",
        },
    },
];


// 验证用户是否是管理员(从 window 中获取)
const isAdmin = () => {
    return window.Base.User.is_admin;
};

// before each route
routes.forEach((route) => {
    route.beforeEnter = (to, from, next) => {
        // 如果是管理员页面，且用户不是管理员，则跳转到首页
        if (route.meta.admin && !isAdmin()) {
            next({name: "index"});
        } else {
            next();
        }
    };
});


const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
