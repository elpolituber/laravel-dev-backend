<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherEvalQuestionsTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-teacher-eval')->create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_type_id')->nullable();
            $table->foreignId('type_id')->comment('Tipo Pregunta, Cuantitativa o Cualitativa')->constrained('ignug.catalogues');
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->string('code')->unique();
            $table->integer('order')->unique();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-teacher-eval')->dropIfExists('questions');
    }
}
