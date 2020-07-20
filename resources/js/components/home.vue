<template>
    <el-container>
        <el-header class="fl-el-header">
            <el-row type="flex" class="row-bg h-60" justify="space-between">
                <el-col :span="2">
                    <el-image style="width: 150px; height: 60px; line-height: 60px;"
                              fit="contain"
                              src="https://cdn.learnku.com/uploads/images/202007/08/16257/YuwONvpOgY.png!large">
                        <div slot="placeholder" class="image-slot">
                            加载中<span class="dot">...</span>
                        </div>
                    </el-image>
                </el-col>
                <el-col :span="3">
                    <el-dropdown @command="handleSelect" placement="bottom">
                        <span class="el-dropdown-link">
                            {{ username }}
                        </span>
                        <el-dropdown-menu slot="dropdown">
                            <el-dropdown-item command="refresh-rules" icon="el-icon-refresh">刷新权限</el-dropdown-item>
                            <el-dropdown-item command="logout" icon="el-icon-switch-button">退出登陆</el-dropdown-item>
                        </el-dropdown-menu>
                    </el-dropdown>
                </el-col>
            </el-row>
        </el-header>

        <el-container>
            <el-aside>
                <left-menu :menu="menu"></left-menu>
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
    import leftMenu from './leftmenu';

    export default {
        data() {
            return {
                menu: [],
            }
        },
        methods: {
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
                    case 'refresh-rules':
                        // 暂时先刷新页面
                        location.reload();
                        break;
                }
            },
            getLeftMenu() {
                api.get('/leftmenu').then(data => {
                    this.menu = data.data;
                })
            }
        },
        computed: {
            username() {
                let user = localStorage.getItem('user');
                return user ? JSON.parse(user).username : '';
            }
        },
        created() {
            this.getLeftMenu();
        },
        components: {
            leftMenu
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
        margin-right: 50px;
    }

    .el-aside {
        width: 200px !important;
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
