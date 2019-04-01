<?php

namespace App\Modules\Keywords\Models;

use App\Modules\Shared\Traits\InsertOnDuplicateKey;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Modules\Keywords\Models\Page
 *
 * @OA\Schema (type="object")
 * @OA\Property (property="id",type="integer")
 * @OA\Property (property="url",type="string")
 * @OA\Property (property="created_at",type="string")
 * @OA\Property (property="updated_at",type="string")
 * @OA\Property (property="md5",type="string")
 */
class Page extends Model
{
    use InsertOnDuplicateKey;
    protected $table = 'tbl_gw_pages';

}

