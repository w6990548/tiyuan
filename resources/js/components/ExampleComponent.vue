<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        I'm an example component.
                    </div>
                </div>
            </div>
        </div>
	    <el-button>默认按钮</el-button>
	    <el-button type="primary">主要按钮</el-button>
	    <el-button type="success">成功按钮</el-button>
	    <el-button type="info">信息按钮</el-button>
	    <el-button type="warning">警告按钮</el-button>
	    <el-button type="danger">危险按钮</el-button>
    </div>
</template>

<script>
	import api from '../../assets/api';
    export default {
        mounted() {
            console.log('Component mounted.')
	        api.get('/api/v1/version', {id:1}).then(data => {
	        	console.log(data);
	        })
		}
    }
</script>
