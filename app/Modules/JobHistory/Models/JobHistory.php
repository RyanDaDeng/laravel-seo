<?php

namespace App\Modules\JobHistory\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * Class JobHistory
 * @package App\Modules\JobHistory\Models
 */
class JobHistory extends Model
{
    //

    protected $guarded = [];
    const PENDING = 0;
    const IN_PROCESSING = 1;
    const FINISHED = 2;
    const ERROR = 3;

    const STATUS_MAP = [
        0 => 'PENDING',
        1 => 'IN_PROCESSING',
        2 => 'FINISHED',
        3 => 'ERROR'
    ];

    protected $appends = [
        'status_name'
    ];

    public static function getName($value)
    {
        return isset(self::STATUS_MAP[$value]) ? self::STATUS_MAP[$value] : 'Unknown';
    }


    public function getStatusNameAttribute()
    {
        return isset(self::STATUS_MAP[$this->attributes['status']]) ? self::STATUS_MAP[$this->attributes['status']] : 'Unknown';
    }
}
