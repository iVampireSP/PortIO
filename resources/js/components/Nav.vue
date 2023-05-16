<template>
    <div>
        <nav class="navbar navbar-expand-md shadow-sm bg-body">
            <div class="container">
                <router-link
                    class="navbar-brand text-auto"
                    to="/"
                    v-text="sitename"
                />

                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="切换导航"
                >
                    <span class="bi bi-list text-auto fs-1"></span>
                </button>

                <div
                    class="collapse navbar-collapse"
                    id="navbarSupportedContent"
                >
                    <MenuVue />
                </div>
            </div>
        </nav>
    </div>
    <div
        class="offcanvas offcanvas-end"
        tabindex="-1"
        id="offcanvasUserRight"
        aria-labelledby="offcanvasUserRightLabel"
    >
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasUserRightLabel">
                用户 ID
            </h5>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="Close"
            ></button>
        </div>
        <div class="offcanvas-body position-relative">
            <div class="mt-3">
                <h4>财务</h4>
                <div>余额: xxx 元</div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref } from "vue";
import MenuVue from "./Menu.vue";

// read window.Base.SiteName as sitename
const sitename = ref(window.Base.SiteName);
</script>
