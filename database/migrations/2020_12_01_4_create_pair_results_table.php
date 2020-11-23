<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePairResultsTable extends Migration
{
    public function up()
    {
        Schema::connection('pgsql-teacher-eval')->create('pair_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('answer_question_id')->constrained('answer_question');
            $table->foreignId('detail_evaluation_id');
            $table->foreignId('state_id')->constrained('ignug.states');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('pgsql-teacher-eval')->dropIfExists('pair_results');
    }
}
