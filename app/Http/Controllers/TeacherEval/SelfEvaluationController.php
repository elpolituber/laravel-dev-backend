<?php

namespace App\Http\Controllers\TeacherEval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ignug\State;
use App\Models\Ignug\Teacher;
use App\Models\TeacherEval\AnswerQuestion;
use App\Models\TeacherEval\Answer;
use App\Models\TeacherEval\Question;
use App\Models\TeacherEval\SelfResult;
use App\Models\TeacherEval\EvaluationType;
use App\Models\TeacherEval\Evaluation;

class SelfEvaluationController extends Controller
{
    public function index(){
        return SelfResult::all();
    }

    public function store(Request $request){

        $data = $request->json()->all();

        $dataTeacher = $data['teacher'];
        $dataAnswerQuestions = $data['answer_questions'];
        $teacher = Teacher::findOrFail($dataTeacher['id']);
        $state = State::firstWhere('code', State::ACTIVE);

        foreach ($dataAnswerQuestions as $eachAnswerQuestion) {
            $selfResult = new SelfResult();
            $answerQuestion = AnswerQuestion::findOrFail($eachAnswerQuestion['id']);
            $selfResult->state()->associate($state);
            $selfResult->teacher()->associate($teacher);
            $selfResult->answerQuestion()->associate($answerQuestion);

            $selfResult->save();
        }
        $this->getResultSelf($dataTeacher['id'],$dataAnswerQuestions );


        return response()->json([
        'data' => [
            'selfResult' => $selfResult,
        ]
    ], 201);
    }

    //Metodo para realizar los calculos y sacar la nota de docencia y gestion con el porcentaje aplicado.
    public function getResultSelf( $teacherId, $AnswerQuestions ){

        $resultEvaluation = 0;
        foreach($AnswerQuestions as $eachAnswerQuestion){

            $answerQuestion = AnswerQuestion::where('id',$eachAnswerQuestion['id'])->first();
            $value = $answerQuestion->answer()->first()->value;
            $evaluationTypeId = $answerQuestion->question()->first()->evaluation_type_id;
            $evaluationTypeParent = EvaluationType::where('id',$evaluationTypeId)->first();
            $percentage = $evaluationTypeParent->parent()->first()->percentage;

            $resultEvaluation += ($value*$percentage)/100;

        }
        $this->createEvaluation($teacherId,$evaluationTypeId,$resultEvaluation);
    }

    //Metodo para guardar en la tabla evaluations.
    public function createEvaluation( $teacherId, $evaluationTypeId, $resultEvaluation ){

        $evaluation = new Evaluation();

        $evaluation->result = $resultEvaluation;
        $state = State::where('code','1')->first();
        $teacher = Teacher::findOrFail($teacherId);
        $evaluationType = EvaluationType::findOrFail($evaluationTypeId);

        $evaluation->state()->associate($state);
        $evaluation->teacher()->associate($teacher);
        $evaluation->evaluationType ()->associate($evaluationType);

        $evaluation->save();
    }

    public function update(Request $request){
        return $request;
    }

    public function destroy($id){
        return $id;
    }
}
