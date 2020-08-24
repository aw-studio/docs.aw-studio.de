/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require("vue");

import animateScrollTo from "animated-scroll-to";

const app = new Vue({
    el: "#app",
    mounted() {
        require("./headline_animation.service");
        require("./mainscroll.service");
    }
});
