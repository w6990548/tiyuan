require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import Vuex from 'vuex'
Vue.use(Vuex);

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import '../assets/css/global.css'
Vue.use(ElementUI);

// v-md-editor 编辑器
import VueMarkdownEditor from '@kangc/v-md-editor';
import '@kangc/v-md-editor/lib/style/base-editor.css'
// v-md-editor 编辑器 vuepress 主题
import vuepressTheme from '@kangc/v-md-editor/lib/theme/vuepress';
// v-md-editor 编辑器 提示信息插件
import createTipPlugin from '@kangc/v-md-editor/lib/plugins/tip/index'
VueMarkdownEditor.use(createTipPlugin);
// v-md-editor 编辑器 多种代码高亮 git+go+java+json+php+python+regex+rust+sql
import '../assets/prism/prism'

VueMarkdownEditor.use(vuepressTheme);
Vue.use(VueMarkdownEditor);

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
window.baseApiUrl = '/api/';
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
	components: {App},
	template: '<App/>',
}).$mount('#app')
