<template>
    <div class="flex-center position-ref" v-if="errorType === 'no authority'">
        <el-alert
            title="无权访问"
            type="error"
            description="权限不足，无法访问该页面！"
            show-icon>
        </el-alert>
    </div>
	<div class="flex-center position-ref full-height" v-else>
		<div class="code">404</div>
		<div class="message" style="padding: 10px;">页面不存在</div>
	</div>
</template>

<script>
	export default {
	    data() {
	        return {
                errorType: '404'
            }
        },
        created() {
	        this.errorType = this.$route.query.errorType ? this.$route.query.errorType : '404';
        },
	}
</script>

<style>
	.flex-center {
		align-items: center;
		display: flex;
		justify-content: center;
	}
	.full-height {
		height: 100vh;
	}
	.code {
		border-right: 2px solid;
		font-size: 26px;
		padding: 0 15px 0 15px;
		text-align: center;
	}
	.message {
		font-size: 18px;
		text-align: center;
	}
	body {
		background-color: #fff;
		color: #636b6f;
		margin: 0;
	}
</style>
