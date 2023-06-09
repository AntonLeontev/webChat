import './bootstrap';

import.meta.glob(["../images/**", "../fonts/**"]);


import { createApp } from "vue/dist/vue.esm-bundler.js";
import App from "./components/layouts/App.vue";
import Chat from "./components/chat/Chat.vue";

createApp({
    components: { App, Chat },
})
    .directive("outside", {
        beforeMount: (el, binding) => {
            el.clickOutsideEvent = (event) => {
				if (!(el == event.target || el.contains(event.target))) {
                    binding.value();
                }
            };
            document.addEventListener("click", el.clickOutsideEvent);
        },
        unmounted: (el) => {
            document.removeEventListener("click", el.clickOutsideEvent);
        },
    })
    .mount("#app");
