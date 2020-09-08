<template>
    <div class="el-fl-background">
        <el-card class="box-card" center>
            <el-form ref="form" :model="form" :rules="rules">
                <el-form-item prop="username">
                    <el-input v-model="form.username" placeholder="账号" class="el-fl-input"></el-input>
                </el-form-item>
                <el-form-item prop="password">
                    <el-input v-model="form.password" placeholder="密码" class="el-fl-input"></el-input>
                </el-form-item>
                <el-form-item prop="captcha" :inline="true">
                    <el-col :span="13">
                        <el-input v-model="form.captcha" placeholder="验证码" class="el-fl-input"></el-input>
                    </el-col>
                    <el-col :span="10" style="height: 40px;margin-left: 14px;">
                        <img :src="src" @click="captcha()" style="border-radius: 4px; opacity: 0.3" alt=""/>
                    </el-col>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" class="el-fl-button" @click="login('form')">登陆</el-button>
                </el-form-item>
            </el-form>
        </el-card>
    </div>
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
                    {required: true, message: '请输入账号', trigger: 'blur'},
                    {min: 5, max: 10, message: '长度在 5-10 个字符', trigger: 'blur'}
                ],
                password: [
                    {required: true, message: '请输入密码', trigger: 'blur'},
                    {min: 6, max: 18, message: '长度在 6-18 个字符', trigger: 'blur'}
                ],
                captcha: [
                    {required: true, message: '请输入验证码', trigger: 'blur'},
                ]
            }
        }
    },
    methods: {
        login(form) {
            this.$refs[form].validate((valid) => {
                if (valid) {
                    api.get('admin/login', this.form).then(data => {
                        if (data.code === 0) {
                            localStorage.setItem('token', 'Bearer' + data.data.token);
                            localStorage.setItem('user', JSON.stringify(data.data.user_info));
                            this.$notify.success({
                                title: '成功',
                                message: '登陆成功'
                            });
                            router.push({
                                path: 'welcome'
                            });
                        }
                    })
                } else {
                    return false;
                }
            })
        },
        captcha() {
            api.post('admin/captchas', this.form).then(data => {
                if (data.code === 0) {
                    this.src = data.data.captcha_image;
                    this.form.captcha_key = data.data.captcha_key
                }
            })
        }
    },
    created() {
        this.captcha();
    },
}
</script>
<style scoped>
.text {
    font-size: 14px;
}

.item {
    padding: 18px 0;
}

.box-card {
    width: 400px;
    position: absolute;
    z-index: 3;
    top: 50%;
    left: 50%;
    margin: -210px auto auto -650px;
    padding: 35px 0 0 0;
}

.el-card {
    background: rgba(0, 0, 0, 0);
    border: none;
}

.el-button {
    width: 100%;
}

.el-fl-background {
    width: 100%;
    height: 100%;
    background-image: url(../images/bg.jpg);
    background-repeat: no-repeat;
    background-size: cover;
    overflow: hidden;
}

.el-fl-button {
    background: rgba(0, 0, 0, 0.0);
    border-color: unset;
    border: 1px solid #DCDFE6;
    color: #f5f7fa;
}
</style>
<style>
.el-fl-input input.el-input__inner {
    background: rgba(0, 0, 0, 0);
}

.el-fl-input input::-webkit-input-placeholder {
    color: #f5f7fa;
}

.el-fl-input input::-moz-input-placeholder {
    color: #f5f7fa;
}

.el-fl-input input::-ms-input-placeholder {
    color: #f5f7fa;
}
</style>
