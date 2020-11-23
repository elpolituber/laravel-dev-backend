<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentResultsTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-teacher-eval')->create('student_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('answer_question_id')->comment('')->constrained('answer_question');
            $table->foreignId('subject_teacher_id')->constrained('ignug.subject_teacher');
            $table->foreignId('student_id')->constrained('ignug.students');
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-teacher-eval')->dropIfExists('student_results');
    }
}
