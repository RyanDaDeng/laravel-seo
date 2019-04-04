<?php
/**
 * Created by PhpStorm.
 * User: rayndeng
 * Date: 29/3/19
 * Time: 1:49 PM
 */

namespace App\Modules\Keywords\Controllers\Api\V1;


use App\Modules\DataMigration\Services\DataMigrationService;
use App\Modules\Keywords\Models\Keyword;
use App\Modules\Keywords\Models\Page;
use App\Modules\Keywords\Models\QueryDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KeywordApiController
{

    /**
     * @OA\Patch(
     *     path="/keywords/v1/pages",
     *     tags={"Keywords"},
     *     summary="Admin will use it to push Google Console Page data",
     *     description="Bulk update or insert data",
     *     deprecated=false,
     *     @OA\RequestBody(
     *          description="Data required to create it",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Items(type="array",
     *              @OA\Items(ref="#/components/schemas/Page"))
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="create a resource"
     *     )
     * )
     */
    public function syncPages(Request $request)
    {
        try{
            Page::insertOnDuplicateKey(array_values($request->all()['data']), ['id', 'md5', 'url', 'created_at', 'updated_at']);
            $migration = new DataMigrationService();
            $migration->generatePathMd5ForGooglePage();
            return ['success' => true];
        }catch(\Exception $e){
            Log::error($e);
            return ['success' => false];
        }

    }


    public function getPathByUrl($url)
    {
        $url = parse_url($url)['path'];
        $path = substr($url, 0, 1) === '/' ? substr($url, 1) : $url;
        return $path === '' ? '/' : $path;
    }

    /**
     * @OA\Patch(
     *     path="/keywords/v1/keywords",
     *     tags={"Keywords"},
     *     summary="Admin will use it to push Keyword data",
     *     description="Bulk update or insert data",
     *     deprecated=false,
     *     @OA\RequestBody(
     *          description="Data required to create it",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Items(type="array",
     *              @OA\Items(ref="#/components/schemas/Keyword"))
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="create a resource"
     *     )
     * )
     */
    public function syncKeywords(Request $request)
    {
        try{
            Keyword::insertOnDuplicateKey(array_values($request->all()['data']), ['id', 'md5', 'keyword', 'created_at', 'updated_at']);
            return ['success' => true];
        }catch(\Exception $e){
            Log::error($e);
            return ['success' => false];
        }

    }


    /**
     * @OA\Patch(
     *     path="/keywords/v1/query-details",
     *     tags={"Keywords"},
     *     summary="Admin will use it to push Google Console query details",
     *     description="Bulk update or insert data",
     *     deprecated=false,
     *     @OA\RequestBody(
     *          description="Data required to create it",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Items(type="array",
     *              @OA\Items(ref="#/components/schemas/QueryDetails"))
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="create a resource"
     *     )
     * )
     */
    public function syncQueryDetails(Request $request)
    {
        try{
            QueryDetails::insertOnDuplicateKey(array_values($request->all()['data']), ['id', 'date', 'keyword', 'device', 'clicks','ctr','impressions','position']);
            $migration = new DataMigrationService();
            $migration->queryDetailsIndexMigration();
            return ['success' => true];
        }catch(\Exception $e){
            Log::error($e);
            return ['success' => false];
        }
    }
}