require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(ElementUI);

import App from './App.vue';
import routes from './router/index.js';

const router = new VueRouter({
	mode: 'history',
	routes
})

const app = new Vue({
    el: '#app',
	router,
	render: h => h(App)
});
