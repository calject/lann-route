<?php
/**
 * Author: 沧澜
 * Date: 2019-12-17
 */

namespace Calject\LannRoute\Contracts;


interface ItemInterface
{
    /**
     * @param array|string $data
     * @return static
     */
    public function add($data);
    
    /**
     * @param array|string $data
     * @return static
     */
    public function set($data);
    
    /**
     * @param string|int $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get($key, $default = null);
    
    /**
     * @param string|int $key
     * @return mixed
     */
    public function delete($key);
    
    /**
     * @return array
     */
    public function all(): array;
    
    /**
     * @return bool
     */
    public function isEmpty(): bool;
    
}