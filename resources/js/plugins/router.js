import { createRouter, createWebHistory } from "vue-router";
// import app from "../config/app";
import { Tooltip, Toast } from "bootstrap";

const routes = [
    {
        path: "/",
        name: "index",
        component: () => import("../views/Index.vue"),
        meta: {
            auth: true,
            title: "欢迎",
        },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
