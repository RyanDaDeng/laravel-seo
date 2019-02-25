<?php

namespace App\Modules\SeoAgent\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Modules\SeoAgent\Facades\SeoAgentService;
use App\Modules\SeoAgent\Models\MetaSchemaEloquent;
use App\Modules\SeoAgent\Models\SeoAgentBaseModel;
use App\Modules\SeoAgent\Models\SeoAgentCurrentData;
use App\Modules\SeoAgent\Requests\Api\V1\SeoAgentBulkUpdateOrInsertMetaRequest;
use App\Modules\SeoAgent\Requests\Api\V1\SeoAgentCreateCurrentDataRequest;
use App\Modules\SeoAgent\Requests\Api\V1\SeoAgentGetOnlyDraftRequest;
use App\Modules\SeoAgent\Requests\Api\V1\SeoAgentUpdateCurrentDataRequest;
use App\Modules\SeoAgent\Requests\Api\V1\UpdateStatusRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SeoAgentApiController extends Controller
{

    /**
     * @OA\Get(
     *     path="/seoagent/v1/draft-data",
     *     tags={"Draft Data"},
     *     summary="Get only draft data",
     *     description="Get only draft data",
     *     deprecated=false,
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="number of items shown in the list",
     *         required=true,
     *         example=1,
     *         @OA\Schema(
     *         type="integer"
     *         ),
     *         style="form"
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="page number",
     *         required=true,
     *          example=1,
     *         @OA\Schema(
     *         type="integer"
     *         ),
     *         style="form"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Create a resource"
     *     )
     * )
     */
    public function getOnlyDraftData(SeoAgentGetOnlyDraftRequest $request)
    {
        $request->validated();
        return SeoAgentService::getOnlyDraftData($request->query('per_page'), $request->query('page'));
    }


    /**
     * @OA\Get(
     *     path="/seoagent/v1/current-data/{hash}",
     *     tags={"Current Data"},
     *     summary="Get current data by HASH",
     *     description="Get current data by HASH",
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="ID of resource to return",
     *         in="path",
     *         name="hash",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Get a resource"
     *     )
     * )
     */
    public function getCurrentDataByHash($hash)
    {
        return SeoAgentService::getCurrentDataByHash($hash);
    }


    /**
     * @OA\Post(
     *     path="/seoagent/v1/current-data",
     *     tags={"Current Data"},
     *     summary="Create a new current meta data entity",
     *     description="Create a new current meta data entity",
     *     deprecated=false,
     *     @OA\RequestBody(
     *          description="Data required to create it",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/SeoAgentBaseModel")
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Create a resource"
     *     )
     * )
     */

    public function createCurrentData(SeoAgentCreateCurrentDataRequest $request)
    {
        $request->validated();
        $data = $request->only(['current_data', 'path', 'hash']);

        $schema = new MetaSchemaEloquent();
        $schema->fill($data['current_data']);

        $prepare = [
            'path' => $request->input('path'),
            'hash' => $request->input('hash'),
            'current_data' => $schema
        ];
        return SeoAgentService::createCurrentData($prepare);
    }


    /**
     * @OA\Put(
     *     path="/seoagent/v1/current-data/{hash}",
     *     tags={"Current Data"},
     *     summary="Create a new current meta data entity",
     *     description="Create a new current meta data entity",
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="ID of resource to return",
     *         in="path",
     *         name="hash",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\RequestBody(
     *          description="Data required to create it",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/MetaSchemaEloquent")
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Update a resource"
     *     )
     * )
     */
    public function updateCurrentDataByHash(SeoAgentUpdateCurrentDataRequest $request, $hash)
    {
        $request->validated();

        $data = $request->only(['title', 'description', 'canonical', 'keywords']);
        $schema = new MetaSchemaEloquent($data);
        return SeoAgentService::updateCurrentDataByHash($hash, $schema);
    }


    /**
     * @OA\Patch(
     *     path="/seoagent/v1/current-data",
     *     tags={"Current Data"},
     *     summary="Bulk update or insert data",
     *     description="Bulk update or insert data",
     *     deprecated=false,
     *     @OA\RequestBody(
     *          description="Data required to create it",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Items(type="array",
     *              @OA\Items(ref="#/components/schemas/SeoAgentBaseModel"))
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Update a resource"
     *     )
     * )
     */
    /**
     * @param SeoAgentBulkUpdateOrInsertMetaRequest $request
     * @return array
     */
    public function patchCurrentData(SeoAgentBulkUpdateOrInsertMetaRequest $request)
    {
        $request->validated();
        $data = $request->all();
        $collection = collect($data);
        $keys = $collection->pluck('hash');
        $mapper = $collection->keyBy('hash');
        $exists = SeoAgentCurrentData::query()->whereIn('hash', $keys)->get()->pluck('hash');
        $notExists = $keys->diff($exists);
        $metaSchema = new MetaSchemaEloquent();
        $defaultDraft = $metaSchema->toArray();
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');

        try {
            if (is_array($data)) {
                // update existing data only
                $existValues = [];
                foreach ($exists as $exist) {
                    if (!$exist) {
                        continue;
                    }
                    $row = [
                        'hash' => $exist,
                        'draft_data' => json_encode($defaultDraft),
                        'type' => SeoAgentBaseModel::TYPE_DEFAULT,
                        'updated_at' => $currentDateTime,
                        'current_data' => !empty($mapper[$exist]['current_data']) ? json_encode($metaSchema->fill($mapper[$exist]['current_data'])->toArray()) : json_encode($defaultDraft),
                    ];
                    $existValues[] = $row;
                }
                SeoAgentCurrentData::insertOnDuplicateKey($existValues, ['current_data', 'updated_at', 'draft_data', 'type']);


                // insert new data
                $notExistRows = [];
                $maps = [];
                foreach ($notExists as $notExist) {
                    if (!$notExist) {
                        continue;
                    }
                    if (isset($maps[$notExist])) {
                        continue;
                    }
                    $maps[$notExist] = 1;

                    $row = [
                        'hash' => $notExist,
                        'path' => $mapper[$notExist]['path'],
                        'draft_data' => json_encode($defaultDraft),
                        'current_data' => !empty($mapper[$notExist]['current_data']) ? json_encode($metaSchema->fill($mapper[$notExist]['current_data'])->toArray()) : json_encode($defaultDraft),
                        'type' => SeoAgentBaseModel::TYPE_NEW,
                        'created_at' => $currentDateTime,
                        'updated_at' => $currentDateTime
                    ];
                    $notExistRows[] = $row;
                }
                SeoAgentCurrentData::query()->insert($notExistRows);

                return ['success' => true];
            }
        } catch (\Exception $e) {
            Log::error($e);
            return ['success' => false];
        }
    }


    /**
     * @OA\Put(
     *     path="/seoagent/v1/draft-data/{hash}/status",
     *     tags={"Current Data"},
     *     summary="Create a new current meta data entity",
     *     description="Create a new current meta data entity",
     *     deprecated=false,
     *     @OA\Parameter(
     *         description="ID of resource to return",
     *         in="path",
     *         name="hash",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\RequestBody(
     *          description="Data required to create it",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="status",
     *                   description="Updated status of the pet",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="comments",
     *                   description="comments",
     *                   type="string"
     *               )
     *           )
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Update a resource"
     *     )
     * )
     */
    public function updateStatus(UpdateStatusRequest $request, $hash)
    {
        $request->validated();
        $result = SeoAgentService::updateStatus(
            $hash,
            $request->input('comments'),
            $request->input('status')
        );

        return $result === true ? \Response::json(['success' => true], 200) : \Response::json(['success' => false], 500);
    }


    /**
     * @OA\Put(
     *     path="/seoagent/v1/deleteMetaData",
     *     tags={"Recovery"},
     *     summary="Create a new current meta data entity",
     *     description="Create a new current meta data entity",
     *     deprecated=false,
     *     @OA\RequestBody(
     *          description="Data required to create it",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Update a resource"
     *     )
     * )
     */
    public function deleteAllData()
    {
        try {
            DB::table('seo_metas')->truncate();
            return \Response::json(['success' => true], 200);
        } catch (\Exception $e) {
            return \Response::json(['success' => false], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/seoagent/v1/online-users",
     *     tags={"Online User"},
     *     summary="is any user online",
     *     description="is any user online",
     *     deprecated=false,
     *     @OA\Response(
     *         response=200,
     *         description="get true or false"
     *     )
     * )
     */
    public function isAnyUserOnline()
    {

        $cache = \Cache::get('last_activity_record');
        $lastActivity = isset($cache['last_activity_time']) ? Carbon::parse($cache['last_activity_time'], 'Australia/Sydney') : Carbon::now();
        $counter = $lastActivity->diffForHumans(Carbon::now());

        return [
            'counter' => $counter,
            'last_activity_record' => \Cache::get('last_activity_record')
        ];
    }
}

