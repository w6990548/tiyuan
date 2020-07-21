<template>
    <el-row>
        <el-form :model="form" label-position="right" :label-width="formLabelWidth" :rules="rules" ref="form">
            <el-form-item label="权限名称" prop="purview_name">
                <el-input v-model="form.purview_name" class="w-300" size="medium" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="所属父权限" v-if="isAddOrEdit === 'isAdd'">
                <el-select v-model="form.pid" size="medium" class="w-300">
                    <el-option :value="0" v-if="id === 0" label="顶级权限"/>
                    <el-option
                            v-for="item in data"
                            :key="item.id"
                            :value="item.id"
                            :label="item.purview_name">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="权限地址" prop="name">
                <el-input v-model="form.name" size="medium" class="w-300" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label-width="0" align="center">
                <el-button size="medium" @click="cancel">取 消</el-button>
                <el-button size="medium" type="primary" @click="save('form')">确 定</el-button>
            </el-form-item>
        </el-form>
    </el-row>
</template>

<script>
    export default {
        props: {
            PermissionsData: {
                type: Array,
                default() {
                    return []
                }
            },
            id: {
                type: Number,
                default: 0
            },
            isAddOrEdit: { type: String, default: '' }
        },
        data() {
            return {
                form: {
                    purview_name: '',
                    pid: 0,
                    name: ''
                },
                data: [],
                formLabelWidth: '90px',
                rules: {
                    purview_name: [
                        { required: true, message: '请输入权限名称', trigger: 'blur' },
                        { min: 2, max: 10, message: '长度在2-10个字符', trigger: 'blur' }
                    ],
                    name: [
                        { required: true, message: '请输入权限地址', trigger: 'blur' },
                        { min: 5, max: 50, message: '长度在5-50个字符', trigger: 'blur' }
                    ]
                }
            }
        },
        methods: {
            initForm() {
                this.data = this.PermissionsData;
                if (this.isAddOrEdit === 'isAdd') {
                    this.form = {
                        name: '',
                        purview_name: '',
                        pid: this.id
                    };
                } else {
                    this.form = {
                        name: this.data[0].name,
                        purview_name: this.data[0].purview_name,
                        id: this.data[0].id,
                        pid: this.id
                    };
                }
            },
            cancel() {
                this.$emit('cancel');
            },
            save(form) {
                this.$refs[form].validate(valid => {
                    if(valid){
                        this.$emit('save', this.form);
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
        }
    }
</script>

<style scoped>

</style>