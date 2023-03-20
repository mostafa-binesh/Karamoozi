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
    // ###############                        #####
    // ! ##################### FACULTIES  #####################
    // ###############                       #####
    public function singleFaculty($id)
    {
        $faculty = University_faculty::find($id);
        if (!$faculty) {
            return response()->json([
                'message' => 'دانشکده پیدا نشد',
            ], 400);
        }
        return $faculty;
    }
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
    public function editFaculty(Request $req, $id)
    {
        // find faculty
        $faculty = University_faculty::find($id);
        if (!$faculty) {
            return response()->json([
                'message' => 'دانشکده پیدا نشد',
            ], 400);
        }
        // validate sent data
        $validator = Validator::make($req->all(), [
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        // edit faculty
        $faculty->faculty_name = $req->name;
        $faculty->save();
        return response()->json([
            'message' => 'دانشکده ویرایش شد',
        ]);
    }
    public function deleteFaculty($id)
    {
        // find faculty
        $faculty = University_faculty::find($id);
        if (!$faculty) {
            return response()->json([
                'message' => 'دانشکده پیدا نشد',
            ], 400);
        }
        // delete
        $faculty->delete();
        return response()->json([
            'message' => 'دانشکده حذف شد',
        ]);
    }
    // ###############                #####
    // ! ################ TERMS  #####################
    // ###############                #####
    public function allTerms(Request $req)
    {
        return Term::cpagination($req, TermResource::class);
    }
    public function singleTerm($id)
    {
        $term = Term::find($id);
        if (!$term) {
            return response()->json([
                'message' => 'ترم پیدا نشد',
            ], 400);
        }
        return $term;
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
    public function editTerm(Request $req, $id)
    {
        $term = Term::find($id);
        if (!$term) {
            return response()->json([
                'message' => 'ترم یافت نشد',
            ], 400);
        }
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
        $term->name = $req->name;
        $term->start_date = $req->start_date;
        $term->end_date = $req->end_date;
        return response()->json([
            'message' => 'سر ترم ویرایش شد',
        ]);
    }
    public function deleteTerm($id)
    {
        $term = Term::find($id);
        if (!$term) {
            return response()->json([
                'message' => 'ترم یافت نشد',
            ], 400);
        }
        $term->delete();
        return response()->json([
            'message' => 'سر ترم حذف شد',
        ]);
    }
}
