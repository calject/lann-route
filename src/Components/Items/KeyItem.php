<?php
/**
 * Author: 沧澜
 * Date: 2019-12-17
 */

namespace Calject\LannRoute\Components\Items;

use Calject\LannRoute\Contracts\AbsItem;

/**
 * Class KeyItem
 * @package Calject\LannRoute\Components\Items
 */
class KeyItem extends AbsItem
{
    /**
     * @param array|string $data
     * @return static
     */
    public function add($data)
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }
}