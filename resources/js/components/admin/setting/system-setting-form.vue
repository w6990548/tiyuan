<template>
    <page title="参数配置">
        <el-row type="flex" class="row-bg">
            <el-col :span="10">
                <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="130px"
                         class="demo-ruleForm m-t-20">
                    <el-form-item label="ICP备案证书号" prop="icp_code">
                        <el-input v-model="ruleForm.icp_code" placeholder="请输入ICP备案证书号"></el-input>
                    </el-form-item>
                    <el-form-item label="ICP备案跳转地址" prop="icp_url">
                        <el-input v-model="ruleForm.icp_url" placeholder="请输入ICP备案跳转地址"></el-input>
                    </el-form-item>
                    <el-form-item label="暂时关闭网站" prop="site_switch">
                        <el-switch v-model="ruleForm.site_switch" active-text="开启" inactive-text="关闭"></el-switch>
                    </el-form-item>
                    <el-form-item label="logo" prop="admin_logo">
                        <image-upload :limit="1" @uploadImage="uploadImage('admin_logo')"
                                      v-model="ruleForm.admin_logo"/>
                    </el-form-item>
                    <el-form-item label="站长二维码" prop="site_qr_code">
                        <image-upload
                                :width="512"
                                :height="512"
                                :limit="1"
                                @uploadImage="uploadImage('site_qr_code')"
                                v-model="ruleForm.site_qr_code"/>
                    </el-form-item>
                    <el-form-item label="v-md编辑器">
                        <v-md-editor
                                v-model="ruleForm.textArea"
                                :disabled-menus="[]"
                                height="400px"
                                @upload-image="editorUploadImage"/>
                    </el-form-item>
                    <el-form-item label="v-md编辑器2">
                        <v-md-editor
                                v-model="ruleForm.textArea2"
                                :disabled-menus="[]"
                                height="400px"
                                @upload-image="editorUploadImage"/>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitForm('ruleForm')">保 存</el-button>
                    </el-form-item>
                </el-form>
            </el-col>
        </el-row>

    </page>
</template>

<script>

    import ImageUpload from "../../../../assets/components/image-upload";

    export default {
        name: "system-setting-form",
        components: {ImageUpload},
        data() {
            return {
                ruleForm: {
                    icp_code: '', // ICP备案证书号
                    icp_url: '', // ICP备案跳转地址
                    site_switch: true, // 暂时关闭网站
                    admin_logo: '', // 管理后台logo
                    site_qr_code: '', // 站长二维码
                    textArea: '',
                    textArea2: '',
                },
                limit: 1,
                rules: {
                    icp_code: [
                        {required: true, message: '请输入ICP备案证书号', trigger: 'blur'},
                        {min: 3, max: 50, message: '长度在 3 到 50 个字符', trigger: 'blur'}
                    ],
                    icp_url: [
                        {required: true, message: '请输入ICP备案跳转地址', trigger: 'blur'},
                        {min: 5, max: 50, message: '长度在 5 到 50 个字符', trigger: 'blur'}
                    ],
                    admin_logo: [
                        {required: true, message: '请上传管理后台logo'},
                    ],
                    site_qr_code: [
                        {required: true, message: '请上传站长二维码'},
                    ],
                }
            };
        },
        methods: {
            getSettings() {
                let _this = this;
                api.get('admin/settings').then(data => {
                    for (let key in _this.ruleForm) {
                        if (data.data.hasOwnProperty(key)) {
                            _this.ruleForm[key] = data.data[key];
                        }
                    }
                    this.ruleForm = data.data;
                })
            },
            uploadImage(formName) {
                this.$refs.ruleForm.validateField(formName);
            },
            // 编辑器上传本地图片
            editorUploadImage(event, insertImage, file) {
                // 限制图片
                let imgTypes = ['image/png', 'image/jpeg', 'image/gif'];
                const isLt2M = file[0].size / 1024 / 1024 < 2;
                if (imgTypes.indexOf(file[0].type) < 0) {
                    this.$message.error('只能上传 png、jpg、jpeg或gif类型的图片');
                    return false;
                }
                if (!isLt2M) {
                    this.$message.error('上传的图片不能大于2M');
                    return false;
                }

                let config = {
                    headers: {'Content-Type': 'multipart/form-data'}
                };
                let formData = new FormData();
                formData.append('file', file[0]);
                // 存储到七牛
                api.post('upload/image', formData, config).then(data => {
                    // 回显到编辑器中
                    insertImage({
                        url: data.data.url,
                        desc: '',
                    });
                })
            },
            submitForm(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        api.post('admin/settings/save', this.ruleForm).then(() => {
                            this.$message.success('保存成功');
                            this.getSettings();
                        })
                    } else {
                        return false;
                    }
                });
            },
        },
        created() {
            this.getSettings();
        }
    }
</script>

<style scoped>

</style>
