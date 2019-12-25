<?php
/**
 * Author: 沧澜
 * Date: 2019-12-19
 */

namespace Calject\LannRoute\Components\Tag;

use Calject\LannRoute\Constant\RouteConstant;
use Calject\LannRoute\Contracts\AbsAnnotationMethodTag;

/**
 * Class Restful
 * @package Calject\LannRoute\Components\Tag
 * @example restful() | restful('[uri]') | restful(prefix='xxx', middleware='xxx', ...)
 */
class Restful extends AbsAnnotationMethodTag
{
    /**
     * @return array|string
     */
    protected function method()
    {
        return RouteConstant::METHOD_RESTFUL;
    }
}