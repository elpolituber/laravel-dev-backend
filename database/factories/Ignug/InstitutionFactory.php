<?php

namespace Database\Factories\Ignug;

use App\Models\Ignug\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstitutionFactory extends Factory
{
    protected $model = Institution::class;

    public function definition()
    {
        return [
            'code' => $this->faker->word,
            'acronym' => $this->faker->text($maxNbChars = 10),
            'denomination' => 'INSTITUTO SUPERIOR TECNLOGICO',
            'name' => $this->faker->text($maxNbChars = 25),
            'slogan' => $this->faker->word,
            'logo' => $this->faker->word,
            'state_id' => 1,
        ];
    }
}
