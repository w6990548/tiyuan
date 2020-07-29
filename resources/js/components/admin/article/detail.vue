<template>
    <page title="文章详情" :breadcrumbs="{文章列表: '/articles'}">
        <el-card class="box-card w-1000 mg-auto">
            <div slot="header" class="clearfix tx-c">
                <h3>{{data.title}}</h3>
                <el-link :underline="false" type="info">发布于：{{data.created_at}}</el-link>
            </div>
            <vmd-editor height="auto" mode="preview" v-model="data.content"></vmd-editor>
        </el-card>
    </page>
</template>

<script>
    import VmdEditor from '../../../../assets/components/vmd-editor'
    export default {
        name: "detail",
        data() {
            return {
                data: [],
            }
        },
        created() {
            this.data = this.$route.query;
            console.log(this.data);
        },
        components: {
            VmdEditor
        }
    }
</script>

<style scoped>

</style>
