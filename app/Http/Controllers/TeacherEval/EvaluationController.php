<?php

namespace App\Http\Controllers\TeacherEval;

use App\Http\Controllers\Controller;
use App\Models\Ignug\State;
use App\Models\Ignug\Teacher;
use App\Models\TeacherEval\Evaluation;
use App\Models\TeacherEval\EvaluationType;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index()
    {
        //return Evaluation::all();
        return Evaluation::all();
    }

    public function show($id)
    {
        $evaluation =  Evaluation::findOrFail($id);
//        $catalogue =  Catalogue::where('id',$id)->get();
        return response()->json([
            'data' => [
                'evaluation' => $evaluation
            ]]);
    }

    public function store(Request $request)
    {
        $data = $request->json()->all();
        
        $dataEvaluation = $data['evaluation'];
        $dataTeacher = $data['teacher'];
        $dataEvaluationType = $data['evaluation_type'];
        $dataState = $data['state'];

        $evaluation = new Evaluation();
        $evaluation->result = $dataEvaluation['result'];

        $state = State::findOrFail($dataState['id']);
        $teacher = Teacher::findOrFail($dataTeacher['id']);
        $evaluationType = EvaluationType::findOrFail($dataEvaluationType['id']);

        $evaluation->state()->associate($state);
        $evaluation->teacher()->associate($teacher);
        $evaluation->evaluationType()->associate($evaluationType);

        $evaluation->save();

        return response()->json([
        'data' => [
            'evaluation' => $evaluation
        ]
    ], 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->json()->all();
        
        $dataEvaluation = $data['evaluation'];
        $dataTeacher = $data['teacher'];
        $dataEvaluationType = $data['evaluation_type'];
        $dataState = $data['state'];

        $evaluation = Evaluation::findOrFail($id);
        $evaluation->result = $dataEvaluation['result'];

        $state = State::findOrFail($dataState['id']);
        $teacher = Teacher::findOrFail($dataTeacher['id']);
        $evaluationType = EvaluationType::findOrFail($dataEvaluationType['id']);

        $evaluation->state()->associate($state);
        $evaluation->teacher()->associate($teacher);
        $evaluation->evaluationType ()->associate($evaluationType);

        $evaluation->save();

        return response()->json([
        'data' => [
            'evaluation' => $evaluation
        ]
    ], 201);
    }

    public function destroy($id)
    {
        $evaluation = Evaluation::findOrFail($id);
/*         $catalogue->delete(); */
/*         $evaluationType->update([
            'state_id'=>'3'
        ]); */

        $evaluation->state_id = '2';
        $evaluation->save();

        return response()->json([
            'data' => [
                'evaluation' => $evaluation
            ]
        ], 201);
    }
}
