<?php

namespace App\Http\Controllers\JobBoard;


use App\Http\Controllers\Controller;
use App\Models\JobBoard\Company;
use App\Models\JobBoard\Offer;
use App\Models\JobBoard\Professional;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OfferController extends Controller
{

    /**
     * Obtiene todas las ofertas activas dentro del rango de fecha actual.
     */
    function getAllOffers()
    {
        $now = Carbon::now();
        $offers = Offer::with('parent')->with('children_category')
            ->with('city')->with('province')->where('state_id', '1')
            ->where('finish_date', '>=', $now->format('Y-m-d'))
            ->where('start_date', '<=', $now->format('Y-m-d'))
            ->get();
        return response()->json(['offers' => $offers], 200);
    }

    function getOffers(Request $request)
    {
        $now = Carbon::now();
        $offers = Offer::where('state_id', '1')
            ->where('end_date', '>=', $now->format('Y-m-d'))
            ->where('start_date', '<=', $now->format('Y-m-d'))
            ->orderby($request->field, $request->order)
            ->paginate($request->limit);
        return response()->json([
            'pagination' => [
                'total' => $offers->total(),
                'current_page' => $offers->currentPage(),
                'per_page' => $offers->perPage(),
                'last_page' => $offers->lastPage(),
                'from' => $offers->firstItem(),
                'to' => $offers->lastItem()
            ], 'offers' => $offers], 200);
    }

    function filterOffers(Request $request)
    {
        $now = Carbon::now();
        $data = $request->json()->all();
        $dataFilter = $data['filters'];
        $offers = Offer::with(['father_category' => function ($query) use($dataFilter) {
            foreach ($dataFilter['conditionsCategoryChildren'] as $key) {
                // $i++;
                $query->orWhere($key);
            }

            // $query->orWhere($dataFilter['conditionsCategoryFather']);
        }])
            ->with(['children_category' => function ($query) use($dataFilter) {
                // $query->orWhere($dataFilter['conditionsCategoryChildren']);
                foreach ($dataFilter['conditionsCategoryChildren'] as $key) {
                    // $i++;
                    $query->orWhere($key);
                }
            }])
            ->with(['city' => function ($query) use($dataFilter) {
                $query->orWhere($dataFilter['conditionsCity']);
            }])
            ->with(['province' => function ($query) use($dataFilter) {
                $query->orWhere($dataFilter['conditionsProvince']);
            }])
            ->orWhere($dataFilter['conditions'])
            ->where('state_id', 1)
            ->where('finish_date', '>=', $now->format('Y-m-d'))
            ->where('start_date', '<=', $now->format('Y-m-d'))
            ->orderby($request->field, $request->order)
            ->paginate($request->limit);
        return response()->json([
            'pagination' => [
                'total' => $offers->total(),
                'current_page' => $offers->currentPage(),
                'per_page' => $offers->perPage(),
                'last_page' => $offers->lastPage(),
                'from' => $offers->firstItem(),
                'to' => $offers->lastItem()
            ], 'offers' => $offers], 200);
    }

    function getTotalOffers()
    {
        $now = Carbon::now();
        $totalOffers = Offer::where('state_id', '1')
            ->where('end_date', '>=', $now->format('Y-m-d'))
            ->where('start_date', '<=', $now->format('Y-m-d'))
            ->count();
        return response()->json(['totalOffers' => $totalOffers], 200);
    }

    function validateAppliedOffer(Request $request)
    {
        try {
            $professional = Professional::where('user_id', $request->user_id)->first();
            if ($professional) {
                $appliedOffer = DB::table('job_board.offer_professional')
                    ->where('offer_id', $request->offer_id)
                    ->where('professional_id', $professional->id)
                    ->first();
                if ($appliedOffer) {
                    return response()->json(true, 200);
                } else {
                    return response()->json(false, 200);
                }
            } else {
                return response()->json(null, 404);
            }

        } catch (ModelNotFoundException $e) {
            return response()->json($e, 405);
        } catch (NotFoundHttpException  $e) {
            return response()->json($e, 405);
        } catch (QueryException $e) {
            return response()->json($e, 409);
        } catch (\PDOException $e) {
            return response()->json($e, 409);
        } catch (Exception $e) {
            return response()->json($e, 500);
        } catch (Error $e) {
            return response()->json($e, 500);
        }
    }

    function applyOffer(Request $request)
    {
        try {
            $data = $request->json()->all();
            $user = $data['user'];
            $offer = $data['offer'];
            $professional = Professional::where('user_id', $user['id'])->first();
            if ($professional) {
                $appliedOffer = DB::table('job_board.offer_professional')
                    ->where('offer_id', $offer['id'])
                    ->where('professional_id', $professional->id)
                    ->first();
                if (!$appliedOffer) {
                    $professional->offers()->attach($offer['id']);
                    return response()->json(true, 201);
                } else {
                    return response()->json(false, 201);
                }
            } else {
                return response()->json(null, 404);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json($e, 405);
        } catch (NotFoundHttpException  $e) {
            return response()->json($e, 405);
        } catch (QueryException $e) {
            return response()->json($e, 409);
        } catch (\PDOException $e) {
            return response()->json($e, 409);
        } catch (Exception $e) {
            return response()->json($e, 500);
        } catch (Error $e) {
            return response()->json($e, 500);
        }

    }
    /*
     * FinGrupo 2
     */

    /*
     * Grupo 3
     */
    function indexOffers()
    {
        $offers = Offer::get();
        return response()->json([
            'data' => [
                'type' => 'offer',
                'attributes' => $offers
            ]
        ], 200);
    }
    /*
     * FinGrupo 3
     */

    /*
     * Grupo 6
     */
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $offerts = Offert::where('code', 'like', '%' . $request->search . '%')
                ->orWhere('position', 'like', '%' . $request->search . '%')
                ->limit(1000)
                ->get();
        } else {
            $offerts = Offert::all();
        }

        return response()->json([
            'data' => [
                'attributes' => $offerts,
                'type' => 'offerts'
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $state = State::firstWhere('code', State::ACTIVE);
        $offert = $state->offert()->create($data);
        return response()->json([
            'data' => [
                'attributes' => $offert,
                'type' => 'offert'
            ]
        ], 201);
    }

    public function show(Offert $offert)
    {
        return $offert;
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $offert = $offert->update($data);
        return response()->json([
            'data' => [
                'attributes' => $offert,
                'type' => 'offert'
            ]
        ], 201);
    }

    public function destroy($id)
    {
        $state = State::where('code', '3')->first();
        $offert = Offert::findOrFail($id);
        $offert->state()->associate($state);
        $offert->update();
        return response()->json([
            'data' => [
                'attributes' => $offert,
                'type' => 'offert'
            ]
        ], 201);
    }
    /*
     * FinGrupo 6
     */
}
