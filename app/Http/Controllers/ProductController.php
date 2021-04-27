<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

// Helper
use App\Helpers\Common;

// Models
use App\Models\Product;

class ProductController extends Controller {
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
            $catID  = $request->has('category_id') && !empty($request->category_id) ? $request->category_id : '';
            
            // Retrieving only those categories that as having at least 1 product
            $list =  Product::With(['category']);
            
            // Total Count
            // Category ID Check
            if(!empty($catID)) { $list = $list->where('category_id', $catID); }

            $total_records =  $list->get()->count();
            
            if($page == 1){
                $list = $list->offset(0)->limit($total);
            }else{
                $offset = ($page - 1) * $total ;
                $list = $list->offset($offset)->limit($total);
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
            'category_id'   => 'numeric|min:1',
            'page'          => 'numeric|min:0',
            'total'         => 'numeric||min:2|max:50'
        ];
    }

    /**
     * Method to get product details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details(Request $request)
    {
        //Validate all requested data
        Common::validateRequest($request->all(), self::detailsValidationRules(), $request);

        try {
            // Retrieving only those categories that as having at least 1 product
            $product =  Product::With(['category'])->find($request->product_id);
            
            $response = [
                'status' => $product ? true : false,
                'message' => $product ? 'details found successfully' : 'couldn\'t find the product details',
                'result' => $product ? $product : [],
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
    private static function detailsValidationRules() {
        return[
            'product_id'   => 'required|numeric'
        ];
    }

}