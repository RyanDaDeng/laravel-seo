<?php

namespace App\Modules\Keywords\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class QueryProfile extends Model
{

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


    public static function get($index)
    {

        $pageId = self::getPageId($index);
        $keywordId = self::getKeywordId($index);
        $page = Page::query()->where('id', $pageId)->first();
        $keyword = Keyword::query()->where('id', $keywordId)->first();

        if ($page && $keyword) {
            Log::info('1');
            $obj =  QueryProfile::query()->where('index', $index)->first();
            if(!$obj){
                Log::info('2');
                $obj = new QueryProfile();
                $obj->ctr_benchmark = 0;
                $obj->click_potential = 0;
                $obj->index = $index;
                $obj->page = $pageId;
                $obj->save();
                return $obj;
            }
            Log::info($obj->toJson());
            return $obj;
        }

        return null;
    }
}

