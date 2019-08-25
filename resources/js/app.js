/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import './bootstrap';
import Vue from 'vue'; // Importing Vue Library
import VueRouter from 'vue-router'; // importing Vue router library
import router from './routes';
import { apiHost, getHeader,redirect,currentLoginStatus,userBroweserHost} from'./config';
import adminroute from './adminroutes';
import VueFlashMessage from 'vue-flash-message';
import interceptor from './interceptor.js';
import Vuelidate from 'vuelidate';
import UserApp from './UserApp.vue';
import AdminApp from './AdminApp.vue';
import VueResource from 'vue-resource';
import moment from 'moment';
import VeeValidate from 'vee-validate';
import CustomValidator from './custom_validator';
import VModal from 'vue-js-modal';
import VueSweetAlert from 'vue-sweetalert';
import Toaster from 'v-toaster';
import QrcodeVue from 'qrcode.vue';
// You need a specific loader for CSS files like https://github.com/webpack/css-loader
import 'v-toaster/dist/v-toaster.css'
// optional set default imeout, the default is 10000 (10 seconds).
Vue.use(Toaster, {timeout: 5000})
require('vue-flash-message/dist/vue-flash-message.min.css');
/*Vue.config.devtools = true;
Vue.config.performance = true;*/
window.Vue = Vue;
Vue.use(VueFlashMessage);
Vue.use(interceptor);
Vue.use(VueRouter);
Vue.use(Vuelidate);
Vue.use(VueResource);
/*window.Vue = require('vue');*/
Vue.use(VueSweetAlert);
//window.$ = window.jQuery = require('jquery');
Vue.use(VeeValidate);
let prefix = '';
import { Validator } from 'vee-validate';
const dictionary = {
  en: {
    attributes: {
      current_pwd: 'Current Password'
    }
  },moment
};


Validator.localize(dictionary);
/*const template = `
  <div
    v-for="(message, index) in storage"
    :key="index"
  >
    <div class="flash__message-content" v-html="message.content"></div>
  </div>
`;

Vue.use(VueFlashMessage, { template });*/
Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('YYYY/MM/DD');
    }
});

Vue.mixin({
  created: function () {
    var myOption = this.$options.myOption
    if (myOption) {
    }
  }
})


// admin vue
if(window.location.href.indexOf("admin") > -1) {
    if(currentLoginStatus)
        prefix = '/admin/';
    axios.defaults.baseURL = apiHost+prefix;
    const app = new Vue({
        router : adminroute,
        template: '<AdminApp/>',
        components: { AdminApp },
    }).$mount('#app');
}else{
    if(currentLoginStatus)
        prefix = '/user/';
    axios.defaults.baseURL = apiHost+prefix;
    const app = new Vue({
        router,
        template: '<UserApp/>',
        components: { UserApp },
        props: ['forbidden'],
    }).$mount('#app');
}