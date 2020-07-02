<template>
	<el-container>
		<el-header class="fl-el-header">
			<el-dropdown @command="handleSelect">
				<span class="el-dropdown-link">
					一个人的江湖
				</span>
				<el-dropdown-menu slot="dropdown">
					<el-dropdown-item command="logout">退出登陆</el-dropdown-item>
				</el-dropdown-menu>
			</el-dropdown>
		</el-header>

		<el-container>
			<el-aside>
				<el-menu class="el-menu-vertical-demo"
				         background-color="#545c64"
				         text-color="#fff"
						 @select="change"
				         active-text-color="#ffd04b"
				         :unique-opened="true" :default-active="defaultActive">
					<el-submenu index="1">
						<template slot="title"><i class="el-icon-message"></i>权限</template>
						<el-menu-item index="/users">用户管理</el-menu-item>
						<el-menu-item index="/roles">角色管理</el-menu-item>
						<el-menu-item index="/permissions">权限管理</el-menu-item>
					</el-submenu>
					<el-submenu index="2">
						<template slot="title"><i class="el-icon-message"></i>导航一</template>
						<el-menu-item index="2-1">选项1</el-menu-item>
						<el-menu-item index="2-2">选项2</el-menu-item>
						<el-menu-item index="2-3">选项3</el-menu-item>
						<el-submenu index="2-4">
							<template slot="title">选项4</template>
							<el-menu-item index="2-4-1">选项4-1</el-menu-item>
						</el-submenu>
					</el-submenu>
				</el-menu>
			</el-aside>
			<el-container>
				<el-main class="fl-el-main">
					<el-col :span="24">
						<router-view></router-view>
					</el-col>
				</el-main>
				<el-footer>Footer</el-footer>
			</el-container>
		</el-container>
	</el-container>
</template>

<script>
	export default {
		data() {
			const item = {
				date: '2016-05-02',
				name: '王小虎',
				address: '上海市普陀区金沙江路 1518 弄'
			};
			return {
				defaultActive: '', // 选中展开的菜单项
			};
		},
		methods: {
			change(key, query){
				if (key !== this.$route.path) {
					router.push({path: key, query: query})
				} else {
					router.replace({
                        path: '/refresh',
                        query: {
                            name: this.$route.name,
                            query: query
                        }
					})
				}
			},
			logout() {
				api.post('/logout').then(() => {
					localStorage.removeItem('token');
					this.$message.success('退出成功');
					this.$router.push({
						path: '/'
					});
				}).catch(() => {

				}).finally(() => {

				})
			},
			handleSelect(key) {
				switch (key) {
					case 'logout':
						this.logout()
						break;
				}
			}
		},
		created() {
			// api.get('/user').then(data => {
			// 	if (data.code === 0) {
			// 		this.$message.success('获取用户信息成功');
			// 	}
			// });
			// 刷新页面展开当前页面导航
			this.defaultActive = this.$route.path;
		}
	};
</script>

<style>
	.fl-el-header, .el-footer {
		background-color: rgb(84, 92, 100);
		font-size: 14px;
		color: rgb(255, 255, 255);
		line-height: 60px;
	}
	.fl-el-header {
		text-align: right;
	}
	.el-footer {
		text-align: center;
	}

	.el-dropdown {
		color: rgb(255, 255, 255);
		margin-right: 20px;
	}

	.el-aside {
		width: 200px!important;
		background-color: rgb(84, 92, 100);
		color: rgb(255, 255, 255);
	}

	.fl-el-main {
		padding: 30px 30px 10px 30px;
	}

	.el-menu {
		border-right: none;
	}

	body > .el-container {
		margin-bottom: 40px;
	}

	html, body, #app, .el-container.is-vertical {
		height: 100%;
	}

	/* 分页 */
	.el-pagination.is-background .btn-prev {
		margin-left: 0px;
	}
	.el-pagination.is-background .btn-next {
		margin-right: 0px;
	}

</style>
