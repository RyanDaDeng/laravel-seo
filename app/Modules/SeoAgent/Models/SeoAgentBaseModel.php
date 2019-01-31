<?php

namespace App\Modules\SeoAgent\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Modules\SeoAgent\Models\SeoAgentBaseModel
 *
 * @OA\Schema (type="object")
 * @OA\Property (property="path",type="string")
 * @OA\Property (property="hash",type="string")
 * @OA\Property (property="current_data",type="object",
 *               @OA\Property(
 *                   property="title",
 *                   description="",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="description",
 *                   description="",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="canonical",
 *                   description="",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="keywords",
 *                   description="",
 *                   type="array",
 *                   @OA\Items(type="string")
 *               )
 * )
 * @property-read mixed $draft_at
 * @property-read mixed $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SeoAgent\Models\SeoAgentBaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SeoAgent\Models\SeoAgentBaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SeoAgent\Models\SeoAgentBaseModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SeoAgent\Models\SeoAgentBaseModel search($term)
 * @mixin \Eloquent
 */
class SeoAgentBaseModel extends Model
{
    CONST TYPE_DEFAULT = 0;
    CONST TYPE_NEW = 1;
    CONST TYPE_IN_DRAFT = 2;

    protected $table = 'seo_metas';

    protected $fillable = [
        'current_data',
        'draft_data',
        'hash',
        'path',
        'draft_at',
        'type'
    ];
    protected $casts = [
        'draft_data' => 'array',
        'current_data' => 'array'
    ];

    /**
     * The columns of the full text index
     */
    protected $searchable = [
        'path',
        'current_data',
        'draft_data'
    ];


    public function getDraftAtAttribute($value)
    {

        return $value ?? Carbon::parse($value)->format('H:i A d/m/Y');
    }


    public function getLastApprovedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i A d/m/Y'):$value;
    }
}

