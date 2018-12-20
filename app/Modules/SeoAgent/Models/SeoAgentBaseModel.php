<?php

namespace App\Modules\SeoAgent\Models;

use App\Modules\Shared\Traits\FullTextSearch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(type="object")
 * @OA\Property(property="path",type="string")
 * @OA\Property(property="hash",type="string")
 * @OA\Property(property="current_data",type="object",
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
 */
class SeoAgentBaseModel extends Model
{
    use FullTextSearch;

    CONST DEFAULT = 0;
    CONST RECENT_APPROVED = 1;
    CONST IN_DRAFT = 2;

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

        return $value ?? Carbon::parse($value, 'UTC')->setTimezone('Australia/Sydney')->format('H:i A d/m/Y');
    }


    public function getLastApprovedAtAttribute($value)
    {
        return $value ?? Carbon::parse($value, 'UTC')->setTimezone('Australia/Sydney')->format('H:i A d/m/Y');
    }
}

