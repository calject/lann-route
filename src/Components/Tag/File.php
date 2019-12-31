<?php
/**
 * Author: 沧澜
 * Date: 2019-12-31
 */

namespace Calject\LannRoute\Components\Tag;

use Calject\LannRoute\Components\Model\RouteClassData;
use Calject\LannRoute\Components\Model\RouteFuncData;
use Calject\LannRoute\Constant\RouteConstant;
use Calject\LannRoute\Contracts\AbsAnnotationTag;

/**
 * Class File
 * @package Calject\LannRoute\Components\Tag
 * @example @file(api) | @file(api.php) | @file(test/api.php) | @file('test/api') | ...
 */
class File extends AbsAnnotationTag
{
    
    /**
     * @param RouteFuncData|RouteClassData $route
     * @param array|string $params
     * @return mixed
     */
    public function handle($route, $params)
    {
        $route->file($params);
    }
    
    /**
     * 作用范围, 可选: class, function
     * @return array|string
     */
    public function scope()
    {
        return RouteConstant::SCOPE_CLASS;
    }
}