<template>
    <div>
        <el-upload
                class="uploader"
                :multiple="multiple"
                :action="action"
                :list-type="listType"
                :file-list="fileList"
                :on-preview="preview ? handlePreview : null"
                :on-success="handleUploadSuccess"
                :on-error="handleError"
                :before-upload="beforeUpload"
                :on-remove="handleRemove"
                :on-change="handleChange"
                :disabled="disabled"
                :limit="limit"
                :data="data"
                :on-exceed="onExceed"
                :class="{'upload-fulled' : fileList.length >= limit}">
            <i v-if="!$slots.default" class="el-icon-plus"></i>
            <slot/>
        </el-upload>
    </div>
</template>

<script>

    /**
     * 多图片上传组件
     * 已完成功能:
     *  选项:
     *      value: 绑定的图片地址
     *      action: 图片上传服务器地址, 默认为: '/api/upload/image'
     *      width: 图片宽度
     *      height: 图片高度
     *      checkSize: 是否检查图片宽高 (宽高都传入时才有效)
     *      limit: 限制高度
     *      disabled: 是否可删除, 禁用
     *      listType: 图片列表类型: picture-card/picture/text, 默认: picture-card
     *      preview: 是否可预览图片
     *  功能:
     *      图片上传功能
     *      删除按钮
     *  事件:
     *      before: 图片上传前, 如果上传前的前置条件检查不通过, 则不会触发, 只有在请求上传服务器时才触发
     *      success: 图片上传成功
     *      fail: 图片上传失败
     *      complete: 上传后(不区分成功与失败, 都会执行)
     *      change: 文件状态改变时的钩子，添加文件、上传成功和上传失败时都会被调用, 第一个参数为file, 其中status会有ready,success,fail 三种状态
     *      error: 上传失败时的钩子
     */
    export default {
        name: "image-upload",
        props: {
            value: {type: Array | String},
            action: {type: String, default: '/api/upload/image'},
            width: {type: Number},
            height: {type: Number},
            whEqualRatio: {type: Boolean, default: false}, // 宽高是否等比
            whIsLowestLimit: {type: Boolean, default: false}, // 宽高是否为最低限制
            checkSize: {type: Boolean, default: true},
            limit: {type: Number},
            multiple: {type: Boolean, default: false},
            disabled: {type: Boolean, default: false},
            listType: {type: String, default: 'picture-card'},
            preview: {type: Boolean, default: false},
            data: {
                type: Object, default: () => {
                }
            }
        },
        data() {
            return {
                valueType: 'array',
                fileList: [],
                isShow: false,
                previewImage: '',
            }
        },
        computed: {},
        methods: {
            // 这个也完毕
            emitInput() {
                let value = [];
                this.fileList.forEach(item => {
                    if (item.status === "success") {
                        // 兼容回显时的 item.url
                        value.push(item.response ? item.response.data.url : item.url);
                    }
                });
                value = value.join(',');
                this.$emit('input', value);
                this.$emit('uploadImage')
            },
            handlePreview(file) {
                this.previewImage = file.url;
                this.isShow = true;
            },
            handleRemove(file, fileList) {
                this.fileList = fileList;
                this.emitInput();
                this.$emit('remove');
            },
            // 已验证完整
            handleUploadSuccess(res, file, fileList) {
                if (res && res.code === 0) {
                    this.fileList = fileList;
                    this.emitInput();
                } else {
                    fileList.forEach(function (item, index) {
                        if (item === file) {
                            fileList.splice(index, 1)
                        }
                    });
                    this.$message.error(res.message || '文件上传失败');
                }
            },
            // 已验证完整
            beforeUpload(file) {
                let _this = this;
                let imgTypes = ['image/png', 'image/jpeg', 'image/gif'];
                const isLt2M = file.size / 1024 / 1024 < 2;
                if (imgTypes.indexOf(file.type) < 0) {
                    this.$message.error('只能上传 png、jpg、jpeg或gif类型的图片');
                    return false;
                }
                if (!isLt2M) {
                    this.$message.error('上传的图片不能大于2M');
                    return false;
                }

                if (_this.width && _this.height) {
                    return new Promise(function (resolve, reject) {
                        let _URL = window.URL || window.webkitURL;
                        let img = new Image();
                        img.onload = function () {
                            let valid = img.width === _this.width && img.height === _this.height;
                            valid ? resolve() : reject();
                        }
                        img.src = _URL.createObjectURL(file);
                    }).then(() => {
                        return file;
                    }, () => {
                        this.$message.error('请上传图片尺寸为' + _this.width + 'px * ' + _this.height + 'px的图片');
                        return Promise.reject();
                    });
                }
            },
            onExceed() {
                this.$message.warning(`最多只能上传${this.limit}张图片`)
            },
            handleChange(file) {
                this.$emit('change', file);
            },
            handleError() {
                this.$emit('error');
                this.$emit('complete')
            },
            // 已验证完整
            initFileList() {
                let value = [];
                if (this.value) {
                    value = this.value.split(',')
                }

                this.fileList.forEach((item, index) => {
                    if (!value[index]) {
                        this.fileList.splice(index, 1)
                    } else {
                        if (value[index] !== item.url) {
                            this.fileList[index].url = value[index];
                        }
                    }
                });

                value.forEach((item, index) => {
                    if (this.fileList[index] && this.fileList[index].url == item) return;
                    this.fileList.push({
                        url: item
                    })
                })
            }
        },
        created() {
            this.initFileList();
        },
        watch: {
            value() {
                this.initFileList()
            }
        },
        components: {}
    }
</script>

<style>
    .upload-fulled .el-upload--picture-card {
        display: none;
    }
</style>

<style scoped>

</style>
