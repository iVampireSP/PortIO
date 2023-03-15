<template>
    <div class="row">
        <div class="col">
            <h3>服务器</h3>

            <div class="mb-3">
                <label for="serverName" class="form-label">服务器名称</label>
                <input
                    type="text"
                    required
                    class="form-control"
                    id="serverName"
                    placeholder="输入服务器名称，他将会被搜索到"
                    name="name"
                    v-model="data.name"
                />
            </div>

            <h3>Frps 信息</h3>
            <div class="mb-3">
                <label for="serverAddr" class="form-label">Frps 地址</label>
                <input
                    type="text"
                    required
                    class="form-control"
                    id="serverAddr"
                    name="server_address"
                    v-model="data.server_address"
                />
            </div>

            <div class="mb-3">
                <label for="serverPort" class="form-label">Frps 端口</label>
                <input
                    type="text"
                    required
                    class="form-control"
                    id="serverPort"
                    name="server_port"
                    v-model="data.server_port"
                />
            </div>

            <div class="mb-3">
                <label for="serverToken" class="form-label">Frps 令牌</label>
                <input
                    type="text"
                    required
                    class="form-control"
                    id="serverToken"
                    name="token"
                    v-model="data.token"
                />
            </div>

            <h3>服务器位置</h3>
            <div class="form-check">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="is_china_mainland"
                    value="1"
                    id="is_china_mainland"
                    v-model="data.is_china_mainland"
                />
                <label class="form-check-label" for="is_china_mainland">
                    在中国大陆
                </label>
            </div>
        </div>

        <div class="col">
            <h3>Frps Dashboard 配置</h3>

            <div class="mb-3">
                <label for="dashboardPort" class="form-label">端口</label>
                <input
                    type="text"
                    required
                    class="form-control"
                    id="dashboardPort"
                    name="dashboard_port"
                    v-model="data.dashboard_port"
                />
            </div>

            <div class="mb-3">
                <label for="dashboardUser" class="form-label">登录用户名</label>
                <input
                    type="text"
                    required
                    class="form-control"
                    id="dashboardUser"
                    name="dashboard_user"
                    v-model="data.dashboard_user"
                />
            </div>

            <div class="mb-3">
                <label for="dashboardPwd" class="form-label">密码</label>
                <input
                    type="text"
                    required
                    class="form-control"
                    id="dashboardPwd"
                    name="dashboard_password"
                    v-model="data.dashboard_password"
                />
            </div>

            <h3>端口范围限制</h3>
            <div class="input-group input-group-sm mb-3">
                <input
                    type="text"
                    required
                    class="form-control"
                    placeholder="最小端口,比如:1024"
                    name="min_port"
                    v-model="data.min_port"
                />
                <input
                    type="text"
                    required
                    class="form-control"
                    placeholder="最大端口,比如:65535"
                    name="max_port"
                    v-model="data.max_port"
                />
            </div>

            <h3>最多隧道数量</h3>
            <div class="input-group input-group-sm mb-3">
                <input
                    type="text"
                    required
                    class="form-control"
                    placeholder="最多隧道数量,比如:1024个隧道"
                    name="max_tunnels"
                    v-model="data.max_tunnels"
                />
            </div>

            <h3>隧道协议限制</h3>
            <div class="form-check">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="allow_http"
                    value="1"
                    id="allow_http"
                    v-model="data.allow_http"
                />
                <label class="form-check-label" for="allow_http">
                    允许 HTTP
                </label>
                <br />
                超文本传输协议
            </div>

            <div class="form-check">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="allow_https"
                    value="1"
                    id="allow_https"
                    v-model="data.allow_https"
                />
                <label class="form-check-label" for="allow_https">
                    允许 HTTPS
                </label>
            </div>

            <div class="form-check">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="allow_tcp"
                    value="1"
                    id="allow_tcp"
                    v-model="data.allow_tcp"
                />
                <label class="form-check-label" for="allow_tcp">
                    允许 TCP
                </label>
            </div>

            <div class="form-check">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="allow_udp"
                    value="1"
                    id="allow_udp"
                    v-model="data.allow_udp"
                />
                <label class="form-check-label" for="allow_udp">
                    允许 UDP
                </label>
            </div>

            <div class="form-check">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="allow_stcp"
                    value="1"
                    id="allow_stcp"
                    v-model="data.allow_stcp"
                />
                <label class="form-check-label" for="allow_stcp">
                    允许 STCP
                </label>
            </div>

            <div class="form-check">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="allow_sudp"
                    value="1"
                    id="allow_sudp"
                    v-model="data.allow_sudp"
                />
                <label class="form-check-label" for="allow_sudp">
                    允许 SUDP
                </label>
            </div>

            <div class="form-check">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="allow_xtcp"
                    value="1"
                    id="allow_xtcp"
                    v-model="data.allow_xtcp"
                />
                <label class="form-check-label" for="allow_xtcp">
                    允许 XTCP
                </label>
            </div>

            <div class="col-auto">
                <button
                    type="submit"
                    class="btn btn-primary mb-3"
                    @click.prevent="submit"
                >
                    新建服务器
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
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
