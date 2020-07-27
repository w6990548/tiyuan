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
                        <vmd-editor :value="text" mode="preview"></vmd-editor>
                    </el-form-item>
                    <el-form-item label="v-md编辑器2">
                        <vmd-editor :value="text" ref="editor"/>
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
    import VmdEditor from '../../../../assets/components/vmd-editor';

    export default {
        name: "system-setting-form",
        components: {ImageUpload, VmdEditor},
        data() {
            return {
                ruleForm: {
                    icp_code: '', // ICP备案证书号
                    icp_url: '', // ICP备案跳转地址
                    site_switch: true, // 暂时关闭网站
                    admin_logo: '', // 管理后台logo
                    site_qr_code: '', // 站长二维码
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
                },
                text: "# 标题 1\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent\n" +
                    "\n" +
                    "## 标题 2\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent\n" +
                    "\n" +
                    "### 标题 3\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent\n" +
                    "\n" +
                    "## 标题 2\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent\n" +
                    "\n" +
                    "### 标题 3\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent\n" +
                    "contentcontentcontent"
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
            // 单图或多图上传验证
            uploadImage(formName) {
                this.$refs.ruleForm.validateField(formName);
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
