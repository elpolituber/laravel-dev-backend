<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::connection('pgsql-community')->table('charitable_institutions')->insert([
            'state_id' => 1,
            'ruc' => '1234567891',
            'name' => "FUNDACION VISTA PARA TODOS",
            'location_id' => 1, //fk propia
            'indirect_beneficiaries' => json_encode(['bio']),
            'legal_representative_name' => "RODRIGO",
            'legal_representative_lastname' => "nogales",
            'legal_representative_identification' => 1725485277,
            'project_post_charge' => "PRESIDENTE",//CARGO
            'direct_beneficiaries' => json_encode(['VISTA PARA TODOS']),
        ]);

        DB::connection('pgsql-community')->table('projects')->insert([
            'charitable_institution_id' => 1,
            'career_id' => 1,
            'assigned_line_id' => 4,//pendiente//lineas de investigacion
            'code' => 'yavirac1223',
            'name' => 'Systema ayuda a los pobres',
            'status_id' => 1,//catalogo propio una fk
            'state_id' => 1,
            'field' => "campo",//campo de area de vinculacion
            'aim' => 'objeto',//objeto
            'fraquency_id' => 4,//frecuencia de actividades
            //'cycle'=>"121",//ciclo
            'location_id' => 1,
            'lead_time' => 3,//plazo de ejecucion
            /* 'delivery_date'=>01/05/2020,// tiempo
            'start_date'=>11/05/2020,// tiempo
            'end_date'=>11/07/2020, */
            'description' => 'ofdasdsafsda',//DESCRIPCION GENERAL  DEL PROYECTO.
            'coordinator_name' => 'OLIVER',
            'coordinator_lastname' => 'NESTLES',
            'coordinator_postition' => 'POSITION',
            'coordinator_funtion' => 'coordinator',
            'introduction' => 'AFDSFDSDDSAFASSD',
            'situational_analysis' => 'AASDSDDSAAFDSSAF',//ANALISIS SITUACIONAL (DIAGNOSTICO)
            'foundamentation' => 'SADADASD',
            'justification' => "ADSSDFDSF",
            //'bibliografia'=>"SE PONE LA BIBLIOGRAFIA",
        ]);
        //Objetivo general
        DB::connection('pgsql-community')->table('specific_aims')->insert([
            'state_id' => 1,
            'project_id' => 1,
            'indicator' => "Número de estudiantes capacidades en el área de informática ",
            'verifications' => json_encode(['Listado de asistencia']),
            'description' => 'Brindar una capacitación en ofimática básica a niños de 8 a 12 años mediante talleres y trabajos dirigidas para su desarrollo educativo',//linea base
            'type_id' => 7,//crear tipo de catologos
            'parent_code_id' => null,//tabla recusiva
        ]);
        //Objetivo especifico
        DB::connection('pgsql-community')->table('specific_aims')->insert([
            'state_id' => 1,
            'project_id' => 1,
            'indicator' => "Número de máquinas por realizar mantenimiento ",
            'verifications' => json_encode(['Informe de estado de maquinas ']),
            'description' => ' Analizar el estado general  de las maquinas mediante una revisión preliminar para verificar el estado y las condiciones de los equipos informáticos',//linea base
            'type_id' => 7,//crear tipo de catologos
            'parent_code_id' => 1,//tabla recusiva
        ]);
        DB::connection('pgsql-community')->table('specific_aims')->insert([
            'state_id' => 1,
            'project_id' => 1,
            'indicator' => "Número de máquinas formateadas",
            'verifications' => json_encode(['Informe de finalización de mantenimiento']),
            'description' => 'Realizar el mantenimiento del centro de cómputo de CENIT para tener un mejor funcionamiento de los equipos.',//linea base
            'type_id' => 7,//crear tipo de catologos
            'parent_code_id' => 1,//tabla recusiva
        ]);
        DB::connection('pgsql-community')->table('specific_aims')->insert([
            'state_id' => 1,
            'project_id' => 1,
            'indicator' => "Listado de temas a tratar ",
            'verifications' => json_encode(['Cronograma de actividades']),
            'description' => 'Iniciar las capacitación en ofimática básica para el desarrollo estudiantil de los niños que acuden al centro cenit',//linea base
            'type_id' => 7,//crear tipo de catologos
            'parent_code_id' => 1,//tabla recusiva
        ]);
        //Resultados
        DB::connection('pgsql-community')->table('specific_aims')->insert([
            'state_id' => 1,
            'project_id' => 1,
            'indicator' => "Documentación técnica",
            'verifications' => json_encode(['Informe técnico']),
            'description' => 'Conocer el número de máquinas que tienen inconvenientes para su funcionamiento ',//linea base
            'type_id' => 8,//crear tipo de catologos
            'parent_code_id' => 2,//tabla recusiva
        ]);
        DB::connection('pgsql-community')->table('specific_aims')->insert([
            'state_id' => 1,
            'project_id' => 1,
            'indicator' => "Documentación técnica",
            'verifications' => json_encode(['Informe técnico']),
            'description' => 'Realización de mantenimiento preventivo y correctivo a las maquinas del centro',//linea base
            'type_id' => 8,//crear tipo de catologos
            'parent_code_id' => 3,//tabla recusiva
        ]);
        DB::connection('pgsql-community')->table('specific_aims')->insert([
            'state_id' => 1,
            'project_id' => 1,
            'indicator' => "Número de estudiantes aprobados",
            'verifications' => json_encode(['Calificaciones']),
            'description' => 'Estudiantes capacitados',//linea base
            'type_id' => 8,//crear tipo de catologos
            'parent_code_id' => 4,//tabla recusiva
        ]);
        //Actividades
        DB::connection('pgsql-community')->table('specific_aims')->insert([
            'state_id' => 1,
            'project_id' => 1,
            'indicator' => "Documentación técnica",
            'verifications' => json_encode(['Informe técnico']),
            'description' => '1.1.1 Reconocimiento del área de trabajo',//linea base
            'type_id' => 8,//crear tipo de catologos
            'parent_code_id' => 2,//tabla recusiva
        ]);
        DB::connection('pgsql-community')->table('specific_aims')->insert([
            'state_id' => 1,
            'project_id' => 1,
            'indicator' => "Documentación técnica",
            'verifications' => json_encode(['Informe técnico']),
            'description' => '1.1.2 Revisión preliminar de las maquinas',//linea base
            'type_id' => 8,//crear tipo de catologos
            'parent_code_id' => 2,//tabla recusiva
        ]);
        DB::connection('pgsql-community')->table('specific_aims')->insert([
            'state_id' => 1,
            'project_id' => 1,
            'indicator' => "Documentación técnica",
            'verifications' => json_encode(['Informe técnico']),
            'description' => '1.1.3 Entrega de informe de estado de las maquinas',//linea base
            'type_id' => 8,//crear tipo de catologos
            'parent_code_id' => 2,//tabla recusiva
        ]);
        DB::connection('pgsql-community')->table('specific_aims')->insert([
            'state_id' => 1,
            'project_id' => 1,
            'indicator' => "Documentación técnica",
            'verifications' => json_encode(['Informe técnico']),
            'description' => '2.1.1 Tareas dirigidas a los niños',//linea base
            'type_id' => 8,//crear tipo de catologos
            'parent_code_id' => 3,//tabla recusiva
        ]);
        DB::connection('pgsql-community')->table('specific_aims')->insert([
            'state_id' => 1,
            'project_id' => 1,
            'indicator' => "Documentación técnica",
            'verifications' => json_encode(['Informe técnico']),
            'description' => 'Avance del mantenimiento de las maquinas',//linea base
            'type_id' => 8,//crear tipo de catologos
            'parent_code_id' => 3,//tabla recusiva
        ]);
        DB::connection('pgsql-community')->table('specific_aims')->insert([
            'state_id' => 1,
            'project_id' => 1,
            'indicator' => "Número de estudiantes aprobados",
            'verifications' => json_encode(['Lista de estudiantes']),
            'description' => '3.1.1 Clases básicas de ofimática ',//linea base
            'type_id' => 8,//crear tipo de catologos
            'parent_code_id' => 4,//tabla recusiva
        ]);
        DB::connection('pgsql-community')->table('specific_aims')->insert([
            'state_id' => 1,
            'project_id' => 1,
            'indicator' => "Número de estudiantes",
            'verifications' => json_encode(['Lista de estudiantes']),
            'description' => '3.1.1 Número de estudiantes capacitados y aprobados',//linea base
            'type_id' => 8,//crear tipo de catologos
            'parent_code_id' => 4,//tabla recusiva
        ]);
        DB::connection('pgsql-community')->table('project_activities')->insert([
            'state_id' => 1,
            'project_id' => 1,
            'type_id' => 5,//un catalogo unico de la tabla
            'detail' => null
        ]);
        /*        DB::connection('pgsql-vinculacion')->table('student_participants')->insert([
                   'state_id'=>1,
                   'student_id'=>1,
                   'project_id'=>1,
                   'funtion_id'=>1,
               ]);
               DB::connection('pgsql-vinculacion')->table('teacher_participants')->insert([
                   'state_id'=>1,
                   'teacher_id'=>1,
                   'project_id'=>1,
                   'workHours'=>1000,//horas de trabajo
                   'funtion_id'=>1,//rol asignado catalogo
               ]); */

        /*
        {
        "ruc": "1234567891",
        "name_institution": "FUNDACION VISTA PARA TODOS",
        "location_id": 1,
        "indirect_beneficiaries": ["jesus","pepe","marcos"],
        "legal_representative_name": "RODRIGO",
        "legal_representative_lastname": "nogales",
        "legal_representative_identification": "1725485277",
        "project_post_charge": "PRESIDENTE",
        "direct_beneficiaries": ["alfredo","marcos","mario"],
        "charitable_institution_id": 1,
        "career_id": 1,
        "assigned_line_id": 45,
        "code": "yavirac1223",
        "project_name": "Systema ayuda a los pobres",
        "status_id": 1,
        "field": "campo",
        "aim": "objeto",
        "fraquency_id": 41,
        "cycle": ["primero","segundo","tercero"],
        "location_project": 1,
        "lead_time": 3,
        "delivery_date": "2018/1/22",
        "start_date": "2018/1/22",
        "end_date": "2018/1/22",
        "description": "ofdasdsafsda",
        "coordinator_name": "OLIVER",
        "coordinator_lastname": "NESTLES",
        "coordinator_postition": "POSITION",
        "coordinator_funtion": "coordinator",
        "introduction": "AFDSFDSDDSAFASSD",
        "situational_analysis": "AASDSDDSAAFDSSAF",
        "foundamentation": "SADADASD",
        "justification": "ADSSDFDSF",
        "bibliografia": ["bibliografia","noce que poner"],
        "indicator": ["Número de estudiantes capacidades en el área de informática","horas"],
        "verifications": [["Listado de asistencia"],["adios"]],
        "description_aims": ["Brindar una capacitación en ofimática básica a niños de 8 a 12 años mediante talleres y trabajos dirigidas para su desarrollo educativo","describir lo que se hace"],
        "type_id_specific": [42,"43"],
        "parent_code_id": [null,"Brindar una capacitación en ofimática básica a niños de 8 a 12 años mediante talleres y trabajos dirigidas para su desarrollo educativo"],
        "type_id_activities":[8,9],
        "detail_activities":["hacer cosas","hacer otras cosas"]
        //pendientes por creacion de catalagos
        "$id_student":[1,2],
        "$funtion_student":[1,2],
        "$teacher_id":[1,2],
        "$workHours":[100,200],
        "$funtion_teacher":[1,2],
    }
    */
    }
}
