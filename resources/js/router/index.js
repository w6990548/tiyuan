import hello from '../components/ExampleComponent.vue';
import Login from '../components/login';

const routes = [
	// {path: '/login', component: Login, name: 'Login'},
	{
		path: '/',
		component: hello,
		children: [
			{path: '/login', component: hello, name: 'SettlementPlatfroms'},
		]
	},
]
export default routes