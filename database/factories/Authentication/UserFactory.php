<?php

namespace Database\Factories\Authentication;

use App\Models\Authentication\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class UserFactory extends Factory
{

    protected $model = User::class;

    public function definition()
    {
        return [
//            'ethnic_origin_id' => random_int(1, 8),
//            'location_id' => 30,
//            'identification_type_id' => random_int(14, 15),
            'identification' => $this->faker->numberBetween($min = 1000000000, $max = 9999999999),
            'first_name' => $this->faker->firstNameMale,
            'second_name' => $this->faker->firstNameMale,
            'first_lastname' => $this->faker->lastName,
            'second_lastname' => $this->faker->lastName,
//            'sex_id' => 10,
//            'gender_id' => 12,
//            'personal_email' => $faker->unique()->safeEmail,
//            'birthdate' => $faker->date($format = 'Y-m-d', $max = 'now'),
//            'blood_type_id' => random_int(16, 23),
            'avatar' => $this->faker->numberBetween($min = 1000000000, $max = 9999999999),
            'username' => $this->faker->numberBetween($min = 1000000000, $max = 9999999999),
            'email' => $this->faker->unique()->safeEmail,
            'state_id' => 1,
            'status_id' => 1,
            'password' => '$2y$10$fojHGTDRXyjmcXSgE7/1xOubqUrv03AiQb.9lKKH4PxJfkoluZGxK', // 12345678
        ];
    }
}
