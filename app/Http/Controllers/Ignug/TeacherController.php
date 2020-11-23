<?php

namespace App\Http\Controllers\Ignug;

use App\Http\Controllers\Controller;
use App\Models\Authentication\User;
use App\Models\Ignug\Image;
use App\Models\Ignug\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Teacher $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Teacher $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Teacher $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        //
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png|max:5120',
            'name' => 'required|max:255',
            'description' => 'required|max:500',
        ]);

        return Image::upload(Teacher::findOrFail($request->teacher_id), $request);
    }
}
