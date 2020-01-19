<?php
/**
 * Author: 沧澜
 * Date: 2019-12-17
 */

namespace Calject\LannRoute\Constant;

/**
 * Interface RouteConstant
 * @package Calject\LannRoute\Constant
 */
interface RouteConstant
{
    const NONE = 'none';
    
    const ENV_NONE = 'null';
    
    const SCOPE_CLASS = 'class';
    const SCOPE_FUNCTION = 'function';
    const SCOPE_ALL = [self::SCOPE_CLASS, self::SCOPE_FUNCTION];
    
    const PARAMS_TYPE_ARRAY = ['method', 'uri', 'middleware', 'other'];
    const PARAMS_TYPE_STRING = ['name', 'prefix', 'action'];
    
    
    const METHOD_GET = 'get';
    const METHOD_POST = 'post';
    const METHOD_PUT = 'put';
    const METHOD_DELETE = 'delete';
    const METHOD_RESTFUL = ['get', 'post', 'put', 'delete'];
    const METHOD_ALL = ['get', 'post', 'put', 'delete', 'patch'];
    
}