<?php

namespace App\Http\Controllers\Ignug;

use App\Http\Controllers\Controller;
use App\Models\Ignug\Catalogue;
use App\Models\Ignug\State;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'data' => [
                'catalogues' => Catalogue::where('type',$request->type)->get()
            ]]);
    }

    public function show(Catalogue $catalogue)
    {
        return response()->json([
            'data' => [
                'catalogue' => $catalogue
            ]]);
    }

    public function store(Request $request)
    {
        $data = $request->json()->all();
        $dataCatalogue = $data['catalogue'];
        $dataParentCode = $data['parent_code'];

        $catalogue = new Catalogue();
        $catalogue->code = $dataCatalogue['code'];
        $catalogue->name = $dataCatalogue['name'];
        $catalogue->icon = $dataCatalogue['icon'];
        $catalogue->type = $dataCatalogue['type'];

        $state = State::firstWhere('code', State::ACTIVE);
        $parentCode = Catalogue::findOrFail($dataParentCode['id']);

        $catalogue->state()->associate($state);
        $catalogue->parentCode()->associate($parentCode);

        $catalogue->save();

        return response()->json([
            'data' => [
                'catalogues' => $catalogue
            ]
        ], 201);
    }

    public function update(Request $request, Catalogue $catalogue)
    {

        $data = $request->json()->all();
        $dataCatalogue = $data['catalogue'];
//        $dataParentCode = $data['parent_code'];

//        $catalogue->code = $dataCatalogue['code'];
        $catalogue->name = $dataCatalogue['name'];
        $catalogue->icon = $dataCatalogue['icon'];
        $catalogue->type = $dataCatalogue['type'];

//        $parentCode = Catalogue::findOrFail($dataParentCode['id']);

//        $catalogue->parentCode()->associate($parentCode);
        $catalogue->save();
        return response()->json([
            'data' => [
                'catalogue' => $catalogue
            ]
        ], 201);
    }

    public function destroy(Catalogue $catalogue)
    {
//        $catalogue->delete();
        $state = State::where('code', '3')->first();
        $catalogue->state()->associate($state);
        $catalogue->save();
        return response()->json([
            'data' => [
                'catalogue' => $catalogue
            ]
        ], 201);
    }

}
