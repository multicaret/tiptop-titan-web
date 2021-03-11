/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


import Multiselect from 'vue-multiselect';
import VueTruncate from 'vue-truncate-filter';
import VueScrollTo from 'vue-scrollto';

Vue.component('multiselect', Multiselect);

Vue.use(VueTruncate);
Vue.use(VueScrollTo, '$vueScroll');

const moment = require('moment');
// require('moment/locale/en');
// require('moment/locale/ar');
Vue.use(require('vue-moment'), {
    moment
});
