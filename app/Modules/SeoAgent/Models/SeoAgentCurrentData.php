<?php

namespace App\Modules\SeoAgent\Models;


use App\Modules\Shared\Traits\InsertOnDuplicateKey;

/**
 * App\Modules\SeoAgent\Models\SeoAgentCurrentData
 *
 * @property-read mixed $draft_at
 * @property-read mixed $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SeoAgent\Models\SeoAgentCurrentData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SeoAgent\Models\SeoAgentCurrentData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SeoAgent\Models\SeoAgentCurrentData query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SeoAgent\Models\SeoAgentBaseModel search($term)
 * @mixin \Eloquent
 */
class SeoAgentCurrentData extends SeoAgentBaseModel
{
    use InsertOnDuplicateKey;

    protected $fillable = [
        'current_data',
        'type',
        'updated_at'
    ];

}

