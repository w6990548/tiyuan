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

    /**
     * 获取某个分类的所有子分类
     * @param $data
     * @param int $parentId
     * @return array
     * @author: FengLei
     * @time: 2020/8/25 19:05
     */
    public static function subClass($data, $parentId = 0)
    {
        $subs = [];
        foreach ($data as $item) {
            if ($item['parent_id'] == $parentId) {
                $subs[] = $item;
                $subs = array_merge($subs, self::subClass($data, $item['id']));
            }
        }
        return $subs;
    }

    /**
     * 一维数据数组生成数据树
     * @param array $list 数据列表
     * @param string $id 主键ID
     * @param string $pid 父ID
     * @param string $son 定义子数据Key
     * @return array
     * @author: FengLei
     * @time: 2020/8/25 19:10
     */
    public static function generateTree($list, $id = 'id', $pid = 'parent_id', $son = 'children')
    {
        list($tree, $map) = [[], []];
        foreach ($list as $item) {
            $map[$item[$id]] = $item;
        }

        foreach ($list as $item) {
            if (isset($item[$pid]) && isset($map[$item[$pid]])) {
                $map[$item[$pid]][$son][] = &$map[$item[$id]];
            } else {
                $tree[] = &$map[$item[$id]];
            }
        }
        unset($map);
        return $tree;
    }

    /**
     * 一维数据数组生成数据树（todo 暂时没有用到）
     * @param array $list 数据列表
     * @param string $id ID Key
     * @param string $pid 父ID Key
     * @param string $path
     * @param string $ppath
     * @return array
     */
    public static function arr2table(array $list, $id = 'id', $pid = 'pid', $path = 'path', $ppath = '')
    {
        $tree = [];
        foreach (self::generateTree($list, $id, $pid) as $attr) {
            $attr[$path] = "{$ppath}-{$attr[$id]}";
            $attr['sub'] = isset($attr['sub']) ? $attr['sub'] : [];
            $attr['spt'] = substr_count($ppath, '-');
            $attr['spl'] = str_repeat("　├　", $attr['spt']);
            $sub = $attr['sub'];
            unset($attr['sub']);
            $tree[] = $attr;
            if (!empty($sub)) {
                $tree = array_merge($tree, self::arr2table($sub, $id, $pid, $path, $attr[$path]));
            }
        }
        return $tree;
    }

    /**
     * 获取随机六位字符串（字母+数字）
     * Created by FengLei on 2020/9/23 15:04
     * @param $start
     * @param $length
     * @return false|string
     */
    public static function getRandomString($start, $length)
    {
        $randStr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
        return substr($randStr, $start, $length);
    }
}
