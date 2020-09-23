<?php

namespace App;

class ResultCode
{
    const SUCCESS = 200;
    const UNKNOWN = 500; // 未知错误

    const API_NOT_FOUND = 1000; // 接口不存在
    const PARAMS_INVALID = 1001; // 参数不合法
    const METHOD_WRONG = 1002; // 请求方法错误
    const FREQUENT_REQUESTS = 1003; // 请求频繁


    const UN_LOGIN = 2000;  // 未登录
    const ACCOUNT_NOT_FOUND = 2001; // 账号不存在
    const ACCOUNT_PASSWORD_ERROR = 2002; // 账号或密码错误
    const TOKEN_INVALID = 2003; // token无效

    const NO_PERMISSION = 3000; // 无权限操作
    const PERMISSION_EXISTS = 3001; // 权限已存在
    const ROLE_EXISTS = 3002; // 角色已存在

    const DB_QUERY_FAIL = 4000; // 数据库查询失败
    const DB_INSERT_FAIL = 4001; // 数据库插入失败
    const DB_UPDATE_FAIL = 4002; // 数据库更新失败
    const DB_DELETE_FAIL = 4003; // 数据库删除失败

    const IMG_SIZE_ILLEGAL = 5000; // 图片过大
}