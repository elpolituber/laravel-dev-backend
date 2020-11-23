<?php

namespace Database\Factories\Authentication;

use App\Models\Authentication\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding Role.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => 'attendance',
            'name' => 'Attendance',
            'system_id' => 1,
            'state_id' => 1,
        ];
    }
}
