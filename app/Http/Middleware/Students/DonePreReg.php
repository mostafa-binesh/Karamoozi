<?php

namespace App\Http\Middleware\Students;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonePreReg
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // if not completed the pre registration, return error
        $student = Auth::user()->student;
        if(!$student->pre_reg_done) {
            return response()->json([
                'message' => 'شما باید پیش ثبت نام را انجام داده باشید',
            ],400);
        }
        // otherwise, return next
        return $next($request);
    }
}
