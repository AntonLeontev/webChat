import './bootstrap';

import.meta.glob(["../images/**", "../fonts/**"]);


import { createApp } from "vue/dist/vue.esm-bundler.js";
import App from "./components/layouts/App.vue";
import Chat from "./components/chat/Chat.vue";

createApp({
    components: { App, Chat },
}).mount("#app");
