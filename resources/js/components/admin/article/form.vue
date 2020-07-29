<template>
    <el-row>
        <el-form :model="form" label-position="right" :rules="rules" ref="form">
            <el-form-item prop="title">
                <el-input v-model="form.title" maxlength="50" placeholder="标题" autocomplete="off"/>
            </el-form-item>
            <el-form-item prop="contents">
                <vmd-editor v-model="form.contents" height="600px" placeholder="请使用 markdown 语法"></vmd-editor>
            </el-form-item>
            <el-form-item>
                <el-collapse accordion class="bor-ra-5 p-l-20 p-r-20" style="border: 1px solid #EBEEF5;">
                    <el-collapse-item>
                        <template slot="title">
                            <i class="header-icon el-icon-info"></i>&nbsp;高级设置
                        </template>
                        <el-select
                                v-model="form.labels"
                                multiple
                                filterable
                                default-first-option
                                placeholder="请选择文章标签" style="width: 100%">
                            <el-option
                                    v-for="item in options"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.id">
                            </el-option>
                        </el-select>
                    </el-collapse-item>
                </el-collapse>
            </el-form-item>
            <el-form-item label-width="0">
                <el-button size="medium" type="primary" icon="el-icon-s-promotion" @click="save('form')">发布文章</el-button>
                <el-button size="medium" icon="el-icon-s-promotion" @click="">保存草稿</el-button>
            </el-form-item>
        </el-form>
    </el-row>
</template>

<script>
    import VmdEditor from '../../../../assets/components/vmd-editor';
    export default {
        name: "article-form",
        data() {
            return {
                form: {
                    title: '',
                    contents: '',
                    labels: [],
                },
                options: [{
                    id: 31,
                    name: 'HTML'
                }, {
                    id: 32,
                    name: 'CSS'
                }, {
                    id: 33,
                    name: 'JavaScript'
                }],
                rules: {
                    title: [
                        { required: true, message: '请输入标题', trigger: 'blur' },
                        { min: 5, max: 50, message: '长度在5-50个字符', trigger: 'blur' }
                    ],
                }
            }
        },
        methods: {
            save(form) {
                this.$refs[form].validate(valid => {
                    if(valid){
                        this.$emit('save', this.form);
                    }
                })
            }
        },
        components: {
            VmdEditor
        }
    }
</script>

<style scoped>

</style>
