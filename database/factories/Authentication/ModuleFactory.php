<?php

namespace Database\Factories\Authentication;

use App\Models\Authentication\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleFactory extends Factory
{

    protected $model = Module::class;

    public function definition()
    {
        return [
            'state_id' => 1,
            'icon' => $this->faker
                ->randomElement(
                    $array = array(
                        'pi pi-align-center',
                        'pi pi-android',
                        'pi pi-apple',
                        'pi pi-chart-bar',
                        'pi pi-cog',
                        'pi pi-microsoft',
                    ))
        ];
    }
}
