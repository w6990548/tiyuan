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
                                    v-for="item in labelOptions"
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
                <el-button size="medium" icon="el-icon-s-promotion" @click="draft('form')">保存草稿</el-button>
            </el-form-item>
        </el-form>
    </el-row>
</template>

<script>
    import VmdEditor from '../../../../assets/components/vmd-editor';
    export default {
        name: "article-form",
        props: {
            editData: {
                type: Object,
                default() {
                    return {}
                }
            },
        },
        data() {
            return {
                form: {
                    title: '',
                    contents: '',
                    labels: [],
                },
                labelOptions: [],
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
            },
            draft(form) {
                console.log('暂时不能保存草稿哦');
            },
            initForm(editData) {
                this.form.id = editData.id;
                this.form.title = editData.title;
                this.form.contents = editData.content ? editData.contents : '';
                editData.labels.forEach(item => {
                    this.form.labels.push(item.id);
                })
            },
            getLables() {
                api.get('/admin/labels').then(data => {
                    this.labelOptions = data.data;
                })
            }
        },
        created() {
            this.getLables();
        },
        components: {
            VmdEditor
        },
        watch: {
            editData() {
                this.initForm(this.editData);
            }
        }
    }
</script>

<style scoped>

</style>
