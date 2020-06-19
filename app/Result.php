<?php


namespace App;


use helpers;

class Result
{
    public static function success($message = '请求成功', $data = [])
    {
        if (!is_string($message)) {
            $data = $message;
            $message = '请求成功';
        }
        if (!is_null($data) && empty($data)) {
            $data = new \stdClass();
        }

        $response = [
            'spend_time' => helpers::spend_time(),
            'code' => 0,
            'message' => $message,
            'data' => $data,
            'timestamp' => time(),
        ];

        return response()->json($response);
    }

    public static function error($code, $message, $data = [])
    {
        if (empty($data)) {
            $data = new \stdClass();
        }
        return response()->json([
            'spend_time' => helpers::spend_time(),
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'timestamp' => time(),
        ]);
    }
}