<template>
    <page title="文章详情" :breadcrumbs="{文章列表: '/articles'}">
        <el-card class="box-card w-1000 mg-auto"
                 v-loading="loading"
                 element-loading-text="拼命加载中"
                 element-loading-spinner="el-icon-loading"
                 element-loading-background="rgba(0, 0, 0, 0.50)">
            <div slot="header" class="clearfix tx-c">
                <h3>{{detail.title}}</h3>
                <el-link :underline="false" type="info">发布于：{{detail.created_at}}</el-link>
            </div>
            <vmd-editor height="auto" mode="preview" :value="detail.content"></vmd-editor>
            <el-row class="p-l-20 p-r-20 m-t-20 m-b-20" v-if="detail.labels && detail.labels.length > 0">
                <i class="el-icon-coin m-r-10 fz-20 c-gray" style="vertical-align: middle;"></i>
                <el-tag type="warning" class="m-r-5 m-b-5" v-for="item in detail.labels" :key="item.id">{{ item.name }}</el-tag>
            </el-row>

        </el-card>
    </page>
</template>

<script>
    import VmdEditor from '../../../../assets/components/vmd-editor'
    export default {
        name: "detail",
        data() {
            return {
                preview: 'preview',
                detail: {},
                loading: false,
            }
        },
        methods: {
            getDetail() {
                this.loading = true;
                api.get('/admin/articles/detail', {
                    id: this.$route.query.id
                }).then(data => {
                    this.detail = data.data;
                    this.loading = false;
                })
            }
        },
        created() {
            this.getDetail();
        },
        components: {
            VmdEditor
        }
    }
</script>

<style scoped>

</style>
