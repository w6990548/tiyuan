<template>
    <el-row>
        <el-col :span="3">
            <button @click="test">测试</button>
            <el-card :body-style="{ padding: '0px' }">
                <a :href="h5Url" target="_blank">
                    <img :src="imgUrl" class="image">
                </a>
            </el-card>
        </el-col>
    </el-row>
</template>

<script>
export default {
    name: "test",
    data() {
        return {
            imgUrl: 'https://wxqrcode.dingdanxia.com/qrcode/images/meituan/2020-09-25/5239/181837/5239.jpg',
            h5Url: '',
        }
    },
    methods: {
        test() {
            api.get('demo').then(res => {
                this.imgUrl = res.data.data.qrcode;
                this.h5Url = res.data.data.short_h5;
            }).catch(error => {
                console.log(11111, error);
            })
        }
    },
    created() {
        // this.test();
    }
}
</script>

<style scoped>
.image {
    width: 100%;
    display: block;
}
</style>
