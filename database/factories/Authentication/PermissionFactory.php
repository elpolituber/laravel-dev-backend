<?php

namespace Database\Factories\Authentication;

use App\Models\Authentication\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{

    protected $model = Permission::class;

    public function definition()
    {
            $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        return [
            'actions' => $this->faker
                ->randomElements(
                    $array = array(
                        $catalogues['permission']['action']['create'],
                        $catalogues['permission']['action']['update'],
                        $catalogues['permission']['action']['delete'],
                        $catalogues['permission']['action']['select']),
                    $count = random_int(1, 4)),
            'state_id' => 1
        ];
    }
}
