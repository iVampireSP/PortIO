<template>
    <div>
        <h3>流量补给</h3>

        <div>
            <p>当前流量: {{ traffic.traffic }}GB</p>
            <div v-if="traffic.is_signed">今日已签到</div>
            <div v-else>
                <p>完成验证码以签到</p>
                <vue-recaptcha
                    :sitekey="key"
                    :theme="theme"
                    loadRecaptchaScript
                    recaptchaHost="www.recaptcha.net"
                    @verify="sign"
                />
            </div>
        </div>

        <button
            type="button"
            class="btn btn-primary"
            style="display: none"
            id="signinButton"
            data-bs-toggle="modal"
            data-bs-target="#signinModal">
        </button>


        <div class="modal fade" id="signinModal" tabindex="-1" aria-labelledby="signinModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="signinModalLabel">签到</h1>
                    </div>
                    <div class="modal-body">
                        签到成功！{{ content }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">确定</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import {ref} from "vue";
import {VueRecaptcha} from 'vue-recaptcha';

import http from "../plugins/http";

const key = window.Base.ReCaptcha
const content = ref("")

const traffic = ref({
    last_sign_at: null,
    traffic: 0,
});

const theme = ref("")

if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
    theme.value = "dark"
} else {
    theme.value = "light"
}

http.get("user")
    .then((res) => {
        traffic.value.traffic = res.data.traffic;
    })

function sign(captcha_token) {
    http.post("traffic", {
        "recaptcha": captcha_token
    })
        .then((res) => {
            traffic.value = res.data;

            content.value = `获得了 ${res.data.traffic} GB 流量！`;

            if (res.data.traffic === 0) {
                content.value = "没有获得流量～";
            }

            document.querySelector("#signinButton").click()

            // alert(content);
        })
        .finally(() => {
            http.get("user")
                .then((res) => {
                    traffic.value.traffic = res.data.traffic;
                })
                .finally(() => {
                    // refreshSign()
                });
        });
}

// function refreshSign() {
//   const date = new Date(traffic.value.last_sign_at)
//
//
//   if (traffic.value.last_sign_at) {
//     date.setDate(date.getDate() + 1)
//     // nextSignAt.value = date.toLocaleString()
//     // 算出差多少小时
//     const diff = date.getTime() - new Date().getTime()
//     const hours = Math.floor(diff / 1000 / 60 / 60)
//     const minutes = Math.floor(diff / 1000 / 60 % 60)
//     nextSignAt.value = `${hours} 小时 ${minutes} 分钟`
//
//   } else {
//     nextSignAt.value = null
//   }
// }
</script>
