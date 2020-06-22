<template>
	<el-card class="box-card" center>
		<el-form ref="form" :model="form" :rules="rules">
			<el-form-item prop="username">
				<el-input v-model="form.username" placeholder="账号"></el-input>
			</el-form-item>
			<el-form-item prop="password">
				<el-input v-model="form.password" placeholder="密码"></el-input>
			</el-form-item>
			<el-form-item prop="captcha" :inline="true">
				<el-col :span="13">
					<el-input v-model="form.captcha" placeholder="验证码"></el-input>
				</el-col>
				<el-col :span="10" style="height: 40px;margin-left: 14px;">
					<img :src="src" @click="captcha()" alt=""/>
				</el-col>
			</el-form-item>
			<el-form-item>
				<el-button type="primary" @click="login('form')">登陆</el-button>
			</el-form-item>
			<el-form-item>
				<el-button type="primary" @click="info()">获取用户信息</el-button>
			</el-form-item>
			<el-form-item>
				<el-button type="primary" @click="logout()">退出登录</el-button>
			</el-form-item>
			<el-form-item>
				<el-button type="primary" @click="captcha()">获取图片验证码</el-button>
			</el-form-item>
		</el-form>
	</el-card>
</template>
<script>
	export default {
		data() {
			return {
				form: {
					username: '',
					password: '',
					captcha: '',
					captcha_key: '', // 验证码缓存key
				},
				src: '', // 验证码地址
				rules: {
					username: [
						{ required: true, message: '请输入账号', trigger: 'blur' },
						{ min: 5, max: 10, message: '长度在 5-10 个字符', trigger: 'blur'}
					],
					password: [
						{ required: true, message: '请输入密码', trigger: 'blur' },
						{ min: 6, max: 18, message: '长度在 6-18 个字符', trigger: 'blur' }
					],
					captcha: [
						{ required: true, message: '请输入验证码', trigger: 'blur' },
					]
				}
			}
		},
		methods: {
			login(form) {
				this.$refs[form].validate((valid) => {
					if (valid) {
						api.get('/api/admin/login', this.form).then(data => {
							if (data.code === 0) {
								localStorage.setItem('token', 'Bearer' + data.data.token);
								this.$message.success('登录成功');
								this.$router.push({
									path: 'welcome'
								});
							}
							console.log('返回数据', data);
						})
					} else {
						return false;
					}
				})
			},
			info() {
				api.get('/api/admin/user').then(data => {
					if (data.code === 0) {
						console.log(data);
						this.$message.success('获取用户信息成功');
					}
				})
			},
			logout() {
				api.post('/api/admin/logout').then(data => {
					if (data.code === 0) {
						localStorage.removeItem('token');
						this.$message.success('退出成功');
					}
				})
			},
			captcha() {
				api.post('/api/admin/captchas', this.form).then(data => {
					if (data.code === 0) {
						console.log(data);
						this.src = data.data.captcha_image;
						this.form.captcha_key = data.data.captcha_key
					}
				})
			}
		},
		created(){
			this.captcha();
		},
	}
</script>
<style>
	.text {
		font-size: 14px;
	}

	.item {
		padding: 18px 0;
	}

	.box-card {
		width: 400px;
		margin: auto;
	}

	.el-button {
		width: 100%;
	}
</style>
