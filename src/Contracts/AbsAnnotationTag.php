<?php
/**
 * Author: 沧澜
 * Date: 2019-12-17
 */

namespace Calject\LannRoute\Contracts;

use Calject\LannRoute\Components\Model\RouteClassData;
use Calject\LannRoute\Components\Model\RouteFuncData;
use Calject\LannRoute\Constant\RouteConstant;

/**
 * Class AbsAnnotationTag
 * @package Calject\LannRoute\Contracts
 */
abstract class AbsAnnotationTag implements AnnotationTagInterface
{
    
    /**
     * 设置默认为类名小写(去命名空间)
     * @return string
     */
    public function tag(): string
    {
        return strtolower(substr(static::class, strrpos(static::class, "\\", -1) + 1));
    }
    
    /**
     * tag过滤参数(数组或者为空)
     * @return array|mixed|null|void
     */
    public function tagParams()
    {
        return null;
    }
    
    /**
     * @param string|array $params
     * @return array
     */
    protected function getArray($params): array
    {
        if (is_array($params)) {
            return $params;
        } else {
            return explode(',', str_replace(' ', '', $params));
        }
    }
    
    /**
     * @param RouteFuncData|RouteClassData $route
     * @param array|string $params
     */
    protected function doRoutes($route, $params)
    {
        foreach ((array)$params as $key => $val) {
            $this->doRoute($route, $key, $val);
        }
    }
    
    /**
     * @param RouteFuncData|RouteClassData $route
     * @param string $key
     * @param array|string $val
     */
    protected function doRoute($route, $key, $val)
    {
        if (in_array($key, RouteConstant::PARAMS_TYPE_STRING, true)) {
            $route->{$key}($val);
        } elseif (in_array($key, RouteConstant::PARAMS_TYPE_ARRAY, true)) {
            is_array($val) || $val = explode(',', str_replace(' ', '', $val));
            $route->{$key}()->set($val);
        }
    }
}