<template>
    <button @click="test">测试</button>
</template>

<script>
export default {
    name: "test",
    data() {
        return {}
    },
    methods: {
        test() {
            api.get('demo').then(res => {
                console.log(2222, res);
            }).catch(error => {
                console.log(11111, error);
            })
        }
    }
}
</script>

<style scoped>

</style>
