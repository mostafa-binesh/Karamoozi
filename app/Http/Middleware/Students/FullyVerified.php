<?php

namespace App\Http\Middleware\Students;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;

class FullyVerified
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
        // ! create another field for pre_reg_done
        $student = Auth::user()->student;
        // first step: verified
        $verified = $student->verified;
        // second step: pre_reg_done
        $pre_reg_done = $student->pre_reg_done;
        // third step: exist industry supervisor id
        $supervisor_id = $student->supervisor_id;
        // fourth step: faculty verified
        // TODO: below line
        // ! removed the original faculty verified to ease the process of development, FIX, REVERT IT AFTER DEVELOPMENT
        // $faculty_verified = $student->faculty_verified;
        $faculty_verified = true;
        if (
            !$verified
            || !$pre_reg_done
            || !isset($supervisor_id)
            || !$faculty_verified
        ) {
            return response()->json([
                'message' => 'شما باید فرایند های تاییدیه، انجام پیش ثبت نام، ثبت توسط سرپرست صنعت و تاییدیه ی دانشکده را گذرانده باشید',
                // TODO: devlopment only, remove the below fileds
                'verified' => $verified,
                'pre_reg_done' => $pre_reg_done,
                'supervisor_id' => isset($supervisor_id),
                'faculty_verified' => $faculty_verified,
            ]);
        }
        return $next($request);
    }
}
