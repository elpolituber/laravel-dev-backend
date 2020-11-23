<?php

namespace Database\Factories\Ignug;

use App\Models\Ignug\Authority;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorityFactory extends Factory
{

    protected $model = Authority::class;


    public function definition()
    {
        return [
            'state_id' => 1,
        ];
    }
}
