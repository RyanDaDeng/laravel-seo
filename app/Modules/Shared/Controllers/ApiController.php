<?php

namespace App\Modules\Shared\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;


/**
 * @SWG\Swagger(
 *     @OA\Info(title="SEO Agent API Documentation", version="1.0")
 * )
 */
class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs;


    /**
     * @SWG\Swagger(
     *     basePath="/api",
     *     host=API_HOST,
     *     schemes=API_SCHEMES,
     *     produces={"application/json"},
     *     consumes={"application/json"},
     *          @SWG\Info(
     *              title="Laravel Swagger Demo",
     *              version="3.0",
     *              description="Swagger creates human-readable documentation for your APIs.",
     *              @SWG\Contact(name="JP Caparas",email="jp@pixelfusion.co.nz"),
     *              @SWG\License(name="Unlicense")
     *          ),
     *          @SWG\Definition(
     *              definition="Timestamps",
     *              @SWG\Property(
     *                  property="created_at",
     *                  type="string",
     *                  format="date-time",
     *                  description="Creation date",
     *                  example="2017-03-01 00:00:00"
     *              ),
     *              @SWG\Property(
     *                  property="updated_at",
     *                  type="string",
     *                  format="date-time",
     *                  description="Last updated",
     *                  example="2017-03-01 00:00:00"
     *              )
     *          )
     * )
     */

    public function apiManagment(){

    }
    public function validate($rules, $data)
    {

        $validator = Validator::make($data,$rules);

        if ($validator->fails()) {
            return $validator->errors();
        }
    }
}
