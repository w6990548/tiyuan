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
import api from '../assets/api';

const router = new VueRouter({
	mode: 'history',
	base: '/admin',
	routes
})

// 全局挂载 router
window.router = router;
// 全局关在 api
window.api = api;

// JWT 用户权限校验，判断 TOKEN 是否在 localStorage 当中
router.beforeEach(({name}, from, next) => {
	// 获取 JWT Token
	if (localStorage.getItem('token')) {
		console.log('已经登录', localStorage.getItem('token'));
		next();
	} else {
		console.log('没有登录');
		if (name === 'login') {
			next();
		} else {
			next({name: 'login'});
		}
	}
});

new Vue({
	el: '#app',
	router,
	components: {App},
	template: '<App/>',
}).$mount('#app')
