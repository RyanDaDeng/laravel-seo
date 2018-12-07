<?php
/**
 * Created by PhpStorm.
 * User: rayndeng
 * Date: 7/12/18
 * Time: 11:07 AM
 */

namespace App\Modules\SeoAgent\Repositories;


use App\Modules\SeoAgent\Repositories\Interfaces\SeoAgentRepositoryInterface;
use App\Modules\SeoAgent\Models\SeoMeta;
use Illuminate\Database\Query\Builder;


class SeoAgentRepository implements SeoAgentRepositoryInterface
{

    /**
     * @param $perPage
     * @param null $orderBy
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getSeoMeta($perPage, $orderBy = null)
    {

        $query = SeoMeta::query();

        $query->when($orderBy !== null, function (Builder $query) use ($orderBy) {
            return $query->orderBy($orderBy);
        });

        return $query->paginate($perPage);
    }

    /**
     * @param $id
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function updateSeoMeta($id, $data = [])
    {
        $obj = SeoMeta::query()->where('id', $id)->first();

        if ($obj) {
            $obj->fill($data);
            $obj->save();
        }

        return $obj;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function getSeoMetaById($id)
    {
        $obj = SeoMeta::query()->where('id', '=', $id)->first();
        return $obj;
    }
}