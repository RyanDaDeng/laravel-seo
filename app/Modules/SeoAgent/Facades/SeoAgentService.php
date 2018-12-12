<?php

namespace App\Modules\SeoAgent\Facades;

use App\Modules\SeoAgent\Contracts\SeoAgentServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Collection getDraftData($per_page,$page)
 * @method static Collection updateDraftData($id,$data =[])
 * @method static Collection getChangeRequests($per_page,$page)
 * @method static Collection updateChangeRequests($hash_id,$data =[])
 * @method static Collection createChangeRequests($data =[])
 * @method static Collection bulkUpdateOrInsertChangeRequests($data =[])
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

