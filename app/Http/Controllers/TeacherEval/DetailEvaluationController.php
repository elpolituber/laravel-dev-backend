<?php

namespace App\Http\Controllers\TeacherEval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ignug\State;
use App\Models\Ignug\Teacher;
use App\Models\TeacherEval\DetailEvaluation;
use App\Models\TeacherEval\EvaluationType;
use App\Models\TeacherEval\Evaluation;

class DetailEvaluationController extends Controller
{
    public function index()
    {
        $state = State::firstWhere('code', State::ACTIVE);
        $detailEvaluations = DetailEvaluation::where('state_id', $state->id)->paginate(10000);
        return response()->json($detailEvaluations, 200);
    }

    public function show($id)
    {
        $detailEvaluation = DetailEvaluation::findOrFail($id);
        return response()->json(['data' => $detailEvaluation], 200);
    }

    public function store(Request $request)
    {
        $data = $request->json()->all();

        $dataEvaluationType = $data['evaluation_type'];
        $dataTeacher = $data['teacher'];
        $dataEvaluators = $data['evaluators'];

        $evaluation = new Evaluation();

        $state = State::firstWhere('code', State::ACTIVE);
        $teacher = Teacher::findOrFail($dataTeacher['id']);
        $evaluationType = EvaluationType::findOrFail($dataEvaluationType['id']);

        $evaluation->state()->associate($state);
        $evaluation->teacher()->associate($teacher);
        $evaluation->evaluationType()->associate($evaluationType);
        $evaluation->save();

        foreach ($dataEvaluators as $evaluator) {
            $detailEvaluation = new DetailEvaluation;
            $detailEvaluation->state()->associate($state);
            $detailEvaluation->detailEvaluationable()->associate(Teacher::findOrFail($evaluator['id']));
            $detailEvaluation->evaluation()->associate($evaluation);
            $detailEvaluation->save();
        }

        return response()->json(['data' => $evaluation], 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->json()->all();

        $dataEvaluationType = $data['evaluation_type'];
        $dataTeacher = $data['teacher'];
        $dataEvaluators = $data['evaluators'];
        $dataState = $data['state'];

        $evaluation = Evaluation::findOrFail($id);
        $state = State::findOrFail($dataState['id']);
        $teacher = Teacher::findOrFail($dataTeacher['id']);
        $evaluationType = EvaluationType::findOrFail($dataEvaluationType['id']);

        $evaluation->state()->associate($state);
        $evaluation->teacher()->associate($teacher);
        $evaluation->evaluationType()->associate($evaluationType);
        $evaluation->save();

        foreach ($dataEvaluators as $evaluator) {
            $detailEvaluation = DetailEvaluation::where('evaluation_id', $id)->first();
            $detailEvaluation->detailEvaluationable()->associate(Teacher::findOrFail($evaluator['id']));
            $detailEvaluation->save();
        }

        return response()->json([
            'data' => [
                'evaluation' => $detailEvaluation
            ]
        ], 201);
    }

    public function destroy($id)
    {
        $evaluation = Evaluation::findOrFail($id);

        $evaluation->state_id = '3';
        $evaluation->save();

        $detailEvaluations = DetailEvaluation::where('evaluation_id', $id)->get();
        foreach ($detailEvaluations as $detailEvaluation) {
            $detailEvaluation->state_id = '3';
            $detailEvaluation->save();
        }

        return response()->json([
            'data' => [
                'detail_evaluation' => $detailEvaluation
            ]
        ], 201);
    }

}
