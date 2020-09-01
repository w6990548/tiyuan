<template>
    <div>
        <template v-for="item in menuData">
            <el-submenu :index="item.name" v-if="item.children">
                <template slot="title">
                    <icon-svg class="al-icon" :icon-class="item.icon" :size="24"/>
                    <span slot="title">{{ item.alias_name }}</span>
                </template>
                <leftmenu :menuData="item.children"></leftmenu>
            </el-submenu>
            <el-menu-item :index="item.url ? item.url.replace('admin', '') : item.url"
                          :url="item.url ? item.url.replace('admin', '') : item.url" v-else>
                <icon-svg class="al-icon" :icon-class="item.icon" :size="24"/>
                <span slot="title">{{ item.alias_name }}</span>
            </el-menu-item>
        </template>
    </div>
</template>

<script>
    export default {
        props: {
            menuData: {},
        },
        name: "leftmenu",
        data() {
            return {

            };
        },
        methods: {

        },
        created() {

        },
    }
</script>

<style scoped>

</style>
