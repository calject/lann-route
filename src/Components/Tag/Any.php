<?php
/**
 * Author: 沧澜
 * Date: 2019-12-25
 */

namespace Calject\LannRoute\Components\Tag;

use Calject\LannRoute\Constant\RouteConstant;
use Calject\LannRoute\Contracts\AbsAnnotationMethodTag;

/**
 * Class Any
 * @package Calject\LannRoute\Components\Tag
 * @example any() | any('[uri]') | any(prefix='xxx', middleware='xxx', ...)
 */
class Any extends AbsAnnotationMethodTag
{
    
    /**
     * @return array|string
     */
    protected function method()
    {
        return RouteConstant::METHOD_ALL;
    }
}