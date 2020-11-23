<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{

    public function up()
    {
        Schema::connection('pgsql-ignug')->create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id');
            $table->foreignId('type_id')->comment('especializada, pedagogica, etc')->constrained('catalogues');
            $table->foreignId('event_type_id')->constrained('catalogues');
            $table->foreignId('entity_id')->comment('Entidad donde recibio o impartio el curso')->constrained('catalogues');
            $table->foreignId('certification_type_id')->comment('aproabacion o asistencia')->constrained('catalogues');
            $table->text('event_name')->comment('Nombre del curso');
            $table->string('area_code')->comment('Codigo del area a la que pertenece el curso')->nullable();
            $table->string('area')->comment('Area a la que pertenece el curso')->nullable();
            $table->string('especiality_code')->comment('Codigo de la especialidad a la que pertenece el curso')->nullable();
            $table->string('especiality')->comment('Especialidad a la que pertenece el curso')->nullable();
            $table->date('start_date')->comment('Fecha de inicio del curso');
            $table->date('end_date')->comment('Fecha de inicio del curso');
            $table->integer('hours')->comment('Horas totales del curso');
            $table->boolean('given')->default(false)->comment('true si el curso es impartido por el docente');
            $table->foreignId('state_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-ignug')->dropIfExists('courses');
    }
}
