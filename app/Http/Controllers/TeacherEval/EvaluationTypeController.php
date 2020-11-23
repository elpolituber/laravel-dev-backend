<?php

namespace App\Http\Controllers\TeacherEval;

use App\Http\Controllers\Controller;
use App\Models\TeacherEval\EvaluationType;
use App\Models\Ignug\State;
use Illuminate\Http\Request;

class EvaluationTypeController extends Controller
{
    public function index()
    {
        $state = State::firstWhere('code', State::ACTIVE);
        $evaluationTypes = EvaluationType::whereHas('state', function ($query) use ($state) {
            $query->where(id, $state->id);
        })->get();
        return response()->json(['data'=>$evaluationTypes],200);
    }

    public function show($id)
    {
        $evaluationType =  EvaluationType::findOrFail($id);
//        $catalogue =  Catalogue::where('id',$id)->get();
        return response()->json([
            'data' => [
                'evaluationType' => $evaluationType
            ]]);
    }

    public function store(Request $request)
    {
       $data = $request->json()->all();

       $dataEvaluationType = $data['evaluationtype'];
       $dataState = $data['state'];
       $dataParentCode = $data['parent_code'];

        $evaluationType = new EvaluationType();
        $evaluationType->code = $dataEvaluationType['code'];
        $evaluationType->name = $dataEvaluationType['name'];
        $evaluationType->percentage = $dataEvaluationType['percentage'];
        $evaluationType->global_percentage = $dataEvaluationType['global_percentage'];

        $state = State::findOrFail($dataState['id']);
        $parentCode = EvaluationType::find($dataParentCode['id']);

        $evaluationType->state()->associate($state);
        if (!$parentCode) {
            $evaluationType->parent_id = null;
        }else{
            $evaluationType->parent()->associate($parentCode);
        }

        $evaluationType->save();

        return response()->json([
        'data' => [
            'evaluationTypes' => $evaluationType
        ]
    ], 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->json()->all();

        $dataEvaluationType = $data['evaluationtype'];
        $dataParentCode = $data['parent_code'];
        $dataState = $data['state'];

        $evaluationType = EvaluationType::findOrFail($id);
        $evaluationType->code = $dataEvaluationType['code'];
        $evaluationType->name = $dataEvaluationType['name'];
        $evaluationType->percentage = $dataEvaluationType['percentage'];
        $evaluationType->global_percentage = $dataEvaluationType['global_percentage'];

        $parentCode = EvaluationType::find($dataParentCode['id']);
        $state = State::findOrFail($dataState['id']);

        if (!$parentCode) {
            $evaluationType->parent_id = null;
        }else{
            $evaluationType->parent()->associate($parentCode);
        }

        $evaluationType->state()->associate($state);

        $evaluationType->save();
        return response()->json([
            'data' => [
                'evaluationTypes' => $evaluationType
            ]
        ], 201);
    }

    public function destroy($id)
    {
        $evaluationType = EvaluationType::findOrFail($id);
/*         $catalogue->delete(); */
/*         $evaluationType->update([
            'state_id'=>'3'
        ]); */

        $evaluationType->state_id = '3';
        $evaluationType->save();

        return response()->json([
            'data' => [
                'evaluationTypes' => $evaluationType
            ]
        ], 201);
    }

}
