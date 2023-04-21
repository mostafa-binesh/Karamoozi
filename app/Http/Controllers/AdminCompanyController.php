<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Resources\admin\CompanyResource;
use App\ModelFilters\CompanyFilter;
use App\ModelFilters\MasterFilter;
use App\Models\ModelHasRole;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class AdminCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        return Company::filter($req->all(),CompanyFilter::class)->cpagination($req, CompanyResource::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            'company_name' => 'required',
            'company_number' => 'required|digits:11|unique:companies,company_number',
            'company_registry_code' => 'required|unique:companies,company_registry_code',
            'company_phone' => 'required|digits:11|unique:companies,company_phone',
            'compny_address' => 'required',
            'company_category' => 'required',
            'company_postal_code' => 'required|unique:companies',
            'company_type' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email|required|unique:User,email',
            'national_code' => 'required|digits:10|unique:users,national_code',
            'username' => 'required|digits:10|unique:users,username',
            'phone_number' => 'required|digits:11|unique:users,phone_number',
            'faculty_id' => 'required'
        ]);
        $boss = User::create([
            'first_name' => $req->first_name,
            'last_name' => $req->last_name,
            'username' => $req->username,
            'national_code' => $req->national_code,
            'phone_number' => $req->phone_number,
            'email' => $req->email,
        ]);
        ModelHasRole::create([
            "role_id" => 5,
            "model_type" => "App\Models\User",
            "model_id" => $$boss->id,
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }

        // return "ccc";
        Company::create([
            'company_name' => $req->company_name,
            'caption' => $req->caption,
            'company_grade' => 0,
            'company_number' => $req->company_number,
            'company_registry_code' => $req->company_registry_code,
            'company_phone' => $req->company_phone,
            'company_address' => $req->compny_address,
            'company_category' => $req->company_category,
            'company_postal_code' => $req->company_postal_code,
            'company_type' => $req->company_type,
            'company_boss_id' => $boss->id,
            'verified' => 1
        ]);
        return response()->json([
            'message' => 'شرکت با موفقیت اضافه شد',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = Company::where("id", $id)->first();
        if (!isset($company)) {
            return response()->json([
                'message' => 'شرکت یافت نشد'
            ], 400);
        }
        return CompanyResource::make($company);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = Company::where("id", $id)->first();
        if (!isset($company)) {
            return response()->json([
                'message' => 'شرکت یافت نشد'
            ], 400);
        }
        return CompanyResource::make($company);
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
            'company_name' => 'required',
            'company_number' => 'required|digits:11|unique:companies,company_number,' . $id,
            'company_registry_code' => 'required|unique:companies,company_registry_code,' . $id,
            'company_phone' => 'required|digits:11|unique:companies,company_phone,' . $id,
            'compny_address' => 'required',
            'company_category' => 'required',
            'company_postal_code' => 'required|unique:companies,company_postal_code,' . $id,
            'verified' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        $company = Company::where("id", $id)->first();
        if (!isset($company)) {
            return response()->json([
                'message' => 'شرکت یافت نشد'
            ], 400);
        }
        $company->company_name = $req->company_name;
        $company->company_number = $req->company_number;
        $company->company_registry_code = $req->company_registry_code;
        $company->company_phone = $req->company_phone;
        $company->company_address = $req->company_address;
        $company->company_grade = $req->company_grade;
        $company->verified = $req->verified;
        $company->caption = $req->caption;
        $company->company_category = $req->company_category;
        $company->company_postal_code = $req->company_postal_code;
        $company->save();
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
        $company = Company::where("id", $id)->first();
        if (!isset($company)) {
            return response()->json([
                'message' => 'شرکت یافت نشد'
            ], 400);
        }
        Company::destroy($id);
        return response()->json([
            'message' => 'شرکت با موفقیت حذف شد'
        ], 200);
    }
}
