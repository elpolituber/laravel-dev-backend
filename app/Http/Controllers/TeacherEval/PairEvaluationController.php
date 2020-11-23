<?php

namespace App\Http\Controllers\TeacherEval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeacherEval\PairResult;
use App\Models\TeacherEval\Evaluation;
use App\Models\TeacherEval\EvaluationType;
use App\Models\TeacherEval\DetailEvaluation;
use App\Models\TeacherEval\AnswerQuestion;
use App\Models\Ignug\State;

class PairEvaluationController extends Controller
{
    public function index()
    {
        $state = State::firstWhere('code', State::ACTIVE);
        $pairResults = PairResult::whereHas('state', function ($query) use ($state) {
            $query->where(id, $state->id);
        })->paginate(10000);
        return response()->json($pairResults,200);
    }
    public function show($id)
    {
       $pairResult =  PairResult::findOrFail($id);
        return response()->json([
            'data' => [
                'pairResult' => $pairResult
        ]]);
    }

    public function store(Request $request)
    {
        $data = $request->json()->all();

        $dataDetailEvaluation = $data['detail_evaluation'];
        $dataAnswerQuestions = $data['answer_questions'];
        $detailEvaluation = DetailEvaluation::findOrFail($dataDetailEvaluation['id']);
        $values = array();
        foreach($dataAnswerQuestions as $answerQuestion)
        {
            $pairResult = new PairResult;
            $pairResult->answerQuestion()->associate(AnswerQuestion::findOrFail($answerQuestion['id']));
            $pairResult->detailEvaluation()->associate($detailEvaluation);
            $pairResult->state()->associate(State::firstWhere('code', State::ACTIVE));
            $pairResult->save();

            $valueQuestion = AnswerQuestion::where('id',$answerQuestion['id'])->first(); //Valor de las preguntas
            array_push($values, $valueQuestion->answer()->first()->value);

            $questionEvaluation_type =  $valueQuestion->question()->first()->evaluation_type_id; //ID tipo de evaluacion q pertenece la pregunta
            $evaluationType = EvaluationType::where('id', $questionEvaluation_type)->first(); //Obtencion del tipo de evaluacion y porcentaje
            $percentage = $evaluationType->parent()->first()->percentage;

            $detailEvaluationResult = array_sum($values)*$percentage/100;
        }
        $this->updateDetailEvaluation($detailEvaluation, $detailEvaluationResult);
        return response()->json([
            'data' => [
                'pairResult' => $pairResult
        ]]);
    }

    public function updateDetailEvaluation($detailEvaluation, $detailEvaluationResult)
    {
        $detailEvaluation->result = $detailEvaluationResult;
        $detailEvaluation->save();

        $this->updateEvaluation($detailEvaluation);
    }

    public function updateEvaluation($detailEvaluation)
    {
        $detailEvaluations = DetailEvaluation::where('evaluation_id',$detailEvaluation->evaluation_id)->where('result',null)->get();
        if(sizeof( $detailEvaluations)===0){
            $detailEvaluations = DetailEvaluation::where('evaluation_id',$detailEvaluation->evaluation_id)->get();
            $resultEvaluation = 0;
            foreach($detailEvaluations as $detailEvaluation){

                $totalNote = $detailEvaluation->result;
                $resultEvaluation += $totalNote / sizeof($detailEvaluations);

                $evaluation = Evaluation::findOrFail($detailEvaluation->evaluation_id);
                $evaluation->result = $resultEvaluation;
                $evaluation->save();
            }
        }
    }

    public function update(Request $request)
    {
        return $request;
    }

    public function destroy($id)
    {
        return $id;
    }
}
