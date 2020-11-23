<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationTypesTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-teacher-eval')->create('evaluation_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->comment('hace una tabla recursiva por eso hace referencia a una misma tabla')->constrained('evaluation_types');
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->integer('percentage')->nullable();
            $table->integer('global_percentage')->nullable()->comment('Este porcentaje es para calculos finales.');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::connection('pgsql-teacher-eval')->dropIfExists('evaluation_types');
    }
}
