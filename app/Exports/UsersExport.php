<?php

namespace App\Exports;

use App\Models\Authentication\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView
{
    public function view(): View
    {
        return view('user', [
            'users' => User::all()
        ]);
    }
}
