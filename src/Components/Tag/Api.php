<?php
/**
 * Author: 沧澜
 * Date: 2019-12-17
 */

namespace Calject\LannRoute\Components\Tag;

use Calject\LannRoute\Components\Model\RouteClassData;
use Calject\LannRoute\Components\Model\RouteFuncData;
use Calject\LannRoute\Constant\RouteConstant;
use Calject\LannRoute\Contracts\AbsAnnotationTag;

/**
 * Class Api
 * @package Calject\LannRoute\Components\Tag
 * @example api(method='get', name='api', ...) | api(method='get,post,..', middleware='api,...')
 */
class Api extends AbsAnnotationTag
{
    
    /**
     * @param RouteFuncData|RouteClassData $route
     * @param array|string $params
     * @return mixed
     */
    public function handle($route, $params)
    {
        $this->doRoutes($route, $params);
    }
    
    /**
     * api(xxx='', xxx='')支持参数
     * @return array|mixed|void|null
     */
    public function tagParams()
    {
        return ['method', 'uri', 'prefix', 'middleware', 'name'];
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