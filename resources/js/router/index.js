import Home from'../components/home';
import login from '../components/login.vue';
import welcome from "../components/welcome.vue";
import ErrorPage from "../components/404.vue";

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
	{ path: '/404', name: '404', component: ErrorPage },
	{ path: '*', redirect: '/404' },
	{ path: '/', redirect: '/login' },
]
export default routes