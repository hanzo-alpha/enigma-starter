// Load plugins
import _ from 'lodash';
import helper from "./helper";
import axios from "axios";
import * as Popper from "@popperjs/core";
import dom from "@left4code/tw-starter/dist/js/dom";
import Alpine from 'alpinejs'

// Set plugins globally
window._ = _;
window.helper = helper;
window.axios = axios;
window.Popper = Popper;
window.$ = dom;
window.Alpine = Alpine

Alpine.start()
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// CSRF token
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    console.error(
        "CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token"
    );
}
