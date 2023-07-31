<template>
    <div>
        <h3>流量充值</h3>
    </div>

    <div>
        <h5>您要充值多少元的流量？</h5>
        <p>
            每 GB 价格: <span>{{ price_per_gb }}</span> 元。
        </p>
        <div class="input-group mb-3">
            <input
                v-model="amount"
                autofocus
                class="form-control"
                placeholder="输入您要的流量 (单位: GB)"
                type="number"
            />
            <div class="input-group-append">
                <span class="input-group-text">GB</span>
            </div>
        </div>

        <div v-if="amount">
            <p>大约 <span v-text="amount * price_per_gb"></span> 元。</p>

            <div v-if="providers">
                <h5 class="mt-3">您将要使用哪个平台充值？</h5>
                <p>如果您在选中的平台没有账号，我们将会帮您自动创建一个。</p>
                <template v-for="p in providers">
                    <div class="form-group form-check">
                        <input
                            :id="'providers_' + p"
                            v-model.value="provider"
                            :value="p"
                            class="form-check-input"
                            name="provider"
                            type="radio"
                            @change="getPayments"
                        />
                        <label
                            :for="'providers_' + p"
                            class="form-check-label"
                            v-text="p"
                        ></label>
                    </div>
                </template>
            </div>
            <div v-else>
                <h5 class="mt-3">暂时没有可用的</h5>
            </div>

            <div v-if="payments">
                <h5 class="mt-3">让我们来选择支付方式。</h5>
                <p>在支付后，您的流量大概需要数秒钟到账。</p>
                <template v-for="py in payments">
                    <div class="form-group form-check">
                        <input
                            :id="'payments_' + py.name"
                            v-model="payment"
                            :value="py.name"
                            class="form-check-input"
                            name="payment"
                            type="radio"
                        />
                        <label
                            :for="'payments_' + py.name"
                            class="form-check-label"
                            v-text="py.title"
                        ></label>
                    </div>
                </template>
            </div>

            <div v-if="payment">
                <button
                    :disabled="loading"
                    class="btn btn-primary mt-3"
                    @click="pay"
                    v-text="loading ? '请稍后' : '立即支付'"
                ></button>
            </div>
        </div>
    </div>

    <p v-if="loading">正在创建订单...</p>

    <div v-if="link" class="mt-3">
        <h5>完成</h5>
        <p>如果您浏览器没有打开新的创建，请点击以下链接来打开。</p>
        <a :href="link" class="link" target="_blank">支付</a>
    </div>
</template>

<script setup>
import {ref} from "vue";

import http from "../plugins/http";

const price_per_gb = ref(0);

const providers = ref([]);
const provider = ref("");

const payments = ref({});
const payment = ref("");

const amount = ref(10);

const link = ref("");
const loading = ref(false);

http.get("price").then((res) => {
    price_per_gb.value = res.data.price_per_gb;
});

http.get("providers").then((res) => {
    providers.value = res.data;

    // 选择第一个(如果有)
    if (providers.value.length > 0) {
        provider.value = providers.value[0];
        getPayments();
    }
});

function getPayments() {
    http.get("providers/" + provider.value + "/payments").then((res) => {
        payments.value = res.data;

        // 选择第一个(如果有)
        if (payments.value.length > 0) {
            payment.value = payments.value[0].name;
        }
    });
}

function pay() {
    loading.value = true;
    http.post("providers/" + provider.value + "/charge", {
        payment: payment.value,
        traffic: amount.value,
    })
        .then((res) => {
            link.value = res.data.redirect_url;

            setTimeout(() => {
                window.open(link.value, "_blank");
            });
        })
        .finally(() => {
            loading.value = false;
        });
}
</script>
