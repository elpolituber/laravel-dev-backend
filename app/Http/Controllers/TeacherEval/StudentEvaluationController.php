<?php

namespace App\Http\Controllers\TeacherEval;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\TeacherEval\EvaluationType;
use App\Models\TeacherEval\StudentResult;
use App\Models\Ignug\State;
use App\Models\Ignug\Student;
use App\Models\Ignug\SubjectTeacher;
use App\Models\TeacherEval\AnswerQuestion;

class StudentEvaluationController extends Controller
{
    
    public function index(){
        return StudentResult::all();
    } 

    public function store(Request $request)
    {
       $data = $request->json()->all();

    //    $dataStudentResult= $data['student_result'];
       $dataSubjectTeacher = $data['subject_teacher'];
       $dataAnswerQuestions = $data['answer_questions'];
       $dataStudent= $data['student'];   

        foreach($dataAnswerQuestions as $answerQuestion)
        {
            
            $studentResult= new StudentResult();
            $state = State::where('code','1')->first();
            $subjectTeacher = SubjectTeacher::findOrFail($dataSubjectTeacher['id']);
            $student = Student::findOrFail($dataStudent['id']);
            
            $studentResult->state()->associate($state);
            $studentResult->subjectTeacher()->associate($subjectTeacher);
            $studentResult->student()->associate($student);
            $studentResult->answerQuestion()->associate(AnswerQuestion::findOrFail($answerQuestion['id']));
            $studentResult->save();

        }

        return response()->json([
        'data' => [
            'studentResult' => $studentResult
        ]
    ], 201);
    }
    public function update(Request $request){
        return $request;
    }

    public function destroy($id){
        return $id;
    }

}
