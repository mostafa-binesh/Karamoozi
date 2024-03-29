<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Providers\FileProvider;
use App\Repositories\CompanyRepo;
use Illuminate\Http\Request;
use App\Http\Resources\admin\CompanyResource;
use App\ModelFilters\CompanyFilter;
use App\Models\User;
use App\Providers\GenerateRandomId;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\IndustrySupervisor;

class AdminCompanyController extends Controller
{

    private $companyRepo;

    private $file;
    public function __construct(CompanyRepo $companyRepository)
    {
        $this->companyRepo = $companyRepository;
        $this->file = new FileProvider('companies');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Company::filter($request->all(), CompanyFilter::class)->cpagination($request, CompanyResource::class);
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
            'company_address' => 'required',
            'company_category' => 'required',
            'company_postal_code' => 'required|unique:companies',
            'company_type' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email|required|unique:users',
            'national_code' => 'required|digits:10|unique:users,national_code',
            'username' => 'required|unique:users,username',
            'phone_number' => 'required|digits:11|unique:users,phone_number',
            // 'faculty_id' => 'required',
            'image' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }

        $image_name = time() . '.png';
        $boss = User::create([
            'rand_id' => GenerateRandomId::generateRandomId(),
            'first_name' => $req->first_name,
            'last_name' => $req->last_name,
            'username' => $req->username,
            'national_code' => $req->national_code,
            'phone_number' => $req->phone_number,
            'email' => $req->email,
            'password' => Hash::make($req->nanational_code)
        ])->assignRole('industry_supervisor');
        $bossInds = IndustrySupervisor::create([
            'user_id' => $boss->id,
            'verified' => 1
        ]);
        Company::create([
            'company_name' => $req->company_name,
            'caption' => $req->caption,
            'company_grade' => 0,
            'company_number' => $req->company_number,
            'company_registry_code' => $req->company_registry_code,
            'company_phone' => $req->company_phone,
            'company_address' => $req->company_address,
            'company_category' => $req->company_category,
            'company_postal_code' => $req->company_postal_code,
            'company_type' => $req->company_type,
            'company_boss_id' => $bossInds->id,
            'verified' => 1,
            'image' => $image_name,
        ]);
        // $this->file->StrogeFile($req);
        $req->file('image')->storeAs('public/companies', $image_name);
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
        $company = $this->companyRepo->getById($id);
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
        $company = $this->companyRepo->getById($id);
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

    public function update_company(Request $req, $id)
    {
        $company = $this->companyRepo->getById($id);
        if (!isset($company)) {
            return response()->json([
                'error' => 'شرکت یافت نشد'
            ], 400);
        }
        $boss = User::where('id',IndustrySupervisor::where('id',$company->company_boss_id)->first()->user_id)->first();
        if(!isset($boss->id)){
            return response()->json([
                'error'=>'سرپرست شرکت یافت نشد'
            ],400);
        }
        $validator = Validator::make($req->all(), [
            'company_name' => 'required',
            'company_number' => 'required|unique:companies,company_number,' . $id,
            'company_registry_code' => 'required|unique:companies,company_registry_code,' . $id,
            'company_phone' => 'required|digits:11|unique:companies,company_phone,' . $id,
            'company_address' => 'required',
            'company_category' => 'required',
            'company_postal_code' => 'required|unique:companies,company_postal_code,' . $id,
            'verified' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email|required|unique:users,email,'.$boss->id,
            'national_code' => 'required|digits:10|unique:users,national_code,'.$boss->id,
            'username' => 'required|unique:users,username,'.$boss->id,
            'phone_number' => 'required|digits:11|unique:users,phone_number,'.$boss->id,
            // 'image'=>'image'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        // $this->companyRepo->update($req->all(),$id);
        if ($req->image) {
            Validator::make($req->all(), [
                'image' => 'image'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors(),
                ], 400);
            }
            if ($company->image) {
                $this->file->delete_image($company->image);
            }

            $imageName = time() . '.png';
            $req->file('image')->storeAs('public/companies/', $imageName);
            $company->image = $imageName;
        }
        $boss->first_name = $req->first_name;
        $boss->last_name = $req->last_name;
        $boss->email = $req->email;
        $boss->national_code = $req->national_code;
        $boss->username = $req->username;
        $boss->phone_number = $req->phone_number;
        $boss->save();
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
    public function update(Request $req, $id)
    {
        return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = $this->companyRepo->getById($id);
        if (!isset($company)) {
            return response()->json([
                'message' => 'شرکت یافت نشد'
            ], 400);
        }
        $this->companyRepo->delete($id);
        return response()->json([
            'message' => 'شرکت با موفقیت حذف شد'
        ], 200);
    }

    public function delete_image($id)
    {
        $company = Company::where("id", $id)->first();
        if (!isset($company)) {
            return response()->json([
                'message' => 'شرکت یافت نشد'
            ], 400);
        }
        $flag = $this->file->delete_image($company->image);
        if ($flag) {
            return response()->json([
                'message' => 'فایل با موفقیت حذف شد',
            ]);
        }
        return response()->json([
            'error' => 'در حذف فایل مشکلی به وجود آمده است'
        ], 400);
    }

    public function upload_image($id, Request $req)
    {
        $company = Company::where("id", $id)->first();
        if (!isset($company)) {
            return response()->json([
                'message' => 'شرکت یافت نشد'
            ], 400);
        }
        $imageName = time() . '.png';
        $req->file('image')->storeAs('public/companies/', $imageName);
        $company->image = $imageName;
        $company->save();
        return response()->json([
            'message' => 'عکس با موفقیت آپلود شد'
        ]);
    }
}
