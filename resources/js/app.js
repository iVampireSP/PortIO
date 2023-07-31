import './bootstrap';

// import bootstrap css
import 'bootstrap/dist/css/bootstrap.min.css';
import "bootstrap-icons/font/bootstrap-icons.css";

import {createApp} from "vue";
import axios from "axios";
import VueAxios from "vue-axios";

import App from "./App.vue";

import router from "./plugins/router";

import "./utils/color-mode";

// if (CSS && 'paintWorklet' in CSS) CSS.paintWorklet.addModule('/assets/js/paint.js');

const app = createApp(App);

app.use(router, VueAxios, axios);

app.mount("#app");
