<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\admin\MasterOriginalResource;
use App\Http\Resources\admin\MasterResource;
use App\Http\Resources\admin\TermResource;
use App\Http\Resources\FacultyResource;
use App\Http\Resources\StudentResource;
use App\ModelFilters\Admin\TermFilter;
use App\Models\Employee;
use App\Models\MasterTerm;
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
        // ! every term should be seperated by faculties
        // ! for example if the term is 1440 and we have 4 univ. faculties
        // ! we need to show 1440 term with every single one of faculties and their student and masters count

        return Term::filter($req->all(), TermFilter::class)->cpagination($req, TermResource::class);

        // $terms =  Term::with(['students', 'masters'])->filter($req->all(), TermFilter::class)->cpagination($req);
        // $faculties = University_faculty::all();
        // $termsAndFaculties = [];
        // for ($i = 0; $i < count($terms["data"]); $i++) {
        //     for ($j = 0; $j < count($faculties); $j++) {
        //         $termWithFaculty = [
        //             'id' => $terms["data"][$i]->id,
        //             'name' => $terms["data"][$i]->name,
        //             'faculty' => $faculties[$j]->faculty_name,
        //             'students' => $terms["data"][$i]->students()->where('faculty_id', $faculties[$j]->id)->count(),
        //             'masters' => $terms["data"][$i]->masters()->where('faculty_id', $faculties[$j]->id)->count(),
        //         ];
        //         array_push($termsAndFaculties, $termWithFaculty);
        //     }
        // }
        // return response()->json([
        //     'meta' => $terms["meta"],
        //     "data" => $termsAndFaculties,
        // ]);
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
        $startDate = $req->start_date;
        $endDate = $req->end_date;
        $terms = Term::whereBetween('start_date', [$startDate, $endDate])
            ->orWhereBetween('end_date', [$startDate, $endDate])
            ->get();
        if ($terms->count() > 0) {
            return response()->json([
                'message' => 'ترم دیگری در این رنج زمانی وجود دارد',
            ], 400);
        }
        $term = Term::create([
            'name' => $req->name,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
        $masters = Employee::get()->all();
        foreach ($masters as $master) {
            MasterTerm::create([
                'master_id'=>$master->id,
                'term_id'=>$term->id,
                'students_count'=> 0
            ]);
        }
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
        Term::destroy($id);
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
