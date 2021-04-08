/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');

import Vue from 'vue';
window.Vue = require('vue').default;
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


// import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
import Multiselect from 'vue-multiselect';
import BootstrapVue from 'bootstrap-vue';

Vue.component('multiselect', Multiselect);
Vue.use(BootstrapVue);
// Vue.component('VueCtkDateTimePicker', VueCtkDateTimePicker);
