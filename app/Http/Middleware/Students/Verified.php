<?php

namespace App\Http\Middleware\Students;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Verified
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
        $student = Auth::user()->student;
        // if student is not verified, return error
        if (!$student->verified) {
            return response()->json([
                'message' => 'شما باید تایید شده باشید',
            ]);
        }
        // otherwise, return next
        return $next($request);
    }
}
