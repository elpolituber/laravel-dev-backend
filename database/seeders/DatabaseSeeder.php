<?php

namespace Database\Seeders;

use App\Models\Authentication\Module;
use App\Models\Authentication\Permission;
use App\Models\Authentication\Role;
use App\Models\Authentication\Route;
use App\Models\Authentication\Shortcut;
use App\Models\Authentication\Status;
use App\Models\Authentication\System;
use App\Models\Authentication\User;
use App\Models\Ignug\Authority;
use App\Models\Ignug\AuthorityType;
use App\Models\Ignug\Career;
use App\Models\Ignug\Catalogue;
use App\Models\Ignug\Institution;
use App\Models\Ignug\Setting;
use App\Models\Ignug\Teacher;
use Illuminate\Database\Seeder;
use App\Models\Ignug\State;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
            $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);

        // Estados de los registris para todas las tablas, para poder hacer borrados logicos
        State::factory()->create([
            'code' => $catalogues['state']['type']['active'],
            'name' => 'ACTIVE',
        ]);
        State::factory()->create([
            'code' => $catalogues['state']['type']['deleted'],
            'name' => 'DELETED',
        ]);

        // Status para la tabla usuarios
        Status::factory()->create([
            'code' => $catalogues['status']['type']['active'],
            'name' => 'ACTIVE',
        ]);
        Status::factory()->create([
            'code' => $catalogues['status']['type']['inactive'],
            'name' => 'INACTIVE',
        ]);
        Status::factory()->create([
            'code' => $catalogues['status']['type']['locked'],
            'name' => 'LOCKED',
        ]);

        // catalogos
        $this->createIdentificationTypeCatalogues();
        $this->createEthnicOriginCatalogues();
        $this->createBloodTypeCatalogues();
        $this->createSexCatalogues();
        $this->createGenderCatalogues();

        $systemStatus = Catalogue::factory()->create([
                'code' => $catalogues['system']['status']['available'],
                'name' => 'AVAILABLE',
                'type' => $catalogues['route']['status']['type']]
        );
        $statusAvailableRoute = Catalogue::factory()->create([
            'code' => $catalogues['route']['status']['available'],
            'name' => 'AVAILABLE',
            'type' => $catalogues['route']['status']['type'],
        ]);
        $statusMaintenanceRoute = Catalogue::factory()->create([
            'code' => $catalogues['route']['status']['maintenance'],
            'name' => 'MAINTENANCE',
            'type' => $catalogues['route']['status']['type'],
        ]);

        // Sistemas de produccion
        $systemIgnug = System::factory()->create([
            'code' => $catalogues['system']['name']['ignug'],
            'name' => 'IGNUG',
            'status_id' => $systemStatus->id,
        ]);

        // Roles para el sistema IGNUG
        $this->createRoles();

        // Menu normal y mega menu
        $menu = Catalogue::factory()->create([
            'code' => $catalogues['menu']['type']['normal'],
            'name' => 'MENU',
            'type' => $catalogues['menu']['type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['menu']['type']['mega'],
            'name' => 'MEGA MENU',
            'type' => $catalogues['menu']['type']['type'],
        ]);

        // Modulos y rutas para el sistema IGNUG
        $moduleAttendance = Module::factory()->create([
            'code' => $catalogues['module']['name']['attendance'],
            'name' => 'ASISTENCIA',
            'system_id' => $systemIgnug->id,
            'status_id' => $systemStatus->id,
        ]);
        $routeAttendance = Route::factory()->create([
            'uri' => $catalogues['route']['name']['attendance']['attendance'],
            'module_id' => $moduleAttendance->id,
            'type_id' => $menu->id,
            'status_id' => $statusAvailableRoute->id,
            'label' => 'ASISTENCIA',
            'order' => 1,
        ]);
        $routeAttendanceAdministration = Route::factory()->create([
            'uri' => $catalogues['route']['name']['attendance']['administration'],
            'module_id' => $moduleAttendance->id,
            'type_id' => $menu->id,
            'status_id' => $statusAvailableRoute->id,
            'label' => 'ADMINISTRACION ASISTENCIA',
            'order' => 2,
        ]);

        // Modalidades y tipos de carrera
        $this->createCareerModality();
        $this->createCareerType();

        // Institutos y carreras
        $this->createInstitutionsCareers();

        // Creacion de permisos para las rutas
        foreach (Route::all() as $route) {
            foreach (Institution::all() as $institution) {
                Permission::factory()->create([
                    'route_id' => $route->id,
                    'institution_id' => $institution->id,
                    'system_id' => $systemIgnug->id,
                ]);
            }
        }

        // Creacion de permisos
        $this->createPermissions($systemIgnug, $routeAttendance, $routeAttendanceAdministration);
        $this->call([
            AttendanceSeeder::class,
        ]);
    }

    private function createRoles()
    {
            $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);

        $systemIgnug = System::firstWhere('code', $catalogues['system']['name']['ignug']);

        Role::factory()->create([
            'code' => $catalogues['role']['name']['admin'],
            'name' => 'ADMINISTRADOR',
            'system_id' => $systemIgnug->id
        ]);
        Role::factory()->create([
            'code' => $catalogues['role']['name']['student'],
            'name' => 'ESTUDIANTE',
            'system_id' => $systemIgnug->id
        ]);
        Role::factory()->create([
            'code' => $catalogues['role']['name']['teacher'],
            'name' => 'PROFESOR',
            'system_id' => $systemIgnug->id
        ]);
        Role::factory()->create([
            'code' => $catalogues['role']['name']['chancellor'],
            'name' => 'RECTOR',
            'system_id' => $systemIgnug->id
        ]);
        Role::factory()->create([
            'code' => $catalogues['role']['name']['vice_chancellor'],
            'name' => 'VICERRECTOR',
            'system_id' => $systemIgnug->id
        ]);
        Role::factory()->create([
            'code' => $catalogues['role']['name']['concierge'],
            'name' => 'CONSERJE',
            'system_id' => $systemIgnug->id
        ]);
        Role::factory()->create([
            'code' => $catalogues['role']['name']['career_coordinator'],
            'name' => 'COORD. CARRERA',
            'system_id' => $systemIgnug->id
        ]);
        Role::factory()->create([
            'code' => $catalogues['role']['name']['academic_coordinator'],
            'name' => 'COORD. ACADEMICO',
            'system_id' => $systemIgnug->id
        ]);
        Role::factory()->create([
            'code' => $catalogues['role']['name']['community_coordinator'],
            'name' => 'COORD. VINCULACION',
            'system_id' => $systemIgnug->id
        ]);
        Role::factory()->create([
            'code' => $catalogues['role']['name']['investigation_coordinator'],
            'name' => 'COORD. INVESTIGACION',
            'system_id' => $systemIgnug->id
        ]);
        Role::factory()->create([
            'code' => $catalogues['role']['name']['administrative_coordinator'],
            'name' => 'COORD. ADMINISTRATIVO',
            'system_id' => $systemIgnug->id
        ]);
    }

    private function createPermissions($systemIgnug, $routeAttendance, $routeAttendanceAdministration)
    {
            $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $roleChancellor = Role::firstWhere('code', $catalogues['role']['name']['chancellor']);
        $roleViceChancellor = Role::firstWhere('code', $catalogues['role']['name']['vice_chancellor']);
        $roleConcierge = Role::firstWhere('code', $catalogues['role']['name']['concierge']);
        $roleCareerCoordinator = Role::firstWhere('code', $catalogues['role']['name']['career_coordinator']);
        $roleTeacher = Role::firstWhere('code', $catalogues['role']['name']['teacher']);
        $roleAdministrativeCoordinator = Role::firstWhere('code', $catalogues['role']['name']['administrative_coordinator']);
        $roleAcademicCoordinator = Role::firstWhere('code', $catalogues['role']['name']['academic_coordinator']);

        foreach (Institution::all() as $institution) {
            $roleChancellor->permissions()->attach(Permission::
            where('route_id', $routeAttendance->id)
                ->where('system_id', $systemIgnug->id)
                ->where('institution_id', $institution->id)
                ->first()
            );
            $roleViceChancellor->permissions()->attach(Permission::
            where('route_id', $routeAttendance->id)
                ->where('system_id', $systemIgnug->id)
                ->where('institution_id', $institution->id)
                ->first()
            );
            $roleConcierge->permissions()->attach(Permission::
            where('route_id', $routeAttendance->id)
                ->where('system_id', $systemIgnug->id)
                ->where('institution_id', $institution->id)
                ->first()
            );
            $roleCareerCoordinator->permissions()->attach(Permission::
            where('route_id', $routeAttendance->id)
                ->where('system_id', $systemIgnug->id)
                ->where('institution_id', $institution->id)
                ->first()
            );
            $roleTeacher->permissions()->attach(Permission::
            where('route_id', $routeAttendance->id)
                ->where('system_id', $systemIgnug->id)
                ->where('institution_id', $institution->id)
                ->first()
            );
            $roleAdministrativeCoordinator->permissions()->attach(Permission::
            where('route_id', $routeAttendance->id)
                ->where('system_id', $systemIgnug->id)
                ->where('institution_id', $institution->id)
                ->first()
            );
        }

        foreach (Institution::all() as $institution) {
            $roleAdministrativeCoordinator->permissions()->attach(Permission::
            where('route_id', $routeAttendanceAdministration->id)
                ->where('system_id', $systemIgnug->id)
                ->where('institution_id', $institution->id)
                ->first()
            );
            $roleCareerCoordinator->permissions()->attach(Permission::
            where('route_id', $routeAttendanceAdministration->id)
                ->where('system_id', $systemIgnug->id)
                ->where('institution_id', $institution->id)
                ->first()
            );
            $roleAcademicCoordinator->permissions()->attach(Permission::
            where('route_id', $routeAttendanceAdministration->id)
                ->where('system_id', $systemIgnug->id)
                ->where('institution_id', $institution->id)
                ->first()
            );
        }
    }

    private function createInstitutionsCareers()
    {
            $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);

        $benitoJuarez = Institution::factory()->create(['name' => 'BENITO JUAREZ', 'logo' => 'institutions/1.png',
            'acronym' => 'BJ', 'short_name' => 'BENITO JUAREZ']);
        $yavirac = Institution::factory()->create(['name' => 'DE TURISMO Y PATRIMONIO YAVIRAC', 'logo' => 'institutions/2.png', 'acronym' => 'Y',
            'short_name' => 'YAVIRAC']);
        $mayo24 = Institution::factory()->create(['name' => '24 DE MAYO', 'logo' => 'institutions/3.png', 'acronym' => '24MAYO',
            'short_name' => '24 DE MAYO']);
        $granColombia = Institution::factory()->create(['name' => 'GRAN COLOMBIA', 'logo' => 'institutions/4.png', 'acronym' => 'GC',
            'short_name' => 'GRAN COLOMBIA']);

        $dualModality = Catalogue::where('type', $catalogues['career']['modality']['type'])->where('code', $catalogues['career']['modality']['dual'])->first();
        $presencialModality = Catalogue::where('type', $catalogues['career']['modality']['type'])->where('code', $catalogues['career']['modality']['presencial'])->first();

        $technologyType = Catalogue::where('type', $catalogues['career']['type']['type'])->where('code', $catalogues['career']['type']['technology'])->first();
        $technicalType = Catalogue::where('type', $catalogues['career']['type']['type'])->where('code', $catalogues['career']['type']['technical'])->first();
        Career::create([
            'institution_id' => $benitoJuarez->id,
            'name' => 'DESAROLLO DE SOFTWARE',
            'short_name' => 'DESAROLLO DE SOFTWARE',
            'modality_id' => $dualModality->id,
            'title' => 'TECNOLOGO SUPERIOR EN DESARROLLO DE SOFTWARE',
            'acronym' => 'DS',
            'logo' => 'careers/1.png',
            'type_id' => $technologyType->id,
            'state_id' => 1,
        ]);
        Career::create([
            'institution_id' => $benitoJuarez->id,
            'name' => 'DESAROLLO DE SOFTWARE',
            'short_name' => 'DESAROLLO DE SOFTWARE',
            'modality_id' => $dualModality->id,
            'title' => 'TECNOLOGO SUPERIOR EN DESARROLLO DE SOFTWARE',
            'acronym' => 'DS',
            'logo' => 'careers/2.png',
            'type_id' => $technologyType->id,
            'state_id' => 1,
        ]);

        Career::create([
            'institution_id' => $yavirac->id,
            'name' => 'TECNOLOGIA SUPERIOR EN ANALISIS DE SISTEMAS',
            'short_name' => 'ANALISIS DE SISTEMAS',
            'modality_id' => $presencialModality->id,
            'title' => 'TECNOLOGO SUPERIOR EN ANALISIS DE SISTEMAS',
            'acronym' => 'AS',
            'logo' => 'careers/3.png',
            'type_id' => $technologyType->id,
            'state_id' => 1,
        ]);
        Career::create([
            'institution_id' => $yavirac->id,
            'name' => 'TECNOLOGIA SUPERIOR EN ELECTRONICA',
            'short_name' => 'ELECTRONICA',
            'modality_id' => $presencialModality->id,
            'title' => 'TECNOLOGO SUPERIOR EN ANALISIS DE ELECTRONICA',
            'acronym' => 'ELT',
            'logo' => 'careers/4.png',
            'type_id' => $technologyType->id,
            'state_id' => 1,
        ]);
        Career::create([
            'institution_id' => $yavirac->id,
            'name' => 'TECNOLOGIA SUPERIOR EN ELECTRICIDAD',
            'short_name' => 'ELECTRICIDAD',
            'modality_id' => $presencialModality->id,
            'title' => 'TECNOLOGO SUPERIOR EN ELECTRICIDAD',
            'acronym' => 'ELC',
            'logo' => 'careers/5.png',
            'type_id' => $technologyType->id,
            'state_id' => 1,
        ]);
        Career::create([
            'institution_id' => $yavirac->id,
            'name' => 'TECNICO SUPERIOR EN GUIANZA TURISTICA CON MENCION EN PATRIMONIO CULTURAL O AVITURISMO',
            'short_name' => 'GUIANZA TURISTICA',
            'modality_id' => $dualModality->id,
            'title' => 'TECNICO SUPERIOR EN GUIANZA TURISTICA CON MENCION EN PATRIMONIO CULTURAL O AVITURISMO',
            'acronym' => 'GT',
            'logo' => 'careers/6.png',
            'type_id' => $technicalType->id,
            'state_id' => 1,
        ]);
        Career::create([
            'institution_id' => $yavirac->id,
            'name' => 'GUIA NACIONAL DE TURISMO CON NIVEL EQUIVALENTE A TECNOLOGIA SUPERIOR',
            'short_name' => 'GUIA NACIONAL',
            'modality_id' => $dualModality->id,
            'title' => 'GUIA NACIONAL DE TURISMO CON NIVEL EQUIVALENTE A TECNOLOGO SUPERIOR',
            'acronym' => 'GN',
            'logo' => 'careers/7.png',
            'type_id' => $technologyType->id,
            'state_id' => 1,
        ]);
        Career::create([
            'institution_id' => $yavirac->id,
            'name' => 'TECNICO SUPERIOR EN ARTE CULINARIO ECUATORIANO',
            'short_name' => 'ARTE CULINARIO',
            'modality_id' => $dualModality->id,
            'title' => 'TECNICO SUPERIOR EN ARTE CULINARIO ECUATORIANO',
            'acronym' => 'AC',
            'logo' => 'careers/8.png',
            'type_id' => $technicalType->id,
            'state_id' => 1,
        ]);
        Career::create([
            'institution_id' => $granColombia->id,
            'name' => 'DISEÑO DE MODAS CON NIVEL EQUIVALENTE A TECNOLOGÍA SUPERIOR',
            'short_name' => 'DISEÑO DE MODAS',
            'modality_id' => $presencialModality->id,
            'title' => 'DISEÑADOR DE MODAS CON NIVEL EQUIVALENTE A TECNOLOGO SUPERIOR',
            'acronym' => 'DM',
            'logo' => 'careers/9.png',
            'type_id' => $technologyType->id,
            'state_id' => 1,
        ]);
        Career::create([
            'institution_id' => $granColombia->id,
            'name' => 'DISEÑO DE MODAS CON NIVEL EQUIVALENTE A TECNOLOGÍA SUPERIOR',
            'short_name' => 'DISEÑO DE MODAS',
            'modality_id' => $presencialModality->id,
            'title' => 'DISEÑADOR DE MODAS CON NIVEL EQUIVALENTE A TECNOLOGO SUPERIOR',
            'acronym' => 'DM',
            'logo' => 'careers/10.png',
            'type_id' => $technologyType->id,
            'state_id' => 1,
        ]);
        Career::create([
            'institution_id' => $yavirac->id,
            'name' => 'TECNOLOGIA SUPERIOR EN MARKETING',
            'short_name' => 'MARKETING',
            'modality_id' => $presencialModality->id,
            'title' => 'TECNOLOGO SUPERIOR EN MARKETING',
            'acronym' => 'MK',
            'logo' => 'careers/11.png',
            'type_id' => $technologyType->id,
            'state_id' => 1,
        ]);
        Career::create([
            'institution_id' => $yavirac->id,
            'name' => 'TECNOLOGIA SUPERIOR EN CONTROL DE INCENDIOS Y OPERACIONES DE RESCATE',
            'short_name' => 'CONTROL DE INCENDIOS Y OPERACIONES DE RESCATE',
            'modality_id' => $dualModality->id,
            'title' => 'TECNOLOGO SUPERIOR EN CONTROL DE INCENDIOS Y OPERACIONES DE RESCATE',
            'acronym' => 'CIOR',
            'logo' => 'careers/12.png',
            'type_id' => $technicalType->id,
            'state_id' => 1,
        ]);
        Career::create([
            'institution_id' => $mayo24->id,
            'name' => 'TECNOLOGIA SUPERIOR EN MARKETING',
            'short_name' => 'MARKETING',
            'modality_id' => $presencialModality->id,
            'title' => 'TECNOLOGO SUPERIOR EN MARKETING',
            'acronym' => 'MK',
            'logo' => 'careers/13.png',
            'type_id' => $technologyType->id,
            'state_id' => 1,
        ]);
    }

    private function createEthnicOriginCatalogues()
    {
            $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory()->create([
            'code' => $catalogues['ethnic_origin']['type']['indigena'],
            'name' => 'INDIGENA',
            'type' => $catalogues['ethnic_origin']['type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['ethnic_origin']['type']['afroecuatoriano'],
            'name' => 'AFROECUATORIANO',
            'type' => $catalogues['ethnic_origin']['type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['ethnic_origin']['type']['negro'],
            'name' => 'NEGRO',
            'type' => $catalogues['ethnic_origin']['type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['ethnic_origin']['type']['mulato'],
            'name' => 'MULATO',
            'type' => $catalogues['ethnic_origin']['type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['ethnic_origin']['type']['montuvio'],
            'name' => 'MONTUVIO',
            'type' => $catalogues['ethnic_origin']['type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['ethnic_origin']['type']['mestizo'],
            'name' => 'MESTIZO',
            'type' => $catalogues['ethnic_origin']['type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['ethnic_origin']['type']['blanco'],
            'name' => 'BLANCO',
            'type' => $catalogues['ethnic_origin']['type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['ethnic_origin']['type']['other'],
            'name' => 'OTRO',
            'type' => $catalogues['ethnic_origin']['type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['ethnic_origin']['type']['unregistered'],
            'name' => 'NO REGISTRA',
            'type' => $catalogues['ethnic_origin']['type']['type'],
        ]);
    }

    private function createIdentificationTypeCatalogues()
    {
            $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory()->create([
            'code' => $catalogues['identification_type']['type']['cc'],
            'name' => 'CEDULA',
            'type' => $catalogues['identification_type']['type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['identification_type']['type']['passport'],
            'name' => 'PASAPORTE',
            'type' => $catalogues['identification_type']['type']['type'],
        ]);
    }

    private function createBloodTypeCatalogues()
    {
            $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory()->create([
            'code' => $catalogues['blood_type']['type']['a-'],
            'name' => 'A-',
            'type' => $catalogues['blood_type']['type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['blood_type']['type']['a+'],
            'name' => 'A+',
            'type' => $catalogues['blood_type']['type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['blood_type']['type']['b-'],
            'name' => 'B-',
            'type' => $catalogues['blood_type']['type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['blood_type']['type']['b+'],
            'name' => 'B+',
            'type' => $catalogues['blood_type']['type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['blood_type']['type']['ab-'],
            'name' => 'AB-',
            'type' => $catalogues['blood_type']['type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['blood_type']['type']['ab+'],
            'name' => 'AB+',
            'type' => $catalogues['blood_type']['type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['blood_type']['type']['o-'],
            'name' => 'O-',
            'type' => $catalogues['blood_type']['type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['blood_type']['type']['o+'],
            'name' => 'O+',
            'type' => $catalogues['blood_type']['type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['blood_type']['type']['a+'],
            'name' => 'A+',
            'type' => $catalogues['blood_type']['type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['blood_type']['type']['a+'],
            'name' => 'A+',
            'type' => $catalogues['blood_type']['type']['type'],
        ]);

    }

    private function createSexCatalogues()
    {
            $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory()->create([
            'code' => $catalogues['sex']['type']['male'],
            'name' => 'HOMBRE',
            'type' => $catalogues['sex']['type']['type']
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['sex']['type']['female'],
            'name' => 'MUJER',
            'type' => $catalogues['sex']['type']['type'],
        ]);
    }

    private function createGenderCatalogues()
    {
            $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory()->create([
            'code' => $catalogues['gender']['type']['male'],
            'name' => 'MASCULINO',
            'type' => $catalogues['gender']['type']['type']
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['gender']['type']['female'],
            'name' => 'FEMENINO',
            'type' => $catalogues['gender']['type']['type'],
        ]);
    }

    private function createCareerModality()
    {
            $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory()->create([
            'code' => $catalogues['career']['modality']['presencial'],
            'name' => 'PRESENCIAL',
            'type' => $catalogues['career']['modality']['type']
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['career']['modality']['semipresencial'],
            'name' => 'SEMI-PRESENCIAL',
            'type' => $catalogues['career']['modality']['type']
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['career']['modality']['distancia'],
            'name' => 'DISTANCIA',
            'type' => $catalogues['career']['modality']['type']
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['career']['modality']['dual'],
            'name' => 'DISTANCIA',
            'type' => $catalogues['career']['modality']['type']
        ]);
    }

    private function createCareerType()
    {
            $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory()->create([
            'code' => $catalogues['career']['type']['technology'],
            'name' => 'TECNOLOGIA',
            'type' => $catalogues['career']['type']['type']
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['career']['type']['technical'],
            'name' => 'TECNICATURA',
            'type' => $catalogues['career']['type']['type']
        ]);
    }
}
/*
            drop schema if exists authentication cascade;
            drop schema if exists attendance cascade;
            drop schema if exists ignug cascade;
            drop schema if exists job_board cascade;
            drop schema if exists web cascade;
            drop schema if exists teacher_eval cascade;
            drop schema if exists community cascade;
            drop schema if exists cecy cascade;

            create schema authentication;
            create schema attendance;
            create schema ignug;
            create schema job_board;
            create schema web;
            create schema teacher_eval;
            create schema community;
            create schema cecy;
 */
