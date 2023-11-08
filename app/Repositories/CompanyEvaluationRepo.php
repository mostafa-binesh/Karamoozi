<?php
use App\Models\CompanyEvaluation;

class CompanyEvaluationRepo
{

    private $em;

    public function __construct(CompanyEvaluation $em)
    {
        $this->em = $em;
    }

    public function create($companyEvaluation)
    {
        return $this->em->create($companyEvaluation);
    }

    public function update($id, $companyEvaluation){
        $this->em->update($companyEvaluation, $id);
    }

    public function delete($id){
        $this->em->destroy($id);
    }

    public function getById($id){
        return $this->em->find($id);
    }

    public function getAll(){
        return $this->em->get()->all();
    }

    
}
