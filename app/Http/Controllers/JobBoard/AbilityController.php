<?php

namespace App\Http\Controllers\JobBoard;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\JobBoard\Professional;
use App\Models\JobBoard\Ability;
use App\Http\Controllers\Controller;

class AbilityController extends Controller
{
    function index(Request $request)
    {
        try {
            $professional = Professional::where('id', $request->user_id)->first();
            if ($professional) {
                $abilities = Ability::where('professional_id', $professional->id)
                    ->where('state', '<>', 'DELETED')
                    ->orderby($request->field, $request->order)
                    ->paginate($request->limit);
                return response()->json([
                    'pagination' => [
                        'total' => $abilities->total(),
                        'current_page' => $abilities->currentPage(),
                        'per_page' => $abilities->perPage(),
                        'last_page' => $abilities->lastPage(),
                        'from' => $abilities->firstItem(),
                        'to' => $abilities->lastItem()
                    ], 'abilities' => $abilities], 200);
            } else {
                return response()->json([
                    'pagination' => [
                        'total' => 0,
                        'current_page' => 1,
                        'per_page' => $request->limit,
                        'last_page' => 1,
                        'from' => null,
                        'to' => null
                    ], 'abilities' => null], 404);
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
            $ability = Ability::findOrFail($id);
            return response()->json(['ability' => $ability], 200);
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

    function validateDuplicate($dataAbility, $professional)
    {
        return Ability::where('category', $dataAbility['category'])
            ->where('professional_id', $professional['id'])
            ->where('state', '<>', 'DELETED')
            ->first();
    }

    function store(Request $request)
    {
        try {
            $data = $request->json()->all();
            $dataUser = $data['user'];
            $dataAbility = $data['ability'];
            $professional = Professional::where('user_id', $dataUser['id'])->first();
            if ($professional) {
                if (!$this->validateDuplicate($dataAbility, $professional)) {
                    $response = $professional->abilities()->create([
                        'category' => $dataAbility ['category'],
                        'description' => strtoupper($dataAbility ['description']),
                    ]);
                    return response()->json($response, 201);
                } else {
                    return response()->json([
                        'errorInfo' => [
                            '0' => '23505',
                            '1' => '7',
                            '2' => 'ERROR:  llave duplicada viola restricción de unicidad «languages_description_unique»',
                        ]], 409);
                }
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

    function updateAbility(Request $request)
    {
        try {
            $data = $request->json()->all();
            $dataAbility = $data['ability'];
            $ability = Ability::findOrFail($dataAbility ['id'])->update([
                'category' => $dataAbility ['category'],
                'description' => strtoupper($dataAbility ['description']),
            ]);
            return response()->json($ability, 201);
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
            $ability = Ability::findOrFail($request->id)->update([
                'state' => 'DELETED',
            ]);
            return response()->json($ability, 201);
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
