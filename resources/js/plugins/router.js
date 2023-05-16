import { createRouter, createWebHistory } from "vue-router";
import { Tooltip, Toast } from 'bootstrap';


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
        path: "/tunnels/:id",
        name: "tunnels.show",
        component: () => import("../views/Tunnels/Show.vue"),
        meta: {
            title: "隧道",
        },
    },
    {
        path: "/downloads",
        name: "downloads",
        component: () => import("../views/Downloads.vue"),
        meta: {
            title: "客户端下载",
        },
    },
    {
        path: "/sign",
        name: "sign",
        component: () => import("../views/Sign.vue"),
        meta: {
            title: "签到",
        },
    },
    {
        path: "/charge",
        name: "charge",
        component: () => import("../views/Charge.vue"),
        meta: {
            title: "流量充值",
        },
    },
];


// 验证用户是否是管理员(从 window 中获取)
const isAdmin = () => {
    return window.Base.User.is_admin;
};


const router = createRouter({
    history: createWebHistory(),
    routes,
});


// before each route
routes.forEach((route) => {

    router.beforeEach((to, from) => {
        new Tooltip(document.body, {
            selector: "[data-bs-toggle='tooltip']",
        });

        Array.from(document.querySelectorAll('.toast')).forEach(
            (toastNode) => new Toast(toastNode)
        );
    });

    route.beforeEnter = (to, from, next) => {
        // 如果是管理员页面，且用户不是管理员，则跳转到首页
        if (route.meta.admin && !isAdmin()) {
            next({ name: "index" });
        } else {
            next();
        }
    };
});


export default router;
