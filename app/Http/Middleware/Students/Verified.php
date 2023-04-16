<?php

namespace App\Http\Middleware\Students;

use App\Models\Student;
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
        // for now
        return $next($request);
        $student = Auth::user()->student;
        // if student is not verified, return error
        if ($student->verified != Student::VERIFIED[1]) {
            return response()->json([
                'message' => 'شما باید تایید شده باشید',
            ],400);
        }
        // otherwise, return next
        return $next($request);
    }
}
