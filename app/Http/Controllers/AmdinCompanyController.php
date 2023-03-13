<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Resources\admin\CompanyResource;
use Illuminate\Support\Facades\Validator;


class AmdinCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        return Company::cpagination($req,CompanyResource::class);
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
        $validator=Validator::make($req->all(),[
            'company_name'=>'required',
            'company_number'=>'required|max:11|min:11|unique:company,company_number',
            'company_registry_code'=>'required|unique:company,company_registry_code',
            'company_phone'=>'required|max:10|min:10|unique:company,company_phone',
            'compny_address'=>'required',
            'company_category'=>'required',
            'company_postal_code'=>'required|unique:company',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        Company::create([
            'company_name'=>$req->company_name,
            'caption'=>$req->caption,
            'company_number'=>$req->company_number,
            'company_registry_code'=>$req->company_registry_code,
            'company_phone'=>$req->company_phone,
            'compny_address'=>$req->compny_address,
            'company_category'=>$req->company_category,
            'company_postal_code'=>$req->company_postal_code,
            'company_grade'=>0,
            'verified'=>1
        ]);
        return response()->json([
            'message' => 'شرکت با موفقیت اضافه شد',
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
        $company=Company::findorfail($id);
        if(!$company->id){
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
        $company=Company::findorfail($id);
        if(!$company->id){
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
        $validator=Validator::make($req->all(),[
            'company_name'=>'required',
            'company_number'=>'required|max:11|min:11|unique:company,company_number',
            'company_registry_code'=>'required|unique:company,company_registry_code',
            'company_phone'=>'required|max:10|min:10|unique:company,company_phone',
            'compny_address'=>'required',
            'company_category'=>'required',
            'company_postal_code'=>'required|unique:company',
            'verified'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        $company=Company::where("id",$id)->first();
        $company->company_name=$req->comapny_name;
        $company->company_number=$req->company_number;
        $company->company_registry_code=$req->company_registry_code;
        $company->company_phone=$req->company_phone;
        $company->company_address=$req->company_address;
        $company->company_grade=$req->company_grade;
        $company->verified=$req->verified;
        $company->caption=$req->caption;
        $company->company_category=$req->company_category;
        $company->company_postal_code=$req->company_postal_code;
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
        $company=Company::findorfail($id);
        if(!$company->id){
            return response()->json([
                'message' => 'شرکت یافت نشد'
            ], 400);
        }
        $company->destroy();
        return response()->json([
            'message'=>'شرکت با موفقیت حذف شد'
        ]);
    }
}
