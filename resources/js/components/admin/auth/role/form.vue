<template>
    <el-row>
        <el-form :model="form" label-position="right" :label-width="formLabelWidth" :rules="rules" ref="form">
            <el-form-item label="角色名称" prop="name">
                <el-input v-model="form.name" class="w-300" size="medium" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="权限列表">
                <el-tree class="fl-el-tree" ref="tree"
                         :data="data"
                         :highlight-current="true"
                         :props="defaultProps"
                         :show-checkbox="true"
                         :check-strictly="true"
                         node-key="id"
                         @check="clickDeal"
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
            },
            clickDeal (currentObj, treeStatus) {
                // 用于：父子节点严格互不关联时，父节点勾选变化时通知子节点同步变化，实现单向关联。
                let selected = treeStatus.checkedKeys.indexOf(currentObj.id) // -1未选中
                // 选中
                if (selected !== -1) {
                    // 子节点只要被选中父节点就被选中
                    this.selectedParent(currentObj)
                    // 统一处理子节点为相同的勾选状态
                    this.uniteChildSame(currentObj, true)
                } else {
                    // 未选中 处理子节点全部未选中
                    if (currentObj.children !== undefined && currentObj.children.length > 0) {
                        this.uniteChildSame(currentObj, false)
                    }
                }
            },
            // 统一处理子节点为相同的勾选状态
            uniteChildSame (treeList, isSelected) {
                this.$refs.tree.setChecked(treeList.id, isSelected)
                if (treeList.children !== undefined && treeList.children.length > 0) {
                    for (let i = 0; i < treeList.children.length; i++) {
                        this.uniteChildSame(treeList.children[i], isSelected)
                    }
                }
            },
            // 统一处理父节点为选中
            selectedParent (currentObj) {
                let currentNode = this.$refs.tree.getNode(currentObj)
                if (currentNode.parent.key !== undefined) {
                    this.$refs.tree.setChecked(currentNode.parent, true)
                    this.selectedParent(currentNode.parent)
                }
            },
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
