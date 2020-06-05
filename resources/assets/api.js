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
	let promise = axios.post(url, params, options).then(res => {
		return handlerRes(res.data);
	});
	promise.catch(handlerError);
	return promise;
}

function handlerRes(res) {
	if (res && res.code === 10) {
		return res;
	} else {
		return Promise.reject(new ResponseError(res));
	}
}

function handlerError(error) {
	if (error instanceof ResponseError) {
		Message.error(error.response.message);
	} else {
		Message.error('请求超时，请检查网络')
	}
}

export default {
	get,
	post,
}

