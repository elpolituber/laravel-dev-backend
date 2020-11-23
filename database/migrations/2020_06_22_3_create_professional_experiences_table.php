<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessionalExperiencesTable extends Migration
{

    public function up()
    {
        Schema::connection('pgsql-ignug')->create('professional_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id');
            $table->string('employer')->comment('Nombre de la empresa o empleador');
            $table->string('position')->comment('Cargo');
            $table->text('description')->comment('Descripcion de las actividades');
            $table->date('start_date')->comment('Fecha de inicio del trabajo');
            $table->date('end_date')->nullable()->comment('Fecha de fin del trabajo');
            $table->text('reason_leave')->comment('Razon de la salida');
            $table->boolean('current_work')->default(false)->comment('true si se encuentra trabajando actualmente');
            $table->foreignId('state_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-ignug')->dropIfExists('professional_experiences');
    }
}
