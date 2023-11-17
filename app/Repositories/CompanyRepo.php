<?php

namespace App\Repositories;

use App\Http\Resources\admin\CompanyResource;
use App\ModelFilters\CompanyFilter;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyRepo{

    private $company;

    public function __construct(Company $company){
        $this->company = $company;
    }

    public function create($company){
        $this->company->create($company);
    }

    public function update($company, $company_id){
        $this->company->update($company, $company_id);
    }

    public function delete($company_id){
        $this->company->destroy($company_id);
    }

    public function getAll(){
        return $this->company->all();
    }

    public function getById($company_id){
        return $this->company->find($company_id);
    }

    public function paginate(Request $request){
        return Company::filter($request->all(), CompanyFilter::class)->cpagination($request, CompanyResource::class);
    }

}
