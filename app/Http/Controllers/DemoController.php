<?php

namespace App\Http\Controllers;

use App\Result;
use App\Support\DingDanXia\DingDanXia;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function index(Request $request)
    {
        // 官方活动转链,饿了么/口碑活动转链（只要有物料ID就可以请求）
        $params = [
            'activity_material_id' => '1571715733668', // 官方的物料id
            'relation_id' => '', // 代理id
            'pid' => '', // 推广位，不传默认走后台默认的
        ];
        $result = DingDanXia::getTbkActivityInfo($params);
        return Result::success($result);


        // 美团联盟外卖订单查询API（时间筛选版）
        // $params = [
        //     'start_time' => '1599667200', // 开始时间时间戳
        //     'end_time' => time(), // 结束时间时间戳
        //     'query_type' => '', // 查询时间类型 1-美团实际付款时间 2-平台订单入库更新时间（订单状态改变，时间会变） ， 默认1
        //     'sid' => '', // 渠道标识
        //     'page' => '', // 页码 默认 1
        //     'page_size' => '', // 每页显示多少 默认 20 最大 50
        //     'status' => '', // 0-全部 1-已提交（已付款）、8-已完成（确认收货）、9-已退款 默认0
        // ];
        //
        // $result = DingDanXia::getMeiTuanOrderList($params);
        // return Result::success($result);

        // 美团联盟外卖订单查询API（订单号版）
        // $params = [
        //     'orderid' => '61726202097953310', // 订单号
        // ];
        // $result = DingDanXia::getMeiTuanOrderBuOrderId($params);
        // return Result::success($result);


        // 美团联盟外卖红包API接口
        // $params = [
        //     'sid' => 'FengLei',
        //     'qrcode' => true,
        //     'generate_we_app' => true,
        // ];
        // $result = DingDanXia::getMeiTuanPrivilege($params);
        // return Result::success($result);
    }
}
