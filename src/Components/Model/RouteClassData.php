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
    protected $file = '';
    
    /**
     * @param string $file
     * @return $this
     */
    public function file(string $file)
    {
        $this->file = $file;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }
    
    /**
     * @return array
     */
    public function toGroupArray(): array
    {
        $group = [];
        if ($prefix = $this->getPrefix()) {
            $group['prefix'] = $prefix;
            $group['as'] = $prefix;
        }
        if ($middleware = $this->getMiddleware()) {
            $group['middleware'] = $middleware;
        }
        return $group;
    }
    
}