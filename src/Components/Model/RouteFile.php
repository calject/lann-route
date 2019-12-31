<?php
/**
 * Author: 沧澜
 * Date: 2019-12-31
 */

namespace Calject\LannRoute\Components\Model;

use Iterator;

/**
 * Class RouteFile
 * @package Calject\LannRoute\Components\Model
 */
class RouteFile
{
    /**
     * @var RouteClassData
     */
    protected $routeClass;
    
    /**
     * @var RouteFuncData[]
     */
    protected $routeFunctions = [];
    
    /**
     * RouteFile constructor.
     * @param RouteClassData $routeClass
     * @param RouteFuncData[] $routeFunctions
     */
    public function __construct(RouteClassData $routeClass = null, array $routeFunctions = [])
    {
        $this->routeClass = $routeClass;
        $this->routeFunctions = $routeFunctions;
    }
    
    
    /**
     * @param RouteClassData $routeClass
     * @return $this
     */
    public function setRouteClass(RouteClassData $routeClass)
    {
        $this->routeClass = $routeClass;
        return $this;
    }
    
    /**
     * @param RouteFuncData[] $routeFunctions
     * @return $this
     */
    public function setRouteFunctions(array $routeFunctions)
    {
        $this->routeFunctions = $routeFunctions;
        return $this;
    }
    
    /**
     * @return RouteClassData
     */
    public function getRouteClass(): RouteClassData
    {
        return $this->routeClass;
    }
    
    
    /**
     * @return RouteFuncData[]
     */
    public function getRouteFunctions(): array
    {
        return $this->routeFunctions;
    }
    
}