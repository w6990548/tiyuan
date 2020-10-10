<?php
/**
 * 订单侠接口
 */

namespace App\Support\DingDanXia;

use App\Exceptions\BaseResponseException;
use GuzzleHttp\Client;
use Log;

class DingDanXia
{
    // 美团联盟外卖红包API接口
    const WAIMAI_MEITUAN_PRIVILEGE = '/waimai/meituan_privilege';
    // 美团联盟外卖订单查询API（订单号版）
    const WAIMAI_MEITUAN_ORDERID = '/waimai/meituan_orderid';
    // 美团联盟外卖订单查询API（时间筛选版）
    const WAIMAI_MEITUAN_ORDERS = '/waimai/meituan_orders';

    // 官方活动转链,饿了么/口碑活动转链
    const TBK_ACTIVITYINFO = '/tbk/activityinfo';

    public static function post($url, $params)
    {
        $params['apikey'] = config('dingdanxia.apikey');
        Log::info('订单侠请求，耗时：'.\helpers::spend_time(), [$url, $params]);
        $client = new Client();
        $response = $client->post(
            config('dingdanxia.host').$url, [
                'query' => $params,
                'timeout' => 60
            ]
        );

        if ($response->getStatusCode() !== 200) {
            Log::error('订单侠API请求失败', compact('url', 'data'));
            throw new BaseResponseException("订单侠API请求失败");
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 美团联盟外卖红包API接口
     * Created by FengLei on 2020/9/25 17:52
     * @param $params
     * @return mixed
     */
    public static function getMeiTuanPrivilege($params)
    {
        return self::post(self::WAIMAI_MEITUAN_PRIVILEGE, $params);
    }

    /**
     * 美团联盟外卖订单查询API（订单号版）
     * Created by FengLei on 2020/9/28 17:08
     * @param $params
     * @return mixed
     */
    public static function getMeiTuanOrderBuOrderId($params)
    {
        return self::post(self::WAIMAI_MEITUAN_ORDERID, $params);
    }

    /**
     * 美团联盟外卖订单查询API（时间筛选版）
     * Created by FengLei on 2020/9/28 17:23
     * @param $params
     * @return mixed
     */
    public static function getMeiTuanOrderList($params)
    {
        return self::post(self::WAIMAI_MEITUAN_ORDERS, $params);
    }

    /**
     * 官方活动转链,饿了么/口碑活动转链
     * Created by FengLei on 2020/9/28 17:34
     * @param $params
     * @return mixed
     */
    public static function getTbkActivityInfo($params)
    {
        return self::post(self::TBK_ACTIVITYINFO, $params);
    }
}
