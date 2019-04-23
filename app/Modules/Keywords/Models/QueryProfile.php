<?php

namespace App\Modules\Keywords\Models;

use App\Modules\Shared\Traits\InsertOnDuplicateKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class QueryProfile extends Model
{
    use InsertOnDuplicateKey;
    protected $table = 'tbl_gw_query_profiles';
    protected $guarded = [];

    /**
     * @param $index
     * @return mixed
     */
    public static function getPageId($index)
    {
        return explode('_', $index)[0];
    }

    /**
     * @param $index
     * @return mixed
     */
    public static function getKeywordId($index)
    {
        return explode('_', $index)[1];
    }

}

