<?php

namespace App\Modules\SeoAgent\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SeoMeta
 * @property $path
 * @property $created_at
 * @property $updated_at
 * @property $hash
 * @property $current_data
 * @property $draft_data
 * @package App\Modules\SeoAgent\Models
 */
class SeoMeta extends Model
{
    //
    protected $fillable = [
        'draft_data'
    ];

    protected $casts = [
        'draft_data' => 'array',
        'current_data' => 'array'
    ];

}
