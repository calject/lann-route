<?php
/**
 * Author: 沧澜
 * Date: 2019-12-17
 */

namespace Calject\LannRoute\Components\Model;


use Calject\LannRoute\Components\Items\ArrayItem;
use Calject\LannRoute\Components\Items\KeyItem;
use Calject\LannRoute\Contracts\ItemInterface;

class RouteFuncData
{
    /**
     * @var ItemInterface
     */
    protected $method;
    
    /**
     * @var ItemInterface
     */
    protected $uri;
    
    /**
     * @var ItemInterface
     */
    protected $middleware;
    
    /**
     * @var string
     */
    protected $action = '';
    
    /**
     * @var string
     */
    protected $prefix = '';
    
    /**
     * @var string
     */
    protected $name = '';
    
    /**
     * @var ItemInterface
     */
    protected $other;
    
    /**
     * AnnotationCache constructor.
     */
    public function __construct()
    {
        $this->method = new ArrayItem();
        $this->uri = new ArrayItem();
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
    public function uri(): ItemInterface
    {
        return $this->uri;
    }
    
    /**
     * @return ItemInterface
     */
    public function middleware(): ItemInterface
    {
        return $this->middleware;
    }
    
    /**
     * @param string $action
     * @return $this
     */
    public function action(string $action)
    {
        $this->action = $action;
        return $this;
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
     * @param string $name
     * @return $this
     */
    public function name(string $name)
    {
        $this->name = $name;
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
    public function getMethods(): array
    {
        return $this->method->all();
    }
    
    /**
     * @return array
     */
    public function getUri(): array
    {
        return $this->uri->all();
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
    public function getAction(): string
    {
        return $this->action;
    }
    
    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @return array
     */
    public function getOther(): array
    {
        return $this->other->all();
    }
    
}