<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if($request->expectsJson()){
            if ($e instanceof TokenInvalidException){
                return response()->json(['error' => 'token_invalid'], 400);
                
            } else if ($e instanceof TokenExpiredException){
                return response()->json(['error' => 'token_expired'], 400);

            } else if ($e instanceof ValidationException){
                return response()->json(['error' => $e->getMessage()], 400);

            } else if ($e instanceof NotFoundHttpException){
                return response()->json(['error' => 'No route found'], 404);

            } else if ($e instanceof MethodNotAllowedHttpException){
                return response()->json(['error' => 'Method not allowed'], 404);

            } else if ($e instanceof UnauthorizedHttpException){
                return response()->json(['error' => 'Unauthorized'], 403);

            } else if ($e instanceof MethodNotAllowedHttpException ){
                return response()->json(['error' => 'Method is not allowed'], 403);

            } else if ($e instanceof QueryException ){
                if(app()->environment() == 'local' || app()->environment() == 'staging'){
                } else {
                    return response()->json(['error' => 'Database error'], 403);
                }
                
            } else {
                dd($e);
                if(app()->environment() == 'local' || app()->environment() == 'staging'){
                    return response()->json(['error' => $e->getMessage()], 403);
                } else {
                    return response()->json(['error' => 'Something went wrong, please try later'], 400);
                }
            }
        }        
        return parent::render($request, $e);
    }
}
