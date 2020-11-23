<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Carbon\Carbon;
use http\Client\Curl\User;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function store(Request $request)
    {
        if (Log::count() >= 1000000) {
            Log::where('created_at', '<=', Carbon::now())->delete();
        }
        $log = Log::create($request->all());
        return response()->json([
            'data' => $log,
            'msg' => [
                'code' => '201',
                'summary' => 'success',
                'detail' => '',
            ]
        ], 201);
    }
}
