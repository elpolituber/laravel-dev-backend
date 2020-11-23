<?php

namespace App\Http\Controllers\Authentication;

use App\Models\Authentication\Module;
use App\Models\Authentication\Route;
use App\Models\Authentication\System;
use App\models\Ignug\Catalogue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RouteController extends Controller
{
    public function index()
    {
        $modules = Catalogue::with('routes')->where('parent_id', 1)->get();
        return response()->json([
            'data' => $modules,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200'
            ]], 200);
    }


    public function store(Request $request)
    {

    }

    public function show(Route $route)
    {
        //
    }

    public function update(Request $request, Route $route)
    {
        //
    }

    public function destroy(Route $route)
    {
        //
    }
}
