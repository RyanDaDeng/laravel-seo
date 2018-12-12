<?php

namespace App\Modules\SeoAgent\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * @property void $draft_data
 * Class SeoAgentDraftData
 * @package App\Modules\SeoAgent\Models
 */
class SeoAgentDraftData extends Model
{

    protected $table = 'seo_metas';
    protected $casts = [
        'draft_data' => 'array',
        'current_data' => 'array'
    ];
    protected $fillable = ['draft_data'];


}

