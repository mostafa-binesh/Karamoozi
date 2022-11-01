<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
// use php-open-source-saver/jwt-auth/
class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    // public function register()
    // {
    //     $this->reportable(function (Throwable $e) {
    //         //
    //     });
    // }
    // protected function unauthenticated(\Illuminate\Auth\AuthenticationException $exception,$request)
    // // protected function unauthenticated(AuthenticationException $exception, AuthenticationException $request)
    // {
    //     return response()->json(['error' => 'Invalid token'], 401);
    //     // if ($request->expectsJson()) {
    //     //     return response()->json(['error' => 'Unauthenticated.'], 401);
    //     // }
    //     // return redirect()->guest('login');
    // }
    public function register()
    {
        $this->renderable(function (\Illuminate\Auth\AuthenticationException  $e, $request) {
            // dd($e);
            return response()->json(['error' => 'شما باید احراز هویت انجام داده باشید.'], 401);
        });
        $this->renderable(function (\PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException  $e, $request) {
            dd($e);
            return response()->json(['error' => 'توکن نامعتبر است'], 401);
        });
        $this->renderable(function (\PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException $e, $request) {
            dd($e);
            return response()->json(['error' => 'مدت زمان استفاده از توکن به اتمام رسیده است'], 401);
        });
        $this->renderable(function (\PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException $e, $request) {
            dd($e);
            return response()->json(['error' => 'خطا در پردازش توکن'], 401);
        });
        $this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return response()->json([
                'error' => 'شما با اطلاعاتی که وارد شدید، دسترسی به این صفحه را ندارید.',
            ], 403);
        });
    }
}
