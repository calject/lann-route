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
     * @var string
     */
    protected $class = '';
    
    /**
     * @var string
     */
    protected $namespace = '';
    
    /**
     * @var string
     */
    protected $realNamespace = '';
    
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
     * @param string $class
     * @return $this
     */
    public function class(string $class)
    {
        $this->class = $class;
        return $this;
    }
    
    /**
     * @param string $namespace
     * @return $this
     */
    public function namespace(string $namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }
    
    /**
     * @param string $realNamespace
     * @return $this
     */
    public function realNamespace(string $realNamespace)
    {
        $this->realNamespace = $realNamespace;
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
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }
    
    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }
    
    /**
     * @return string
     */
    public function getRealNamespace(): string
    {
        return $this->realNamespace;
    }
    
    /**
     * @return array
     */
    public function toGroupArray(): array
    {
        $group = [];
        if ($prefix = $this->getPrefix()) {
            $group['prefix'] = $prefix;
            // $group['as'] = $prefix;
        }
        if ($middleware = $this->getMiddleware()) {
            $group['middleware'] = $middleware;
        }
        if ($namespace = $this->getRealNamespace()) {
            $group['namespace'] = $namespace;
        }
        return $group;
    }
    
}