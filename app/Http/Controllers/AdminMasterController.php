<?php

namespace App\Http\Controllers;

use App\Http\Resources\admin\MasterResource;
use App\Http\Resources\FacultyResource;
use App\Http\Resources\UniversityFacultyResource;
use App\ModelFilters\MasterFilter;
use App\Models\Employee;
use App\Models\ModelHasRole;
use App\Models\User;
use App\Models\University_faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        // ! probably elequent query is not optimized, because we get the employee relation and withing the employee, we get the faculty relation
        $master = User::role("master")->filter($req->all(), MasterFilter::class)->cpagination($req, MasterResource::class);
        return response()->json([
            'meta' => $master['meta'],
            'data' => [
                'faculties' => UniversityFacultyResource::collection(University_faculty::all()),
                'master' => $master['data'],
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return [
            "data" => FacultyResource::collection(University_faculty::all())
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email|required|unique:users,email',
            'national_code' => 'required|digits:10|unique:users,national_code',
            'personal_code' => 'required|digits:10|unique:users,username',
            'phone_number' => 'required|digits:11|unique:users,phone_number',
            'faculty_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        $master = User::create([
            'first_name' => $req->first_name,
            'last_name' => $req->last_name,
            'username' => $req->personal_code,
            'national_code' => $req->national_code,
            'phone_number' => $req->phone_number,
            'email' => $req->email,
        ]);
        ModelHasRole::create([
            "role_id" => 3,
            "model_type" => "App\Models\User",
            "model_id" => $master->id,
        ]);
        Employee::create([
            'user_id' => $master->id,
            'faculty_id' => $req->faculty_id,
        ]);
        return response()->json([
            'message' => 'استاد با موفقیت اضافه شد',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $master = User::where("id", $id)->first();
        if ($master->cRole() != 'master') {
            return response()->json([
                'message' => 'استاد یافت نشد'
            ], 400);
        }
        return MasterResource::make($master);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $master = User::where("id", $id)->first();
        if ($master->cRole() != 'master') {
            return response()->json([
                'message' => 'استاد یافت نشد'
            ], 400);
        }
        return [
            "data" => [
                "id" => $master->id,
                "first_name" => $master->first_name,
                "last_name" => $master->last_name,
                "national_code" => $master->national_code,
                "personal_code" => $master->username,
                "email" => $master->email,
                "phone_number" => $master->phone_number,
                "faculty" => $master->employee->faculty
            ]
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email|required|unique:users,email,' . $id,
            'national_code' => 'required|digits:10|unique:users,national_code,' . $id,
            'personal_code' => 'required|digits:10|unique:users,username,' . $id,
            'phone_number' => 'required|digits:11|unique:users,phone_number,' . $id,
            'faculty_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        $master = User::where("id", $id)->first();
        if ($master->cRole() != 'master') {
            return response()->json([
                'message' => 'استاد یافت نشد'
            ], 400);
        }
        $master->phone_number = $req->phone_number;
        $master->first_name = $req->first_name;
        $master->last_name = $req->last_name;
        $master->email = $req->email;
        $master->username = $req->personal_code;
        $master->national_code = $req->national_code;
        $master->save();
        $emp = Employee::where("user_id", $master->id)->first();
        // return $emp;
        $emp->faculty_id = $req->faculty_id;
        $emp->save();
        return response()->json([
            'message' => 'اطلاعات با موفقیت ویرایش شد',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $master = User::where("id", $id)->first();
        if ($master->cRole() != 'master') {
            return response()->json([
                'message' => 'استاد یافت نشد'
            ], 400);
        }
        User::destroy($master->id);
        return response()->json([
            'message' => 'استاد حذف شد'
        ]);
    }
    public function faculties(Request $req)
    {
        return University_faculty::cpagination($req, FacultyResource::class);
    }
}
