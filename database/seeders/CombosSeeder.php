<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ignug\Catalogue;
use Illuminate\Support\Facades\DB;

class CombosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            //status
            Catalogue::factory()->create([
                'code' => '1',
                'name' => 'en proceso',
                'type' => 'status_vinculacion',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '2',
                'name' => 'pendiente',
                'type' => 'status_vinculacion',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '3',
                'name' => 'ractificar',
                'type' => 'status_vinculacion',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '4',
                'name' => 'corregido',
                'type' => 'status_vinculacion',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '5',
                'name' => 'aprobado',
                'type' => 'status_vinculacion',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '6',
                'name' => 'culminado',
                'type' => 'status_vinculacion',
                'state_id' => 1,
            ]);
            //funcion teacher
            Catalogue::factory()->create([
                'code' => '1',
                'name' => 'tutor',
                'type' => 'funtion_vinculacion',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '2',
                'name' => 'cordinador',
                'type' => 'funtion_vinculacion',
                'state_id' => 1,
            ]);
            //FraquencyOfActivity Frecuencia de actividades
            Catalogue::factory()->create([
                'code' => '1',
                'name' => 'Diaria',
                'type' => 'fraquency_activity',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '2',
                'name' => 'Semanal',
                'type' => 'fraquency_activity',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '3',
                'name' => 'Quincenal',
                'type' => 'fraquency_activity',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '4',
                'name' => 'Mensual',
                'type' => 'fraquency_activity',
                'state_id' => 1,
            ]);
            //AssignedLine
            Catalogue::factory()->create([
                'code' => '1',
                'name' => 'Gestion de Integracion del Proyecto',//Linea Asignada
                'type' => 'assigned_line',
                'state_id' => 1,
            ]);

            Catalogue::factory()->create([
                'code' => '2',
                'name' => 'Gestion del Alcance del Proyecto',
                'type' => 'assigned_line',
                'state_id' => 1,
            ]);

            Catalogue::factory()->create([
                'code' => '3',
                'name' => 'Gestion de Tiempo del Proyecto',
                'type' => 'assigned_line',
                'state_id' => 1,
            ]);

            Catalogue::factory()->create([
                'code' => '4',
                'name' => 'Gestion de Costo del Proyecto',
                'type' => 'assigned_line',
                'state_id' => 1,
            ]);

            Catalogue::factory()->create([
                'code' => '5',
                'name' => 'Gestion de la Calidad del Proyecto',
                'type' => 'assigned_line',
                'state_id' => 1,
            ]);

            Catalogue::factory()->create([
                'code' => '6',
                'name' => 'Gestion RRHH del Proyecto',
                'type' => 'assigned_line',
                'state_id' => 1,
            ]);

            Catalogue::factory()->create([
                'code' => '7',
                'name' => 'Gestion de Comunicaciones del Proyecto',
                'type' => 'assigned_line',
                'state_id' => 1,
            ]);

            Catalogue::factory()->create([
                'code' => '8',
                'name' => 'Gestion de Riesgos del Proyecto',
                'type' => 'assigned_line',
                'state_id' => 1,
            ]);

            Catalogue::factory()->create([
                'code' => '9',
                'name' => 'Gestion de Adquisiones del Proyecto',
                'type' => 'assigned_line',
                'state_id' => 1,
            ]);
            //aims_types
            Catalogue::factory()->create([
                'code' => '1',
                'name' => 'Objetivo especifico',
                'type' => 'aims_types',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '2',
                'name' => 'Resultado',
                'type' => 'aims_types',
                'state_id' => 1,
            ]);
            //MeansOfVerification

            Catalogue::factory()->create([
                'code' => '1',
                'name' => 'medios de verificacion',
                'type' => 'Means_Verification',
                'state_id' => 1,
            ]);

            //LinkageAxes/ejes de vinculacion
            Catalogue::factory()->create([
                'code' => '1',
                'name'=>'Ambiental',
                'type' => 'linkage_axes',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '2',
                'name'=>'Interculturalidad/género ',
                'type' => 'linkage_axes',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '3',
                'name'=>'Investigativo Académico',
                'type' => 'linkage_axes',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '4',
                'name'=>'Desarrollo social,comunitario',
                'type' => 'linkage_axes',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '5',
                'name'=>'Desarrollo local',
                'type' => 'linkage_axes',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '6',
                'name'=>'Economía popular y solidaria',
                'type' => 'linkage_axes',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '7',
                'name'=>'Desarrollo técnico y tecnológico',
                'type' => 'linkage_axes',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '8',
                'name'=>'Innovación',
                'type' => 'linkage_axes',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '9',
                'name'=>'Responsabilidad social universitaria',
                'type' => 'linkage_axes',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '10',
                'name'=>'Matriz productiva',
                'type' => 'linkage_axes',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '11',
                'name'=>'Otros',
                'type' => 'linkage_axes',
                'state_id' => 1,
            ]);

            //BondingActivities/Actividad de vinculación
            Catalogue::factory()->create([
                'code' => '1',
                'name'=>'Investigación',
                'type' => 'bonding_activities',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '2',
                'name'=>'Acuerdo',
                'type' => 'bonding_activities',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '3',
                'name'=>'Proyecto de vinculación propio IST JME',
                'type' => 'bonding_activitiess',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '4',
                'name'=>'Programa de capacitación a la comunidad',
                'type' => 'bonding_activities',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '5',
                'name'=>'Practicas Vinculación con la comunidad',
                'type' => 'bonding_activities',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '6',
                'name'=>'Practicas Vinculación con la comunidad',
                'type' => 'bonding_activities',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '7',
                'name'=>'Convenios institucionales',
                'type' => 'bonding_activities',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '8',
                'name'=>'Otros',
                'type' => 'bonding_activities',
                'state_id' => 1,
            ]);
            //Institute
            DB::connection('pgsql-ignug')->table('institutions')->insert([
                'code'=>null,
                'name'=>"Instituto Benito Juarez",
                'slogan'=>"blablablbaa",
                'state_id'=>1,
            ]);
            //research_areas/area de investigacion
            Catalogue::factory()->create([
                'code' => '1',
                'name'=>'Atención a la Ciudadanía',
                'type' => 'research_areas',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '2',
                'name'=>'Dirección',
                'type' => 'research_areas',
                'state_id' => 1,
            ]);
            //General Aim
            Catalogue::factory()->create([
                'code' => '1',
                'name'=>'objetivo general',
                'type' => 'aims',
                'state_id' => 1,
            ]);
            //Especifics Aims
            Catalogue::factory()->create([
                'code' => '2',
                'name'=>'objetivo especifico',
                'type' => 'aims',
                'state_id' => 1,
            ]);
            //Resultado
            Catalogue::factory()->create([
                'code' => '3',
                'name'=>'resultado',
                'type' => 'aims',
                'state_id' => 1,
            ]);
            //Actividades
            Catalogue::factory()->create([
                'code' => '4',
                'name'=>'Actividades',
                'type' => 'aims',
                'state_id' => 1,
            ]);


            // career modality
            Catalogue::factory()->create([
                'code' => '1',
                'name' => 'PRESENCIAL',
                'type' => 'career_modality',
                'state_id' => 1,
             ]);

             Catalogue::factory()->create([
                'code' => '2',
                'name' => 'SEMI-PRESENCIAL',
                'type' => 'career_modality',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '3',
                'name' => 'DISTANCIA',
                'type' => 'career_modality',
                'state_id' => 1,
            ]);
            Catalogue::factory()->create([
                'code' => '4',
                'name' => 'DUAL',
                'type' => 'career_modality',
                'state_id' => 1,
            ]);

            //Career
            $modalidad=Catalogue::where( "name", "DUAL")->first();
            DB::connection('pgsql-ignug')->table('careers')->insert([
                'institution_id'=>1,
                'code'=>'c12',
                'name'=>'desarrollo de software',
                'description'=>'assdadasdljafhjkasn',
                'modality_id'=>$modalidad->id,
                'title'=>'asdasffdsadsf',
                'acronym'=>'desarrollo',
                'type_id'=>1,
                'state_id'=>1,

            ]);

    }
}
