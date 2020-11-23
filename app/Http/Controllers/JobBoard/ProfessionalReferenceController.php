<?php

namespace App\Http\Controllers\JobBoard;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\JobBoard\ProfessionalReference;
use App\Models\JobBoard\Professional;
use App\Models\Ignug\State;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProfessionalReferenceController extends Controller
{
    function index(Request $request)
    {
        try {
            $professional = Professional::where('id', $request->user_id)->first();
            if ($professional) {
                $professionalReferences = ProfessionalReference::where('professional_id', $professional->id)
                    ->where('state', 'ACTIVE')
                    ->orderby($request->field, $request->order)
                    ->paginate($request->limit);
                return response()->json([
                    'pagination' => [
                        'total' => $professionalReferences->total(),
                        'current_page' => $professionalReferences->currentPage(),
                        'per_page' => $professionalReferences->perPage(),
                        'last_page' => $professionalReferences->lastPage(),
                        'from' => $professionalReferences->firstItem(),
                        'to' => $professionalReferences->lastItem()
                    ], 'professionalReferences' => $professionalReferences], 200);
            } else {
                return response()->json([
                    'pagination' => [
                        'total' => 0,
                        'current_page' => 1,
                        'per_page' => $request->limit,
                        'last_page' => 1,
                        'from' => null,
                        'to' => null
                    ], 'professionalReference' => null], 404);
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
        } catch (ErrorException $e) {
            return response()->json($e, 500);
        }
    }

    function show($id)
    {
        try {
            $professionalReference = ProfessionalReference::findOrFail($id);
            return response()->json(['professionalReference' => $professionalReference], 200);
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
            $dataProfessionalReference = $data['professionalReference'];
            $professional = Professional::where('user_id', $dataUser['id'])->first();

            if ($professional) {
                $response = $professional->professionalReferences()->create([
                    'institution' => strtoupper($dataProfessionalReference ['institution']),
                    'position' => strtoupper($dataProfessionalReference ['position']),
                    'contact' => strtoupper($dataProfessionalReference ['contact']),
                    'phone' => $dataProfessionalReference ['phone'],
                ]);
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

    function update(Request $request)
    {
        try {
            $data = $request->json()->all();
            $dataProfessionalReference = $data['professionalReference'];
            $professionalReference = ProfessionalReference::findOrFail($dataProfessionalReference['id'])->update([
                'institution' => strtoupper($dataProfessionalReference ['institution']),
                'position' => strtoupper($dataProfessionalReference ['position']),
                'contact' => strtoupper($dataProfessionalReference ['contact']),
                'phone' => $dataProfessionalReference ['phone'],
            ]);
            return response()->json($professionalReference, 201);
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
            $professionalReference = ProfessionalReference::findOrFail($request->id)->delete();
            return response()->json($professionalReference, 201);
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
