<template>
    <div>
        <h2>隧道: {{ tunnel.name }}</h2>
    </div>

    <div>
        <div v-show="chart" id="chart" style="height: 400px"></div>
    </div>

    <div>
        <h2>配置文件</h2>
        <pre
            >{{ tunnel.config.server }}

{{ tunnel.config.client }}
        </pre>
    </div>

    <div v-if="tunnel.run_id" class="mb-3">
        <h2>强制下线</h2>
        <button class="btn btn-primary" @click="kickTunnel()">强制下线</button>
    </div>

    <div>
        <h2>删除</h2>
        <button class="btn btn-primary" @click="deleteTunnel()">
            删除隧道
        </button>
    </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from "vue";
import http from "../../plugins/http";
import router from "../../plugins/router";
import * as echarts from "echarts";
const showChart = ref(false);
let chart = undefined;

let tunnel_id = router.currentRoute.value.params.id;

const tunnel = ref({
    name: "",
    protocol: "",
    local_address: "",
    remote_port: "",
    config: {
        server: "",
        client: "",
    },
    run_id: "",
});

function getTunnel() {
    http.get(`/tunnels/${tunnel_id}`).then((res) => {
        tunnel.value = res.data;
    });
}

function updateTunnel() {
    http.put(`/tunnels/${tunnel_id}`, tunnel.value).then((res) => {
        tunnel.value = res.data;
    });
}

let chartOptions = {
    tooltip: {
        trigger: "axis",
        axisPointer: {
            type: "shadow",
        },
        formatter: function (data) {
            let html = "";
            if (data.length > 0) {
                html += data[0].name + "<br/>";
            }
            for (let v of data) {
                let colorEl =
                    '<span style="display:inline-block;margin-right:5px;' +
                    "border-radius:10px;width:9px;height:9px;background-color:" +
                    v.color +
                    '"></span>';
                html += `${colorEl + v.seriesName}: ${Humanize.fileSize(
                    v.value
                )}<br/>`;
            }
            return html;
        },
    },
    legend: {
        data: ["入站流量", "出站流量"],
    },
    grid: {
        left: "3%",
        right: "4%",
        bottom: "3%",
        containLabel: true,
    },
    xAxis: [
        {
            type: "category",
            data: [],
        },
    ],
    yAxis: [
        {
            type: "value",
            axisLabel: {
                formatter: function (value) {
                    return Humanize.fileSize(value);
                },
            },
        },
    ],
    series: [
        {
            name: "入站流量",
            type: "bar",
            data: [],
        },
        {
            name: "出站流量",
            type: "bar",
            data: [],
        },
    ],
};

function initChart() {
    let chartDom = document.getElementById("chart");
    chart = echarts.init(chartDom, {
        backgroundColor: "transparent",
        renderer: "svg",
    });

    chartOptions && chart.setOption(chartOptions);
}

function deleteTunnel() {
    if (confirm("确定删除隧道吗？")) {
        http.delete(`/tunnels/${tunnel_id}`).then(() => {
            alert("删除成功");

            router.push({ name: "tunnels" });
        });
    }
}

function kickTunnel() {
    http.post(`/tunnels/${tunnel_id}/close`);
}

function refresh() {
    http.get("/tunnels/" + tunnel_id).then((res) => {
        tunnel.value = res.data;
        // console.log(res.data)
        // tunnel.value.tunnel = res.data.tunnel
        // console.log(tunnel.value.tunnel.conf)
        // console.log(tunnel,tunnel.value,res.data);
        // console.log(res.data);

        if (res.data.traffic) {
            if (!showChart.value) {
                // initChart()
                showChart.value = true;
            }

            let now = new Date();
            now = new Date(
                now.getFullYear(),
                now.getMonth(),
                now.getDate() - 6
            );
            let dates = [];
            for (let i = 0; i < 7; i++) {
                dates.push(
                    now.getFullYear() +
                        "-" +
                        (now.getMonth() + 1) +
                        "-" +
                        now.getDate()
                );
                now = new Date(
                    now.getFullYear(),
                    now.getMonth(),
                    now.getDate() + 1
                );
            }

            chartOptions.xAxis[0].data = dates;

            let trafficInArr = res.data.traffic.traffic_in;
            let trafficOutArr = res.data.traffic.traffic_out;

            if (!trafficInArr || !trafficOutArr) {
                return;
            }

            trafficInArr = trafficInArr.reverse();
            trafficOutArr = trafficOutArr.reverse();

            if (chart) {
                chartOptions.series[0].data = trafficInArr;
                chartOptions.series[1].data = trafficOutArr;

                chart.setOption(chartOptions);
            }
        }
    });
}

refresh();

let resizeInterval = setInterval(() => {
    chart && chart.resize();
});

onMounted(() => {
    getTunnel();

    window.addEventListener("resize", () => {
        chart && chart.resize();
    });
});

const timer = setInterval(refresh, 10000);

onUnmounted(() => {
    clearInterval(timer);

    if (resizeInterval) {
        clearInterval(resizeInterval);
    }

    // remove listener
    window.removeEventListener("resize", () => {
        resizeInterval = setInterval(() => {
            chart && chart.resize();
        }, 1000);
        initChart();
        chart && chart.resize();
    });
});
</script>
