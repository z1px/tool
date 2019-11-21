<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2019/11/21
 * Time: 10:52 上午
 */


namespace Z1px\Tool;


class Arr
{
    /**
     * 笛卡尔积
     * @param $data
     * @return array
     */
    public static function descartes($data)
    {
        $result = [];
        if(empty($data) || !is_array($data)){
            return $result;
        }
        $current = reset($data);
        if(empty($current) || !is_array($current)){
            return $result;
        }
        $result = array_map(function ($value){
            return [$value];
        }, $current);

        $cursor = next($data);
        while ($cursor && is_array($cursor)){
            $current = $result;
            $result = [];
            foreach ($current as $value){
                foreach ($cursor as $val){
                    $result[] = array_merge($value, [$val]);
                }
            }
            $cursor = next($data);
        }
        return $result;
    }
}
