<?php

namespace App\Modules\SeoAgent\Repositories;

use App\MetaHistory;
use App\Modules\SeoAgent\Models\MetaSchemaEloquent;
use App\Modules\SeoAgent\Models\SeoAgentBaseModel;
use App\Modules\SeoAgent\Models\SeoAgentCurrentData;
use App\Modules\SeoAgent\Models\SeoAgentDraftData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;


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
     * @param $hash
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function getDraftDataByHash($hash)
    {
        $data = SeoAgentDraftData::query()->where('hash', $hash)->first();
        return $data ? $data : [];
    }


    /**
     * @param $perPage
     * @param null $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getOnlyDraftData($perPage, $page = null)
    {
        $data = SeoAgentDraftData::query()->select('hash', 'draft_data', 'type')->where('type', SeoAgentBaseModel::TYPE_IN_DRAFT);
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
        $data->type = SeoAgentDraftData::TYPE_DEFAULT;
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
        $obj->type = SeoAgentBaseModel::TYPE_DEFAULT;
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
     * @param null $status
     * @param null $md5
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getDraftMetaData($perPage, $page = null, $orderBy = null, $orderDesc = null, $wildSearch = null, $type = null, $status = null, $md5 = null)
    {
        $data = SeoAgentDraftData::query();

        if ($md5) {
            $data = $data->where('hash', $md5);
        } else if (filter_var($wildSearch, FILTER_VALIDATE_URL) !== false) {
            $wildSearch = parse_url($wildSearch)['path'];
            if ($wildSearch[0] === '/') {
                $wildSearch[0] = '|';
                $wildSearch = str_replace('|', '', $wildSearch);
            }
            $data->where('path', '=', str_replace('|', '', $wildSearch));
        } else if ($wildSearch !== null) {
            $data->where(function (Builder $q) use ($wildSearch) {
                $q->where('draft_data', 'LIKE', '%' . $wildSearch . '%');
                $q->orWhere('current_data', 'LIKE', '%' . $wildSearch . '%');
            });
        }

        if ($type > 0) {
            $data->where('type', $type);
        }
        if ($status > 0) {
            $data->where('last_history_status', $status);
        }

        if ($orderBy !== null && $orderDesc !== null) {
            $data->orderBy($orderBy, $orderDesc == true ? 'desc' : 'asc');
        } else {
            $data->orderBy('updated_at', 'desc');
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
            $item->type = SeoAgentDraftData::TYPE_IN_DRAFT;
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
            $item->save();
        }
        return $item;
    }

    /**
     * @param $metaId
     * @return MetaHistory[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getHistoryByMetaHash($metaId)
    {
        return MetaHistory::query()->where('seo_meta_hash', $metaId)->orderBy('created_at', 'asc')->get();
    }


    /**
     * @param SeoAgentDraftData $meta
     * @param $comments
     * @param $status
     * @return bool
     */
    public function createHistory(SeoAgentDraftData $meta, $comments, $status)
    {
        if (!MetaHistory::query()->where('seo_meta_hash', $meta->hash)->exists()) {
            $new = new MetaHistory();
            $new->comments = '';
            $new->seo_meta_hash = $meta->hash;
            $new->data = $meta->current_data;
            $new->status = MetaHistory::STATUS_ORIGINAL;
            $new->save();
        }
        $new = new MetaHistory();
        $new->comments = empty($comments) ? '' : $comments;
        $new->seo_meta_hash = $meta->hash;
        $new->data = $meta->draft_data;
        $new->status = $status;
        $new->save();

        if ((int)$status === MetaHistory::STATUS_APPROVED) {
            $meta->current_data = $meta->draft_data;
            $meta->type = SeoAgentBaseModel::TYPE_DEFAULT;
            $metaSchema = new MetaSchemaEloquent();
            $defaultDraft = $metaSchema->toArray();
            $meta->draft_data = $defaultDraft;
        }

        if ((int)$status === MetaHistory::STATUS_REJECTED) {
            $meta->type = SeoAgentBaseModel::TYPE_DEFAULT;
            $metaSchema = new MetaSchemaEloquent();
            $defaultDraft = $metaSchema->toArray();
            $meta->draft_data = $defaultDraft;
        }

        $meta->last_history_id = $new->id;
        $meta->last_history_status = $status;
        $meta->save();
        return true;
    }

}

