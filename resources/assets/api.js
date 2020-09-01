import axios from 'axios';
import { Notification } from 'element-ui';
import NProgress from "nprogress";

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
		if (error.response && error.response.code && document.getElementsByClassName('el-notification').length === 0) {

            switch (error.response.code) {
                case 10003:
                    Notification.warning({'title': '身份验证', 'message': error.response.message});
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    router.push('/');
                    break;
                default:
                    Notification.warning({'title': '提示', 'message': error.response.message});
                    break;
            }
            NProgress.done();
        }
	} else {
        Notification.error({'title': '异常', 'message': '请求超时，请检查网络'});
	}
}

export default {
	get,
	post,
}

