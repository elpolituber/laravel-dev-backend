<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelfResultsTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-teacher-eval')->create('self_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('answer_question_id')->constrained('answer_question');
            $table->foreignId('teacher_id')->constrained('ignug.teachers');
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-teacher-eval')->dropIfExists('self_results');
    }
}
