<?php

namespace App\Modules\Keywords\Models;

use App\Modules\Shared\Traits\InsertOnDuplicateKey;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Modules\Keywords\Models\Page
 *
 * @OA\Schema (type="object")
 * @OA\Property (property="id",type="integer")
 * @OA\Property (property="page",type="string")
 * @OA\Property (property="keyword",type="string")
 * @OA\Property (property="device",type="string")
 * @OA\Property (property="clicks",type="string")
 * @OA\Property (property="ctr",type="string")
 * @OA\Property (property="impressions",type="string")
 * @OA\Property (property="position",type="string")
 */
class QueryDetails extends Model
{

    use InsertOnDuplicateKey;
    protected $table = 'tbl_gw_query_details';
    public $timestamps = false;

    const DEVICE_DESKTOP = 1;
    const DEVICE_MOBILE = 2;
    const DEVICE_TABLET = 3;

    /**
     * Get the phone record associated with the user.
     */
    public function keyword()
    {
        return $this->hasOne(Keyword::class, 'id', 'keyword');
    }


    public function getDeviceAttribute($value)
    {
        return self::getDeviceNameById($value);
    }


    public static function getDeviceNameById($value){
        switch ($value) {
            case self::DEVICE_DESKTOP:
                return 'desktop';
            case self::DEVICE_MOBILE:
                return 'mobile';
            case self::DEVICE_TABLET:
                return 'tablet';
            default:
                return 'unknown';
        }
    }


    public static function getDeviceTypeByName($value){
        switch ($value) {
            case 'desktop':
                return self::DEVICE_DESKTOP;
            case 'mobile':
                return self::DEVICE_MOBILE;
            case 'tablet':
                return self::DEVICE_TABLET;
            default:
                return 'unknown';
        }
    }

    public function getPositionAttribute($value)
    {
        return round($value,2);
    }


    public function getCtrAttribute($value)
    {
        return round($value,2);
    }

    public function getImpressionsAttribute($value)
    {
        return round($value,2);
    }
}

