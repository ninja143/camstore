<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

// Helper
use App\Helpers\Common;

// Models
use App\Models\ProductCategory;

class ProductCategoryController extends Controller {
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt) {
        $this->jwt = $jwt;
        // $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        //Validate all requested data
        Common::validateRequest($request->all(), self::listValidationRules(), $request);

        try {
            $total  = $request->total ? $request->total : 10;
            $page   = $request->page ? $request->page:1;
            
            // Retrieving only those categories that as having at least 1 product
            $model =  ProductCategory::has('products');
            $total_records =  $model->get()->count();
            if($page == 1){
                $list = $model->withCount(['products'])->offset(0)->limit($total);
            }else{
                $offset = ($page - 1) * $total ;
                $list = $model->withCount(['products'])->offset($offset)->limit($total);
            }
            
            $listData = $list->get()->toArray();
            $response = [
                'status' => true,
                'message' => 'fetched successfully',
                'result' => $listData,
                'page_count' => $page,
                'per_page' => $total,
                'total_records' => $total_records,
            ];
            Common::sendResponse($response, config('HttpCodes.code.success'), $request);
        } catch (JWTException $e) {
            Common::sendResponse(['error' => config('HttpCodes.status.something_wrong')], config('HttpCodes.code.something_wrong'), $request);
        }

        // return $this->respondWithToken($token);
    }

    /**
     * Method to validate login methos Params
     */
    private static function listValidationRules() {
        return[
            'page'  => 'numeric|min:0',
            'total' => 'numeric||min:2|max:50'
        ];
    }

}