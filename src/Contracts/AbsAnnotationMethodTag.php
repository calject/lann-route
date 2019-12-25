<?php
/**
 * Author: 沧澜
 * Date: 2019-12-19
 */

namespace Calject\LannRoute\Contracts;


use Calject\LannRoute\Components\Model\RouteClassData;
use Calject\LannRoute\Components\Model\RouteFuncData;
use Calject\LannRoute\Constant\RouteConstant;

/**
 * Class AbsAnnotationMethodTag
 * @package Calject\LannRoute\Contracts
 * @xxx() | @xxx('[uri]') | @xxx(prefix='xxx', middleware='xxx', ...)
 */
abstract class AbsAnnotationMethodTag extends AbsAnnotationTag
{
    
    /**
     * @return array|string
     */
    abstract protected function method();
    
    /**
     * @param RouteFuncData|RouteClassData $route
     * @param array|string $params
     * @return mixed
     */
    public function handle($route, $params)
    {
        $route->method()->set($this->method());
        if (is_array($params)) {
            $this->doRoutes($route, $params);
        } else if($route instanceof RouteFuncData) {
            $route->uri()->set((array)$params);
        }
    }
    
    /**
     * 作用范围, 可选: class, function
     * @return array|string
     */
    public function scope()
    {
        return RouteConstant::SCOPE_ALL;
    }
    
    /**
     * tag过滤参数(数组或者为空)
     * @return array|mixed|null|void
     */
    public function tagParams()
    {
        return ['middleware', 'prefix', 'uri', 'name'];
    }
}