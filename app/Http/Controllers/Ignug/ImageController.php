<?php

namespace App\Http\Controllers\Ignug;

use App\Http\Controllers\Controller;
use App\Models\Authentication\User;
use App\Models\Ignug\Image;
use App\Models\Ignug\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{

    public function index()
    {
        //
    }


    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png|max:5120',
        ]);

        $user = User::findOrFail($request->user_id);
        //$filePath = $request->avatar->storeAs('avatars', $user->username . '.png', 'public');

        $avatar = $user->images()->where('type', Image::AVATAR_TYPE)->first();
        if (!$avatar) {
            $avatar = new Image([
                'code' => $user->username,
                'name' => 'Avatar',
                'description' => 'Avatar',
                'type' => Image::AVATAR_TYPE,
                'uri' => $filePath,
            ]);
            $avatar->imageable()->associate($user);
            $avatar->state()->associate(State::firstWhere('code', State::ACTIVE));
            $avatar->save();
        }

        return $avatar;
    }


    public function show(Image $image)
    {

    }

    public function update(Request $request, Image $image)
    {
        //
    }


    public function destroy(Image $image)
    {
        //
    }

    public function createAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|mimes:jpg,jpeg,png|max:10',
        ]);

        $user = User::findOrFail($request->user_id);
        $filePath = $request->avatar->storeAs('avatars', $user->username . '.png', 'public');

        $avatar = $user->images()->where('type', Image::AVATAR_TYPE)->first();
        if (!$avatar) {
            $avatar = new Image([
                'code' => $user->username,
                'name' => 'Avatar',
                'description' => 'Avatar',
                'type' => Image::AVATAR_TYPE,
                'uri' => $filePath,
            ]);
            $avatar->imageable()->associate($user);
            $avatar->state()->associate(State::firstWhere('code', State::ACTIVE));
            $avatar->save();
        }

        return $avatar;

    }
}
