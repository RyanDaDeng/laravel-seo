<?php

namespace App\Modules\SeoAgent\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Modules\SeoAgent\Facades\SeoAgentService;
use App\Modules\SeoAgent\Models\MetaSchemaEloquent;
use App\Modules\SeoAgent\Models\SeoAgentCurrentData;
use App\Modules\SeoAgent\Requests\Api\V1\SeoAgentCreateCurrentDataRequest;
use App\Modules\SeoAgent\Requests\Api\V1\SeoAgentGetOnlyDraftRequest;
use App\Modules\SeoAgent\Requests\Api\V1\SeoAgentUpdateCurrentDataRequest;

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
    /**
     * @param SeoAgentGetOnlyDraftRequest $request
     * @return \Illuminate\Support\Collection
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
    /**
     * @param $hash
     * @return \Illuminate\Support\Collection
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
    /**
     * @param SeoAgentCreateCurrentDataRequest $request
     * @return \Illuminate\Support\Collection
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


}

