<template>
    <page title="文章详情" :breadcrumbs="{文章列表: '/articles'}">
        <el-card class="box-card w-1000 mg-auto">
            <div slot="header" class="clearfix tx-c">
                <h3>{{detail.title}}</h3>
                <el-link :underline="false" type="info">发布于：{{detail.created_at}}</el-link>
            </div>
            <vmd-editor height="auto" mode="preview" :value="detail.content"></vmd-editor>
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
            }
        },
        methods: {
            getDetail() {
                api.get('/admin/articles/detail', {
                    id: this.$route.query.id
                }).then(data => {
                    this.detail = data.data;
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
