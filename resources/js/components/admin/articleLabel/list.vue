<template>
    <page title="标签列表">
        <el-card shadow="hover">
            <el-tag
                    type="info"
                    :key="item.id"
                    v-for="item in dynamicTags"
                    closable
                    :disable-transitions="false"
                    @close="deleteTag(item)" class="m-r-10 m-b-10">
                {{item.name}}
            </el-tag>

            <el-input
                    class="input-new-tag"
                    v-if="inputVisible"
                    v-model="inputValue"
                    ref="saveTagInput"
                    size="small"
                    maxlength="10"
                    @keyup.enter.native="save"
                    @blur="save">
            </el-input>
            <el-tag effect="dark" v-else class="button-new-tag" size="small" @click="showInput">
                <i class="el-icon-circle-plus-outline m-r-5"></i>添加标签
            </el-tag>
        </el-card>
    </page>
</template>

<script>
    export default {
        name: "list",
        data() {
            return {
                dynamicTags: [],
                inputVisible: false,
                inputValue: '',
            };
        },
        methods: {
            getLables() {
                api.get('admin/labels').then(data => {
                    this.dynamicTags = data.data
                });
            },
            deleteTag(tag) {
                api.post('admin/labels/delete', {
                    id: tag.id
                }).then(() => {
                    this.dynamicTags.splice(this.dynamicTags.indexOf(tag), 1);
                    this.$message.success('删除成功');
                    this.getLables();
                })
            },
            initForm() {
                let inputValue = this.inputValue;
                if (inputValue.length > 10) {
                    this.$message.error('标签长度必须介于 1 - 10 个字符之间');
                    return false;
                }
                this.inputVisible = false;
                this.inputValue = '';
                return inputValue;
            },
            showInput() {
                this.inputVisible = true;
                this.$nextTick(_ => {
                    this.$refs.saveTagInput.$refs.input.focus();
                });
            },
            save() {
                let inputValue = this.initForm();
                if (inputValue) {
                    api.post('admin/labels/create', {
                        name: inputValue
                    }).then(data => {
                        this.$message.success('添加成功');
                        if (inputValue) {
                            this.dynamicTags.push(data.data);
                        }
                    });

                }
            }
        },
        created() {
            this.getLables();
        }
    }
</script>

<style scoped>
    .button-new-tag {
        height: 32px;
        line-height: 30px;
        padding-top: 0;
        padding-bottom: 0;
    }

    .input-new-tag {
        width: 90px;
        margin-right: 10px;
        vertical-align: middle;
    }
</style>
