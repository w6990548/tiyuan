import axios from 'axios';
import { Message } from 'element-ui';

window.baseApiUrl = window.baseApiUrl || '';
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
    url = getRealUrl(url);
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
    url = getRealUrl(url);
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

// 处理 API 请求地址
function getRealUrl(url) {
    if (url.indexOf(window.baseApiUrl) === 0) {
        return url;
    }
    if (url.indexOf('/') === 0) {
        url = url.substr(1);
    }
    return window.baseApiUrl + url
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
					Message.error('您的登录信息已失效，请重新登录');
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

