/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
require('./bootstrap');
Vue.use(VueRouter);
Vue.use(VueResource);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#csrf-token').getAttribute('content');

import OfficialsData   from './components/official/officials_data.vue'
import OfficialsReport from './components/official/official_report.vue'
import ImportData      from './components/official/import_data.vue'

window.router = new VueRouter({
  routes: [
    { 
      path: '/', 
      redirect: '/officials/data' 
    },
    { 
      path: '/officials/data', 
      component: OfficialsData
    },
    {
	    path: '/officials/report',
	    component: OfficialsReport
    },
    {
      path: '/officials/import',
      component: ImportData
    }
  ]
});

const app = new Vue({
  	router
}).$mount('#app');

