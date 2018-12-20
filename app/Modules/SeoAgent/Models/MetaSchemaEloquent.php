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
 * @OA\Schema(type="object")
 * @OA\Property(property="title",type="string")
 * @OA\Property(property="description",type="string")
 * @OA\Property(property="canonical",type="string")
 * @OA\Property(
 *     type="array",
 *     property="keywords",
 *       @OA\Items(type="string")
 * )
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
        'canonical' => ''
    ];

    protected $fillable = [
        'title',
        'description',
        'keywords',
        'canonical'
    ];


    public static function rules()
    {
        return [
            'title' => 'string|required',
            'description' => 'string|required',
            'canonical' => 'string|required',
            'keywords' => 'array'
        ];
    }

    public static function schema(
        $title,
        $description,
        $canonical,
        $keywords
    )
    {
        return [
            'meta' => [
                'defaults' => [
                    'title' => $title,
                    'description' => $description,
                    'canonical' => $canonical,
                    'keywords' => $keywords,
                ]
            ]
        ];
    }


    public function toArray()
    {
        $data = parent::attributesToArray();
        return self::schema(
            $data['title'],
            $data['description'],
            $data['canonical'],
            $data['keywords']
        );
    }
}