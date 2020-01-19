<?php
/**
 * Author: 沧澜
 * Date: 2020-01-19
 */

namespace Calject\LannRoute\Utils;


class StrUtil
{
    
    /**
     * @param string $str
     * @param string $split
     * @return string
     */
    public static function leftTo(string $str, string $split)
    {
        if ($len = strrpos($str, $split)) {
            return substr($str, 0, $len);
        }
        return $str;
    }
    
    /**
     * @param string $str
     * @param string $split
     * @return string
     */
    public static function rightTo(string $str, string $split)
    {
        if ($len = strrpos($str, $split)) {
            return substr($str, $len + 1);
        }
        return $str;
    }
    
    /**
     * @param array $array
     * @return mixed
     */
    public static function arrayToStr(array $array)
    {
        return str_replace(
            ['{', '}', ':', ','],
            ['[', ']', ' => ', ', '],
            json_encode($array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
        
    }
    
}