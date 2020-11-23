<?php

namespace Database\Factories\Ignug;

use App\Models\Ignug\AuthorityType;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorityTypeFactory extends Factory
{
    protected $model = AuthorityType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->jobTitle,
            'state_id' => 1,
        ];
    }
}
