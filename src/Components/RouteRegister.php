<?php
/**
 * Author: 沧澜
 * Date: 2019-12-17
 */

namespace Calject\LannRoute\Components;

use Calject\LannRoute\Constant\RouteConstant;
use Calject\LannRoute\Contracts\AnnotationTagInterface;
use CalJect\Productivity\Components\DataProperty\CallDataProperty;

/**
 * Class RouteRegister
 * @package Calject\LannRoute\Components
 */
class RouteRegister extends CallDataProperty
{
    /**
     * @var AnnotationTagInterface[]
     */
    protected $tags;
    
    /**
     * @var array
     */
    protected $scopeTags;
    
    /**
     * @param AnnotationTagInterface $annotationTag
     * @return RouteRegister
     */
    public function register(AnnotationTagInterface $annotationTag)
    {
        $tag = $annotationTag->tag();
        $this->tags[$tag] = $annotationTag;
        if ($this->checkScope(RouteConstant::SCOPE_CLASS, $annotationTag)) {
            $this->scopeTags[RouteConstant::SCOPE_CLASS][$tag] = $annotationTag;
        }
        if ($this->checkScope(RouteConstant::SCOPE_FUNCTION, $annotationTag)) {
            $this->scopeTags[RouteConstant::SCOPE_FUNCTION][$tag] = $annotationTag;
        }
        return $this;
    }
    
    /**
     * @param array $tags
     * @return $this
     */
    public function registers(array $tags)
    {
        foreach ($tags as $tag) {
            if ($tag instanceof AnnotationTagInterface) {
                $this->register($tag);
            }
        }
        return $this;
    }
    
    /**
     * @param string $tag
     * @param string $scope
     * @param mixed $default
     * @return AnnotationTagInterface|null
     */
    public function get(string $tag, string $scope, $default = null)
    {
        return $this->scopeTags[$scope][$tag] ?? $default;
    }
    
    
    /**
     * @return array
     */
    public function all()
    {
        return $this->tags;
    }
    
    /**
     * @param string $scope
     * @param AnnotationTagInterface $annotationTag
     * @return bool
     */
    private function checkScope(string $scope, AnnotationTagInterface $annotationTag): bool
    {
        return in_array($scope, (array)$annotationTag->scope());
    }
    
}