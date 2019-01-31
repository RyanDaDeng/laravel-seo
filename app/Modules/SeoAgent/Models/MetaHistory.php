<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\MetaHistory
 *
 * @property $status
 * @property $comments
 * @property $seo_meta_hash
 * @property $data
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MetaHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MetaHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MetaHistory query()
 * @mixin \Eloquent
 */
class MetaHistory extends Model
{
    //
    const STATUS_ORIGINAL = 3;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;

    /**
     * @return array
     */
    public static function getStatusList()
    {

        return [
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
            self::STATUS_ORIGINAL,
        ];
    }

    /**
     * @param $id
     * @return string
     */
    public static function getStatusMap($id)
    {
        switch ($id) {
            case self::STATUS_APPROVED:
                return 'Approved';
            case self::STATUS_REJECTED:
                return 'Rejected';
            case self::STATUS_ORIGINAL:
                return 'Original';
            default:
                return 'Unknown';
        }
    }

    protected $casts = [
        'data' => 'array'
    ];

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i A d/m/Y') : $value;
    }


    public function getStatusAttribute($value)
    {
        return MetaHistory::getStatusMap($value);
    }
}
