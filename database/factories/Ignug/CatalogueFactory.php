<?php

namespace Database\Factories\Ignug;

use App\Models\Ignug\Catalogue;
use Illuminate\Database\Eloquent\Factories\Factory;

class CatalogueFactory extends Factory
{

    protected $model = Catalogue::class;

    public function definition()
    {
        return [
            'code' => $this->faker->word,
            'name' => $this->faker->word,
            'type' => $this->faker->word,
            'icon' => $this->faker->word,
            'color' => $this->faker->word,
            'state_id' => 1,
        ];
    }
}
