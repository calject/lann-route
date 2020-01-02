<?php
/**
 * Author: 沧澜
 * Date: 2020-01-02
 */

namespace Calject\LannRoute\Helper;


use Calject\LannRoute\Contracts\AbsRouteData;

class RouteDataHelper
{
    /**
     * @param string|array $envs
     * @param AbsRouteData $data
     * @return bool
     */
    public static function checkEnvs($envs, AbsRouteData $data): bool
    {
        return !$data->getEnvs() || (bool)array_intersect((array)$envs, $data->getEnvs());
    }
}