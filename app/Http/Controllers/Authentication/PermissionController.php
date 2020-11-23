<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\Authentication\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Permission::all()], 200);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Permission $permission)
    {
        //
    }

    public function update(Request $request, Permission $permission)
    {
        //
    }

    public function destroy(Permission $permission)
    {
        //
    }
}
