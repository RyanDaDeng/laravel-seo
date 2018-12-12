<?php

namespace App\Modules\SeoAgent\Repositories;

use App\Modules\SeoAgent\Models\SeoAgentDraftData;
use Illuminate\Support\Collection;


class SeoAgentRepository
{


    /**
 * @param $perPage
 * @param null $page
 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
 */
    public function getDraftMetaData($perPage, $page = null)
    {
        return SeoAgentDraftData::query()->paginate($perPage);
    }

    /**
     * @param $id
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function updateDraftData($id, $data = [])
    {
        $item = SeoAgentDraftData::query()->where('id', $id)->first();

        if ($item) {
            $item->draft_data = $data['draft_data'];
            $item->save();
        }
        return $item;
    }

}

