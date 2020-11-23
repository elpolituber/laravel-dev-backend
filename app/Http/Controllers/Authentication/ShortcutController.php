<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\Authentication\Permission;
use App\Models\Authentication\Route;
use App\Models\Authentication\System;
use App\Models\Ignug\State;
use Illuminate\Http\Request;

class ShortcutController extends Controller
{
    public function index(Request $request)
    {
        $shortcuts = Permission::whereHas('shortcut', function ($shortcut) use ($request) {
            $shortcut
                ->where('role_id', $request->role_id)
                ->where('user_id', $request->user_id);
        })->with('shortcut')
            ->with('route')
            ->where('institution_id', $request->institution_id)
            ->where('state_id', State::firstWhere('code', State::ACTIVE)->id)
            ->limit(100)
            ->get();
        return response()->json([
            'data' => $shortcuts,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200'
            ]], 200);
    }
}
