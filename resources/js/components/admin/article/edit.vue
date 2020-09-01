<template>
    <page title="编辑文章" :breadcrumbs="{文章列表: '/articles'}">
        <el-card class="box-card"
                 v-loading="loading"
                 element-loading-text="拼命加载中"
                 element-loading-spinner="el-icon-loading"
                 element-loading-background="rgba(0, 0, 0, 0.50)">
            <div slot="header" class="clearfix tx-c">
                <h3>{{ editData.title }}</h3>
                <el-link :underline="false" type="info">发布于：{{ editData.created_at }}</el-link>
            </div>
            <edit-article-form :editData="editData" @save="doEdit"></edit-article-form>
        </el-card>
    </page>
</template>

<script>
    import EditArticleForm from './form';

    export default {
        name: "edit",
        data() {
            return {
                editData: {},
                loading: false,
            }
        },
        methods: {
            getDetail() {
                this.loading = true;
                api.get('/admin/articles/detail', {
                    id: this.$route.query.id
                }).then(data => {
                    this.editData = data.data;
                    this.loading = false;
                })
            },
            doEdit(form) {
                api.post('admin/articles/edit', form).then(() => {
                    this.$notify.success({'title': '提示', message: '编辑成功'});
                    this.$router.push('/articles');
                })
            }
        },
        created() {
            this.getDetail();
        },
        components: {
            EditArticleForm
        }
    }
</script>

<style scoped>

</style>
