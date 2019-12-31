<?php
/**
 * Author: 沧澜
 * Date: 2019-12-31
 */

namespace Calject\LannRoute\Utils;


use Closure;

class FileUtil
{
    
    /**
     * 读取目录下所有文件名
     * @param string $path 读取的路径
     * @param Closure|null $fileHandle
     * @param bool $finalSet
     * @return array
     */
    final public static function getFilesHandle(string $path, Closure $fileHandle = null, bool $finalSet = true)
    {
        $files = [];
        $path = realpath($path);
        if(!is_dir($path)) return [];
        if($handle = opendir($path)) {
            while (($fl = readdir($handle)) !== false) {
                $temp = $path . DIRECTORY_SEPARATOR . $fl;
                if (is_dir($temp) && !in_array($fl, ['.', '..'])) {
                    $files = array_merge_recursive($files, self::getFilesHandle($temp, $fileHandle, $finalSet));
                } else {
                    if (!in_array($fl, ['.', '..']) && !is_dir($temp)) {
                        $fileHandle && call_user_func_array($fileHandle, [$temp, $files]);
                        $finalSet && $files[] = $temp;
                    }
                }
            }
        }
        return $files;
    }
    
    /**
     * 读取目录下所有文件名
     * @param string $path      读取的路径
     * @param bool $recursion   是否递归查找所有子目录 true: 查找所有子目录 false:仅查找指定目录，不遍历子目录
     * @return array
     */
    final public static function readFilesInDir(string $path, $recursion = true)
    {
        $files = [];
        $path = realpath($path);
        if(!is_dir($path)) return [$path];
        if($handle = opendir($path)) {
            while (($fl = readdir($handle)) !== false) {
                $temp = $path . DIRECTORY_SEPARATOR . $fl;
                if ($recursion && is_dir($temp) && !in_array($fl, ['.', '..'])) {
                    $files = array_merge_recursive($files, self::readFilesInDir($temp));
                } else {
                    if (!in_array($fl, ['.', '..']) && !is_dir($temp)) {
                        $files[] = $temp;
                    }
                }
            }
        }
        return $files;
    }
}