<template>
	<div class="flex-center position-ref full-height">
		<div class="code">404</div>
		<div class="message" style="padding: 10px;">页面不存在</div>
	</div>
</template>

<script>
	export default {

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
