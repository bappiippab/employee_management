/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueAxios from 'vue-axios';
import axios from 'axios';
import VueSweetalert2 from 'vue-sweetalert2';
Vue.use(VueSweetalert2);
Vue.use(VueAxios, axios);
Vue.component('paginate', require('vuejs-paginate'));

Vue.component('company-index-component', require('./components/CompanyIndexComponent.vue').default);
Vue.component('employee-index-component', require('./components/EmployeeIndexComponent.vue').default);

const app = new Vue({
    el: '#app'
});