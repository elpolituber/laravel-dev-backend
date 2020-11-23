<?php

namespace Database\Factories\Ignug;

use App\Models\Ignug\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{

    protected $model = Setting::class;

    public function definition()
    {
        return [
            'code' => $this->faker->word,
            'name' => $this->faker->word,
            'description' => $this->faker->word,
            'value' => $this->faker->word,
            'type_id' => 1,
            'status_id' => 1,
            'state_id' => 1,
        ];
    }
}
