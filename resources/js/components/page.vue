<template>
    <el-container>
        <el-header style="height:20px;padding: 0">
            <el-breadcrumb separator-class="el-icon-arrow-right">
                <el-breadcrumb-item
                        v-for="item in breadcrumbs"
                        :key="item.path"
                        @click.native="toPath(item.path)"
                        style="cursor:pointer;">
                    <router-link :to="item.path">{{item.title}}</router-link>
                </el-breadcrumb-item>
                <el-breadcrumb-item>{{title}}</el-breadcrumb-item>
            </el-breadcrumb>
        </el-header>
        <el-main>
            <slot/>
        </el-main>
    </el-container>
</template>

<script>
    export default {
        name: "page",
        props: {
            title: {type: String, required: true},
            /**
             * 数组元素 可以是一个字符串, 也可以是一个对象, 对象时包含字段为name和path
             */
            breadcrumbs: {type: Array, default: () => [] },
        },
        methods: {
            toPath(path){
                router.push(path);
            }
        },
    }
</script>

<style scoped>

</style>
