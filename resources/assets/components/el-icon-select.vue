<template>
    <el-select v-model="iconValue"
               size="medium"
               filterable
               clearable
               placeholder="请选择" @change="iconChange">
        <el-option class="fl-option-style"
                   v-for="item in icons"
                   :key='item'
                   :label='dds'
                   :value="item">
            <span style="float: left">
                <div class="icon_svg m-t-5">
                    <icon-svg :icon-class="item" :size="30"/>
                </div>
            </span>
            <span style="float: right; color: #8492a6; font-size: 13px">{{ item }}</span>
        </el-option>
    </el-select>
</template>

<script>

// 导入从阿里图库中下的svg文件
let url = require("../../assets/icon/iconfont.svg");

function getText(url) {
    return new Promise(function (resolve, reject) {
        let client = new XMLHttpRequest();
        client.open("GET", url);
        client.onreadystatechange = handler;
        client.responseType = "text";
        client.setRequestHeader("Accept", "text/plan");
        client.send();

        function handler() {
            if (this.readyState !== 4) {
                return;
            }
            if (this.status === 200) {
                resolve(this.response);
            } else {
                reject(new Error(this.statusText));
            }
        }
    });
}

export default {
    name: "el-icon-select",
    props: {
        /* 初始值 */
        value: {
            type: String, default: 'al-icon-record',
        },
    },
    data() {
        return {
            icons: [],
            iconValue: 'al-icon-record',
            dds: '',
        }
    },
    mounted() {
        getText(url).then(data => {
            let txt = data;
            if (!txt || txt === "") return;
            let iconArr = txt.match(/glyph-name="(\S*)/gm);
            iconArr.forEach(v => {
                this.icons.push('al-icon-' + v.split("=")[1].replace("\"", "").replace("\"", ""));
            });
        });
        this.iconValue = this.value;
    },
    methods: {
        iconChange() {
            this.$emit('getIcon', this.iconValue);
        }
    },
    watch: {
        value() {
            this.iconValue = this.value;
        }
    },
}
</script>

<style scoped>
.fl-option-style {
    height: 40px;
    line-height: 40px;
}
</style>
