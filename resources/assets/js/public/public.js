require('./bootstrap');

import Vue from "vue";
import VeeValidate from "vee-validate";
import VueMask from "v-mask";

Vue.use(VeeValidate);
Vue.use(VueMask);

Vue.component('donation-form', require('./components/donation-form.vue'));

const app = new Vue({
    el: '#app'
});
