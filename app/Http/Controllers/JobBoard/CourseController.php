<?php

namespace App\Http\Controllers\JobBoard;

use App\Http\Controllers\Controller;
use App\Models\Ignug\Catalogue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\JobBoard\Professional;
use App\Models\JobBoard\Course;

class CourseController extends Controller
{
    // Muestra lista de cursos existentes aqqquiiiiiiiii//
    function index(Request $request)
    {
        try {
            $professional = Professional::with(['courses' => function ($query) {
                $query->with('institution');
            }])->where('user_id', $request->user_id)->get();

            return response()->json(['data' => ['courses' => $professional]]);
        } catch (ModelNotFoundException $e) {
            return response()->json($e, 405);
        } catch (NotFoundHttpException  $e) {
            return response()->json($e, 405);
        } catch (QueryException $e) {
            return response()->json($e, 400);
        } catch (Exception $e) {
            return response()->json($e, 500);
        } catch (Error $e) {
            return response()->json($e, 500);
        } catch (ErrorException $e) {
            return response()->json($e, 500);
        }
    }

    // Muestra el dato especifico del Curso//
    function show($id)
    {
        try {
            $course = Course::findOrFail($id);
            return response()->json(['data' => ['course' => $course]], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json($e, 405);
        } catch (NotFoundHttpException  $e) {
            return response()->json($e, 405);
        } catch (QueryException $e) {
            return response()->json($e, 400);
        } catch (Exception $e) {
            return response()->json($e, 500);
        } catch (Error $e) {
            return response()->json($e, 500);
        }
    }

    //Almacena los  Datos creado del curso envia//
    function store(Request $request)
    {
        try {
            $data = $request->json()->all();
            $dataUser = $data['user'];
            $dataCourse = $data['course'];
            $professional = Professional::where('user_id', $dataUser['id'])->first();
            if ($professional) {
                $course = new Course();
                $course->event_name = strtoupper($dataCourse ['event_name']);
                $course->event_name = strtoupper($dataCourse ['event_name']);
                $course->event_name = strtoupper($dataCourse ['event_name']);
                $course->event_name = strtoupper($dataCourse ['event_name']);
                $professional = Catalogue::findOrFail($dataCourse['event_type']['id']);
                $eventType = Catalogue::findOrFail($dataCourse['event_type']['id']);
                $eventType = Catalogue::findOrFail($dataCourse['event_type']['id']);
                $eventType = Catalogue::findOrFail($dataCourse['event_type']['id']);
                $course->eventType()->associate($eventType);
                $course->eventType()->associate($eventType);
                $course->eventType()->associate($eventType);
                $course->eventType()->associate($eventType);
                $course->save();

                return response()->json($response, 201);
            } else {
                return response()->json(null, 404);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json($e, 405);
        } catch (NotFoundHttpException  $e) {
            return response()->json($e, 405);
        } catch (QueryException $e) {
            return response()->json($e, 400);
        } catch (Exception $e) {
            return response()->json($e, 500);
        } catch (Error $e) {
            return response()->json($e, 500);
        }
    }

    //Actualiza los datos del curso creado//
    function update(Request $request)
    {
        try {
            $data = $request->json()->all();
            $dataCourse = $data['course'];
            $course = Course::findOrFail($dataCourse ['id'])->update([
                'event_name' => $dataCourse ['event_name'],
                'start_date' => $dataCourse ['start_date'],
                'end_date' => $dataCourse ['end_date'],
                'hours' => $dataCourse ['hours'],
            ]);
            return response()->json($course, 201);
        } catch (ModelNotFoundException $e) {
            return response()->json($e, 405);
        } catch (NotFoundHttpException  $e) {
            return response()->json($e, 405);
        } catch (QueryException $e) {
            return response()->json($e, 400);
        } catch (Exception $e) {
            return response()->json($e, 500);
        } catch (Error $e) {
            return response()->json($e, 500);
        }
    }

    //Elimina los datos del curso//
    function destroy($id)
    {
        try {
            $workday = Workday::findOrFail($id);
            $state = State::findOrFail($id);
            $workday->state()->associate($state);
            $workday->save();
            $course = Course::findOrFail($request->id)->delete();
            return response()->json($course, 201);
        } catch (ModelNotFoundException $e) {
            return response()->json($e, 405);
        } catch (NotFoundHttpException  $e) {
            return response()->json($e, 405);
        } catch (QueryException $e) {
            return response()->json($e, 400);
        } catch (Exception $e) {
            return response()->json($e, 500);
        } catch (Error $e) {
            return response()->json($e, 500);
        }
    }
}
