<?php

namespace App\Modules\Setting\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Modules\Setting\Models\PushSetting
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Setting\Models\PushSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Setting\Models\PushSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Setting\Models\PushSetting query()
 * @mixin \Eloquent
 */
class PushSetting extends Model
{
    const ID = 2;
    protected $table = 'settings';

    protected $fillable =[
        'last_updated'
    ];
}

