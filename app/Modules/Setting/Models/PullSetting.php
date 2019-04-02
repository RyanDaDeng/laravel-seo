<?php

namespace App\Modules\Setting\Models;



/**
 * App\Modules\Setting\Models\PullSetting
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Setting\Models\PullSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Setting\Models\PullSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Setting\Models\PullSetting query()
 * @mixin \Eloquent
 */
class PullSetting extends AllSetting
{
    const ID = 1;

    protected $table ='settings';

}

