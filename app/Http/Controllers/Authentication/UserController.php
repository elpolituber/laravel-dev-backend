<?php

namespace App\Http\Controllers\Authentication;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\User\UserCreateRequest;
use App\Http\Requests\Authentication\UserRequest;
use App\Models\Authentication\PassworReset;
use App\Models\Authentication\System;
use App\Models\Ignug\Catalogue;
use App\Models\Ignug\State;
use App\Models\Authentication\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class  UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('conditions') && $request->conditions && $request->conditions != 'undefined') {
            $users = User::where('state_id', State::firstWhere('code', State::ACTIVE)->id)
                ->where(function ($query) use ($request) {
                    $query->orWhere($this->filter($request->conditions));
                })
                ->with('ethnicOrigin')
                ->with('location')
                ->with('identificationType')
                ->with('sex')
                ->with('gender')
                ->with('roles')
                ->paginate($request->per_page);
        } else {
            $users = User::where('state_id', State::firstWhere('code', State::ACTIVE)->id)
                ->with('ethnicOrigin')
                ->with('location')
                ->with('identificationType')
                ->with('sex')
                ->with('gender')
                ->with('roles')
                ->paginate($request->per_page);
        }
        return response()->json([
            'data' => $users,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200'
            ]], 200);
    }

    public function show($username, Request $request)
    {
        $system = System::where('code', $request->system_code)->first();
        $user = User::
        with('ethnicOrigin')
            ->with('location')
            ->with('identificationType')
            ->with('sex')
            ->with('gender')
            ->with('state')
            ->with(['institutions' => function ($institutions) {
                $institutions->orderBy('name');
            }])
            ->with(['roles' => function ($roles) use ($request, $system) {
                $roles
                    ->with('system')
                    ->with(['permissions' => function ($permissions) {
                        $permissions->with(['route' => function ($route) {
                            $route->with('module')->with('type')->with('images')->with('status');
                        }])->with('institution');
                    }])->where('system_id', $system->id);
            }])
            ->where('username', $username)
            ->first();
        return response()->json([
            'data' => $user,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200'
            ]], 200);
    }

    public function store(UserCreateRequest $request)
    {
        $data = $request->json()->all();
        $dataUser = $data['user'];
        $user = new User();

        $user->identification = strtoupper(trim($dataUser['identification']));
        $user->username = trim($dataUser['username']);
        $user->first_name = strtoupper(trim($dataUser['first_name']));
        $user->first_lastname = strtoupper(trim($dataUser['first_lastname']));
        $user->birthdate = trim($dataUser['birthdate']);
        $user->email = strtolower(trim($dataUser['email']));
        $user->password = Hash::make(trim($dataUser['password']));

        $ethnicOrigin = Catalogue::findOrFail($dataUser['ethnic_origin']['id']);
        $location = Catalogue::findOrFail($dataUser['location']['id']);
        $identificationType = Catalogue::findOrFail($dataUser['identification_type']['id']);
        $sex = Catalogue::findOrFail($dataUser['sex']['id']);
        $gender = Catalogue::findOrFail($dataUser['gender']['id']);
        $state = Catalogue::where('code', '1')->first();
        $user->ethnicOrigin()->associate($ethnicOrigin);
        $user->location()->associate($location);
        $user->identificationType()->associate($identificationType);
        $user->sex()->associate($sex);
        $user->gender()->associate($gender);
        $user->state()->associate($state);
        $user->save();
        return response()->json([
            'data' => $user,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '201'
            ]], 201);
    }

    public function update(Request $request)
    {
        $data = $request->json()->all();
        $dataUser = $data['user'];
        $user = User::findOrFail($dataUser['id']);
        $user->identification = $dataUser['identification'];
        $user->username = strtoupper(trim($dataUser['username']));
        $user->first_name = strtoupper(trim($dataUser['first_name']));
        $user->first_lastname = strtoupper(trim($dataUser['first_lastname']));
        $user->birthdate = trim($dataUser['birthdate']);
        $user->email = strtolower(trim($dataUser['email']));

        $ethnicOrigin = Catalogue::findOrFail($dataUser['ethnic_origin']['id']);
        $location = Catalogue::findOrFail($dataUser['location']['id']);
        $identificationType = Catalogue::findOrFail($dataUser['identification_type']['id']);
        $sex = Catalogue::findOrFail($dataUser['sex']['id']);
        $gender = Catalogue::findOrFail($dataUser['gender']['id']);
        $state = Catalogue::where('code', '1')->first();
        $user->ethnicOrigin()->associate($ethnicOrigin);
        $user->location()->associate($location);
        $user->identificationType()->associate($identificationType);
        $user->sex()->associate($sex);
        $user->gender()->associate($gender);
        $user->state()->associate($state);
        $user->save();
        return response()->json([
            'data' => $user,
            'msg' => [
                'summary' => 'update',
                'detail' => '',
                'code' => '201'
            ]], 201);
    }

    public function destroy($id)
    {
        $state = Catalogue::where('code', '3')->first();
        $user = User::findOrFail($id);
        $user->state()->associate($state);
        $user->save();
        return response()->json([
            'data' => $user,
            'msg' => [
                'summary' => 'deleted',
                'detail' => '',
                'code' => '201'
            ]], 201);
    }

    public function uploadAvatarUri(Request $request)
    {
        if ($request->file('file_avatar')) {
            $user = User::findOrFail($request->user_id);
            Storage::delete($user->avatar);
            $pathFile = $request->file('file_avatar')->storeAs('private/avatar',
                $user->id . '.png');
//            $path = storage_path() . '/app/' . $pathFile;
            $user->update(['avatar' => $pathFile]);
            return response()->json([
                'data' => $user,
                'msg' => [
                    'summary' => 'upload',
                    'detail' => '',
                    'code' => '201'
                ]], 201);
        } else {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Archivo no valido',
                    'detail' => 'Intenta de nuevo',
                    'code' => '400'
                ]], 400);
        }

    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    private function filter($conditions)
    {
        $filters = array();
        foreach ($conditions as $condition) {
            if ($condition['match_mode'] === 'contains') {
                array_push($filters, array($condition['field'], $condition['logic_operator'], '%' . $condition['value'] . '%'));
            }
            if ($condition['match_mode'] === 'start') {
                array_push($filters, array($condition['field'], $condition['logic_operator'], $condition['value'] . '%'));
            }
            if ($condition['match_mode'] === 'end') {
                array_push($filters, array($condition['field'], $condition['logic_operator'], '%' . $condition['value']));
            }
        }
        return $filters;
    }
}
