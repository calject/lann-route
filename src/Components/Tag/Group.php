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
 * Class Group
 * @package Calject\LannRoute\Components\Tag
 * @example @group(method='get,post,put,delete', prefix='xxx', middleware='xxx,...') | @group(prefix='xxx')
 */
class Group extends AbsAnnotationTag
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
     * 作用范围, 可选: class, function
     * @return array|string
     */
    public function scope()
    {
        return RouteConstant::SCOPE_CLASS;
    }
    
    /**
     * group过滤参数(数组或者为空)
     * @return array|mixed|null|void
     */
    public function tagParams()
    {
        return ['method', 'middleware', 'prefix'];
    }
    
}