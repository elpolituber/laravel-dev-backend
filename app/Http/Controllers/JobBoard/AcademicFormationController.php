<?php

namespace App\Http\Controllers\JobBoard;

use http\Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\Jobboard\Professional;
use App\Models\JobBoard\AcademicFormation;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class AcademicFormationController extends Controller
{
    function index(Request $request)
    {
        try {
            $professional = Professional::with(['academicFormations' => function ($query) {
                $query->with(['state' => function ($query) {
                    $query->where('code', '1');
                }])->with(['category' => function ($query) {
                    $query->with(['state' => function ($query) {
                        $query->where('code', '1');
                    }]);
                }])->with(['professionalDegree' => function ($query) {
                    $query->with(['state' => function ($query) {
                        $query->where('code', '1');
                    }]);
                }]);
            }])->with(['state' => function ($query) {
                $query->where('code', '1');
            }])->where('id', $request->user_id)->get();


            return response()->json([
                'data' => ['academicFormations' => $professional]], 200);
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

    function show($id)
    {
        try {
            $academicFormation = AcademicFormation::findOrFail($id);
            return response()->json(['academicFormation' => $academicFormation], 200);
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

    function store(Request $request)
    {
        try {
            $data = $request->json()->all();
            $dataUser = $data['user'];
            $dataAcademicFormation = $data['academicFormation'];
            $professional = Professional::where('user_id', $dataUser['id'])->first();
            if ($professional) {
                $response = $professional->academicFormations()->create([
                    'institution' => $dataAcademicFormation ['institution'],
                    'career' => $dataAcademicFormation ['career'],
                    'professional_degree' => $dataAcademicFormation ['professional_degree'],
                    'registration_date' => $dataAcademicFormation ['registration_date'],
                    'senescyt_code' => $dataAcademicFormation ['senescyt_code'],
                    'has_titling' => $dataAcademicFormation ['has_titling'],
                ]);
                return response()->json($response, 201);
            } else {
                return response()->json(null, 404);
            }
        } catch (
        ModelNotFoundException $e) {
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

    function update(Request $request)
    {
        try {
            $data = $request->json()->all();
            $dataAcademicFormation = $data['academicFormation'];
            $academicFormation = AcademicFormation::findOrFail($dataAcademicFormation ['id'])->update([
                'institution' => $dataAcademicFormation ['institution'],
                'career' => $dataAcademicFormation ['career'],
                'professional_degree' => $dataAcademicFormation ['professional_degree'],
                'registration_date' => $dataAcademicFormation ['registration_date'],
                'senescyt_code' => $dataAcademicFormation ['senescyt_code'],
                'has_titling' => $dataAcademicFormation ['has_titling'],
            ]);
            return response()->json($academicFormation, 201);
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

    function destroy(Request $request)
    {
        try {
            $academicFormation = AcademicFormation::findOrFail($request->id)->delete();
            return response()->json($academicFormation, 201);
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
