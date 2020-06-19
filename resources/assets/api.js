import axios from 'axios';
import { Message } from 'element-ui';

class ResponseError {
	constructor(response) {
		this.response = response;
	}
}

function get(url, params) {
	let options = {
		headers: {'X-Requested-With': 'XMLHttpRequest'},
		params: params,
	};
	getToken(options);
	let promise = axios.get(url, options).then(res => {
		return handlerRes(res.data);
	});
	promise.catch(handlerError)
	return promise;
}

function post(url, params) {
	let options = {
		headers: {'X-Requested-With': 'XMLHttpRequest'},
	};
	getToken(options);
	let promise = axios.post(url, params, options).then(res => {
		return handlerRes(res.data);
	});
	promise.catch(handlerError);
	return promise;
}

// 获取token
function getToken(options) {
	let token = localStorage.getItem('token');
	if (token) {
		options.headers.Authorization = token;
		return options;
	}
}

function handlerRes(res) {
	if (res && res.code === 0) {
		return res;
	} else {
		return Promise.reject(new ResponseError(res));
	}
}

function handlerError(error) {
	if (error instanceof ResponseError) {
		if (error.response && error.response.code) {
			switch (error.response.code) {
				case 10003:
					Message.error('您的登录信息已失效, 请先登录');
					router.push('/');
					break;
				default:
					if (!error.response.disableErrorMessage) {
						Message.error(error.response.message)
					}
					break;
			}
		} else {
			Message.error(error.response.message);
		}
	} else {
		Message.error('请求超时，请检查网络')
	}
}

export default {
	get,
	post,
}

