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
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="切换导航"
                    class="navbar-toggler"
                    data-bs-target="#navbarSupportedContent"
                    data-bs-toggle="collapse"
                    type="button"
                >
                    <span class="bi bi-list text-auto fs-1"></span>
                </button>

                <div
                    id="navbarSupportedContent"
                    class="collapse navbar-collapse"
                >
                    <MenuVue/>
                </div>
            </div>
        </nav>
    </div>
    <div
        id="offcanvasUserRight"
        aria-labelledby="offcanvasUserRightLabel"
        class="offcanvas offcanvas-end"
        tabindex="-1"
    >
        <div class="offcanvas-header">
            <h5 id="offcanvasUserRightLabel" class="offcanvas-title">
                用户 ID
            </h5>
            <button
                aria-label="Close"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                type="button"
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
import {ref} from "vue";
import MenuVue from "./Menu.vue";

// read window.Base.SiteName as sitename
const sitename = ref(window.Base.SiteName);
</script>
