<?php

class helpers
{
    /**
     * 返回系统毫秒时间
     * @return float
     * @author: FengLei
     * @time: 2020/6/12 18:32
     */
    public static function microtime_float()
    {
        list($usec, $sec) = explode(' ', microtime());
        return ((float)$usec + (float)$sec);
    }

    /**
     * 返回程序执行耗时
     * @return int|string
     * @author: FengLei
     * @time: 2020/6/12 18:31
     */
    public static function spend_time()
    {
        if (!isset($_SERVER['REQUEST_TIME_FLOAT'])) {
            return 0;
        }
        $spend_time = self::microtime_float() - $_SERVER['REQUEST_TIME_FLOAT'];
        $spend_time = number_format($spend_time, 6);
        return $spend_time;
    }
}