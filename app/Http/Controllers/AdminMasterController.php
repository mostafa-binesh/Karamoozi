<?php

namespace App\Http\Controllers;

use App\Http\Resources\admin\MasterResource;
use App\Models\Employee;
use App\Models\User;
use App\Models\University_faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        return User::role('master')->cpagination($req,MasterResource::class);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return University_faculty::all();
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
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'email|required',
            'national_code'=>'required|max:10|min:10',
            'PersonnelCode'=>'required|max:10|min:10',
            'phone_number'=>'required|max:11|min:11',
            'faculty_name'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        $faculty=University_faculty::where("faculty_name",$req->faculty_name)->first();
        $master=User::create([
            'first_name'=>$req->first_name,
            'last_name'=>$req->last_name,
            'username'=>$req->PersonnelCode,
            'national_code'=>$req->national_code,
            'phone_number'=>$req->phone_number,
            'email'=>$req->email,
        ]);
        Employee::create([
            'user_id'=>$master->id,
            'faculty_id'=>$faculty->id,
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
        $master = User::findorfail($id);
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
        $master=Employee::where("user_id",$id)->with(["user",'faculty'])->first();
        return $master;
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
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'email|required',
            'national_code'=>'required|max:10|min:10',
            'PersonnelCode'=>'required|max:10|min:10',
            'phone_number'=>'required|max:11|min:11',
            'faculty_name'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        $master=User::where("id",$id)->first();
        $master->phone_number=$req->phone_number;
        $master->first_name=$req->first_name;
        $master->last_name=$req->last_name;
        $master->email=$req->email;
        $master->username=$req->PersonnelCode;
        $master->national_code=$req->national_code;
        $master->save();
        $emp=Employee::where("user_id",$id);
        $faculty=University_faculty::where("faculty_name",$req->faculty_name)->first();
        $emp->faculty_id=$faculty->id;
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
        $master = User::findorfail($id)->first();
        if ($master->cRole() != 'master') {
            return response()->json([
                'message' => 'استاد یافت نشد'
            ], 400);
        }
        $master->destroy();
        return response()->json([
            'message' => 'استاد حذف شد'
        ]);
    }
}
