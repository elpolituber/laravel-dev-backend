<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Attendance\WorkdayController;
use App\Http\Controllers\Attendance\TaskController;

Route::group(['prefix' => 'workdays'], function () {
    Route::post('start_day', [WorkdayController::class, 'startDay']);
    Route::put('end_day', [WorkdayController::class, 'endDay']);
});

Route::group(['prefix' => 'tasks'], function () {
    Route::post('', [TaskController::class, 'store']);
    Route::delete('{id}', [TaskController::class, 'destroy']);
    Route::get('total_processes', [TaskController::class, 'getTotalProcesses']);
    Route::get('processes', [TaskController::class, 'getProcess']);
});

Route::group(['prefix' => 'attendances'], function () {
    Route::get('user_current_day', [AttendanceController::class, 'getUserCurrentDay']);
    Route::get('user_attendances', [AttendanceController::class, 'getUserAttendances']);
    Route::post('user_history_attendances', [AttendanceController::class, 'getUserHistoryAttendances']);
});

Route::group(['prefix' => 'attendances'], function () {
    Route::get('total_processes', [AttendanceController::class, 'getTotalProcesses']);
    Route::get('processes', [AttendanceController::class, 'getProcess']);
    Route::post('current_day', [AttendanceController::class, 'getCurrentDay']);
    Route::get('users', [AttendanceController::class, 'getUsers']);
    Route::post('history_attendances', [AttendanceController::class, 'getHistoryAttendances']);
    Route::post('start_day', [AttendanceController::class, 'startDay']);
    Route::put('end_day', [AttendanceController::class, 'endDay']);
    Route::put('day', [AttendanceController::class, 'updateDay']);
    Route::post('register_tasks', [AttendanceController::class, 'registerTask']);
});

Route::apiResource('attendances', AttendanceController::class);

Route::get('report', function () {
    $data = [
        'titulo' => 'Styde.net'
    ];

    $pdf = \PDF::loadView('reports.attendance.attendance', $data);

    return $pdf->download('archivo.pdf');
})->withoutMiddleware(['auth:api', 'check-attempts', 'check-status']);

Route::group(['prefix' => 'reports'], function () {
    Route::get('attendances', [AttendanceController::class, 'reportAttendances'])->withoutMiddleware(['auth:api', 'check-attempts', 'check-status']);;
    Route::get('tasks', [AttendanceController::class, 'reportTasks'])->withoutMiddleware(['auth:api', 'check-attempts', 'check-status']);;
});
