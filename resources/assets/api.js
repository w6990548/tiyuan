import axios from 'axios';
import {Notification} from 'element-ui';
import NProgress from 'nprogress';

window.baseApiUrl = window.baseApiUrl || '';

class ResponseError {
    constructor(response) {
        this.response = response;
    }
}

function get(url, params) {
    let options = {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Authorization': localStorage.getItem('token')
        },
        params: params,
    };
    let promise = axios.get(getRealUrl(url), options).then(res => {
        return handlerRes(res.data);
    });
    promise.catch(handlerError)
    return promise;
}

function post(url, params) {
    let options = {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Authorization': localStorage.getItem('token')
        }
    };
    let promise = axios.post(getRealUrl(url), params, options)
        .then(res => {
            return handlerRes(res.data);
        });
    promise.catch(handlerError)
    return promise;
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
    if (res && res.code === 200) {
        return res;
    } else {
        return Promise.reject(new ResponseError(res));
    }
}

function handlerError(error) {
    if (error instanceof ResponseError) {
        if (error.response && error.response.code && document.getElementsByClassName('el-notification').length === 0) {
            Notification.warning({'title': '提示', 'message': error.response.message});
            if (error.response.code === 2000) {
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                router.push('/');
            }
            NProgress.done();
        }
    } else {
        console.log('错误', error.response);
        Notification.error({'title': error.response.status, 'message': error.response.statusText});
    }
}

export default {
    get,
    post,
}

