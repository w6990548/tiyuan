import Home from'../components/home';
import login from '../components/login.vue';
import welcome from "../components/welcome.vue";
import refresh from '../components/refresh.vue'
import ErrorPage from "../components/404.vue";

// 权限模块
import UserList from '../components/admin/auth/user/list';
import RoleList from '../components/admin/auth/role/list';
import PermissionList from '../components/admin/auth/Permission/list';


const routes = [
	{ path: '/', component: login, name: 'login' },
	{
		path: '/',
		component: Home,
		children: [
			{path: 'welcome', component: welcome, name: 'welcome'},
		]
	},
	{ path: '/welcome', name: welcome, component: welcome },
	// 刷新组件
	{ path: '/refresh', component: refresh, name: 'refresh' },
	// 拦截所有无效页面到404
	{ path: '/404', name: '404', component: ErrorPage },
	{ path: '*', redirect: '/404' },
	{ path: '/', redirect: '/login' },

	// 权限模块
	{
		path: '/',
		component: Home,
		children: [
			{path: 'users', component: UserList, name: 'UserList'},
			{path: 'roles', component: RoleList, name: 'RoleList'},
			{path: 'permissions', component: PermissionList, name: 'PermissionList'},
		]
	},

]
export default routes