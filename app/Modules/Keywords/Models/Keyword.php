<?php

namespace App\Modules\Keywords\Models;

use App\Modules\Shared\Traits\InsertOnDuplicateKey;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Modules\Keywords\Models\Keyword
 *
 * @OA\Schema (type="object")
 * @OA\Property (property="id",type="integer")
 * @OA\Property (property="md5",type="string")
 * @OA\Property (property="keyword",type="string")
 * @OA\Property (property="created_at",type="string")
 * @OA\Property (property="updated_at",type="string")
 */
class Keyword extends Model
{
    use InsertOnDuplicateKey;
    protected $table = 'tbl_gw_keywords';
    protected $visible = ['keyword'];
}

