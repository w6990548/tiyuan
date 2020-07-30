<template>
    <page title="编辑文章" :breadcrumbs="{文章列表: '/articles'}">
        <el-card class="box-card">
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
            }
        },
        methods: {
            getDetail() {
                api.get('/admin/articles/detail', {
                    id: this.$route.query.id
                }).then(data => {
                    this.editData = data.data;
                })
            },
            doEdit(form) {
                api.post('admin/articles/edit', form).then(() => {
                    this.$message.success('编辑成功');
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
