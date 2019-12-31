<?php
/**
 * Author: 沧澜
 * Date: 2019-12-17
 */

namespace Calject\LannRoute\Components\Model;

use Calject\LannRoute\Components\Items\ArrayItem;
use Calject\LannRoute\Contracts\AbsRouteData;
use Calject\LannRoute\Contracts\ItemInterface;

/**
 * Class RouteFuncData
 * @package Calject\LannRoute\Components\Model
 */
class RouteFuncData extends AbsRouteData
{
    /**
     * @var ItemInterface
     */
    protected $uri;
    
    /**
     * @var string
     */
    protected $action = '';
    
    /**
     * @var string
     */
    protected $name = '';
    
    /**
     * init
     * @return mixed|void
     */
    protected function init()
    {
        $this->uri = new ArrayItem();
    }
    
    /*---------------------------------------------- set ----------------------------------------------*/
    
    /**
     * @return ItemInterface
     */
    public function uri(): ItemInterface
    {
        return $this->uri;
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
     * @param string $name
     * @return $this
     */
    public function name(string $name)
    {
        $this->name = $name;
        return $this;
    }
    
    
    /*---------------------------------------------- get ----------------------------------------------*/
    
    /**
     * @return array
     */
    public function getUri(): array
    {
        return $this->uri->all();
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
    public function getName(): string
    {
        return $this->name;
    }
    
}