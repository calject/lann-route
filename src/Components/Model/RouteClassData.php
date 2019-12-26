<?php
/**
 * Author: 沧澜
 * Date: 2019-12-17
 */

namespace Calject\LannRoute\Components\Model;


use Calject\LannRoute\Components\Items\ArrayItem;
use Calject\LannRoute\Components\Items\KeyItem;
use Calject\LannRoute\Contracts\ItemInterface;

class RouteClassData
{
    
    /**
     * @var ItemInterface
     */
    protected $method;
    
    /**
     * @var ItemInterface
     */
    protected $middleware;
    
    /**
     * @var string
     */
    protected $prefix = '';
    
    /**
     * @var ItemInterface
     */
    protected $other;
    
    
    /**
     * RouteClassData constructor.
     */
    public function __construct()
    {
        $this->method = new ArrayItem();
        $this->middleware = new ArrayItem();
        $this->other = new KeyItem();
    }
    
    /*---------------------------------------------- set ----------------------------------------------*/
    
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
     * @param string $prefix
     * @return $this
     */
    public function prefix(string $prefix)
    {
        $this->prefix = $prefix;
        return $this;
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
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }
    
    /**
     * @return array
     */
    public function getOther(): array
    {
        return $this->other->all();
    }
    
}