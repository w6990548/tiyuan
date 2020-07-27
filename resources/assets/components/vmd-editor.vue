<template>
    <v-md-editor
            v-model="contents"
            :mode="mode"
            :height="height"
            :placeholder="placeholder"
            :autofocus="autofocus"
            :include-level="includeLevel"
            :disabled-menus="disabledMenus"
            :left-toolbar="leftToolbar"
            :right-toolbar="rightToolbar"
            :toolbar="customizeToolbar"
            @fullscreen-change="fullscreenChange"
            @save="save"
            @change="change"
            @copy-code-success="handleCopyCodeSuccess"
            @upload-image="editorUploadImage"/>
</template>

<script>
    /**
     * v-md-editor API 文档地址：https://code-farmer-i.github.io/vue-markdown-editor/zh/
     * props
         * v-model: 双向绑定内容，注意：只有编辑组件支持该属性，需要显示的值
         * mode: 默认：editable 可选值 edit(纯编辑模式) editable(编辑与预览模式) preview(纯预览模式)
         * height：正常模式下编辑器的高度
         * placeholder：默认文字
         * autofocus：编辑器加载完是否自动聚焦
         * include-level：目录导航生成时包含的标题。默认包含 2 级、3 级标题。
         * disabled-menus：禁用的菜单，为空数组则开启全部，默认禁用为 image 工具栏下的上传本地图片菜单。示例：['h/h1'] （禁用标题工具栏下的 h1 标题菜单）
         * left-toolbar：工具栏左侧，默认值： undo redo clear | h bold italic strikethrough quote | ul ol table hr | link image code | save
         * right-toolbar：工具栏右侧，默认值： preview toc sync-scroll fullscreen
         * toolbar：自定义工具栏
     * Methods
         * focus：使编辑器聚焦
         * insert：参数：(getInsertContent: Function)，向编辑器中插入文本。
     * Events
         * change：回调参数：(text, html)，内容变化时触发的事件。text 为输入的内容，html 为解析之后的 html 字符串。
         * save：回调参数：(text, html)，点击 save toolbar 时触发的事件。
         * fullscreen-change：回调参数：(isFullscreen)，切换全屏状态时触发的事件。
         * upload-image：回调参数：(event, insertImage)，toolbar 中使用上传图片之后，用户触发图片上传动作时会触发该事件（例如：选择图片上传，拖拽图片到编辑器中，截图后粘贴到编辑器中）。
     *
     *
     *
     * 预览组件 v-md-preview
         * text: 需要解析预览的 markdown 字符串，注意：只有预览组件支持该属性。
     *
     */

    export default {
        name: "vmd-editor",
        props: {
            mode: {type: String, default: 'editable'},
            value: {type: String, default: ''},
            height: {type: String, default: '400px'},
            placeholder: {type: String, default: ''},
            autofocus: {type: Boolean, default: false},
            includeLevel: {
                type: Array,
                default() {
                    return [2,3]
                }
            },
            disabledMenus: {type: Array, default: () => []},
            leftToolbar: {
                type: String,
                default: 'undo redo clear | h bold italic strikethrough quote | ul ol table hr | link image code | emoji todo-list prompt | save'
            },
            rightToolbar: {type: String, default: 'preview toc sync-scroll fullscreen'},
            toolbar: {type: Object, default: () => {}}
        },
        data() {
            return {
                contents: '',
                customizeToolbar: null,
            }
        },
        methods: {
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
            // 自定义工具栏插入提示
            insertTip(editor, type) {
                editor.insert(function (selected) {
                    const prefix = '::: '+type+'\n';
                    const suffix = ':::';
                    const placeholder = '在此输入内容\n';
                    const content = selected || placeholder;

                    return {
                        text: `${prefix}${content}${suffix}`,
                        selected: content,
                    };
                });
            },
            fullscreenChange() {
                console.log('切换全屏回调');
            },
            save() {
                console.log('工具栏保存按钮回调');
            },
            change() {
                console.log('文本内容变化回调');
            },
            handleCopyCodeSuccess(code) {
                console.log('代码快捷复制回调【'+code+'】');
            },
        },
        created() {
            let _this = this;
            _this.contents = this.value
            _this.customizeToolbar = this.toolbar
            _this.customizeToolbar = {
                prompt: {
                    title: '插入提示',
                    icon: 'v-md-icon-tip',
                    menus: [
                        {
                            name: 'prompt',
                            text: '提示',
                            action(editor) {
                                _this.insertTip(editor, 'tip');
                            },
                        },
                        {
                            name: 'warning',
                            text: '注意',
                            action(editor) {
                                _this.insertTip(editor, 'warning');
                            },
                        },
                        {
                            name: 'danger',
                            text: '警告',
                            action(editor) {
                                _this.insertTip(editor, 'danger');
                            },
                        },
                    ],
                },
            }
        },
        watch: {

        },
    }
</script>

<style scoped>

</style>
