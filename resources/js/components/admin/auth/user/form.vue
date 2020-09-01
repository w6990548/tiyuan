<template>
    <el-row>
        <el-form :model="form" label-position="right" :label-width="formLabelWidth" :rules="rules" ref="form">
            <el-form-item label="用户名" prop="username">
                <el-input v-model="form.username" placeholder="请输入用户名" class="w-300" size="medium"
                          autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="密码" prop="password" v-if="showPwd">
                <el-input v-model="form.password" placeholder="请输入密码" class="w-300" size="medium"
                          autocomplete="off" show-password></el-input>
            </el-form-item>
            <el-form-item label="所属角色">
                <el-select v-if="form.role === 1" value="administrator" disabled
                           size="medium" class="w-300" clearable>
                </el-select>
                <el-select v-else v-model="form.role" size="medium" class="w-300" placeholder="请选择" clearable>
                    <el-option
                        v-for="item in data"
                        :key="item.id"
                        :value="item.id"
                        :label="item.name + ' ----- ' + item.alias_name ">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label-width="0" align="center">
                <el-button size="medium" @click="cancel('form')">取 消</el-button>
                <el-button size="medium" type="primary" @click="save('form')">确 定</el-button>
            </el-form-item>
        </el-form>
    </el-row>
</template>

<script>
export default {
    props: {
        roleData: {
            type: Array,
            default() {
                return []
            }
        },
        rowData: {
            type: Array,
            default() {
                return [];
            }
        },
        id: {
            type: Number,
            default: 0
        },
    },
    data() {
        return {
            form: {
                id: 0,
                username: '',
                password: '',
                role: '',
            },
            showPwd: true,
            data: [],
            formLabelWidth: '90px',
            rules: {
                username: [
                    {required: true, message: '请输入用户名', trigger: 'blur'},
                    {min: 3, max: 10, message: '长度在3-10个字符', trigger: 'blur'}
                ],
                password: [
                    {required: true, message: '请输入密码', trigger: 'blur'},
                    {min: 6, max: 18, message: '长度在6-18个字符', trigger: 'blur'}
                ]
            }
        }
    },
    methods: {
        initForm() {
            this.data = this.roleData;
            if (this.rowData.showPwd === false) {
                this.form = {
                    id: this.rowData.role.id,
                    username: this.rowData.role.username,
                    role: this.rowData.role.roles[0] ? this.rowData.role.roles[0].id : '',
                    name: this.rowData.role.roles[0] ? this.rowData.role.roles[0].name : '',
                };
                this.showPwd = this.rowData.showPwd;
            }
        },
        cancel(formName) {
            this.$refs[formName].resetFields();
            this.$emit('cancel');
        },
        save(form) {
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

</style>
