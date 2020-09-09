<template>
    <el-container>
        <el-aside>
            <el-image class="el-fl-logo" :src="settings.admin_logo" fit="contain">
                <div slot="error" class="image-slot">
                    <i class="el-icon-picture-outline"></i>
                </div>
            </el-image>
            <el-menu class="el-menu-vertical-demo"
                     background-color="#313a46"
                     text-color="#8391a2"
                     @select="change"
                     :default-active="defaultActive"
                     active-text-color="#fff"
                     :unique-opened="true">
                <left-menu :menuData="menus"></left-menu>
            </el-menu>
        </el-aside>
        <el-container>
            <el-header class="fl-el-header">
                <el-row type="flex" justify="space-between">
                    <el-dropdown @command="handleSelect" placement="bottom">
                                <span class="el-dropdown-link">
                                    {{ username }}
                                </span>
                        <el-dropdown-menu slot="dropdown">
                            <el-dropdown-item command="refresh-rules" icon="el-icon-refresh">刷新权限</el-dropdown-item>
                            <el-dropdown-item command="logout" icon="el-icon-switch-button">退出登陆</el-dropdown-item>
                        </el-dropdown-menu>
                    </el-dropdown>
                </el-row>
            </el-header>
            <el-main class="fl-el-main">
                <el-col :span="24">
                    <router-view></router-view>
                </el-col>
            </el-main>
            <!--<el-footer>
                    2018 - 2020 © Hyper - Coderthemes.com
                    <el-link type="info" target="_blank" :href="settings.icp_url">{{ settings.icp_code }}</el-link>
                    <el-link type="info" :underline="false">&nbsp;|&nbsp;</el-link>
                    <el-popover
                        placement="top-start"
                        width="200"
                        trigger="hover">
                        <el-image :src="settings.site_qr_code" fit="scale-down"/>
                        <el-link type="info" slot="reference">联系站长</el-link>
                    </el-popover>
            </el-footer>-->
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
body {
    background-color: #fafbfe !important;
}

.fl-el-header {
    background-color: #fff;
    box-shadow: 0 0 10px 0 rgba(0, 0, 0, .1);
    -webkit-box-shadow: 0 0 10px 0 rgba(0, 0, 0, .1);
    border: 1px solid #eef2f7;
    font-size: 14px;
    color: #98a6ad;
}

.el-footer {
    line-height: 60px;
    border: 1px solid #eef2f7;
    font-size: 14px;
    color: #98a6ad;
    text-align: center;
}

.el-aside {
    background-color: #313a46;
    color: rgb(255, 255, 255);
}

.fl-el-main .el-main {
    -webkit-box-shadow: 0 0 10px 0 rgba(0, 0, 0, .1);
    box-shadow: 0 0 10px 0 rgba(0, 0, 0, .1);
    margin-bottom: 24px;
    border: 1px solid #eef2f7;
    border-radius: 4px;
    margin-top: 20px;
    background-color: #fff;
}

.fl-el-header {
    text-align: right;
}

.el-dropdown {
    position: absolute;
    right: 50px;
    line-height: 60px;
}

.el-aside {
    width: 260px !important;
    background-color: #313a46;
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

html, body, #app, .el-container {
    height: 100%;
    position: relative;
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

.el-fl-logo {
    text-align: center;
    height: 60px;
}

</style>
