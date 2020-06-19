<template>
	<el-container>
		<el-header>
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
				         active-text-color="#ffd04b"
				         :unique-opened="true">
					<el-submenu index="1">
						<template slot="title"><i class="el-icon-message"></i>导航一</template>
						<el-menu-item index="1-1">选项1</el-menu-item>
						<el-menu-item index="1-2">选项2</el-menu-item>
						<el-menu-item index="1-3">选项3</el-menu-item>
						<el-submenu index="1-4">
							<template slot="title">选项4</template>
							<el-menu-item index="1-4-1">选项4-1</el-menu-item>
						</el-submenu>
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
				<el-main>
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
				tableData: Array(20).fill(item)
			};
		},
		methods: {
			logout() {
				api.post('/api/admin/logout').then(() => {
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
			api.get('/api/admin/user').then(data => {
				if (data.code === 200) {
					console.log(data);
					this.$message.success('获取用户信息成功');
				}
			})
		}
	};
</script>

<style>
	.el-header, .el-footer {
		background-color: rgb(84, 92, 100);
		font-size: 14px;
		color: rgb(255, 255, 255);
		line-height: 60px;
	}
	.el-header {
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

	.el-main {
		color: #333;
		text-align: center;
		line-height: 160px;
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

</style>