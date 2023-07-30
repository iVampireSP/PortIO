<template>
    <div class="row">
        <div class="col">
            <h3>服务器</h3>

            <div class="mb-3">
                <label class="form-label" for="serverName">服务器名称</label>
                <input
                    id="serverName"
                    v-model="data.name"
                    class="form-control"
                    name="name"
                    placeholder="输入服务器名称，他将会被搜索到"
                    required
                    type="text"
                />
            </div>

            <h3>Frps 信息</h3>
            <div class="mb-3">
                <label class="form-label" for="serverAddr">Frps 地址</label>
                <input
                    id="serverAddr"
                    v-model="data.server_address"
                    class="form-control"
                    name="server_address"
                    required
                    type="text"
                />
            </div>

            <div class="mb-3">
                <label class="form-label" for="serverPort">Frps 端口</label>
                <input
                    id="serverPort"
                    v-model="data.server_port"
                    class="form-control"
                    name="server_port"
                    required
                    type="text"
                />
            </div>

            <div class="mb-3">
                <label class="form-label" for="serverToken">Frps 令牌</label>
                <input
                    id="serverToken"
                    v-model="data.token"
                    class="form-control"
                    name="token"
                    required
                    type="text"
                />
            </div>

            <h3>服务器位置</h3>
            <div class="form-check">
                <input
                    id="is_china_mainland"
                    v-model="data.is_china_mainland"
                    class="form-check-input"
                    name="is_china_mainland"
                    type="checkbox"
                    value="1"
                />
                <label class="form-check-label" for="is_china_mainland">
                    在中国大陆
                </label>
            </div>
        </div>

        <div class="col">
            <h3>Frps Dashboard 配置</h3>

            <div class="mb-3">
                <label class="form-label" for="dashboardPort">端口</label>
                <input
                    id="dashboardPort"
                    v-model="data.dashboard_port"
                    class="form-control"
                    name="dashboard_port"
                    required
                    type="text"
                />
            </div>

            <div class="mb-3">
                <label class="form-label" for="dashboardUser">登录用户名</label>
                <input
                    id="dashboardUser"
                    v-model="data.dashboard_user"
                    class="form-control"
                    name="dashboard_user"
                    required
                    type="text"
                />
            </div>

            <div class="mb-3">
                <label class="form-label" for="dashboardPwd">密码</label>
                <input
                    id="dashboardPwd"
                    v-model="data.dashboard_password"
                    class="form-control"
                    name="dashboard_password"
                    required
                    type="text"
                />
            </div>

            <h3>端口范围限制</h3>
            <div class="input-group input-group-sm mb-3">
                <input
                    v-model="data.min_port"
                    class="form-control"
                    name="min_port"
                    placeholder="最小端口,比如:1024"
                    required
                    type="text"
                />
                <input
                    v-model="data.max_port"
                    class="form-control"
                    name="max_port"
                    placeholder="最大端口,比如:65535"
                    required
                    type="text"
                />
            </div>

            <h3>最多隧道数量</h3>
            <div class="input-group input-group-sm mb-3">
                <input
                    v-model="data.max_tunnels"
                    class="form-control"
                    name="max_tunnels"
                    placeholder="最多隧道数量,比如:1024个隧道"
                    required
                    type="text"
                />
            </div>

            <h3>隧道协议限制</h3>
            <div class="form-check">
                <input
                    id="allow_http"
                    v-model="data.allow_http"
                    class="form-check-input"
                    name="allow_http"
                    type="checkbox"
                    value="1"
                />
                <label class="form-check-label" for="allow_http">
                    允许 HTTP
                </label>
                <br/>
                超文本传输协议
            </div>

            <div class="form-check">
                <input
                    id="allow_https"
                    v-model="data.allow_https"
                    class="form-check-input"
                    name="allow_https"
                    type="checkbox"
                    value="1"
                />
                <label class="form-check-label" for="allow_https">
                    允许 HTTPS
                </label>
            </div>

            <div class="form-check">
                <input
                    id="allow_tcp"
                    v-model="data.allow_tcp"
                    class="form-check-input"
                    name="allow_tcp"
                    type="checkbox"
                    value="1"
                />
                <label class="form-check-label" for="allow_tcp">
                    允许 TCP
                </label>
            </div>

            <div class="form-check">
                <input
                    id="allow_udp"
                    v-model="data.allow_udp"
                    class="form-check-input"
                    name="allow_udp"
                    type="checkbox"
                    value="1"
                />
                <label class="form-check-label" for="allow_udp">
                    允许 UDP
                </label>
            </div>

            <div class="form-check">
                <input
                    id="allow_stcp"
                    v-model="data.allow_stcp"
                    class="form-check-input"
                    name="allow_stcp"
                    type="checkbox"
                    value="1"
                />
                <label class="form-check-label" for="allow_stcp">
                    允许 STCP
                </label>
            </div>

            <div class="form-check">
                <input
                    id="allow_sudp"
                    v-model="data.allow_sudp"
                    class="form-check-input"
                    name="allow_sudp"
                    type="checkbox"
                    value="1"
                />
                <label class="form-check-label" for="allow_sudp">
                    允许 SUDP
                </label>
            </div>

            <div class="form-check">
                <input
                    id="allow_xtcp"
                    v-model="data.allow_xtcp"
                    class="form-check-input"
                    name="allow_xtcp"
                    type="checkbox"
                    value="1"
                />
                <label class="form-check-label" for="allow_xtcp">
                    允许 XTCP
                </label>
            </div>

            <div class="col-auto">
                <button
                    class="btn btn-primary mb-3"
                    type="submit"
                    @click.prevent="submit"
                >
                    新建服务器
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from "vue";
import http from "../../plugins/http";

const data = ref({
    name: "",
    server_address: "",
    token: "",
    dashboard_port: "",
    dashboard_user: "",
    dashboard_password: "",
    min_port: "",
    max_port: "",
    max_tunnels: "",
    allow_http: "",
    allow_https: "",
    allow_tcp: "",
    allow_udp: "",
    allow_stcp: "",
    allow_sudp: "",
    allow_xtcp: "",
    is_china_mainland: "",
});

const submit = () => {
    http.post("/servers", data.value).then((res) => {
        if (res.status === 200 || res.status === 201) {
            alert("服务器创建成功。");
        }
    });
};

// 根据页面查询参数自动填充表单
const fillForm = () => {
    const urlParams = new URLSearchParams(window.location.search);
    for (const [key, value] of urlParams.entries()) {
        // push to data
        data.value[key] = value;
    }
};

fillForm();
</script>
