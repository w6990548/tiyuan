<template>
    <el-container>
        <el-header class="fl-el-header">
            <el-row type="flex" class="row-bg h-60" justify="space-between">
                <el-col :span="2" class="tx-c">
                    <el-image class="w-150 h-60" :src="settings.admin_logo" fit="contain">
                        <div slot="error" class="image-slot">
                            <i class="el-icon-picture-outline"></i>
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
                <el-menu class="el-menu-vertical-demo"
                         background-color="#545c64"
                         text-color="#fff"
                         @select="change"
                         :default-active="defaultActive"
                         active-text-color="#ffd04b"
                         :unique-opened="true">
                    <left-menu :menuData="menus"></left-menu>
                </el-menu>
            </el-aside>
            <el-container>
                <el-main class="fl-el-main">
                    <el-col :span="24">
                        <router-view></router-view>
                    </el-col>
                </el-main>
                <el-footer>
                    <div class="p-t-20">
                        <el-link type="info" target="_blank" :href="settings.icp_url">{{ settings.icp_code }}</el-link>
                        <el-link type="info" :underline="false">&nbsp;|&nbsp;</el-link>
                        <el-popover
                                placement="top-start"
                                width="200"
                                trigger="hover">
                            <el-image :src="settings.site_qr_code" fit="scale-down"/>
                            <el-link type="info" slot="reference">联系站长</el-link>
                        </el-popover>
                    </div>
                </el-footer>
            </el-container>
        </el-container>
    </el-container>

</template>

<script>
    import leftMenu from './leftmenu';

    export default {
        data() {
            return {
                menus: [],
                settings: [],
                centerDialogVisible: false,
                defaultActive: '', // 选中展开的菜单项,
            }
        },
        methods: {
            logout() {
                api.post('admin/logout').then(() => {
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    this.$notify.success({
                        title: '成功',
                        message: '退出成功',
                    });
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
                api.get('admin/leftmenus').then(data => {
                    localStorage.removeItem('leftMenu');
                    this.menus = data.data.menus;
                    this.settings = data.data.settings;
                    localStorage.setItem('leftMenu', JSON.stringify(data.data.slugs));
                })
            },
            change(key, query) {
                if (key !== this.$route.path) {
                    router.push({path: key, query: query})
                }
            },
        },
        computed: {
            username() {
                let user = localStorage.getItem('user');
                return user ? JSON.parse(user).username : '';
            }
        },
        created() {
            this.getLeftMenu();
            // 刷新页面展开当前页面导航
            this.defaultActive = this.$route.path;
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
