<?php
/**
 * Created by PhpStorm.
 * User: rayndeng
 * Date: 18/12/18
 * Time: 10:52 AM
 */

namespace App\Modules\SeoAgent\Models;


use Illuminate\Database\Eloquent\Model;


/**
 * App\Modules\SeoAgent\Models\MetaSchemaEloquent
 *
 * @OA\Schema (type="object")
 * @OA\Property (property="title",type="string")
 * @OA\Property (property="description",type="string")
 * @OA\Property (property="canonical",type="string")
 * @OA\Property (
 *     type="array",
 *     property="keywords",
 *       @OA\Items(type="string")
 * )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SeoAgent\Models\MetaSchemaEloquent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SeoAgent\Models\MetaSchemaEloquent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SeoAgent\Models\MetaSchemaEloquent query()
 * @mixin \Eloquent
 */
class MetaSchemaEloquent extends Model
{

    public $title;
    public $desc;
    public $keywords;
    public $canonical;

    protected $attributes = [
        'keywords' => [],
        'title' => '',
        'description' => '',
        'canonical' => '',
        'is_agent' => true,
        'user_name' => ''
    ];

    protected $fillable = [
        'title',
        'description',
        'keywords',
        'canonical',
        'is_agent',
        'user_name'
    ];


    public static function rules()
    {
        return [
            'title' => 'string|required',
            'description' => 'string|required',
            'canonical' => 'string|required',
            'keywords' => 'array',
            'is_agent' => 'bool',
            'user_name' => 'string'
        ];
    }

    public static function schema(
        $title,
        $description,
        $canonical,
        $keywords,
        $isAgent,
        $userName
    )
    {
        return [
            'meta' => [
                'defaults' => [
                    'title' => $title,
                    'description' => $description,
                    'canonical' => $canonical,
                    'keywords' => $keywords,
                    'is_agent' => $isAgent,
                    'user_name' => $userName
                ]
            ]
        ];
    }


    public function toArray()
    {
        $data = parent::attributesToArray();
        return self::schema(
            $data['title'] === null ? '' : $data['title'],
            $data['description'] === null ? '' : $data['description'],
            $data['canonical'] === null ? '' : $data['canonical'],
            $data['keywords'] === null ? [] : $data['keywords'],
            $data['is_agent'],
            $data['user_name']=== null ? '' : $data['user_name']
        );
    }
}