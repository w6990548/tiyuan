import Home from'../components/home';
import login from '../components/login.vue';
import welcome from "../components/welcome.vue";
import ErrorPage from "../components/404.vue";

// 权限模块
import UserList from '../components/admin/auth/user/list';
import RoleList from '../components/admin/auth/role/list';
import PermissionList from '../components/admin/auth/Permission/list';

// 设置模块
import SystemSettingForm from '../components/admin/setting/system-setting-form';

// 文章模块
import ArticleList from '../components/admin/article/list';
import ArticleAdd from '../components/admin/article/add';
import ArticleEdit from '../components/admin/article/edit';
import ArticleDetail from '../components/admin/article/detail';

// 标签模块
import ArticleLabelList from '../components/admin/articleLabel/list';


const routes = [
	{ path: '/login', component: login, name: 'login' },
	{
		path: '/',
		component: Home,
		children: [
			{path: 'welcome', component: welcome, name: 'welcome'},
            {path: '404', name: '404', component: ErrorPage},
		]
	},
	// 拦截所有无效页面到404
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

    // 设置模块
    {
        path: '/',
        component: Home,
        children: [
            {path: 'system-setting', component: SystemSettingForm, name: 'SystemSettingForm'}
        ]
    },

    // 文章模块
    {
        path: '/',
        component: Home,
        children: [
            {path: 'articles', component: ArticleList, name: 'ArticleList'},
            {path: 'articles/create', component: ArticleAdd, name: 'ArticleAdd'},
            {path: 'articles/edit', component: ArticleEdit, name: 'ArticleEdit'},
            {path: 'articles/detail', component: ArticleDetail, name: 'ArticleDetail'}
        ]
    },

    // 标签模块
    {
        path: '/',
        component: Home,
        children: [
            {path: 'labels', component: ArticleLabelList, name: 'ArticleLabelList'},
        ]
    },

]
export default routes
