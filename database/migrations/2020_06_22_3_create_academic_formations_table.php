<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicFormationsTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-ignug')->create('academic_formations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id');
            $table->foreignId('type_id')->comment('Tipo de titulo Tenico, pregrado, postgrado, artesanal, competencias laborales')->constrained('catalogues');
            $table->foreignId('entity_id')->comment('Entidad de la que proviene el titulo')->constrained('catalogues');
            $table->foreignId('degree_id')->comment('Nombre del titulo obtenido')->constrained('ignug.catalogues');
            $table->date('registration_date')->comment('Fecha de registro de la senescyt')->nullable();
            $table->string('senescyt_code')->comment('codigo del registro en senescyt')->nullable();
            $table->boolean('has_degree')->comment('true si ya tiene el titulo')->nullable()->default(false);;
            $table->foreignId('state_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-ignug')->dropIfExists('academic_formations');
    }
}
