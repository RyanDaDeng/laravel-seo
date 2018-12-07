<?php
/**
 * Created by PhpStorm.
 * User: rayndeng
 * Date: 7/12/18
 * Time: 12:11 PM
 */

namespace App\Modules\SeoAgent\Facades;

use App\Modules\SeoAgent\Repositories\CacheSeoAgentRepositoryDecorator;
use App\Modules\SeoAgent\Repositories\Interfaces\SeoAgentRepositoryInterface;
use Illuminate\Support\Facades\Facade;


/**
 * @method static \Illuminate\Contracts\Pagination\LengthAwarePaginator getSeoMeta($perPage, $orderBy = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object updateSeoMeta($id, $data = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object getSeoMetaById($id)
 *
 * @see \App\Modules\SeoAgent\Repositories\SeoAgentRepository
 * @see CacheSeoAgentRepositoryDecorator
 */
class SeoAgentRepository extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SeoAgentRepositoryInterface::class;
    }
}
