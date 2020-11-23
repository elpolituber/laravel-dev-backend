<?php

namespace Database\Factories\Authentication;

use App\Models\Authentication\System;
use Illuminate\Database\Eloquent\Factories\Factory;

class SystemFactory extends Factory
{
    protected $model = System::class;

    public function definition()
    {
        return [
            'state_id' => 1,
        ];
    }
}
