<?php

namespace App\Modules\Setting\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Modules\Setting\Models\PullSetting
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Setting\Models\PullSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Setting\Models\PullSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Setting\Models\PullSetting query()
 * @mixin \Eloquent
 */
class PullSetting extends Model 
{
    const ID = 1;

    protected $table ='settings';

}

