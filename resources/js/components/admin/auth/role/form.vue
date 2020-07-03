<template>
    <el-row>
        <el-form :model="form" label-position="right" :label-width="formLabelWidth" :rules="rules" ref="form">
            <el-form-item label="角色名称" prop="name">
                <el-input v-model="form.name" class="w-300" size="medium" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="权限列表">
                <el-tree class="fl-el-tree" ref="tree"
                         :data="data"
                         :props="defaultProps"
                         show-checkbox
                         node-key="id"
                         default-expand-all>
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
            permisionData: {
                type: Array,
                default() {
                    return []
                }
            },
            rowData: {
                type: Object,
                default() {
                    return {}
                }
            }
        },
        data() {
            return {
                form: {
                    id: 0,
                    name: '',
                    keys: [],
                },
                formLabelWidth: '90px',
                rules: {
                    name: [
                        {required: true, message: '请输入角色名称', trigger: 'blur'},
                        {min: 2, max: 15, message: '长度在2-15个字符', trigger: 'blur'}
                    ]
                },
                data: [],
                newCheckedKeys: [],
                defaultProps: {
                    children: 'children',
                    label: 'purview_name'
                }
            };
        },
        methods: {
            initForm() {
                this.data = this.permisionData;
                this.form = {
                    name: this.rowData.name,
                    id: this.rowData.id,
                    keys: this.rowData.checkedKeys,
                }
                if (this.rowData.isEdit) {
                    this.$nextTick(() => {
                        // 渲染已经存在的权限
                        this.$refs.tree.setCheckedNodes(this.form.keys);
                    });
                }
            },
            cancel() {
                this.$emit('cancel');
            },
            save(form) {
                this.form.keys = this.$refs.tree.getCheckedNodes(false, true).map((item) => {
                    return item.id;
                })
                this.$refs[form].validate(valid => {
                    if (valid) {
                        this.$emit('save', this.form);
                    }
                })
            }
        },
        created() {
            this.initForm();
        },
        watch: {}
    }
</script>

<style scoped>
    .fl-el-tree {
        padding-top: 8px;
    }
</style>
