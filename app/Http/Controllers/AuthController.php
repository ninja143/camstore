<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

// Helper
use App\Helpers\Common;

// Models
use App\Models\User;

class AuthController extends Controller {
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
    public function login(Request $request)
    {
        //Validate all requested data
        Common::validateRequest($request->all(), self::postLoginValidationRules(), $request);

        try {
            $credentials['email'] = $request->email;
            $credentials['password'] = $request->password;
            $client_data = User::where('email', $credentials['email'])->first();

            // Invliad Login Check
            if (empty($client_data)) {
                return response()->json(['status' => 0, 'error' => config('HttpCodes.msg.invalid_credentials')], config('HttpCodes.code.bad_request'));
            }

            //end of check the agent is blocked 
            if (!$token = $this->jwt->attempt($credentials)) {
                $no_of_wrong_attempt = '0';
                return response()->json(['status' => 0, 'error' => config('HttpCodes.msg.invalid_credentials'), 'wrong_attempt_no' => $no_of_wrong_attempt], config('HttpCodes.code.bad_request'));
            }

            $client = Common::getClientLimitedData();
            $response = [
                'status' => true,
                'message' => 'Logged in successfully',
                'result' => array_merge(
                        $client, [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60
                        ]
                )
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
    private static function postLoginValidationRules() {
        return[
            'email' => 'required|email|max:128',
            'password' => 'required|min:6|max:15',
        ];
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function payload(Request $request)
    {
        $client = Common::getClientLimitedData();
        $response = [
            'status' => true,
            'message' => 'Fetched payload successfully',
            'result' => auth()->payload()
        ];

        Common::sendResponse($response, config('HttpCodes.code.success'), $request);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        $client = Common::getClientLimitedData();
        $response = [
            'status' => true,
            'message' => 'Logged in successfully',
            'result' => array_merge(
                    $client, [
                'access_token' => auth()->getToken(),
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
                    ]
            )
        ];

        Common::sendResponse($response, config('HttpCodes.code.success'), $request);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}