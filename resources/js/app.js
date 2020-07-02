require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import Vuex from 'vuex'
Vue.use(Vuex);

import store from '../assets/store';
window.store = store;

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import '../assets/css/global.css'
Vue.use(ElementUI);

// component函数提交组件，第一个参数为组件的名称，第二个参数是一个注册组件的对象
import page from './components/page'
Vue.component('page', page)

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
// 全局挂载 api
window.api = api;
// 初始化接口根地址
window.baseApiUrl = '/api/admin/';
// 解决 ElementUI 导航栏中的 vue-router 在 3.0 版本以上重复点菜单报错问题
const originalPush = VueRouter.prototype.push
VueRouter.prototype.push = function push(location) {
	return originalPush.call(this, location).catch(err => err)
}

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
    store,
	components: {App},
	template: '<App/>',
}).$mount('#app')
