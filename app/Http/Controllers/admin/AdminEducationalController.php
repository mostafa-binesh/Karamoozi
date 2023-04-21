<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\admin\MasterOriginalResource;
use App\Http\Resources\admin\MasterResource;
use App\Http\Resources\admin\TermResource;
use App\Http\Resources\FacultyResource;
use App\Http\Resources\StudentResource;
use App\ModelFilters\Admin\TermFilter;
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
        return FacultyResource::make($faculty);
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
        return Term::with(['students', 'masters'])->filter($req->all(), TermFilter::class)->cpagination($req, TermResource::class);
        // ! TODO add name filter
    }
    public function singleTerm($id)
    {
        $term = Term::with(['students', 'masters'])->find($id);
        if (!$term) {
            return response()->json([
                'message' => 'ترم پیدا نشد',
            ], 400);
        }
        return TermResource::make($term);
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
        $term->save();
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
    //
    public function termStudents($id, Request $req)
    {
        $term = Term::find($id);
        if (!$term) {
            return response()->json([
                'message' => 'ترم یافت نشد',
            ], 400);
        }
        return $term->students()->cpagination($req, StudentResource::class);
    }
    public function termMasters($id, Request $req)
    {
        $term = Term::find($id);
        if (!$term) {
            return response()->json([
                'message' => 'ترم یافت نشد',
            ], 400);
        }
        return $term->masters()->cpagination($req, MasterOriginalResource::class);
    }

    // ! ################ TERMS AND FACULTIES  #####################
    // data passing -> ، نام سر ترم ، نام دانشکده ، تعداد دانشجو ، تعداد استاد و مشاهده جزییات .
    public function TermAndFaculty()
    {
        
    }
}
