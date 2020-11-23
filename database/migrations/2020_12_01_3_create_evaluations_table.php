<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationsTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-teacher-eval')->create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('ignug.teachers');
            $table->foreignId('evaluation_type_id')->comment('pares, autoevaluacion,estudiante');
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->double('result',5,2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-teacher-eval')->dropIfExists('evaluations');
    }
}
