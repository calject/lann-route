<?php
/**
 * Author: 沧澜
 * Date: 2020-01-19
 */

namespace Calject\LannRoute\Components\Tag;

use Calject\LannRoute\Components\Model\RouteClassData;
use Calject\LannRoute\Components\Model\RouteFuncData;
use Calject\LannRoute\Constant\RouteConstant;
use Calject\LannRoute\Contracts\AbsAnnotationTag;

/**
 * Class Des
 * @package Calject\LannRoute\Components\Tag
 * @example @des(路由描述) | @des('路由描述')
 */
class Des extends AbsAnnotationTag
{
    
    /**
     * @param RouteFuncData|RouteClassData $route
     * @param array|string $params
     * @return mixed
     */
    public function handle($route, $params)
    {
        if (is_array($params)) {
            $route->des(implode("\n", $params));
        } else {
            $route->des($params);
        }
    }
    
    /**
     * 作用范围, 可选: class, function
     * @return array|string
     */
    public function scope()
    {
        return RouteConstant::SCOPE_FUNCTION;
    }
}