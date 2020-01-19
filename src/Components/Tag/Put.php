<?php
/**
 * Author: 沧澜
 * Date: 2019-12-19
 */

namespace Calject\LannRoute\Components\Tag;

use Calject\LannRoute\Constant\RouteConstant;
use Calject\LannRoute\Contracts\AbsAnnotationMethodTag;

/**
 * Class Post
 * @package Calject\LannRoute\Components\Tag
 * @example @put() | @put('[uri]') | @put(prefix='xxx', middleware='xxx', ...)
 */
class Put extends AbsAnnotationMethodTag
{
    /**
     * @return array|string
     */
    protected function method()
    {
        return RouteConstant::METHOD_PUT;
    }
}