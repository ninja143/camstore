<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

// Helper
use App\Helpers\Common;

// Models
use App\Models\UserCart;

class UserCartController extends Controller {
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;
    public $qnty = 100;

    public function __construct(JWTAuth $jwt) {
        $this->jwt = $jwt;
        // $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Method to add product in user carts
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart(Request $request)
    {
        //Validate all requested data
        Common::validateRequest($request->all(), self::addToCartValidationRules($this->qnty), $request);

        try {
            // Retrieving only those categories that as having at least 1 product
            $userCart =  new UserCart();

            $isFound = $userCart->selectRaw('*, sum(quantity) as applied_quantity')
                        ->where('product_id', $request->product_id)
                        ->where('user_id', Auth::id())
                        ->where('status', PRODUCT_ACTIVE)
                        ->groupBy('product_id')->get()->first();
            
            if($isFound) {
                $appliedQnty = $isFound->applied_quantity;
                $remainingQnty = $this->qnty - $appliedQnty;
                if($remainingQnty < $request->quantity) {
                    $response = [
                        'status' => false,
                        'message' => $remainingQnty == 0 ? 'You can not purchase this product for now.' : 'You exceeded your purchage quntity limit of 100 nos. You only can have '.$remainingQnty.' in nos.',
                    ];
                } else {
                    $isFound->quantity = $isFound->quantity + $request->quantity;
                    $isSaved = $isFound->save();
                    $response = [
                        'status' => $isSaved,
                        'message' => $isSaved ? 'updated successfully' : 'couldn\'t update due to some technical issue'
                    ];
                }
            } else {
                $userCart->product_id = $request->product_id;
                $userCart->user_id = Auth::id();
                $userCart->quantity = $request->quantity;
                $userCart->status = PRODUCT_ACTIVE;
                $isSaved = $userCart->save();
                $response = [
                    'status' => $isSaved,
                    'message' => $isSaved ? 'updated successfully' : 'couldn\'t update due to some technical issue'
                ];
            }
            
            
            Common::sendResponse($response, config('HttpCodes.code.success'), $request);
        } catch (JWTException $e) {
            Common::sendResponse(['error' => config('HttpCodes.status.something_wrong')], config('HttpCodes.code.something_wrong'), $request);
        }

        // return $this->respondWithToken($token);
    }

    /**
     * Method to validate login methos Params
     */
    private static function addToCartValidationRules($qnty) {
        return[
            'product_id'    => 'required|numeric',
            'quantity'      => 'required|numeric|min:1|max:'.$qnty,
        ];
    }


    /**
     * Method to add product in user carts
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCart(Request $request)
    {
        try {
            // Retrieving only those categories that as having at least 1 product
            $userCart =  UserCart::With(['product'])
                                    ->where('user_id', Auth::id())
                                    ->where('status', PRODUCT_ACTIVE)
                                    ->get();
            
            $response = [
                'status' => $userCart->count() > 0 ? true : false,
                'message' => $userCart->count() > 0 ? 'fetched successfully' : 'You do not have any item in cart',
                'result' => $userCart
            ];
            
            Common::sendResponse($response, config('HttpCodes.code.success'), $request);
        } catch (JWTException $e) {
            Common::sendResponse(['error' => config('HttpCodes.status.something_wrong')], config('HttpCodes.code.something_wrong'), $request);
        }

        // return $this->respondWithToken($token);
    }

}