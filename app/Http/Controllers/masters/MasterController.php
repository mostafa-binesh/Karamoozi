<?php

namespace App\Http\Controllers\masters;

use App\Http\Controllers\Controller;
use App\Http\Resources\MasterEvaluationResource;
use App\Models\Form3s;
use App\Models\MasterEvaluation;
use App\Models\Option;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterController extends Controller
{
    public function show($id)
    {
        $term_id = Term::where('start_date', '<=', now())->where('end_date', '>=', now())->first()->id;
        $master = MasterEvaluation::where('student_id', $id)->where('term_id', $term_id)->get();
        if(count($master)==0){
            return [];
        }
        return [
            "internship_visit"=>$master[0]->grade,
            "report_validation"=>$master[1]->grade,
            "examination_score"=>$master[2]->grade,
            "final_evaluation"=>$master[3]->grade,
        ];
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'student_id' => 'required',
            'evaluate' => 'required'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()
            ], 400);
        }
        $array = [
            "internship_visit",
            "report_validation",
            "examination_score",
            "final_evaluation",
        ];
        $grade = 0;
        $term_id = Term::where('start_date', '<=', now())->where('end_date', '>=', now())->first()->id;
        $index = 0 ;
        foreach ($request->evaluate as $evaluate) {
            $option_id = Option::where('name', $array[$index])->first()->id;
            $grade += $evaluate;
            MasterEvaluation::create([
                'student_id' => $request->student_id,
                'term_id' => $term_id,
                'option_id' => $option_id,
                'grade' => $evaluate,
            ]);
            $index ++;
        }
        Form3s::create([
            'student_id' => $request->student_id,
            'term_id' => $term_id,
            'grade' => $grade / count($request->evaluate),
            'verified' => 0
        ]);
        return response()->json([
            'message' => 'نمره با موفقیت ثبت شد'
        ]);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'evaluate' => 'required'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()
            ], 400);
        }
        $grade = 0;
        $term_id = Term::where('start_date', '<=', now())->where('end_date', '>=', now())->first()->id;
        $evaluates = MasterEvaluation::where('student_id', $id)->where('term_id', $term_id)->get();
        $array = [
            "internship_visit",
            "report_validation",
            "examination_score",
            "final_evaluation",
        ];
        $index=0;
        foreach ($request->evaluate as $evaluate) {
            $option_id = Option::where('name', $array[$index])->first()->id;
            $grade += $evaluate;
            foreach($evaluates as $item){
                if($option_id == $item->option_id){
                    $item->grade =  $evaluate;
                    $item->save();
                    break;
                }
            }
            $index++;
        }
        $form3= Form3s::where('student_id',$id)->where('term_id',$term_id)->first();
        $form3->grade = $grade/count($request->evaluate);
        $form3->save();
        return response()->json([
            'message' => 'نمره با موفقیت ویرایش شد'
        ]);

    }
}
