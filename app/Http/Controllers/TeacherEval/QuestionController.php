<?php

namespace App\Http\Controllers\TeacherEval;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Models\TeacherEval\Question;
use App\Models\TeacherEval\EvaluationType;
use App\Models\TeacherEval\Answer;
use App\Models\Ignug\Catalogue;
use App\Models\Ignug\State;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function index()
    {
        return Question::all();
    }

    public function show($id)
    {
        $question = Question::findOrFail($id);
        return response()->json([
            'data' =>[
                'question' => $question
            ]
        ]);
    }  
    public function store(Request $request){
        $data = $request->json()->all();

       $dataQuestion = $data['question'];
       $dataState = $data['state'];
       $dataTypeId= $data['type_id'];
       $dataEvaluationType= $data['evaluation_type_id'];
       /* $dataAnswerId= $data['answer_id']; */
       
        $question = new Question();
        $question->code = $dataQuestion['code'];
        $question->order = $dataQuestion['order'];
        $question->name = $dataQuestion['name'];

        $state = State::findOrFail($dataState['id']);
        $type_id = Catalogue::find($dataTypeId['id']);
        $evaluationType = EvaluationType::findOrFail($dataEvaluationType['id']);
        /* $answer_id = Question::find($dataAnswerId['id']); */
  
        $question->state()->associate($state);
        $question->type()->associate($type_id);
        $question->evaluationType()->associate($evaluationType);

        $question->save();

        $answersIds = array();
        $answers = Answer::where('state_id', 1)
        ->get();
        foreach ($answers as $answer) {
            array_push($answersIds,$answer->id);
        }

        $question->answers()->attach( $answersIds); 
        
        return response()->json([
        'data' => [
            'questions' => $question
        ]
    ], 201);
    }
    public function update(Request $request, $id)
    {
        $data = $request->json()->all();

        $dataQuestion = $data['question'];
        $dataState = $data['state'];
        $dataTypeId= $data['type_id'];
       $dataEvaluationType = $data['evaluation_type_id'];

       $question = Question::findOrFail($id);
        $question->code = $dataQuestion['code'];
        $question->order = $dataQuestion['order'];
        $question->name = $dataQuestion['name'];
        

        $state = State::findOrFail($dataState['id']);
        $type_id = Catalogue::find($dataTypeId['id']);
        $evaluationType = EvaluationType::findOrFail($dataEvaluationType['id']);

        $question->state()->associate($state);
        $question->type()->associate($type_id);
        $question->evaluationType()->associate($evaluationType);
        
        $question->save();
        return response()->json([
            'data' => [
                'questions' => $question
            ]
        ], 201);
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
/*         $catalogue->delete(); */
/*         $evaluationType->update([
            'state_id'=>'3'
        ]); */

        $question->state_id = '3';
        $question->save();

        return response()->json([
            'data' => [
                'questions' => $question
            ]
        ], 201);
    }

    public function indexAnswer()
    {
        $myArr = array();
        $flights = Answer::where('state_id', 1)
        ->get();
        foreach ($flights as $flight) {
            array_push($myArr,$flight->id);
        }
        /* echo implode(",",$myArr); */
        echo $myArr;
    }

    public function showAnswer($id)
    {
        $question = Answer::findOrFail($id);
        return response()->json([
            'data' =>[
                'answers' => $question
            ]
        ]);
    }  

    public function storeAnswer(Request $request){
        $data = $request->json()->all();

       $dataAnswer = $data['answer'];
       $dataState = $data['state'];
       $dataTypeId= $data['type_id'];
       
        $answer = new Answer();
        $answer->code = $dataAnswer['code'];
        $answer->order = $dataAnswer['order'];
        $answer->name = $dataAnswer['name'];
        $answer->value = $dataAnswer['value'];

        $state = State::findOrFail($dataState['id']);
        $type_id = Catalogue::find($dataTypeId['id']);
        
  
        $answer->state()->associate($state);
        $answer->type()->associate($type_id);

            $answer->save();

        return response()->json([
        'data' => [
            'questions' => $answer
        ]
    ], 201);
    }

    public function updateAnswer(Request $request, $id)
    {
        $data = $request->json()->all();

        $dataAnswer = $data['answer'];
        $dataState = $data['state'];
        $dataTypeId= $data['type_id'];

       $answer = Answer::findOrFail($id);
        $answer->code = $dataAnswer['code'];
        $answer->order = $dataAnswer['order'];
        $answer->name = $dataAnswer['name'];
        $answer->value = $dataAnswer['value'];

        

        $state = State::findOrFail($dataState['id']);
        $type_id = Catalogue::find($dataTypeId['id']);

        $answer->state()->associate($state);
        $answer->type()->associate($type_id);
        
        $answer->save();
        return response()->json([
            'data' => [
                'answers' => $answer
            ]
        ], 201);
    }

    public function destroyAnswer($id)
    {
        $answer = Answer::findOrFail($id);
/*         $catalogue->delete(); */
/*         $evaluationType->update([
            'state_id'=>'3'
        ]); */

        $answer->state_id = '3';
        $answer->save();

        return response()->json([
            'data' => [
                'answers' => $answer
            ]
        ], 201);
    }

}

