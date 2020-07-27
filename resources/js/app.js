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

// v-md-editor 编辑器 预览组件
import VMdPreview from '@kangc/v-md-editor/lib/preview';
import '@kangc/v-md-editor/lib/style/preview.css';
// v-md-editor 编辑器 vuepress 主题
import vuepressTheme from '@kangc/v-md-editor/lib/theme/vuepress';
// v-md-editor 编辑器 emoji 表情
import createEmojiPlugin from '@kangc/v-md-editor/lib/plugins/emoji/index';
// v-md-editor 编辑器 TodoList 任务列表
import createTodoListPlugin from '@kangc/v-md-editor/lib/plugins/todo-list/index';
// v-md-editor 编辑器 代码行号
// import createLineNumbertPlugin from '@kangc/v-md-editor/lib/plugins/line-number/index';
// v-md-editor 编辑器 高亮代码行 使用方式：``` js {1,3} ```
// import createHighlightLinesPlugin from '@kangc/v-md-editor/lib/plugins/highlight-lines/index';
// v-md-editor 编辑器 快捷复制代码
import createCopyCodePlugin from '@kangc/v-md-editor/lib/plugins/copy-code/index';

// v-md-editor 编辑器 多种代码高亮 git+go+java+json+php+python+regex+rust+sql
import '../assets/prism/prism'
VueMarkdownEditor.use(vuepressTheme);
VueMarkdownEditor.use(createEmojiPlugin());
// 此处可带参数：类型: String，默认值：#3eaf7c，checkbox的颜色，例如{ color: 'red' }
VueMarkdownEditor.use(createTodoListPlugin());
// VueMarkdownEditor.use(createLineNumbertPlugin());
// VueMarkdownEditor.use(createHighlightLinesPlugin());
VueMarkdownEditor.use(createCopyCodePlugin());
Vue.use(VueMarkdownEditor);
VMdPreview.use(vuepressTheme);
Vue.use(VMdPreview);

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
