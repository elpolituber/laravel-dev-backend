<?php

namespace App\Http\Controllers\JobBoard;

use Illuminate\Support\Facades\DB;
use App\Models\JobBoard\Company;
use App\Models\JobBoard\Offer;
use App\Models\JobBoard\CompanyProfessional;
use App\Models\Ignug\State;
use App\Models\Authentication\User;
use App\Models\JobBoard\Professional;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// use Laravel\Lumen\Routing\Controller as BaseController;

class CompanyController extends Controller
{
    function detachPostulant($id)
    {
        // $company = Company::where('code','3')->first();
        // $company = Company::findOrFail($id);
        // $company->assosiate($professional);
        // $company->save();
        // return response()->json(['message'=>'Professional quitado','professional'=> $company],200);
        $company = Company::fndOrFail($id);
        $company->delete();
        return response()->json(['message'=>'Professional quitado', 'professional'=>$company],200);
        // try {
        //     $data = $request->json()->all();
        //     $user = $data['user'];
        //     $postulant = $data['postulant'];
        //     $company = Company::where('user_id', $user['id'])->first();
        //     if ($company) {
        //         $response = $company->professionals()->detach($postulant['professional_id']);
        //         return response()->json($response, 201);

        //     } else {
        //         return response()->json(null, 404);
        //     }
        // } catch (ModelNotFoundException $e) {
        //     return response()->json($e, 405);
        // } catch (NotFoundHttpException  $e) {
        //     return response()->json($e, 405);
        // } catch (QueryException $e) {
        //     return response()->json($e, 409);
        // } catch (\PDOException $e) {
        //     return response()->json($e, 409);
        // } catch (Exception $e) {
        //     return response()->json($e, 500);
        // } catch (Error $e) {
        //     return response()->json($e, 500);
        // }

    }

    function getAppliedProfessionals(Request $request)
    {

        // $company = User::with(['companies'=>function($query){
        //     $query->with(['professionals'=>function($queryTwo){
        //         $queryTwo->with(['state'=>function($queryThree){
        //             $queryThree->where('code','1');
        //         }]);
        //     }])->with(['state'=>function($queryFour){
        //         $queryFour->where('code','1');
        //     }]);
        // }])->where('id',$request->user_id)->get();
        $company = Company::with(['professionals'=>function($query){
            $query->with(['user'=>function($queryFive){
            }]);
            $query->with(['state'=>function($queryTwo){
                $queryTwo->where('code','1');
            }]);
        }])->with(['state'=>function($queryThree){
            $queryThree->where('code','1');
        }])
        ->where('user_id', $request->user_id)
        ->get();
        // $professional = Professional::with(['user']);
        $interestedProfessionals=[];
        foreach($company as $compania){
            array_push($interestedProfessionals, $compania->professionals);
        }
        // return $company;
        return response()->json([
            'data'=>[
                'professionals'=>$interestedProfessionals
                // $interestedProfessionals,
                // ,$professional
            ]
            ],200);
        //     return response()->json([
        //         'data'=> ['company'=>$professional
        //     ]
        // ],200);

            // try {
            //     $validar = Company::where('user_id',$request ->user_id)->get();
            //     if($validar){
                // $company = Professional::with(['companies'=>function($query){
                //     $query->with(['state'=>function($queryTwo){
                //         $queryTwo->where('code','1');
                //     }]);
                // }])->with(['state'=>function($queryThree){
                //     $queryThree->where('code','1');
                // }])->where('user_id', $request->user_id)->get();
                // // return $company;
                // return response()->json([
                //     'data'=>[
                //         'professional'=>$company
                //     ]
                //     ],200);
        // }else{
        //     return response()->json([
        //         'data'=>[
        //             'profesional'=>'no hay usuario'
        //         ]
        //     ]);
        // }

        // } catch (ModelNotFoundException $e) {
        //     return response()->json($e, 405);
        // } catch (NotFoundHttpException  $e) {
        //     return response()->json($e, 405);
        // } catch (QueryException $e) {
        //     return response()->json($e, 400);
        // } catch (Exception $e) {
        //     return response()->json($e, 500);
        // } catch (Error $e) {
        //     return response()->json($e, 500);
        // }

    }

    function show($id)
    {
        try {
            $company = Company::where('user_id', $id)->get();
            return response()->json(['company' => $company], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json($e, 405);
        } catch (NotFoundHttpException  $e) {
            return response()->json($e, 405);
        } catch (QueryException  $e) {
            return response()->json($e, 405);
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
            $dataCompany = $data['company'];
            $company = Company::findOrFail($dataCompany['id']);
            $company->update([
                'identity' => trim($dataCompany['identity']),
                'email' => strtolower(trim($dataCompany['email'])),
                'nature' => $dataCompany['nature'],
                'trade_name' => strtoupper(trim($dataCompany['trade_name'])),
                'comercial_activity' => strtoupper(trim($dataCompany['comercial_activity'])),
                'phone' => trim($dataCompany['phone']),
                'cell_phone' => trim($dataCompany['cell_phone']),
                'web_page' => strtolower(trim($dataCompany['web_page'])),
                'address' => strtoupper(trim($dataCompany['address'])),
            ]);
            $company->user()->update(['email' => strtolower(trim($dataCompany['email']))]);
            return response()->json($company, 201);
        } catch (ModelNotFoundException $e) {
            return response()->json($e, 405);
        } catch (NotFoundHttpException  $e) {
            return response()->json($e, 405);
        } catch (QueryException  $e) {
            return response()->json($e, 405);
        } catch (Exception $e) {
            return response()->json($e, 500);
        } catch (Error $e) {
            return response()->json($e, 500);
        }
    }
}
