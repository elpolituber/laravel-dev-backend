<?php

namespace Database\Seeders;

use App\Models\Attendance\Attendance;
use App\Models\Attendance\Workday;
use App\Models\Authentication\Role;
use App\Models\Authentication\Route;
use App\Models\Authentication\System;
use App\Models\Authentication\User;
use App\Models\Ignug\Catalogue;
use App\Models\Ignug\Institution;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $data = file_get_contents(storage_path()."/catalogues.json");
        $catalogues = json_decode($data, true);

        Catalogue::factory()->create([
            'code' => $catalogues['workday']['type']['work'],
            'name' => 'JORNADA',
            'type' => $catalogues['workday']['type']['type'],
        ]);

        Catalogue::factory()->create([
            'code' => $catalogues['workday']['type']['lunch'],
            'name' => 'ALMUERZO',
            'type' => $catalogues['workday']['type']['type'],
        ]);

        $roleRector = Role::firstWhere('code',$catalogues['role']['name']['chancellor']);
        $processRector = $roleRector->catalogues()->create([
            'code' => $catalogues['task']['process']['chancellor'],
            'name' => 'ACTIVIDADES RECTORADO',
            'type' => $catalogues['task']['process']['type'],
            'color' =>'#ff7043',
            'state_id' => 1,
        ]);

        $roleVicerrector = Role::firstWhere('code',$catalogues['role']['name']['vice_chancellor']);
        $processVicerrector = $roleVicerrector->catalogues()->create([
            'code' => $catalogues['task']['process']['vice_chancellor'],
            'name' => 'ACTIVIDADES VICERECTORADO',
            'type' => $catalogues['task']['process']['type'],
            'color' =>'#78909c',
            'state_id' => 1,
        ]);

        $roleConcierge = Role::firstWhere('code',$catalogues['role']['name']['concierge']);
        $processConcierge = $roleConcierge->catalogues()->create([
            'code' => $catalogues['task']['process']['concierge'],
            'name' => 'ACTIVIDADES CONSERJE',
            'type' => $catalogues['task']['process']['type'],
            'color' =>'#5c6bc0',
            'state_id' => 1,
        ]);

        $roleTeacher = Role::firstWhere('code',$catalogues['role']['name']['teacher']);
        $processAcademic = $roleTeacher->catalogues()->create([
            'code' => $catalogues['task']['process']['academic'],
            'name' => 'ACTIVIDADES ACADEMICAS',
            'type' => $catalogues['task']['process']['type'],
            'color' =>'#2196f3',
            'state_id' => 1,
        ]);
        $processAdministrative = $roleTeacher->catalogues()->create([
            'code' => $catalogues['task']['process']['administrative'],
            'name' => 'ACTIVIDADES ADMINISTRATIVAS',
            'type' => $catalogues['task']['process']['type'],
            'color' =>'#ffc107',
            'state_id' => 1,
        ]);
        $processCommunity = $roleTeacher->catalogues()->create([
            'code' =>$catalogues['task']['process']['community'],
            'name' => 'ACTIVIDADES DE VINCULACION',
            'type' => $catalogues['task']['process']['type'],
            'color' =>'#00bcd4',
            'state_id' => 1,
        ]);
        $processInvestigation = $roleTeacher->catalogues()->create([
            'code' => $catalogues['task']['process']['investigation'],
            'name' => 'ACTIVIDADES DE INVESTIGACION',
            'type' => $catalogues['task']['process']['type'],
            'color' =>'#9c27b0',
            'state_id' => 1,
        ]);

        $roleCareerCoordinator = Role::firstWhere('code',$catalogues['role']['name']['career_coordinator']);
        $roleCareerCoordinator->catalogues()->attach($processAcademic);
        $roleCareerCoordinator->catalogues()->attach($processAdministrative);
        $roleCareerCoordinator->catalogues()->attach($processCommunity);
        $roleCareerCoordinator->catalogues()->attach($processInvestigation);

        $roleAdminstrativeCoordinator = Role::firstWhere('code',$catalogues['role']['name']['administrative_coordinator']);
        $roleAdminstrativeCoordinator->catalogues()->attach($processAcademic);
        $roleAdminstrativeCoordinator->catalogues()->attach($processAdministrative);
        $roleAdminstrativeCoordinator->catalogues()->attach($processCommunity);
        $roleAdminstrativeCoordinator->catalogues()->attach($processInvestigation);

        $this->createRectorActivities($processRector);
        $this->createVicerrectorActivities($processVicerrector);
        $this->createConciergeActivities($processConcierge);
        $this->createAcademicActivities($processAcademic);
        $this->createAdministrativeActivities($processAdministrative);
        $this->createEntailmentActivities($processCommunity);
        $this->createInvestigationActivities($processInvestigation);
    }

    private function createRectorActivities($process)
    {
        $data = file_get_contents(storage_path()."/catalogues.json");
        $catalogues = json_decode($data, true);

        $process->children()->create([
            'code' => '1',
            'name' => 'ACTIVIDADES DE DIRECCION, REPRESENTACION DE LA INSTITUCION',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '2',
            'name' => 'ELABORACION DEL INFORME DE RENDICION DE CUENTAS',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '3',
            'name' => 'OTRAS ACTIVIDADES RESPALDADAS POR EL ESTATUTO INSTITUCIONAL',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
    }

    private function createVicerrectorActivities($process)
    {
        $data = file_get_contents(storage_path()."/catalogues.json");
        $catalogues = json_decode($data, true);
        $process->children()->create([
            'code' => '1',
            'name' => 'SEGUIMIENTO DE LA GESTION ACADEMICA',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '2',
            'name' => 'SEGUIMIENTO DE LA GESTION ACADEMICA',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '3',
            'name' => 'SEGUIMIENTO A LAS ACTIVIDADES DE FORMACION',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '4',
            'name' => 'SEGUIMIENTO A LAS ACTIVIDADES DE VINCULACION',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '5',
            'name' => 'ELABORACION DE INFORMES SOBRE PROYECTOS DE CARRERAS DE NIVEL TECNICO O TECNOLOGICO SUPERIOR',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '6',
            'name' => 'ELABORACION DEL MODELO DE GESTION DE FORMACION, INVESTIGACION Y VINCULACION CON LA SOCIEDAD',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '7',
            'name' => 'PLANIFICAR PROGRAMAS DE FORMACION, VINCULACION CON LA SOCIEDAD, EDUCACION CONTINUA E INVESTIGACION A SER DESARROLLADOS POR LAS CORRESPONDIENTES COORDINACIONES',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '8',
            'name' => 'ELABORACION DEL CALENDARIO EN CADA PERIODO ACADEMICO',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '9',
            'name' => 'MEDIACION, CONFLICTOS ACADEMICOS QUE NO HAYAN PODIDO SER RESUELTOS EN CADA CARRERA',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '10',
            'name' => 'ELABORAR PROPUESTAS DE MEJORA ACADEMICA SOBRE LAS CARRERAS',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '11',
            'name' => 'ELABORAR PLAN DE PERFECCIONAMIENTO ACADEMICO DE LOS DOCENTES',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '12',
            'name' => 'OTRAS ACTIVIDADES RESPALDADAS POR EL ESTATUTO INSTITUCIONAL',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
    }

    private function createConciergeActivities($process)
    {
        $data = file_get_contents(storage_path()."/catalogues.json");
        $catalogues = json_decode($data, true);
        $process->children()->create([
            'code' => '1',
            'name' => 'APERTURA Y CIERRE DEL EDIFICIO Y DEPENDENCIAS DEL INSTITUTO',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '2',
            'name' => 'VERIFICACION QUE EL TABLERO DE LLAVE SE ENCUENTRE COMPLETO',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '3',
            'name' => 'ASEO, LIMPIEZA DE RECTORADO',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '4',
            'name' => 'ASEO, LIMPIEZA DE AULAS PRIMER PISO',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '5',
            'name' => 'ASEO, LIMPIEZA DE AULAS SEGUNDO PISO',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '6',
            'name' => 'ASEO, LIMPIEZA DE OFICINAS',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '7',
            'name' => 'ASEO, LIMPIEZA DE BAÑOS',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '8',
            'name' => 'ENTREGA DE DOCUMENTACION EN SENESCYT U OTRA INSTITUCION',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '9',
            'name' => 'INFORMACION A PERSONAL EXTERNO',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '10',
            'name' => 'ATENCION DE LA BIBLIOTECA',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '11',
            'name' => 'CODIFICACION DE LIBROS',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '12',
            'name' => 'ACTUALIZACION DE BASE DE DATOS DE LIBROS',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '13',
            'name' => 'OTRAS ACTIVIDADES ENCOMENDADAS POR LAS AUTORIDADES',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
    }

    private function createAcademicActivities($process)
    {
        $data = file_get_contents(storage_path()."/catalogues.json");
        $catalogues = json_decode($data, true);
        $process->children()->create([
            'code' => '1',
            'name' => 'IMPARTIR CLASES PRESENCIALES, VIRTUALES O EN LINEA',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '2',
            'name' => 'PREPARACION Y ACTUALIZACION DE CLASES, SEMINARIOS, TALLERES Y OTROS',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '3',
            'name' => 'DISEÑO Y ELABORACION DE GUIAS, MATERIAL DIDACTICO Y SYLLABUS',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '4',
            'name' => 'ORIENTACION Y ACOMPAÑAMIENTO A TRAVES DE TUTORIAS PRESENCIALES O VIRTUALES, INDIVIDUALES O GRUPALES',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '5',
            'name' => 'ELABORACION DE REPORTES DE NIVEL ACADEMICO REFERENTE A EVALUACIONES, TRABAJOS Y RENDIMIENTO DEL ESTUDIANTE',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '6',
            'name' => 'VISITAS DE CAMPO',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '7',
            'name' => 'PREPARACION, ELABORACION, APLICACION Y CALIFICACION DE EXAMENES Y  PRACTICAS',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
    }

    private function createAdministrativeActivities($process)
    {
        $data = file_get_contents(storage_path()."/catalogues.json");
        $catalogues = json_decode($data, true);
        $process->children()->create([
            'code' => '1',
            'name' => 'PARTICIPACION EN PROCESOS DEL SISTEMA NACIONAL DE EVALUACION PARA INGRESO A UNIVERSIDADES',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '2',
            'name' => 'ACTIVIDADES DE DIRECCION O GESTION EN SUS DISTINTOS NIVELES DE ORGANIZACION ACADEMICA E INSTITUCIONAL',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '3',
            'name' => 'REUNIONES DE ORGANO COLEGIADO SUPERIOR',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '4',
            'name' => 'DISEÑO DE PROYECTOS DE CARRERAS Y PROGRAMAS DE ESTUDIOS',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '5',
            'name' => 'ACTIVIDADES RELACIONADAS CON LA EVALUACION INSTITUCIONAL EXTERNA',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
    }

    private function createEntailmentActivities($process)
    {
        $data = file_get_contents(storage_path()."/catalogues.json");
        $catalogues = json_decode($data, true);
        $process->children()->create([
            'code' => '1',
            'name' => 'DIRECCION SEGUIMIENTO Y EVALUACION DE PRACTICAS PRE PROFESIONALES',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '2',
            'name' => 'DISEÑO E IMPARTICION DE CURSOS DE EDUCACION CONTINUA O DE CAPACITACION Y ACTUALIZACION',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '3',
            'name' => 'PARTICIPACION EN ACTIVIDADES DE PROYECTOS SOCIALES, ARTISTICOS, PRODUCTIVOS Y EMPRESARIALES DE VINCULACION CON LA SOCIEDAD',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '4',
            'name' => 'ELABORACION DE INFORMES DE SEGUIMIENTO DE PROYECTOS DE VINCULACION',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
    }

    private function createInvestigationActivities($process)
    {
        $data = file_get_contents(storage_path()."/catalogues.json");
        $catalogues = json_decode($data, true);
        $process->children()->create([
            'code' => '1',
            'name' => 'GESTIONAR PROYECTOS DE INVESTIGACION, COMUNITARIOS Y/O DE EMPRENDIMIENTO',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '2',
            'name' => 'REALIZACION DE INVESTIGACION PARA LA RECUPERACION, FORTALECIMIENTO Y POTENCIAC ION DE LOS SABERES ANCESTRALES',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '3',
            'name' => 'PARTICIPACION EN CONGRESOS, SEMINARIOS Y CONFERENCIAS PARA LA PRESENTACION DE AVANCES Y RESULTADOS DE SUS INVESTIGACIONES',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '4',
            'name' => 'DISEÑO, GESTION Y PARTICIPACION EN REDES Y PROGRAMAS DE INVESTIGACION LOCAL NACIONAL E INTERNACIONAL',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '5',
            'name' => 'PARTICIPACION EN COMITES O CONSEJOS ACADEMICOS Y EDITORIALES DE REVISTAS CIENTIFICAS Y ACADEMICAS INDEXADAS, Y DE ALTO IMPACTO CIENTIFICO O ACADEMICO',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
        $process->children()->create([
            'code' => '6',
            'name' => 'DIFUSION DE RESULTADOS Y BENEFICIOS SOCIALES DE LA INVESTIGACION, A TRAVES DE PUBLICACIONES, PRODUCCIONES ARTISTICAS, ACTUACIONES, CONCIERTOS, CREACION U ORGANIZACION DE INSTALACIONES Y DE EXPOSICIONES, ENTRE OTROS',
            'type' => $catalogues['task']['activity']['type'],
            'state_id' => 1,
        ]);
    }
}
