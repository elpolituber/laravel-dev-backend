<?php

namespace Database\Factories\Authentication;

use App\Models\Authentication\Route;
use Illuminate\Database\Eloquent\Factories\Factory;

class RouteFactory extends Factory
{
    protected $model = Route::class;

    public function definition()
    {
        return [
            'uri' => $this->faker->url,
            'label' => $this->faker
                ->randomElement(
                    $array = array(
                        'CALIFICACIONES',
                        'TAREAS',
                        'CLASES',
                        'PORTAFOLIO',
                        'ASISTENCIAS',
                        'INCIDENTES',
                        'CALENDARIO ACADEMICO',
                        'HORARIO',
                        'PROFESORES',
                        'REPORTES',
                        'CORREO ELECTRONICO',
                        'ESTUDIANTES',
                        'PERFIL',
                        'FACTURACION',
                    )),
            'icon' => 'pi pi-external-link',
            'description' => $this->faker->word,
            'order' => 1,
            'state_id' => 1,
        ];
    }
}
