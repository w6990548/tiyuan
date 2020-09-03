<template>
    <el-row>
        <el-form :model="form" label-position="right" :label-width="formLabelWidth" :rules="rules" ref="form">
            <el-form-item label="权限名称" prop="alias_name">
                <el-input v-model="form.alias_name" class="w-300" size="medium" placeholder="请输入权限名称"
                          autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="权限标识" prop="name">
                <el-input v-model="form.name" class="w-300" size="medium" placeholder="权限标识（menu-xx，api-xx，page-xx）"
                          autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="图标" :inline="true" prop="icon">
                <div class="fl-icon-show">
                    <icon-svg class="al-icon" style="margin-top: 2px" :icon-class="iconName" :size="30"/>
                </div>
                <el-icon-select v-model="form.icon" class="w-230 fl-icon-select" @getIcon="getIcon"></el-icon-select>
            </el-form-item>
            <el-form-item label="所属父权限" v-if="dialogTitle === 'addSubTitle'">
                <el-select v-model="form.parent_id" disabled size="medium" class="w-300">
                    <el-option :label="labelName" :value="form.parent_id"/>
                </el-select>
            </el-form-item>
            <el-form-item label="所属父权限" v-else>
                <el-tree-select :value="form.parent_id" :props="props" size="medium" @getValue="getValue"
                                :options="data"></el-tree-select>
            </el-form-item>
            <el-form-item label="权限类型" prop="type" required>
                <el-radio-group v-model="form.type" @change="changeType">
                    <el-radio :label="1">菜单</el-radio>
                    <el-radio :label="2">API</el-radio>
                    <el-radio :label="3">页面</el-radio>
                </el-radio-group>
            </el-form-item>
            <el-form-item label="权限地址" prop="url" :required="requiredUrl">
                <el-input v-model="form.url" size="medium" class="w-300" placeholder="请输入权限地址"
                          autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label-width="0" align="center">
                <el-button size="medium" @click="cancel">取 消</el-button>
                <el-button size="medium" type="primary" @click="save('form')">确 定</el-button>
            </el-form-item>
        </el-form>
    </el-row>
</template>

<script>
import ElTreeSelect from '../../../../../assets/components/el-tree-select';
import ElIconSelect from '../../../../../assets/components/el-icon-select';

/**
 * 递归去除本身项
 * @param arr
 * @param tag
 */
function call(arr, tag) {
    for (var i = arr.length; i > 0; i--) {
        if (arr[i - 1].id === tag) {
            arr.splice(i - 1, 1);
        } else {
            if (arr[i - 1].children) {
                call(arr[i - 1].children, tag)
            }
        }
    }
}

export default {
    props: {
        PermissionsData: {
            type: Array,
            default() {
                return []
            }
        },
        currentData: {
            type: Object,
            default() {
                return {};
            }
        },
        dialogTitle: {type: String, default: ''}
    },
    data() {
        // 验证权限地址
        var checkUrl = (rule, value, callback) => {
            if (this.form.type === 2) {
                if (!value) {
                    return callback(new Error('权限地址不能为空'));
                }
                if (value.length >= 5 && value.length <= 50) {
                    callback();
                } else {
                    callback(new Error('长度在5-50个字符'));
                }
            }
            callback();
        };
        return {
            form: {
                name: '',
                alias_name: '',
                parent_id: 0,
                url: '',
                type: 1,
                icon: 'al-icon-record',
            },
            props: {
                value: 'id',            // ID字段名
                label: 'alias_name',    // 显示名称
                children: 'children'    // 子级字段名
            },
            requiredUrl: false,
            requiredType: [2, 3], // 权限类型必填项
            data: [],
            labelName: '',
            formLabelWidth: '90px',
            iconName: 'al-icon-record',
            rules: {
                alias_name: [
                    {required: true, message: '请输入权限名称', trigger: 'blur'},
                    {min: 2, max: 10, message: '长度在2-10个字符', trigger: 'blur'}
                ],
                name: [
                    {required: true, message: '请输入权限标识', trigger: 'blur'},
                    {min: 5, max: 50, message: '长度在5-50个字符', trigger: 'blur'}
                ],
                url: [
                    {validator: checkUrl, trigger: 'blur'},
                ]
            }
        }
    },
    methods: {
        getValue(value) {
            this.form.parent_id = value;
        },
        getIcon(value) {
            this.iconName = value;
            this.form.icon = value;
        },
        changeType() {
            this.requiredUrl = this.requiredType.includes(this.form.type);
        },
        initForm() {
            this.data = this.PermissionsData;

            if (this.dialogTitle === 'addSubTitle') {
                this.form.parent_id = this.currentData.id;
                this.labelName = this.currentData.alias_name;
            }

            if (this.dialogTitle === 'editTitle') {
                // 编辑权限时去除自己与下级的权限（防止父权限变更到子权限下）
                call(this.data, this.currentData.id);
                // 编辑时，type 为 api 或 页面元素，type 为必填项
                this.requiredUrl = this.requiredType.includes(this.currentData.type);

                this.form = {
                    id: this.currentData.id,
                    name: this.currentData.name,
                    alias_name: this.currentData.alias_name,
                    parent_id: this.currentData.parent_id,
                    url: this.currentData.url,
                    type: this.currentData.type,
                    icon: this.currentData.icon,
                };
                this.iconName = this.currentData.icon;
            }
        },
        cancel() {
            this.$emit('cancel');
        },
        save(form) {
            this.$refs[form].validate(valid => {
                if (valid) {
                    this.$emit('save', this.form, this.dialogTitle);
                    this.$emit('cancel');
                    this.initForm();
                }
            })
        }
    },
    created() {
        this.initForm();
    },
    watch: {
        PermissionsData() {
            this.data = this.PermissionsData;
        },
    },
    components: {
        ElTreeSelect,
        ElIconSelect
    }
}
</script>

<style scoped>
.fl-icon-show {
    position: absolute;
    top: 2px;
    height: 34px;
    border-radius: 3px;
    border: 1px solid #dcdfe6;
    width: 60px;
    text-align: center;
}

.fl-icon-select {
    margin-left: 70px
}
</style>
