<?php
/**
 * Created by PhpStorm.
 * User: rayndeng
 * Date: 7/12/18
 * Time: 11:07 AM
 */

namespace App\Modules\SeoAgent\Repositories;

use App\Modules\SeoAgent\Repositories\Interfaces\SeoAgentRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class CacheSeoAgentRepositoryDecorator extends SeoAgentRepository implements SeoAgentRepositoryInterface
{
    /**
     * @param $perPage
     * @param null $orderBy
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getSeoMeta($perPage, $orderBy = null)
    {
        return Cache::remember('getSeoMeta', 5, function () use ($perPage, $orderBy) {
            return parent::getSeoMeta($perPage, $orderBy);
        });
    }
}