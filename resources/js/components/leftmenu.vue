<template>
    <el-menu class="el-menu-vertical-demo"
             background-color="#545c64"
             text-color="#fff"
             @select="change"
             active-text-color="#ffd04b"
             :unique-opened="true"
             :default-active="defaultActive"
    >
        <el-submenu v-for="item in menu" :index="item.name" :key="item.id">
            <template slot="title">
                <i class="el-icon-message"></i>{{ item.purview_name }}
            </template>
            <el-menu-item
                    :index="child.name.replace('api/admin', '')"
                    v-if="item.children.length > 0"
                    v-for="child in item.children"
                    :key="child.id">
                {{ child.purview_name }}
            </el-menu-item>
        </el-submenu>
    </el-menu>
</template>

<script>
    export default {
        props: {
            menu: {}
        },
        name: "leftmenu",
        data() {
            return {
                defaultActive: '', // 选中展开的菜单项,
            };
        },
        methods: {
            change(key, query) {
                if (key !== this.$route.path) {
                    router.push({path: key, query: query})
                }
            },
        },
        created() {
            // 刷新页面展开当前页面导航
            this.defaultActive = this.$route.path;
        },
    }
</script>

<style scoped>

</style>
