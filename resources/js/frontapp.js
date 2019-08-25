
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

// window.Vue = require('vue');
// Vue.component('task', require('./components/Task.vue'));
// Vue.config.devtools = true;
// Vue.config.performance = true;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/*import App from './components/App.vue';
import Task from './components/Task.vue';
const app = new Vue({
  el: '#app',
  components: {
    Task
  },
  render: h => h(Task)
});
*/
import './bootstrap';
import Vue from 'vue'; // Importing Vue Library
import VueRouter from 'vue-router'; // importing Vue router library
import router from './routes2';
import { apiHost } from'./config';
import VueFlashMessage from 'vue-flash-message';
require('vue-flash-message/dist/vue-flash-message.min.css');
/*Vue.config.devtools = true;
Vue.config.performance = true;*/
window.Vue = Vue;
Vue.use(VueFlashMessage);

Vue.use(VueRouter);
axios.defaults.baseURL = apiHost;
//Vue.component('pagination', require('laravel-vue-pagination'));
const template = `
  <div
    v-for="(message, index) in storage"
    :key="index"
  >
    <div class="flash__message-content" v-html="message.content"></div>
  </div>
`;
Vue.use(VueFlashMessage, { template });


const app = new Vue({
    el: '#app',
    router,
    data: {
    },
});