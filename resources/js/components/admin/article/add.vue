<template>
    <page title="发布文章" :breadcrumbs="{文章列表: '/articles'}">
        <el-card class="box-card">
            <div slot="header" class="clearfix tx-c">
                <h3>新建博文</h3>
            </div>
            <add-article-form @save="doAdd"></add-article-form>
        </el-card>
    </page>
</template>

<script>
    import AddArticleForm from './form';
    export default {
        name: "add",
        data() {
            return {

            }
        },
        methods: {
            doAdd(form) {
                api.post('admin/articles/create', form).then(() => {
                    this.$notify.success({'title': '提示', message: '发布成功'});
                    this.$router.push('/articles');
                })
            }
        },
        components: {
            AddArticleForm
        },
        created() {

        },
    }
</script>

<style scoped>

</style>
