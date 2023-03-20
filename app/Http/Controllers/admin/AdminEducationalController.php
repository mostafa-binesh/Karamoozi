<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\admin\TermResource;
use App\Models\Term;
use App\Models\University_faculty;
use Illuminate\Support\Facades\Validator;

class AdminEducationalController extends Controller
{
    public function addFaculty(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        University_faculty::create([
            'faculty_name' => $req->name,
        ]);
        return response()->json([
            'message' => 'دانشکده با موفقیت افزوده شد',
        ]);
    }
    public function addTerm(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        Term::create([
            'name' => $req->name,
            'start_date' => $req->start_date,
            'end_date' => $req->end_date,
        ]);
        return response()->json([
            'message' => 'سر ترم با موفقیت افزوده شد',
        ]);
    }
    public function allTerms(Request $req)
    {
        return Term::cpagination($req, TermResource::class);
    }
}
