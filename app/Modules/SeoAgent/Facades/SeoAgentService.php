<?php

namespace App\Modules\SeoAgent\Facades;

use App\Modules\SeoAgent\Contracts\SeoAgentServiceInterface;
use App\Modules\SeoAgent\Models\MetaSchema;
use App\Modules\SeoAgent\Models\MetaSchemaEloquent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 *  @method static Collection updateCurrentDataByHash($hash, $schema)
 * @method static Collection createCurrentData($prepare)
 * @method static Collection getCurrentDataByHash($id)
 * @method static Collection getOnlyDraftData($perPage, $page)
 * @method static Collection getDraftData($per_page,$page, $orderBy,$orderDesc, $wildSearch, $type)
 * @method static Collection updateDraftData($id,MetaSchemaEloquent $data)
 * @method static Collection deleteDraft($id)
 */
class SeoAgentService extends Facade 
{


    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SeoAgentServiceInterface::class;
    }

}

