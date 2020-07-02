<template>
    <el-row>
        <el-form :model="form" label-position="right" :label-width="formLabelWidth" :rules="rules" ref="form">
            <el-form-item label="角色名称" prop="name">
                <el-input v-model="form.name" class="w-300" size="medium" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="权限列表">
                <el-tree class="fl-el-tree"
                        :data="data"
                         ref="tree"
                        show-checkbox
                        node-key="id"
                        default-expand-all
                        :props="defaultProps">
                </el-tree>
            </el-form-item>
            <el-form-item label-width="0" align="center">
                <el-button size="medium" @click="cancel">取 消</el-button>
                <el-button size="medium" type="primary" @click="save('form')">保 存</el-button>
            </el-form-item>
        </el-form>
    </el-row>
</template>

<script>
    export default {
        props: {

        },
        data() {
            return {
                form: {
                    name: '',
                    keys: [],
                },
                // data: [],
                formLabelWidth: '90px',
                rules: {
                    name: [
                        { required: true, message: '请输入角色名称', trigger: 'blur' },
                        { min: 2, max: 15, message: '长度在2-15个字符', trigger: 'blur' }
                    ]
                },
                data: [],
                defaultProps: {
                    children: 'children',
                    label: 'purview_name'
                }
            };
        },
        methods: {
            initForm() {

            },
            cancel() {
                this.$emit('cancel');
            },
            getCheckedNodes() {
                console.log(this.$refs.tree.getCheckedNodes());
            },
            getCheckedKeys() {
                console.log(this.$refs.tree.getCheckedKeys());
            },
            save(form) {
                this.form.keys = this.$refs.tree.getCheckedKeys();
                this.$refs[form].validate(valid => {
                    if(valid){
                        this.$emit('save', this.form);
                        // this.$emit('cancel');
                    }
                })
            }
        },
        created() {
            api.get('/permissions').then(data => {
                this.data = data.data;
            })
        },
        watch: {

        }
    }
</script>

<style scoped>
    .fl-el-tree {
        padding-top: 8px;
    }
</style>
