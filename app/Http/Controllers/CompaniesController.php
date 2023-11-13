<?php

namespace App\Http\Controllers;

use App\Http\Resources\HomeCompanyResource;
use App\Http\Resources\Students\CompanyResource;
use App\ModelFilters\CompanyFilter;
use App\Models\Company;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    public function index(Request $request){
        return Company::filter($request->all(), CompanyFilter::class)->cpagination($request, HomeCompanyResource::class);
    }
}
