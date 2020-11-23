<?php

namespace App\Http\Requests\Authentication\User;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user.username' => ['required', 'unique:pgsql-authentication.users,username', 'max:50'],
            'user.identification' => ['required', 'unique:pgsql-authentication.users,identification', 'max:20', 'min:9'],
            'user.first_name' => ['required', 'min:3', 'max:50'],
            'user.second_name' => ['min:3', 'max:50'],
            'user.first_lastname' => ['required', 'min:3', 'max:50'],
            'user.second_lastname' => ['min:3', 'max:50'],
            'user.personal_email' => ['max:50'],
            'user.email' => ['required', 'max:50'],
        ];
    }

    public function messages()
    {
        return [
            //            'user.username.required' => 'username es required',
        ];
    }

    public function attributes()
    {
        return [
            //            'user.username' => 'username',
        ];
    }

}
