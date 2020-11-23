<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherEval\QuestionController;
use App\Http\Controllers\TeacherEval\EvaluationTypeController;
use App\Http\Controllers\TeacherEval\PairEvaluationController;
use App\Http\Controllers\TeacherEval\SelfEvaluationController;
use App\Http\Controllers\TeacherEval\EvaluationController;


Route::apiResource('evaluation_types',EvaluationTypeController::class);
Route::apiResource('questions', QuestionController::class);

Route::get('answers', 'App\Http\Controllers\TeacherEval\QuestionController@indexAnswer');
Route::get('answers/{id}', 'App\Http\Controllers\TeacherEval\QuestionController@showAnswer');
Route::post('answers', 'App\Http\Controllers\TeacherEval\QuestionController@storeAnswer');
Route::put('answers/{id}', 'App\Http\Controllers\TeacherEval\QuestionController@updateAnswer');
Route::delete('answers/{id}', 'App\Http\Controllers\TeacherEval\QuestionController@destroyAnswer');
Route::apiResource('evaluations', EvaluationController::class);
Route::apiResource('detail_evaluations', App\Http\Controllers\TeacherEval\DetailEvaluationController::class);
Route::apiResource('student_evaluations', App\Http\Controllers\TeacherEval\StudentEvaluationController::class);
Route::apiResource('self_evaluations', SelfEvaluationController::class);
Route::apiResource('pair_evaluations', PairEvaluationController::class)->except(['store']);
Route::post('pair_evaluations/teachers',[PairEvaluationController::class,'storeTeacherEvalutor']);
Route::post('pair_evaluations/authorities',[PairEvaluationController::class,'storeAuthorityEvalutor']);







