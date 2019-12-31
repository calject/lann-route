<?php
/**
 * Author: 沧澜
 * Date: 2019-12-17
 */

namespace Calject\LannRoute\Components\Model;

use Calject\LannRoute\Contracts\AbsRouteData;

/**
 * Class RouteClassData
 * @package Calject\LannRoute\Components\Model
 */
class RouteClassData extends AbsRouteData
{
    
    /**
     * @var string
     */
    protected $prefix = '';
    
    /**
     * @param string $prefix
     * @return $this
     */
    public function prefix(string $prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }
    
}