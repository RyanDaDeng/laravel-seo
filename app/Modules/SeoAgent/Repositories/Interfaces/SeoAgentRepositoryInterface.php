<?php
/**
 * Created by PhpStorm.
 * User: rayndeng
 * Date: 7/12/18
 * Time: 12:02 PM
 */

namespace App\Modules\SeoAgent\Repositories\Interfaces;


interface SeoAgentRepositoryInterface
{
    /**
     * @param $perPage
     * @param null $orderBy
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getSeoMeta($perPage, $orderBy = null);

    /**
     * @param $id
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function updateSeoMeta($id, $data = []);


    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function getSeoMetaById($id);
}