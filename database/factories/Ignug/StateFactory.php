<?php

namespace Database\Factories\Ignug;

use App\Models\Ignug\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class StateFactory extends Factory
{
    protected $model = State::class;

    public function definition()
    {
        return [
            'code' => $this->faker->word,
            'name' => $this->faker->sentence,
        ];
    }
}
