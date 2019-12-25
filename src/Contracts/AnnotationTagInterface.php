<?php
/**
 * Author: 沧澜
 * Date: 2019-12-17
 */

namespace Calject\LannRoute\Contracts;

use Calject\LannRoute\Components\Model\RouteClassData;
use Calject\LannRoute\Components\Model\RouteFuncData;

/**
 * Interface IAnnotationTag
 * @package Calject\LannRoute\Contracts
 */
interface AnnotationTagInterface
{
    /**
     * @return string
     */
    public function tag(): string;
    
    /**
     * @param RouteFuncData|RouteClassData $route
     * @param array|string $params
     * @return mixed
     */
    public function handle($route, $params);
    
    /**
     * tag过滤参数(数组或者为空)
     * @return array|mixed|null|void
     */
    public function tagParams();
    
    /**
     * 作用范围, 可选: class, function
     * @return array|string
     */
    public function scope();
    
}