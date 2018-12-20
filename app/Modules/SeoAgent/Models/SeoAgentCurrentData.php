<?php

namespace App\Modules\SeoAgent\Models;


class SeoAgentCurrentData extends SeoAgentBaseModel
{
    protected $fillable = [
        'current_data',
        'last_approved_at',
        'type'
    ];

}

