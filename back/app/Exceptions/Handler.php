<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        if ($exception instanceof \League\OAuth2\Server\Exception\OAuthServerException) {
            $transPayload = trans('auth.' . $exception->getErrorType());
            if (is_array($transPayload)) { // can be remove if you translate all error types!
                $exception->setPayload($transPayload);
            }
        }
    
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Laravel\Passport\Exceptions\OAuthServerException) {
            if ($exception->getCode() === 10) {
                $transPayload = trans('auth.invalid_credentials');
                return response()->json($transPayload, 401);
            }
        }

        return parent::render($request, $exception);
    }
}
