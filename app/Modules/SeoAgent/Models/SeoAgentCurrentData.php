<?php

namespace App\Modules\SeoAgent\Models;


use App\Modules\Shared\Traits\InsertOnDuplicateKey;

class SeoAgentCurrentData extends SeoAgentBaseModel
{
    use InsertOnDuplicateKey;

    protected $fillable = [
        'current_data',
        'last_approved_at',
        'type',
        'updated_at'
    ];

}

