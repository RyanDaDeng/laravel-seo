<?php

namespace App\Modules\Setting\Models;

use Illuminate\Database\Eloquent\Model;


class PushSetting extends Model
{
    const ID = 2;
    protected $table = 'settings';

    protected $fillable =[
        'last_updated'
    ];
}

