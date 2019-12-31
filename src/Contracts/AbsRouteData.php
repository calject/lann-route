<?php
/**
 * Author: 沧澜
 * Date: 2019-12-31
 */

namespace Calject\LannRoute\Contracts;

use Calject\LannRoute\Components\Items\ArrayItem;
use Calject\LannRoute\Components\Items\KeyItem;

/**
 * Class AbsRouteData
 * @package Calject\LannRoute\Contracts
 */
abstract class AbsRouteData
{
    
    /**
     * @var string
     */
    protected $prefix = '';
    
    /**
     * @var array
     */
    protected $envs = [];
    
    /**
     * @var ItemInterface
     */
    protected $method;
    
    /**
     * @var ItemInterface
     */
    protected $middleware;
    
    /**
     * @var ItemInterface
     */
    protected $other;
    
    /**
     * AbsRouteData constructor.
     */
    public function __construct()
    {
        $this->method = new ArrayItem();
        $this->middleware = new ArrayItem();
        $this->other = new KeyItem();
        $this->init();
    }
    
    /**
     * init
     * @return mixed
     */
    protected function init()
    {
    
    }
    
    /*---------------------------------------------- set ----------------------------------------------*/
    
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
     * @param array $envs
     * @return $this
     */
    public function envs(array $envs)
    {
        $this->envs = $envs;
        return $this;
    }
    
    /**
     * @return ItemInterface
     */
    public function method(): ItemInterface
    {
        return $this->method;
    }
    
    /**
     * @return ItemInterface
     */
    public function middleware(): ItemInterface
    {
        return $this->middleware;
    }
    
    /**
     * @return ItemInterface
     */
    public function other(): ItemInterface
    {
        return $this->other;
    }
    
    /*---------------------------------------------- get ----------------------------------------------*/
    
    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }
    
    /**
     * @return array
     */
    public function getEnvs(): array
    {
        return $this->envs;
    }
    
    /**
     * @return array
     */
    public function getMethod(): array
    {
        return $this->method->all();
    }
    
    /**
     * @return array
     */
    public function getMiddleware(): array
    {
        return $this->middleware->all();
    }
    
    /**
     * @return array
     */
    public function getOther(): array
    {
        return $this->other->all();
    }
    
}