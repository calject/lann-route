<?php
/**
 * Author: 沧澜
 * Date: 2019-12-17
 */

namespace Calject\LannRoute\Contracts;

/**
 * Class AbsItem
 * @package Calject\LannRoute\Contracts
 */
abstract class AbsItem implements ItemInterface
{
    /**
     * @var array
     */
    protected $data = [];
    
    /**
     * @param array|string $data
     * @return static
     */
    public function set($data)
    {
        $this->data = (array)$data;
        return $this;
    }
    
    /**
     * @param string|int $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }
    
    /**
     * @param string|int $key
     * @return mixed
     */
    public function delete($key)
    {
        unset($this->data[$key]);
    }
    
    /**
     * @return array
     */
    public function all(): array
    {
        return $this->data;
    }
    
    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->data);
    }
    
}