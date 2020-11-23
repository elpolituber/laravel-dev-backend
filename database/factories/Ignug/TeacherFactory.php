<?php

namespace Database\Factories\Ignug;

use App\Models\Ignug\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition()
    {
        return [
            'state_id' => 1
        ];
    }
}
