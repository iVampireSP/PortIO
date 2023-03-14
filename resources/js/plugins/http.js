import axios from "axios";
import router from "./router";

// 实例
let instance = axios.create({
    baseURL: '/api',
    timeout: 10000,

    // csrf
    headers: {
        "X-Requested-With": "XMLHttpRequest",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
});

instance.interceptors.request.use(
    (config) => {
        if (config.headers === undefined) {
            config.headers = {};
        }

        config.headers["Accept"] = "application/json";
        // config.headers["Authorization"] = "Bearer " + store.state.token;

        return config;
    },
    (error) => {
        console.error(error);
        return Promise.reject(error);
    }
);

instance.interceptors.response.use(
    (res) => {
        return Promise.resolve(res);
    },
    (error) => {
        console.error("axios error", error);

        let data = [];

        if (error.response.data.data) {
            data = error.response.data.data;
        }

        if (error.response.data.message) {
            data = error.response.data.message;
        }

        if (error.response.data.error) {
            data = error.response.data.error.message;
        }

        if (error.response.status === 429) {
            alert("请求次数过多");
        } else if (error.response.status === 401) {
            if (router.currentRoute.value.name !== "login") {
                if (!temp.isAlertedToken) {
                    //   alert('您的 访问密钥 可能已经失效，请尝试通过侧边栏来重新登录。');

                    setTimeout(() => {
                        router.push({ name: "login" });
                    }, 1000);

                }
            }
        } else if (error.response.status === 404) {
            router.push({ name: "index" });
        } else {
            if (data.length !== 0) {
                alert(data);
            }
        }
    }
);

export default instance;
