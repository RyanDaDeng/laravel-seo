<?php

namespace App\Modules\SeoAgent\Repositories;

use App\Modules\SeoAgent\Models\MetaSchemaEloquent;
use App\Modules\SeoAgent\Models\SeoAgentBaseModel;
use App\Modules\SeoAgent\Models\SeoAgentCurrentData;
use App\Modules\SeoAgent\Models\SeoAgentDraftData;
use Carbon\Carbon;


class SeoAgentRepository
{

    /**
     * @param $hash
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function getCurrentDataByHash($hash)
    {
        $data = SeoAgentCurrentData::query()->where('hash', $hash)->first();
        return $data ? $data : [];
    }

    /**
     * @param $perPage
     * @param null $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getOnlyDraftData($perPage, $page = null)
    {
        $data = SeoAgentDraftData::query()->where('type', SeoAgentBaseModel::IN_DRAFT);
        return $data->paginate($perPage);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function deleteDraft($id)
    {
        $data = SeoAgentDraftData::query()->where('id', $id)->first();
        $obj = new MetaSchemaEloquent();
        $obj->fill([
            'description' => '',
            'keywords' => [],
            'title' => '',
            'canonical' => ''
        ]);
        $data->draft_data = $obj->toArray();
        $data->draft_at = null;
        $data->type = SeoAgentDraftData::DEFAULT;
        $data->save();
        return $data;
    }

    /**
     * @param $prepare
     * @return SeoAgentBaseModel
     */
    public function createCurrentData($prepare)
    {
        $obj = new SeoAgentBaseModel();
        $obj->current_data = $prepare['current_data'];
        $obj->path = $prepare['path'];
        $obj->hash = $prepare['hash'];
        $obj->draft_data = (new MetaSchemaEloquent())->toArray();
        $obj->type = SeoAgentBaseModel::DEFAULT;
        $obj->save();
        return $obj;
    }

    /**
     * @param $hash
     * @return bool
     */
    public function existsByHash($hash)
    {
        return SeoAgentDraftData::query()->where('hash', $hash)->exists();
    }

    /**
     * @param $id
     * @return bool
     */
    public function exists($id)
    {
        return SeoAgentDraftData::query()->where('id', $id)->exists();
    }

    /**
     * @param $perPage
     * @param null $page
     * @param null $orderBy
     * @param null $orderDesc
     * @param null $wildSearch
     * @param null $type
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getDraftMetaData($perPage, $page = null, $orderBy = null, $orderDesc = null, $wildSearch = null, $type = null)
    {
        $data = SeoAgentDraftData::query();

        if ($wildSearch !== null) {
            $data->search($wildSearch);
        }

        if ($type > 0) {
            $data->where('type', $type);
        }
        if ($orderBy !== null && $orderDesc !== null) {
            $data->orderBy('last_approved_at', $orderDesc == true ? 'desc' : 'asc');
        }
        return $data->paginate($perPage);
    }

    /**
     * @param $id
     * @param MetaSchemaEloquent $metaSchema
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function updateDraftData($id, MetaSchemaEloquent $metaSchema)
    {
        $item = SeoAgentDraftData::query()->where('id', $id)->first();

        if ($item) {
            $item->draft_data = $metaSchema->toArray();
            $item->type = SeoAgentDraftData::IN_DRAFT;
            $item->draft_at = Carbon::now()->format('Y-m-d H:i:s');
            $item->save();
        }
        return $item;
    }


    /**
     * @param $hash
     * @param MetaSchemaEloquent $metaSchema
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function updateCurrentDataByHash($hash, MetaSchemaEloquent $metaSchema)
    {
        $item = SeoAgentDraftData::query()->where('hash', $hash)->first();

        if ($item) {
            $item->current_data = $metaSchema->toArray();
            $item->type = SeoAgentCurrentData::RECENT_APPROVED;
            $item->last_approved_at = Carbon::now()->format('Y-m-d H:i:s');
            $item->save();
        }
        return $item;
    }

}

